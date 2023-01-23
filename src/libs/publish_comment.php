<?php
    require_once 'bootstrap.php';
    require_once 'database.php';
    require_once 'functions.php';

    sec_session_start();

    //Richiama funzione nel DB per registrare il nuovo commento nel DB
    if (isset($_GET["id"]) && isset($_GET["comm"]) && $dbh -> login_check()) {
        $dbh -> publish_comment($_GET["id"], $_SESSION["user_id"], $_GET["comm"]);
    }
?>