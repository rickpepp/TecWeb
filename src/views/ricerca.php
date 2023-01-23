<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {
        $templateParams["titolo"] = "TinkleArt - Ricerca";
        $templateParams["css"] = "ricerca.css";
        $templateParams["iconaTab"] = "Home.png";
        $templateParams["iconaMod"] = "Modifica.png";
        $templateParams["iconaElimina"] = "Elimina.png";

        if(isset($_GET["formmsg"])){
            $templateParams["formmsg"] = $_GET["formmsg"];
        }
        
        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION['user_id']);
        $templateParams["following"] = $dbh -> getFollowing($_SESSION['user_id']);

        //Section
        $templateParams["section"] = "ricerca-home.php";
        $templateParams["mioprofilo"] = $dbh -> getPersona($_SESSION["user_id"]);
        require '../libs/base.php';

    } else {
        header('location: ../views/login.php');
    }
?>