<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {
        //Base Template
        //Head
        $templateParams["titolo"] = "TinkleArt - Impostazioni";
        $templateParams["iconaTab"] = "Menu.png";   
        $templateParams["css"] = "impostazioni.css";

        //Header & Footer
        $templateParams["login"] = $_SESSION['imgpersona'];

        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION["user_id"]);
        $templateParams["following"] = $dbh -> getFollowingNum(4,$_SESSION["user_id"]);

        //Section
        $templateParams["section"] = "impostazioniElenco.php";
        $templateParams["categorieSeguite"] = $dbh ->getCategorieSeguite($_SESSION["user_id"]);
    
        require '../libs/base.php';
    }else {
        header('location: ../views/login.php');
    }    
?>