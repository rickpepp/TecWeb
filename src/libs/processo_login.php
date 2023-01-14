<?php
    require_once 'database.php';
    require_once 'functions.php';
    require_once 'bootstrap.php';

    sec_session_start();

    if(isset($_POST['email_accesso'], $_POST['password_accesso'])) { 
        $email = $_POST['email_accesso'];
        $password = $_POST['password_accesso']; // Recupero la password criptata.

        if($dbh -> login($email, $password, $dbh) == true) {
           // Login eseguito
           header('Location: ../views/home.php');
        } else {
           // Login fallito
           header('Location: ../views/pagina_informativa.php?title=Accesso Negato&msg=La seguente email/password non sono corretti.');
        }
     } else { 
        // Le variabili corrette non sono state inviate a questa pagina dal metodo POST.
        header('Location: ../views/pagina_informativa.php?title=Invalid Request&msg=Invalid Request');
     }
?>