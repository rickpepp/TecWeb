<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();
    //Rappresenta tutti i post relativi ad un hashtag
    if ($dbh -> login_check()) {
        $templateParams["titolo"] = "TinkleArt - Hashtag";
        $templateParams["css"] = "hashtag.css";
        $templateParams["iconaTab"] = "Home.png";
        $templateParams["iconaMod"] = "Modifica.png";
        $templateParams["iconaElimina"] = "Elimina.png";
        
        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION['user_id']);
        $templateParams["following"] = $dbh -> getFollowingNum(4,$_SESSION['user_id']);

        //Section
        $templateParams["section"] = "hashtag-home.php";
        $templateParams["mioprofilo"] = $dbh -> getPersona($_SESSION["user_id"]);
        $templateParams["hashtag"] = $dbh -> getHashtagFromId($_GET["idhashtag"]);
        $templateParams["hashtagPost"] = $dbh -> getPostByHashtagId($_GET["idhashtag"]);
        require '../libs/base.php';

    } else {
        header('location: ../views/login.php');
    }
?>