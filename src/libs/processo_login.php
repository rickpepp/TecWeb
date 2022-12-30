<?php
    require_once 'database.php';
    require_once 'functions.php';
    require_once 'bootstrap.php';
    sec_session_start();
    if(isset($_POST['email_accesso'], $_POST['password_accesso'])) { 
        $email = $_POST['email_accesso'];
        $password = $_POST['password_accesso']; // Recupero la password criptata.
        if($dbh->login($email, $password, $dbh) == true) {
           // Login eseguito
           echo 'Success: You have been logged in!';
        } else {
           // Login fallito
           $_GET['error'] = '1';
           echo 'Non va';
        }
     } else { 
        // Le variabili corrette non sono state inviate a questa pagina dal metodo POST.
        echo 'Invalid Request';
     }
?>