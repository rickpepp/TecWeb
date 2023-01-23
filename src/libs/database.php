<?php
   //Classe che gestisce i rapport con il DB
   class DatabaseHelper{
      //Puntatore al DB
      private $db;

      //Costruttore
      public function __construct($servername, $username, $password, $dbname, $port){
         $this->db = new mysqli($servername, $username, $password, $dbname, $port);
         //Gestione eccezione
         if ($this->db->connect_error) {
            die("Connection failed: " . $db->connect_error);
         }        
      }

      //Registrazione nuovo utente
      public function sign_up($nome, $cognome, $email, $password, $random_salt) {
         //Controllo che la mail inserita non sia già presente nel DB
         if ($stmt = $this->db->prepare("SELECT idpersona FROM persona WHERE email = ? LIMIT 1")) { 
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows != 0) {
               //Caso già presente
               return false;
            } else {
               //E-mail non presente nel DB, quindi si procede con l'inserimento
               if ($insert_stmt = $this->db->prepare("INSERT INTO persona (nome, cognome, email, password, salt) VALUES (?, ?, ?, ?, ?)")) {    
                  $insert_stmt->bind_param('sssss', $nome, $cognome, $email, $password, $random_salt); 
                  $insert_stmt->execute();
                  return true;
               }
            }
         }  
      } 

      //Login utente
      public function login($email, $password) {
         // Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
         if ($stmt = $this->db->prepare("SELECT idpersona, email, password, salt FROM persona WHERE email = ? LIMIT 1")) { 
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_id, $username, $db_password, $salt);
            $stmt->fetch();

            $password = hash('sha512', $password.$salt); // codifica la password usando una chiave univoca.

            if($stmt->num_rows == 1) { // se l'utente esiste
               // verifichiamo che non sia disabilitato in seguito all'esecuzione di troppi tentativi di accesso errati.
               if($this->checkbrute($user_id) == true) { 
                  // Account disabilitato (richiamo alla pagina informativa)
                  header('Location: ../views/pagina_informativa.php?title=Account Disabilitato&msg=Per questioni di sicurezza abbiamo disabilitato il seguente account, in seguito a svariati tentativi di accesso falliti.');
                  return false;
               } else {
                  if($db_password == $password) { // Verifica che la password memorizzata nel database corrisponda alla password fornita dall'utente.
                     // Password corretta!            
                     $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.
                     $user_id = preg_replace("/[^0-9]+/", "", $user_id); // ci proteggiamo da un attacco XSS
                     $_SESSION['user_id'] = $user_id; 
                     $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // ci proteggiamo da un attacco XSS
                     $_SESSION['username'] = $username;
                     $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
                     // Login eseguito con successo.
                     return true;    
                  } else {
                     // Password incorretta.
                     // Registriamo il tentativo fallito nel database.
                     $this->db->query("INSERT INTO tentativi_accesso (persona) VALUES ('$user_id')");
                     return false;
                  }
               }
            } else {
               // L'utente inserito non esiste.
               return false;
            }
         }
      }

      //Controllo numero di accessi non andati a buon fine nelle ultime due ore
      private function checkbrute($user_id) {
         if ($stmt = $this->db->prepare("SELECT ora FROM tentativi_accesso WHERE persona = ? AND ora > SUBTIME(CURRENT_TIMESTAMP,'2:0:0')")) { 
            $stmt->bind_param('i', $user_id); 
            // Eseguo la query creata.
            $stmt->execute();
            $stmt->store_result();

            // Verifico l'esistenza di più di 5 tentativi di login falliti.
            if($stmt->num_rows > 5) {
               //Account disabilitato
               return true;
            } else {
               //Account abilitato
               return false;
            }
         }
      }

      //Controllo che l'utente abbia prima fatto il login
      public function login_check() {
         // Verifica che tutte le variabili di sessione siano impostate correttamente
         if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
            $user_id = $_SESSION['user_id'];
            $login_string = $_SESSION['login_string'];
            $username = $_SESSION['username'];     
            $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
            if ($stmt = $this->db->prepare("SELECT password FROM persona WHERE idpersona = ? LIMIT 1")) { 
               $stmt->bind_param('i', $user_id); // esegue il bind del parametro '$user_id'.
               $stmt->execute(); // Esegue la query creata.
               $stmt->store_result();
      
               if($stmt->num_rows == 1) { // se l'utente esiste
                  $stmt->bind_result($password); // recupera le variabili dal risultato ottenuto.
                  $stmt->fetch();
                  $login_check = hash('sha512', $password.$user_browser);
                  if($login_check == $login_string) {
                     // Login eseguito!!!!
                     return true;
                  } else {
                     //  Login non eseguito
                     return false;
                  }
               } else {
                  // Login non eseguito
                  return false;
               }
            } else {
               // Login non eseguito
               return false;
            }
         } else {
            // Login non eseguito
            return false;
         }
      }

      //Ricerca se una mail è presente nel db alla richiesta per reimpostare la password
      public function aggiungi_recupero($email,$recupero) {
         //E' presente un utente con quella mail?
         if ($stmt = $this->db->prepare("SELECT idpersona FROM persona WHERE email = ? LIMIT 1")) { 
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($persona);
            $stmt->fetch();

            if($stmt->num_rows == 0) {
               //No
               return false;
            } else {
               //E' presente
               if ($insert_stmt = $this->db->prepare("INSERT INTO tentativi_recupero (tentativo, persona) VALUES (?, ?)")) {    
                  $insert_stmt->bind_param('ss', $recupero,$persona); 
               $insert_stmt->execute();
               return true;
               }
            }  
         }
      }

      //Reimposta una password di un utente
      public function reimposta_password($codice,$password,$salt_password) {
         //Seleziona nel DB la persona corrispondente al codice inviato per email (tempo massimo per eseguire l'operazione è di 10 minuti)
         if ($stmt = $this->db->prepare("SELECT persona FROM tentativi_recupero WHERE tentativo = ? AND ora > SUBTIME(CURRENT_TIMESTAMP,'00:10:00') LIMIT 1")) { 
            $stmt->bind_param('s', $codice);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($persona);
            $stmt->fetch();

            if($stmt->num_rows == 0) {
               //Nessuna corrispondenza trovata
               return false;
            } else {
               //Corrispondenza trovata
               if ($insert_stmt = $this->db->prepare("UPDATE persona SET password = ?, salt = ? WHERE idpersona = ? ")) {    
                  $insert_stmt->bind_param('sss', $password,$salt_password,$persona); 
                  $insert_stmt->execute();
                  return true;
               }
            }  
         }
      }

      //Elenco di tutte le categorie
      public function getCategorieAll(){
         $stmt = $this->db->prepare("SELECT * FROM categoria ");
         $stmt->execute();
         $result = $stmt->get_result();
         $result->fetch_all(MYSQLI_ASSOC);
         return $result;
     }

     //Elenco delle categorie seguite
     public function getCategorieSeguite($idPersona){
         $stmt = $this->db->prepare("SELECT idcategoria, nomecategoria, imgcategoria FROM segui_categoria, categoria WHERE persona=? AND categoria=idcategoria");
         $stmt->bind_param('i',$idPersona);
         $stmt->execute();
         $result = $stmt->get_result();
         $result->fetch_all(MYSQLI_ASSOC);
         return $result;
     }

     //Elenco delle persone seguite
     public function getFollowing($idPersona){
         $stmt = $this->db->prepare("SELECT idpersona, nome, cognome, imgpersona FROM segui_persona, persona WHERE personasegue=? AND personaseguita=idpersona");
         $stmt->bind_param('i',$idPersona);
         $stmt->execute();
         $result = $stmt->get_result();
         $result->fetch_all(MYSQLI_ASSOC);
         return $result;
     }

     public function getCategorieNonSeguite($idPersona){
         // Prendo dal db l'elenco di tutte le categorie e di quelle seguite
         $categorieAll = $this->getCategorieAll();
         $categorieSeguite = $this->getCategorieSeguite($idPersona);
         
         // Mi segno in un array tutti gli id delle categorie seguite
         $idCatSeguite = array();
         $i = 0;
         foreach($categorieSeguite as $categoriaSeguita){
             $idCatSeguite[$i] = $categoriaSeguita["idcategoria"];
             $i++;
         }

         // Mi segno in un array tutti gli id delle categorie
         $idCatAll = array();
         $count = 0;
         foreach($categorieAll as $categoria){
             $idCatAll[$count] = $categoria["idcategoria"];
             $count++;
         }

         // Calcolo quali sono le categorie che non segui e me le segno in un array tramite gli id
         $count = 0;
         foreach(array_diff($idCatAll,$idCatSeguite) as $categoria){
             $idCatNonSeguite[$count] = $categoria;
             $count++;
         }

         // Creo l'array delle categorie non seguite
         $categorieNonSeguite = array();
         $size = 0;
         foreach($categorieAll as $categoria){
             if($size < $count && $idCatNonSeguite[$size] == $categoria["idcategoria"]){
                 $categorieNonSeguite[$size] = $categoria;
                 $size++;
             }
         }
         
         return $categorieNonSeguite;
     }

     // Elenco delle categorie e i bottoni associati 
     public function getCategorie($numeroCat, $idPersona){
         $categorie = array();
         
         // Prendo dal db l'elenco delle categorie seguite e mi segno quante sono 
         $categorieSeguite = $this->getCategorieSeguite($idPersona); 
         $size = $categorieSeguite->num_rows;
         
         // Creo l'array delle categorie da visualizzare
         $lengthCat = 0;
         if($size < $numeroCat){
             foreach($categorieSeguite as $categoriaSeguita){
                 $categorie[$lengthCat]["idcategoria"] = $categoriaSeguita["idcategoria"];
                 $categorie[$lengthCat]["nomecategoria"] = $categoriaSeguita["nomecategoria"];
                 $categorie[$lengthCat]["imgcategoria"] = $categoriaSeguita["imgcategoria"];
                 $categorie[$lengthCat]["tipoBottone"] = "button";
                 $categorie[$lengthCat]["testoBottone"] = "Non seguire pi&ugrave;";
                 $lengthCat++;
             }
             $categorieNonSeguite = $this->getCategorieNonSeguite($idPersona); 
             foreach($categorieNonSeguite as $categoriaNonSeguita){
                 while($lengthCat < $numeroCat){
                     $categorie[$lengthCat]["idcategoria"] = $categoriaNonSeguita["idcategoria"];
                     $categorie[$lengthCat]["nomecategoria"] = $categoriaNonSeguita["nomecategoria"];
                     $categorie[$lengthCat]["imgcategoria"] = $categoriaNonSeguita["imgcategoria"];
                     $categorie[$lengthCat]["tipoBottone"] = "submit";
                     $categorie[$lengthCat]["testoBottone"] = "Segui";
                     $lengthCat++;
                 }
             }
         }else{
             foreach($categorieSeguite as $categoriaSeguita){
                 while($lengthCat < $numeroCat){
                     $categorie[$lengthCat]["idcategoria"] = $categoriaSeguita["idcategoria"];
                     $categorie[$lengthCat]["nomecategoria"] = $categoriaSeguita["nomecategoria"];
                     $categorie[$lengthCat]["imgcategoria"] = $categoriaSeguita["imgcategoria"];
                     $categorie[$lengthCat]["tipoBottone"] = "button";
                     $categorie[$lengthCat]["testoBottone"] = "Non seguire pi&ugrave;";
                     $lengthCat++;
                 }
             }
         }

         //Mettere che le categorie vengano stampate in base al fatto che ne richiede un totale 
         return $categorie;
     }

     // Elenco dei post da visualizzare nella home
     public function getPost($numPost, $idPersona){
         $stmt = $this->db->prepare("SELECT DISTINCT idpost, imgpost, testopost, datapost, idpersona, nome, cognome, imgpersona, idcategoria, imgcategoria FROM post,segui_categoria,post_ha_categoria,segui_persona, persona, categoria WHERE ((segui_categoria.persona=? && post_ha_categoria.categoria=segui_categoria.categoria) || (segui_persona.personasegue=? && post.persona=segui_persona.personaseguita)) && persona.idpersona=post.persona && categoria.idcategoria=post_ha_categoria.categoria && post_ha_categoria.post=post.idpost ORDER BY post.datapost DESC LIMIT ?;");
         $stmt->bind_param('iii',$idPersona, $idPersona, $numPost);
         $stmt->execute();
         $result = $stmt->get_result();
         $result->fetch_all(MYSQLI_ASSOC);
         return $result;
     }

     //Restituisce singolo post
     public function getSinglePost($numPost){
      $stmt = $this->db->prepare("SELECT DISTINCT idpost, imgpost, testopost, datapost, idpersona, nome, cognome, imgpersona, idcategoria, imgcategoria FROM post, post_ha_categoria, persona, categoria WHERE idpost=? && persona.idpersona=post.persona && post_ha_categoria.post=post.idpost && categoria.idcategoria=post_ha_categoria.categoria");
      $stmt->bind_param('i', $numPost);
      $stmt->execute();
      $result = $stmt->get_result();
      $result->fetch_all(MYSQLI_ASSOC);
      return $result;
  }

   //Elenco dei commenti relativi ad un post
   public function get_comments($idPost){
      $stmt = $this->db->prepare("SELECT idcommento, data, testocommento, nome, cognome, imgpersona FROM commento, persona WHERE idpersona = commento.persona && commento.post = ? ORDER BY commento.data DESC");
      $stmt->bind_param('i',$idPost);
      $stmt->execute();
      $result = $stmt->get_result();
      $result->fetch_all(MYSQLI_ASSOC);
      return $result;
   }

   //Salva un commento
   public function publish_comment($idPost, $idPersona, $testo){
      $stmt = $this->db->prepare("INSERT INTO commento (persona, post, testocommento) VALUES (?, ?, ?)");
      $stmt->bind_param('iis',$idPersona, $idPost, $testo);
      $stmt->execute();
      return true;
   }

   //Controlla se il post in input possiede il like dalla persona in input
   public function getLike($idPost, $idPersona){
      $stmt = $this->db->prepare("SELECT post FROM `like` WHERE persona = ? AND post = ?");
      $stmt->bind_param('ii', $idPersona, $idPost);
      $stmt->execute();
      $stmt->store_result();
      $stmt->fetch();

      if($stmt->num_rows == 0) {
         //Like al post NON è presente
         return false;
      } else {
         //Like al post è presente
         return true;
      }
   }

   //Registra o cancella il like ad un post
   public function set_like($idPost, $idPersona) {
      //Il like è già presente?
      if ($this -> getLike($idPost, $idPersona)) {
         //Caso affermativo, si procede alla cancellazione
         $stmt = $this->db->prepare("DELETE FROM `like` WHERE post = ? AND persona = ?");
         $stmt->bind_param('ii', $idPost, $idPersona);
         $stmt->execute();

         //E decremento nlike nel post
         $stmt = $this->db->prepare("UPDATE post SET nlike = nlike - 1 WHERE idpost = ?");
         $stmt->bind_param('i', $idPost);
         $stmt->execute();
         return false;
      } else {
         //Caso negativo, si procede all'inserimento
         $stmt = $this->db->prepare("INSERT INTO `like` (persona, post) VALUES (?, ?)");
         $stmt->bind_param('ii', $idPersona, $idPost);
         $stmt->execute();

         //E incremento il numero di like nel post
         $stmt = $this->db->prepare("UPDATE post SET nlike = nlike + 1 WHERE idpost = ?");
         $stmt->bind_param('i',  $idPost);
         $stmt->execute();
         return true;
      }
   }

   //Restituisce le notifiche di un utente
   public function get_notifiche($idPersona) {
      //Ricerca notifiche commenti
      $stmt = $this->db->prepare("SELECT DISTINCT nome, cognome, post, visualizzato, data FROM commento, persona, post WHERE post.persona=? && commento.persona=persona.idpersona && commento.post=post.idpost LIMIT 7");
      $stmt->bind_param('i',$idPersona);
      $stmt->execute();
      $result["commenti"]=$stmt->get_result();
      $result["commenti"]->fetch_all(MYSQLI_ASSOC);

      //Ricerca notifiche likes
      $stmt = $this->db->prepare("SELECT DISTINCT nome, cognome, post, data, visualizzato FROM `like`, persona, post WHERE post.persona=? && like.persona=persona.idpersona && like.post=post.idpost LIMIT 7");
      $stmt->bind_param('i',$idPersona);
      $stmt->execute();
      $result["likes"]=$stmt->get_result();
      $result["likes"]->fetch_all(MYSQLI_ASSOC);

      
      //Ricerca notifiche seguiti
      $stmt = $this->db->prepare("SELECT DISTINCT nome, cognome, personasegue, data, visualizzato FROM persona, segui_persona WHERE segui_persona.personaseguita=? && segui_persona.personasegue=persona.idpersona LIMIT 7");
      $stmt->bind_param('i',$idPersona);
      $stmt->execute();
      $result["seguiti"]=$stmt->get_result();
      $result["seguiti"]->fetch_all(MYSQLI_ASSOC);

      return $result;
   }

   public function insertPost($imgpost, $testopost, $datapost, $nlike, $persona){
      $query = "INSERT INTO post (imgpost, testopost, datapost, nlike, persona) 
      VALUES (?, ?, ?, ?, ?)";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('sssii',$imgpost, $testopost, $datapost, $nlike, $persona);
      $stmt->execute();
      
      return $stmt->insert_id;
   }

   public function updatePersonaPost($idpost, $imgpost, $testopost, $datapost, $persona){
      $query = "UPDATE post SET imgpost = ?, testopost = ?, datapost = ? WHERE idpost = ? AND persona = ?";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('sssii',$imgpost, $testopost, $datapost, $idpost, $persona);
      
      return $stmt->execute();
  }

  public function deletePersonaPost($idpost, $idpersona){
      $query = "DELETE FROM post WHERE idpost = ? AND persona = ?";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('ii',$idpost, $idpersona);
      $stmt->execute();
      var_dump($stmt->error);
      return true;
  }

   public function insertCategoryOfPost($post, $categoria){
      $query = "INSERT INTO post_ha_categoria (post, categoria) VALUES (?, ?)";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('ii',$post, $categoria);
      return $stmt->execute();
   }

   public function deleteCategoryOfPost($post, $categoria){
      $query = "DELETE FROM post_ha_categoria WHERE post = ? AND categoria = ?";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('ii',$post, $categoria);
      return $stmt->execute();
  }

   public function deleteCategoriesOfPost($idpost){
      $query = "DELETE FROM post_ha_categoria WHERE post = ?";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('i',$idpost);
      return $stmt->execute();
  }

   public function getPersona($idpersona){
      $query = "SELECT idpersona, nome, cognome, descrizione, imgpersona FROM persona WHERE idpersona=?";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('i',$idpersona);
      $stmt->execute();
      $result = $stmt->get_result();

      return $result->fetch_all(MYSQLI_ASSOC);
   }

   public function getAllPersonaPost($idpersona){
   $stmt = $this->db->prepare("SELECT DISTINCT idpost, imgpost, testopost, datapost, idpersona, nome, cognome, imgpersona FROM post INNER JOIN persona on (persona = idpersona) WHERE idpersona = ? ORDER BY post.datapost DESC;");
   $stmt->bind_param('i',$idpersona);
   $stmt->execute();
   $result = $stmt->get_result();

   return $result->fetch_all(MYSQLI_ASSOC);
   }

   public function getPostByIdAndPersona($idpersona, $idpost){
      $query = "SELECT idpost, imgpost, testopost, datapost, (SELECT GROUP_CONCAT(categoria) FROM post_ha_categoria WHERE post=idpost GROUP BY post) as categorie FROM post WHERE idpost=? AND persona=?";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('ii',$idpost, $idpersona);
      $stmt->execute();
      $result = $stmt->get_result();

      return $result->fetch_all(MYSQLI_ASSOC);
   }

   public function getPostByCategory($idcategoria){
      $stmt = $this->db->prepare("SELECT DISTINCT idpost, imgpost, testopost, datapost, idpersona, nome, cognome, imgpersona, nomecategoria, imgcategoria FROM post INNER JOIN post_ha_categoria on (post = idpost) INNER JOIN categoria on (categoria = idcategoria) INNER JOIN persona on (persona = idpersona) WHERE idcategoria = ? ORDER BY post.datapost DESC;");
      $stmt->bind_param('i',$idcategoria);
      $stmt->execute();
      $result = $stmt->get_result();

      return $result->fetch_all(MYSQLI_ASSOC);
   }
   
   public function updatePersona($idpersona, $imgpersona, $descrizione){
      $query = "UPDATE persona SET imgpersona = ?, descrizione = ? WHERE idpersona = ?";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('ssi',$imgpersona, $descrizione, $idpersona);
      
      return $stmt->execute();
   }

}
?>