<?php
    require_once 'database.php';
    require_once 'bootstrap.php';

    if(isset($_POST['nome_registrazione']) && isset($_POST['cognome_registrazione']) && isset($_POST['email_registrazione'])) {
        $nome= $_POST['nome_registrazione']; 
        $cognome= $_POST['cognome_registrazione']; 
        $email = $_POST['email_registrazione']; 

        //Recupero la password criptata dal form di inserimento.
        $password = $_POST['password_registrazione']; 

        //Crea una chiave casuale
        $random_salt_password = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

        //Crea una password usando la chiave appena creata.
        $password = hash('sha512', $password.$random_salt_password);

        if($dbh -> sign_up($nome, $cognome, $email, $password, $random_salt_password)) {
            //Pagina informativa di avvenuta registrazione
            header('Location: ../views/pagina_informativa.php?title=Account Inserito con Successo&msg=L\'account è stato inserito correttamente, effettuare il login per accedere alla propria area personale.');
        } else {
            //Caso già presente (richiamo alla pagina informativa con dati in GET)
            header('Location: ../views/pagina_informativa.php?title=Impossibile Aggiungere l\'account.&msg=Email già presente nel database. In caso di password smarrita, eseguire la procedura di recupero della stessa.');
        }
    } else {
        //Elementi POST non settati
        header('Location: ../views/pagina_informativa.php?title=Impossibile Aggiungere l\'account.&msg=Errore nella procedura di registrazione dell\'account.');
    }
?>