<?php
    require_once 'bootstrap.php';
    require_once 'database.php';
    require_once 'functions.php';

    sec_session_start();

    if (isset($_GET["p"]) && $dbh -> login_check()) {
        //Si esegue l'operazione NOT sul like del post
        if($dbh -> set_like($_GET["p"], $_SESSION["user_id"])) {
            //Ora il like è presente
            echo 'piace';
        } else {
            //Ora il like NON è più presente
            echo 'nonpiace';
        }
    }
?>