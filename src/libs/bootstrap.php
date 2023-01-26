<?php
    require_once("database.php");

    //Definisco il percorso delle immagini che si usano di più
    define("UPLOAD_DIR", "../img/icone/");
    define("UPLOAD_PROF", "../img/profilo/");
    define("UPLOAD_POST", "../img/post/");

    //Creazione connessione al DB
    $dbh = new DatabaseHelper("localhost", "secur_user", "8lT]4gtavyMPWF)s", "tinkleart", 3306);
?>