<?php
    require_once 'database.php';
    require_once 'bootstrap.php';
    $nome= $_POST['nome_registrazione']; 
    $cognome= $_POST['cognome_registrazione']; 
    $email = $_POST['email_registrazione']; 
    // Recupero la password criptata dal form di inserimento.
    $password = $_POST['password_registrazione']; 
    // Crea una chiave casuale
    $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
    // Crea una password usando la chiave appena creata.
    $password = hash('sha512', $password.$random_salt);
    // Inserisci a questo punto il codice SQL per eseguire la INSERT nel tuo database
    // Assicurati di usare statement SQL 'prepared'.
    $dbh->sign_up($nome, $cognome, $email, $password, $random_salt);
?>