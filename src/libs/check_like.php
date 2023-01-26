<?php
    require_once 'bootstrap.php';
    require_once 'database.php';
    require_once 'functions.php';

    //Per Ogni Post Controlla se l'utente ha messo like oppure no
    if ($dbh -> getLike($post["idpost"], $_SESSION["user_id"])) {
        //Caso Affermativo
        echo 'Like2.png';
    } else {
        //Caso Negativo
        echo 'Like.png';
    }
?>