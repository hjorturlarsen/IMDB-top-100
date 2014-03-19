<?php
	/**
		Þessi skrá vinnur með IMDB-id frá movies.json skránni sem við fengum í verkefni 4.
		Því er listinn out-dated og breytist aldrei, en þetta er top 100 í október 2013 skv. www.imdb.com
		Skráin notar id-in til þess að sækja ítarlegri upplýsingar um viðkomandi kvikmynd frá www.omdbapi.com og
		skrifar þær inn í .json skrá á heimasvæði. 
		Þegar því er lokið er titill kvikmyndar sóttur úr .json skránni og titilinn notaðut til að ná í
		json gögn frá YouTube, þaðan er slóð á trailer sótt og bætt inn í .json skránna á heimasvæðinu.
	**/
	//Skrá notuð til þess að vista gögn frá www.omdbapi.com og á heimasvæði.
	//Skrárnar eru með nafn í samræmi rank.

	$json_data = file_get_contents("../data/movies.json");
	$json_decode = json_decode($json_data, true);

	$i = 0;
	$all = "";
	$filename = 1;
	//Fyrir sérhverja kvikmynd í movies.json
	foreach ($json_decode as $key) {
		//Ef 1.json .. 100.json skrá er ekki til staðar, þá búum við hana til.
		if (!file_exists("../data/top100/".$filename.".json")){
			//Nær í imdbID úr movies.json
			$id = $json_decode[$i]["id"];
			//omdbapi slóðin fyrir tiltekna mynd
			$url = "http://www.omdbapi.com/?i=".$id;
			//lesum json gögnin
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL,$url);
			$result = curl_exec($ch);
			//Búum til json skrár 1.json .. 100.json
			createJson($filename, $result);
			$i++;
			$filename++;
		}
		else {
			$i++;
			$filename++;
		}
	}

	//NOTKUN: createJson($filename, $data)
	//FYRIR: $filename er nafnið sem þú villt á skránna, $data er innihald skrárinnar
	//EFTIR: búin hefur verið til .json skrá með nafni gildisins $filename með 
	//			innihaldi $data gildisins.
	function createJson($filename, $data){
		$file = "../data/top100/".$filename.".json";
		$fp = fopen($file, 'w');
		fwrite($fp, $data);
		fclose($fp);
	}

	//Eftir að allar 1.json .. 100.json skrárnar hafa verið búnar til
	//þurfum við að bæta við gögnum fyrir YouTube trailer.
	$j = 0;
	$json_name = 1;

	//Fyrir sérhverja kvikmynd í movies.json
	foreach ($json_decode as $key) {

		//Titill myndar t.d. "The Shawshank Redemption"
		$title = $json_decode[$j]["title"];

		//Titill myndar á forminu "The+Shawshank+Redemption"
		$title_url_string = str_replace(" ", "+", $title);
		
		//json skrá með YouTube gögnum
		$gdata_url = "http://gdata.youtube.com/feeds/api/videos?q=".$title_url_string."-trailer&start-index=1&max-results=1&v=2&alt=json";

		//lesum json skránna
		$ch = curl_init($gdata_url); 
		curl_setopt($ch, CURLOPT_URL, $gdata_url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		$output = curl_exec($ch); 
		curl_close($ch);
		//Fáum json skránna á vigurform
		$result = json_decode($output, true);

		//ef trailer slóð er til staðar í json skránni þá sækjum við hana.
		//https er sett inn fyrir http, annars er ekki hægt að opna video-in.
		$trailer_data = $result["feed"]["entry"][0]["media\$group"]["media\$content"][0]["url"];
		if(isset($trailer_data)){
			$trailer_url = $result["feed"]["entry"][0]["media\$group"]["media\$content"][0]["url"];
			$trailer_url_https = str_replace("http", "https", $trailer_url);
			$trailer_url_https = str_replace("&", "&amp;", $trailer_url_https);
		}

		//opnum 1.json .. 100.json til að setja inn gögn.
		$file = file_get_contents("../data/top100/".$json_name.".json");
		//fáum json skránna á vigurform
		$data = json_decode($file, true);

		//Ef trailer-slóðin er ekki til staðar í json skránni, þá bætum við henni í skránna.
		if(!isset($data[0]["trailer"])){
			//Kemur í veg fyrir gagnamissi í stórum json skrám.
			unset($file);
			//Bætum slóðinni inn í json skránna.
			array_push($data, array('trailer' => $trailer_url_https));
			//vistum breytingarnar.
			file_put_contents("../data/top100/".$json_name.".json",json_encode($data));
			//sleppa minni.
			unset($data);
			$j++;
			$json_name++;
		}
		else {
			$j++;
			$json_name++;
		}
	}
?>