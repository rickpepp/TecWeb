<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    //Base Template
    //Head
    $templateParams["titolo"] = "TinkleArt - Home";
    $templateParams["iconaTab"] = "Home.png";

    //Aside
    //$templateParams["categorie"] = $dbh -> getCategorieSeguite(2);
    $templateParams["categorie"] = $dbh -> getCategorie(100,2);
    $templateParams["following"] = $dbh -> getFollowing(1);

    require '../libs/base.php';
?>