<?php 
    require_once ("../libs/bootstrap.php");

    //Base template
    //Header
    $templateParams["titolo"] = "TinkleArt - Follower";
    $templateParams["iconaTab"] = "Amici.png";
    $templateParams["css"] = "follow.css";

    //Aside
    $templateParams["categorie"] = $dbh -> getCategorie(3,2);
    $templateParams["following"] = $dbh -> getFollowing(2);

    //Section
    $templateParams["section"] = "followerElenco.php";
    $templateParams["followerElenco"] = $dbh -> getFollower(2);
    
    require '../libs/base.php';
?>