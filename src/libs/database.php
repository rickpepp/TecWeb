<?php 
    class DatabaseHelper{
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
         if ($stmt = $this->db->prepare("SELECT idpersona, email, password, salt, imgpersona FROM persona WHERE email = ? LIMIT 1")) { 
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_id, $username, $db_password, $salt, $imgpersona);
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
                     $_SESSION['imgpersona'] = $imgpersona;
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

      //Elenco delle categorie non seguite
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
               if($lengthCat < $numeroCat){
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
               if($lengthCat < $numeroCat){
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

      //Elenco delle persone seguite
      public function getFollowing($idPersona){
         $stmt = $this->db->prepare("SELECT idpersona, nome, cognome, imgpersona FROM segui_persona, persona WHERE personasegue=? AND personaseguita=idpersona");
         $stmt->bind_param('i',$idPersona);
         $stmt->execute();
         $result = $stmt->get_result();
         $result->fetch_all(MYSQLI_ASSOC);
         return $result;
      }

      //Elenco delle persone seguite con limite
      public function getFollowingNum($numeroPer, $idPersona) {
         $stmt = $this->db->prepare("SELECT idpersona, nome, cognome, imgpersona FROM segui_persona, persona WHERE personasegue=? AND personaseguita=idpersona LIMIT ?");
         $stmt->bind_param('ii',$idPersona, $numeroPer);
         $stmt->execute();
         $result = $stmt->get_result();
         $result->fetch_all(MYSQLI_ASSOC);
         return $result;
      }

      //Elenco delle persone che mi seguono
      public function getFollower($idPersona){
         $stmt = $this->db->prepare("SELECT idpersona, nome, cognome, imgpersona FROM persona, segui_persona WHERE segui_persona.personaseguita = ? AND segui_persona.personasegue = persona.idpersona;");
         $stmt->bind_param('i',$idPersona);
         $stmt->execute();
         $result = $stmt->get_result();
         $result->fetch_all(MYSQLI_ASSOC);

         //Prendo dal db le persone che seguo e mi segno i loro id
         $personeSeguite = $this -> getFollowing(50,$idPersona);
         $idPersoneSeguite = array();
         $count = 0;
         foreach($personeSeguite as $persona){
            $idPersoneSeguite[$count] = $persona["idpersona"];
            $count++;
         }
         
         //Mi segno gli id delle persone che mi seguono
         $idFollower = array();
         $count = 0;
         foreach($result as $persona){
            $idFollower[$count] = $persona["idpersona"];
            $count++;
         } 

         //Divido in due array le persone che seguo e quelle no            
         $idFollowerNonSeguiti = array_diff($idFollower,$idPersoneSeguite);
         $idFollowerSeguiti = array_intersect($idFollower,$idPersoneSeguite); 

         //Creo i due array follower grazie agli arrray id
         $sizeFollowerS = 0;
         $followerSeguiti = array();
         if(count($idFollowerSeguiti) != 0){
            foreach($result as $persona){
               if($persona["idpersona"] == $idFollowerSeguiti[$sizeFollowerS]){
                     $followerSeguiti[$sizeFollowerS] = $persona;
                     $sizeFollowerS++;
               }
            }
         }

         $sizeFollowerNS = 0;
         $followerNonSeguiti = array();
         if(count($idFollowerNonSeguiti) != 0){
            foreach($result as $persona){
               if($persona["idpersona"] == $idFollowerNonSeguiti[$sizeFollowerNS]){
                     $followerNonSeguiti[$sizeFollowerNS] = $persona;
                     $sizeFollowerNS++;
               }
            }
         }

         //Creo l'array dei follower con il bottone associato
         $size = 0;
         $follower = array();
         if($sizeFollowerS != 0){
            foreach($followerSeguiti as $persona){
               $follower[$size]["idpersona"] = $persona["idpersona"];
               $follower[$size]["nome"] = $persona["nome"];
               $follower[$size]["cognome"] = $persona["cognome"];
               $follower[$size]["imgpersona"] = $persona["imgpersona"];
               if ($this -> isFollowed($idPersona,$persona["idpersona"])) {
                  $follower[$size]["tipoBottone"] = "button";
                  $follower[$size]["testoBottone"] = "Non seguire pi&ugrave";
               } else {
                  $follower[$size]["tipoBottone"] = "submit";
                  $follower[$size]["testoBottone"] = "Segui";
               }
               
               $size++;
            }
         }
         if($sizeFollowerNS != 0){
            foreach($followerNonSeguiti as $persona){
               $follower[$size]["idpersona"] = $persona["idpersona"];
               $follower[$size]["nome"] = $persona["nome"];
               $follower[$size]["cognome"] = $persona["cognome"];
               $follower[$size]["imgpersona"] = $persona["imgpersona"];
               if ($this -> isFollowed($idPersona,$persona["idpersona"])) {
                  $follower[$size]["tipoBottone"] = "button";
                  $follower[$size]["testoBottone"] = "Non seguire pi&ugrave";
               } else {
                  $follower[$size]["tipoBottone"] = "submit";
                  $follower[$size]["testoBottone"] = "Segui";
               }

               $size++;
            }
         }
         return $follower;
      }

      // Elenco dei post da visualizzare nella home
      public function getPost($numPost, $idPersona){
         $stmt = $this->db->prepare("SELECT DISTINCT idpost, imgpost, testopost, datapost, idpersona, nome, cognome, imgpersona, idcategoria, imgcategoria FROM post,segui_categoria,post_ha_categoria,segui_persona, persona, categoria WHERE ((segui_categoria.persona=? && post_ha_categoria.categoria=segui_categoria.categoria) || (segui_persona.personasegue=? && post.persona=segui_persona.personaseguita)) && persona.idpersona=post.persona && categoria.idcategoria=post_ha_categoria.categoria && post_ha_categoria.post=post.idpost ORDER BY post.datapost DESC LIMIT ?");
         $stmt->bind_param('iii',$idPersona, $idPersona, $numPost);
         $stmt->execute();
         $result = $stmt->get_result();
         $result->fetch_all(MYSQLI_ASSOC);
         return $result;
      }

      //Restituisce singolo post (utile per la notifica), inoltre imposta la notifica come visualizzata
      public function getSinglePost($id, $tipo, $idpost){
         $stmt = $this->db->prepare("SELECT DISTINCT idpost, imgpost, testopost, datapost, idpersona, nome, cognome, imgpersona, idcategoria, imgcategoria FROM post, post_ha_categoria, persona, categoria WHERE idpost=? && persona.idpersona=post.persona && post_ha_categoria.post=post.idpost && categoria.idcategoria=post_ha_categoria.categoria");
         $stmt->bind_param('i', $idpost);
         $stmt->execute();
         $result = $stmt->get_result();
         $result->fetch_all(MYSQLI_ASSOC);

         if ($tipo == "commento") {
            $stmt = $this->db->prepare("UPDATE commento SET visualizzato = 1 WHERE idcommento = ?");
            $stmt->bind_param('i',$id);
            $stmt->execute();
         } else if ($tipo == "like") {
            $stmt = $this->db->prepare("UPDATE `like` SET visualizzato = 1 WHERE post = ? AND persona = ?");
            $stmt->bind_param('ii',$idpost, $id);
            $stmt->execute();
         }
         
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
         $stmt = $this->db->prepare("SELECT DISTINCT nome, cognome, post, visualizzato, data, imgpersona, idcommento FROM commento, persona, post WHERE post.persona=? && commento.persona=persona.idpersona && commento.post=post.idpost LIMIT 7");
         $stmt->bind_param('i',$idPersona);
         $stmt->execute();
         $result["commenti"]=$stmt->get_result();
         $result["commenti"]->fetch_all(MYSQLI_ASSOC);

         //Ricerca notifiche likes
         $stmt = $this->db->prepare("SELECT DISTINCT nome, cognome, post, data, visualizzato, imgpersona, persona.idpersona FROM `like`, persona, post WHERE post.persona=? && like.persona=persona.idpersona && like.post=post.idpost LIMIT 7");
         $stmt->bind_param('i',$idPersona);
         $stmt->execute();
         $result["likes"]=$stmt->get_result();
         $result["likes"]->fetch_all(MYSQLI_ASSOC);

         
         //Ricerca notifiche seguiti
         $stmt = $this->db->prepare("SELECT DISTINCT nome, cognome, personasegue, data, visualizzato, imgpersona FROM persona, segui_persona WHERE segui_persona.personaseguita=? && segui_persona.personasegue=persona.idpersona LIMIT 7");
         $stmt->bind_param('i',$idPersona);
         $stmt->execute();
         $result["seguiti"]=$stmt->get_result();
         $result["seguiti"]->fetch_all(MYSQLI_ASSOC);

         return $result;
      }

      //Inserisci categoriaSeguita
      public function insertCatSeguita($idElemento, $idPersona){
         $stmt = $this->db->prepare("INSERT INTO segui_categoria VALUE (?,?);");
         $stmt->bind_param('ii',$idPersona,$idElemento);
         $stmt->execute();
      }

      //Cancella categoriaSeguita
      public function deleteCatSeguita($idElemento, $idPersona){
         $stmt = $this->db->prepare("DELETE FROM segui_categoria WHERE segui_categoria.persona=? AND segui_categoria.categoria=?;");
         $stmt->bind_param('ii',$idPersona,$idElemento);
         $stmt->execute();
      }

      //Inserisci personaSeguita
      public function insertPerSeguita($idElemento, $idPersona){
         $stmt = $this->db->prepare("INSERT INTO segui_persona (personaseguita, personasegue) VALUE (?,?);");
         $stmt->bind_param('ii',$idElemento,$idPersona);
         $stmt->execute();
      }

      //Cancella personaSeguita
      public function deletePerSeguita($idElemento, $idPersona){
         $stmt = $this->db->prepare("DELETE FROM segui_persona WHERE personasegue=? AND personaseguita=?;");
         $stmt->bind_param('ii',$idPersona,$idElemento);
         $stmt->execute();
      }

        //Inserimento post
        public function insertPost($imgpost, $testopost, $datapost, $nlike, $persona){
            $query = "INSERT INTO post (imgpost, testopost, datapost, nlike, persona) 
            VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('sssii',$imgpost, $testopost, $datapost, $nlike, $persona);
            $stmt->execute();
            
            return $stmt->insert_id;
        }
        
        //Modifica post
        public function updatePersonaPost($idpost, $imgpost, $testopost, $datapost, $persona){
            $query = "UPDATE post SET imgpost = ?, testopost = ?, datapost = ? WHERE idpost = ? AND persona = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('sssii',$imgpost, $testopost, $datapost, $idpost, $persona);
            $stmt->execute();
            
            return $stmt->insert_id;
        }
        
        //Cancellazione post
        public function deletePersonaPost($idpost){
            $query = "DELETE FROM commento WHERE post = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i',$idpost);
            $stmt->execute();

            $query = "DELETE FROM `like` WHERE post = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i',$idpost);
            $stmt->execute();

            $query = "DELETE FROM post_ha_categoria WHERE post = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i',$idpost);
            $stmt->execute();

            $query = "DELETE FROM hashtag_ha_post WHERE post = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i',$idpost);
            $stmt->execute();

            $query = "DELETE FROM post WHERE idpost = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i',$idpost);
            $stmt->execute();

            return true;
        }
        
        //Inserimento categorie del post
        public function insertCategoryOfPost($post, $categoria){
            $query = "INSERT INTO post_ha_categoria (post, categoria) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii',$post, $categoria);
            return $stmt->execute();
        }
        
        //Cancellazione categoria dal post
        public function deleteCategoryOfPost($post, $categoria){
            $query = "DELETE FROM post_ha_categoria WHERE post = ? AND categoria = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii',$post, $categoria);
            return $stmt->execute();
        }
        
        //Cancellazione di tutte le categorie del post
        public function deleteCategoriesOfPost($idpost){
            $query = "DELETE FROM post_ha_categoria WHERE post = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i',$idpost);
            return $stmt->execute();
        }

        //Inserimento hashtag del post
        public function insertHashtagOfPost($post, $hashtag){
            $query = "INSERT INTO hashtag_ha_post (post, hashtag) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii',$post, $hashtag);
            return $stmt->execute();
        }
        
        //Cancellazione hashtag dal post
        public function deleteHashtagOfPost($post, $hashtag){
            $query = "DELETE FROM hashtag_ha_post WHERE post = ? AND hashtag = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii',$post, $hashtag);
            return $stmt->execute();
        }
        
        //Cancellazione di tutti gli hashtag del post
        public function deleteAllHashtagOfPost($idpost){
            $query = "DELETE FROM hashtag_ha_post WHERE post = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i',$idpost);
            return $stmt->execute();
        }
        
        //Recupera persona dal suo id
        public function getPersona($idpersona){
            $query = "SELECT idpersona, nome, cognome, descrizione, imgpersona FROM persona WHERE idpersona=?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i',$idpersona);
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        //Recupero post con l'id della persona
        public function getAllPersonaPost($idpersona){
            $stmt = $this->db->prepare("SELECT DISTINCT idpost, imgpost, testopost, datapost, idpersona, nome, cognome, imgpersona FROM post INNER JOIN persona on (persona = idpersona) WHERE idpersona = ? ORDER BY post.datapost DESC;");
            $stmt->bind_param('i',$idpersona);
            $stmt->execute();
            $result = $stmt->get_result();
        
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        //Recupero post con l'id del post e della persona per modificarlo
        public function getPostByIdAndPersona($idpersona, $idpost){
            $query = "SELECT idpost, imgpost, testopost, datapost, (SELECT GROUP_CONCAT(categoria) FROM post_ha_categoria WHERE post=idpost GROUP BY post) as categorie, (SELECT GROUP_CONCAT(hashtag) FROM hashtag_ha_post WHERE post=idpost GROUP BY post) as hashtags FROM post WHERE idpost=? AND persona=?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii',$idpost, $idpersona);
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        //Recupera categoria con l'id
        public function getCategoryFromId($id) {
            $query = "SELECT nomecategoria FROM categoria WHERE idcategoria = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        //Recupera tutti i post di una determinata categoria
        public function getPostByCategory($idcategoria){
            $stmt = $this->db->prepare("SELECT DISTINCT idpost, imgpost, testopost, datapost, idpersona, nome, cognome, imgpersona, nomecategoria, imgcategoria FROM post INNER JOIN post_ha_categoria on (post = idpost) INNER JOIN categoria on (categoria = idcategoria) INNER JOIN persona on (persona = idpersona) WHERE idcategoria = ? ORDER BY post.datapost DESC;");
            $stmt->bind_param('i',$idcategoria);
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        //Aggiorna le informazioni della persona
        public function updatePersona($idpersona, $imgpersona, $descrizione){
            $query = "UPDATE persona SET imgpersona = ?, descrizione = ? WHERE idpersona = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ssi',$imgpersona, $descrizione, $idpersona);
            
            return $stmt->execute();
        }

        //Recupera tutti gli utenti registrati
        public function getPersonaAll() {
            $query = "SELECT * FROM persona";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        //Recupera tutti gli hashtag
        public function getHashtagAll() {
            $query = "SELECT * FROM hashtag";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        //Recupera nome hashtag dall'id
        public function getHashtagFromId($id) {
            $query = "SELECT nomehashtag FROM hashtag WHERE idhashtag = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }

        //Recupera post dall'id dell'hashtag
        public function getPostByHashtagId($id) {
            $stmt = $this->db->prepare("SELECT DISTINCT idpost, imgpost, testopost, datapost, idpersona, nome, cognome, imgpersona FROM post INNER JOIN persona on (persona = idpersona) INNER JOIN hashtag_ha_post on (hashtag_ha_post.post = post.idpost) WHERE hashtag = ? ORDER BY post.datapost DESC;");
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        //Controlla se persona è seguita
        public function isFollowed($idSession, $idPersona) {
            $stmt = $this->db->prepare("SELECT personaseguita, personasegue FROM segui_persona WHERE personaseguita = ? AND personasegue = ?");
            $stmt->bind_param('ii',$idPersona,$idSession);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows == 0) {
                return false;
            } else {
                return true;
            }
        }

        //Controlla se categoria è seguita
        public function isCategoriaSeguita($idSession,$idCategotia) {
         $stmt = $this->db->prepare("SELECT persona, categoria FROM segui_categoria WHERE persona = ? AND categoria = ?");
            $stmt->bind_param('ii',$idSession,$idCategotia);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows == 0) {
                return false;
            } else {
                return true;
            }
        }

        //Imposta le notifiche seguito su visualizzato
        public function seguitoVisualizzato($idSession, $idPersona) {
         $stmt = $this->db->prepare("UPDATE segui_persona SET visualizzato = 1 WHERE personaseguita = ? AND personasegue = ?");
         $stmt->bind_param('ii',$idSession, $idPersona);
         $stmt->execute();
        }
    }   
 ?>