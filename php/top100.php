<?php
        /**
                Þessi skrá sækir .json skrár á heimasvæði sem búnar voru til með createJson.php.
                og les úr þeim og býr til html kóða fyrir top 100 listann á vefsíðunni okkar.
                Þessi skrá sér einnig um að vista Poster fyrir kvikmyndir inn á heimasvæði,
                það var gert til þess að forðast það að sækja myndir frá ótraustri vefslóð (e. http).
        **/
        $file = 1;
	for ($i=0; $i < 100; $i++) { 
		$json = file_get_contents("../data/top100/".$file.".json");
		$json_data = json_decode($json, true);

                //Nær í IMDB poster og vistar þau í local skrá.
                $image_file = "../data/images/".$json_data["imdbID"].".jpg";
                $image_url = $json_data["Poster"];
                if(!file_exists($image_file)){
                        $ch = curl_init($image_url);
                        $fp = fopen($image_file, "wb");
                        curl_setopt($ch, CURLOPT_FILE, $fp);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_exec($ch);
                        curl_close($ch);
                        fclose($fp);             
                }

		echo "<div class=\"movie\">";
		echo 	"<div class=\"title\"><b>".$file.". </b>".$json_data["Title"]."</div>";
		echo	"<div class=\"flipbox-container\">";
                echo        "<div class=\"flipbox\" id=\"".$file."flipbox\">";
                echo                    "<input class=\"cbxClass\" type=\"checkbox\" id=\"".$json_data["imdbID"]."cbx"."\" name=\"checkboxName\">";
                echo			"<label class=\"label\" for=\"".$json_data["imdbID"]."cbx"."\">";
                echo                           "<img class=\"overlay\" src=\"../data/seen1.png\" alt=\"overlay-".$file."\" id=\"".$json_data["imdbID"]."overlay\">";
                                        //Ef mistókst að ná í IMDB poster þá notum við slóðina á myndina, annars local file-inn.
                                        if(!file_exists("../data/images/".$json_data["imdbID"].".jpg")){
                echo            	       "<img src=\"".$json_data["Poster"]."\" alt=\"".$json_data["Title"]."\" class=\"poster-img\" id=\"".$json_data["imdbID"]."img"."\">";
                                        }
                                        else{
                echo                           "<img src=\"../data/images/".$json_data["imdbID"].".jpg\" alt=\"".$json_data["Title"]."\" class=\"poster-img\" id=\"".$json_data["imdbID"]."img\">";                                 
                                        }
                echo 			"</label>";
                echo        "</div>";
                echo    "</div>";
                echo    "<div class=\"info-button\" id=\"".$file."\">Info</div>";
                echo    "<a class=\"video-layer\" href=\"".$json_data[0]["trailer"]."\">";
                echo            "<div class=\"trailer-button\" id=\"".$file."trailer"."\">Trailer</div>";
                echo    "</a>";
                echo "</div>";

                $file++;
	}
?>