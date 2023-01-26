<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();
    //Rappresenta tutti i post di una singola categoria
    if ($dbh -> login_check()) {
        $templateParams["titolo"] = "TinkleArt - Categoria";
        $templateParams["css"] = "categoria.css";
        $templateParams["iconaTab"] = "Home.png";
        $templateParams["iconaMod"] = "Modifica.png";
        $templateParams["iconaElimina"] = "Elimina.png";
        
        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION['user_id']);
        $templateParams["following"] = $dbh -> getFollowingNum(4,$_SESSION['user_id']);

        //Section
        $templateParams["section"] = "categoria-home.php";
        $templateParams["mioprofilo"] = $dbh -> getPersona($_SESSION["user_id"]);
        $templateParams["categoriePost"] = $dbh -> getPostByCategory($_GET["idcategoria"]);
        $templateParams["categoria"] = $dbh -> getCategoryFromId($_GET["idcategoria"]);

        require '../libs/base.php';

    } else {
        header('location: ../views/login.php');
    }
?>