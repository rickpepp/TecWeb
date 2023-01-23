<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {
        //Base Template
        //Head
        $templateParams["titolo"] = "TinkleArt - Notifiche";
        $templateParams["iconaTab"] = "Home.png";
        $templateParams["css"] = "../css/home.css";
    

        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION['user_id']);
        $templateParams["following"] = $dbh -> getFollowing(4,$_SESSION['user_id']);

        //Section
        $templateParams["section"] = "elenco_notifiche.php";
        $templateParams["post"] = $dbh -> getPost(5,$_SESSION['user_id']);

        require '../libs/base.php';
    } else {
        header('login.php');
    }

    
?>