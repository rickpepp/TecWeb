<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    //Rappresenta un singolo post
    //Utilizzato per le notifiche

    if ($dbh -> login_check() && isset($_GET["id"]) && isset($_GET["tipo"]) && isset($_GET["post"])) {
        $templateParams["titolo"] = "TinkleArt - Home";
        $templateParams["iconaTab"] = "Home.png";
        $templateParams["css"] = "../css/home.css";

        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION['user_id']);
<<<<<<< HEAD
        $templateParams["following"] = $dbh -> getFollowingNum(4,$_SESSION['user_id']);
=======
        $templateParams["following"] = $dbh -> getFollowing(4,$_SESSION['user_id']);
>>>>>>> 4f952d5098c895b561be3273e59eaab5ec91516a

        //Section
        $templateParams["section"] = "postHome.php";
        $templateParams["post"] = $dbh -> getSinglePost($_GET["id"], $_GET["tipo"], $_GET["post"]);

        require '../libs/base.php';
    } else {
        header('login.php');
    }
?>