<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {
        if($_GET["action"]!=1){
            $risultato = $dbh->getPostByIdAndPersona($_SESSION["user_id"], $_GET["id"]);
            if(count($risultato)==0){
                $templateParams["post"] = null;
            }
            else{
                $templateParams["post"] = $risultato[0];
                $templateParams["post"]["categorie"] = explode(",", $templateParams["post"]["categorie"]);
            }
        }
        else{
            $templateParams["post"] = getEmptyPost();
        }

        $templateParams["titolo"] = "TinkleArt - Gestisci Post";
        $templateParams["iconaTab"] = "Home.png";
        $templateParams["css"] = "aggiungipost.css";

        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION['user_id']);
        $templateParams["following"] = $dbh -> getFollowing(4,$_SESSION['user_id']);

        //Section
        $templateParams["section"] = "post-form.php";
        $templateParams["mioprofilo"] = $dbh -> getPersona($_SESSION["user_id"]);
        $templateParams["azione"] = $_GET["action"];
        $templateParams["categorietot"] = $dbh -> getCategorieAll();

        require '../libs/base.php';

    } else {
        header('location: ../views/login.php');
    }
?>