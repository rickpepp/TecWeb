<?php
    include 'functions.php';

    //Elimina tutti i valori della sessione
    sec_session_start();
    
    //Recupera i parametri di sessione
    $_SESSION = array();
    
    //Cancella i cookie attuali
    $params = session_get_cookie_params();
    
    //Cancella la sessione
    session_destroy();

    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    
    header('Location: ./');
?>