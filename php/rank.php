<?php
    /**
    php fyrir login kerfi
    **/
    date_default_timezone_set("Iceland");

    $data = false;

    ini_set("display_errors", 1);
    ini_set("error_reporting", E_ALL | E_STRICT);

    // this is a demonstrator function, which gets called when new users register
    function registration_callback($username, $email, $userdir)
    {
        // all it does is bind registration data in a global array,
        // which is echoed on the page after a registration
        global $data;
        $data = array($username, $email, $userdir);
    }

    require_once("user.php");
    $USER = new User("registration_callback");
?>

<!doctype html>
<html lang="is">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../js/fancybox/source/jquery.fancybox.css">
    <meta name="description" content="IMDB top 100 movies, your rank among other users."/>


    <title>Top 100 IMDB movies, rank, users</title>
</head>

<body>
    <div id="fb-root"></div>
    <!-- script fyrir facebook like/share takka -->
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&status=0";
            fjs.parentNode.insertBefore(js, fjs);
        }
        (document, 'script', 'facebook-jssdk'));
    </script>
    <?php //include "php/createJson.php" ?>
    <div class="container">
        <div class="page-header">
            <h1>
                MOVIES
                <small>
                    <!-- Þetta birtist bara ef notandi er skráður inn -->                         
                    <?php if($USER->authenticated) { ?>
                        <p>Logged in as: <b><?php echo $_SESSION["username"]; ?></b></p>
                    <?php } ?>
            </small>
            </h1>
            <!-- Þetta birtist bara ef notandi er skráður inn -->   
            <?php if($USER->authenticated) { ?>
                <div class="count"></div>
            <?php } ?>

        </div>
        <nav class="navbar navbar-default" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">MOVIES</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Top100</a></li>
                    <li class="active"><a href="rank.php">Rank</a></li>
                    <li class="dropdown">  
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Social  
                            <b class="caret"></b>  
                        </a>  
                        <ul class="dropdown-menu">  
                            <li class="socials"><!-- Place this tag where you want the +1 button to render -->  
                                <g:plusone annotation="inline" width="150"></g:plusone>  
                            </li>  
                            <li class="socials"><div class="fb-like" data-href="https://notendur.hi.is/~hth154/Vefforittun/Lokaverkefni/php/index.php" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div></li>
                            <li class="socials"><div class="fb-share-button" data-href="https://notendur.hi.is/~hth154/Vefforittun/Lokaverkefni/php/index.php" data-type="button_count"></div></li>
                            <li class="socials"><a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
                                <!-- script fyrir facebook like/share takka -->
                                <script>
                                    !function(d, s, id) {
                                        var js, fjs = d.getElementsByTagName(s)[0];
                                        if (!d.getElementById(id)) {
                                            js = d.createElement(s);
                                            js.id = id;
                                            js.src = "//platform.twitter.com/widgets.js";
                                            fjs.parentNode.insertBefore(js, fjs);
                                        }
                                    }(document, "script", "twitter-wjs");
                                </script>
                            </li>  
                        </ul>  
                    </li>  
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if(!$USER->authenticated) { ?>
                        <li><a href="newuser.php" id="newuser-button">Create new user</a></li>
                        <li><a href="login.php" id="login-button">Log in</a></li>
                    <?php } ?>
                    <!-- Þetta birtist bara ef notandi er skráður inn -->  
                    <?php if($USER->authenticated) { ?>
                        <li class="dropdown">  
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account 
                                <b class="caret"></b>  
                            </a>  
                            <ul class="dropdown-menu">  
                                <li class="account"><!-- Place this tag where you want the +1 button to render -->  
                                    <g:plusone annotation="inline" width="150"></g:plusone>  
                                </li>  
                                <li class="account"><a href="updateAccount.php" id="update-button">Update E-mail/password</a></li>
                                <li class="account"><a href="unregister.php" id="unregister-button">Unregister</a></li>
                            </ul>  
                        </li>
                        <li>
                            <form class="controlbox navbar-form" name="Log out" id="logout" action="index.php" method="POST">
                                <input type="hidden" name="op" value="logout"/>
                                <input type="hidden" name="username" value="<?php echo $_SESSION["username"]; ?>" />
                                <input type="submit" value="Log out"/>
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
        <!-- hleður inn upplýsingum um hvað notendur eru búnir að sjá margar myndir -->
        <div class="content">   
            <?php
                $db = new PDO('sqlite:../db/users.db');
                $place = 1;
                $percent = 0;
                foreach ($db->query("SELECT COUNT(seen), uName FROM seen WHERE seen='true' GROUP BY uName ORDER BY COUNT(seen) DESC") as $rass) {
                    $percent = $rass["COUNT(seen)"];
                    echo    "<div class=\"place\">";
                    echo    "<div class=\"ranklist\">";
                    echo        "<h2>".$place.".&nbsp;</h2>";
                    echo        "<h3 >".$rass["uName"]."</h3>";
                    echo        "<div  id=\"progressbar\">";
                    echo            "<div style=\"width:".$percent."%\">";
                    echo                $percent."%";
                    echo            "</div>";
                    echo        "</div>";
                    echo    "</div>";
                    echo    "</div>";
                    $place++;
                }
            ?>
        </div>
    </div>
    <script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="../js/script.js"></script>
    <script type="text/javascript" src="../js/jquery.flippy.min.js"></script>
    <script type="text/javascript" src="../js/fancybox/source/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="../js/user.js"></script>
    <script type="text/javascript" src="../js/sha1.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
</body>

</html>