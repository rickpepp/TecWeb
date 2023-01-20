<?php
   require_once 'database.php';
   require_once 'bootstrap.php';

   //Recupero codice di recupero da 124 bit
   $recupero=$_POST['recupero'];

   $recupero2=$_POST['codice_recupero'];

   //Recupero la password criptata dal form di inserimento.
   $password = $_POST['password_registrazione']; 

   //Crea una chiave casuale
   $random_salt_password = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

   //Crea una password usando la chiave appena creata
   $password = hash('sha512', $password.$random_salt_password);
    
   if($dbh -> reimposta_password($recupero.$recupero2,$password,$random_salt_password)) {
      //Password modificata con successo
      header('Location: ../views/pagina_informativa.php?title=Password Modificata&msg=Password modificata correttamente, ripetere la procedura di login.');
   } else {
      //Password NON modificata
      header('Location: ../views/pagina_informativa.php?title=Nessuna corrispondenzae&msg=Impossibile effettuare l\'operazione di recupero, ripetere l\'operazione. Ricordarsi che il link di recupero password vale 10 minuti.');
   }
?>