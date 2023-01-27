<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();
    //form di modifica del profilo personale
    if ($dbh -> login_check()) {
        $templateParams["titolo"] = "TinkleArt - Gestisci Profilo";
        $templateParams["iconaTab"] = "Home.png";
        $templateParams["css"] = "aggiungipost.css";
        
        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION['user_id']);
        $templateParams["following"] = $dbh -> getFollowing(4,$_SESSION['user_id']);

        //Section
        $templateParams["section"] = "profilo-form.php";
        $templateParams["mioprofilo"] = $dbh -> getPersona($_SESSION["user_id"]);
        $templateParams["persona"] = $dbh -> getPersona($_GET["idpersona"]);
        require '../libs/base.php';

    } else {
        header('location: ../views/login.php');
    }
?>