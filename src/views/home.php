<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    //Base Template
    //Head
    $templateParams["titolo"] = "TinkleArt - Home";
    $templateParams["iconaTab"] = "Home.png";
    

    //Aside
    $templateParams["categorie"] = $dbh -> getCategorie(3,2);
    $templateParams["following"] = $dbh -> getFollowing(2);

    //Section
    $templateParams["section"] = "postHome.php";
    $templateParams["post"] = $dbh -> getPost(5,2);

    require '../libs/base.php';
?>