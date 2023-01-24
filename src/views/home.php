<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {
        //Base Template
        //Head
        $templateParams["titolo"] = "TinkleArt - Home";
        $templateParams["iconaTab"] = "Home.png";

        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION['user_id']);
        $templateParams["following"] = $dbh -> getFollowing(4,$_SESSION['user_id']);

        //Section
        $templateParams["section"] = "postHome.php";
        $templateParams["post"] = $dbh -> getPost(5,$_SESSION['user_id']);
        require '../libs/base.php';
    } else {
        header('location: login.php');
    }

    
?>