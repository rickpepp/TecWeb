<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {
        //Base Template
        //Head
        $templateParams["titolo"] = "TinkleArt - Home";
        $templateParams["iconaTab"] = "Tecnologia.png";
        $templateParams["css"] = "categorieelenco.css";

        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION["user_id"]);
        $templateParams["following"] = $dbh -> getFollowing(4,$_SESSION["user_id"]);

        //Section
        $templateParams["section"] = "categorieElenco.php";
        $templateParams["categorieElenco"] = $dbh -> getCategorie($dbh -> getCategorieAll() -> num_rows,$_SESSION["user_id"]);
        
        require '../libs/base.php';
    }else {
        header('login.php');
    }   
?>
