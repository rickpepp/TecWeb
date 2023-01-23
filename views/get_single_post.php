<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    //Rappresenta un singolo post

    if ($dbh -> login_check() && isset($_GET["id"])) {
        $templateParams["titolo"] = "TinkleArt - Home";
        $templateParams["iconaTab"] = "Home.png";
    

        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION['user_id']);
        $templateParams["following"] = $dbh -> getFollowing($_SESSION['user_id']);

        //Section
        $templateParams["section"] = "postHome.php";
        $templateParams["post"] = $dbh -> getSinglePost($_GET["id"]);

        require '../libs/base.php';
    } else {
        header('login.php');
    }
?>