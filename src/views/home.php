<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    //Base Template
    //Head
    $templateParams["titolo"] = "TinkleArt - Home";
    $templateParams["iconaTab"] = "Home.png";

    //Aside
    $templateParams["categorie"] = getCategorie(2);

    require '../libs/base.php';
?>