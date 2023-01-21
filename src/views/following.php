<?php 
    require_once ("../libs/bootstrap.php");

    //Base template
    //Header
    $templateParams["titolo"] = "TinkleArt - Following";
    $templateParams["iconaTab"] = "Amici.png";
    $templateParams["css"] = "follow.css";

    //Aside
    $templateParams["categorie"] = $dbh -> getCategorie(3,2);
    $templateParams["following"] = $dbh -> getFollowing(2);

    //Section
    $templateParams["section"] = "followingElenco.php";
    $templateParams["followingElenco"] = $dbh -> getFollowing(2);
    
    require '../libs/base.php';
?>