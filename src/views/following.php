<?php 
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {

        //Base template
        //Header
        $templateParams["titolo"] = "TinkleArt - Following";
        $templateParams["iconaTab"] = "Amici.png";
        $templateParams["css"] = "elenchi.css";

        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION["user_id"]);
        $templateParams["following"] = $dbh -> getFollowingNum(4,$_SESSION["user_id"]);

        //Section
        $templateParams["section"] = "followingElenco.php";
        $templateParams["followingElenco"] = $dbh -> getFollowing($_SESSION["user_id"]);
        
        require '../libs/base.php';
    }else {
    header('login.php');
    }    
?>