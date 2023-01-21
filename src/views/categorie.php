<?php
    require_once ("../libs/bootstrap.php");
    
    //Base Template
    //Head
    $templateParams["titolo"] = "TinkleArt - Home";
    $templateParams["iconaTab"] = "Tecnologia.png";
    $templateParams["css"] = "categorieelenco.css";

    //Aside
    $templateParams["categorie"] = $dbh -> getCategorie(3,2);
    $templateParams["following"] = $dbh -> getFollowing(2);

    //Section
    $templateParams["section"] = "categorieElenco.php";
    $templateParams["categorieElenco"] = $dbh -> getCategorie($dbh -> getCategorieAll() -> num_rows,2);
    
    require '../libs/base.php';
?>
