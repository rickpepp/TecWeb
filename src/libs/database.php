<?php 
    class DatabaseHelper{
        private $db;

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
        public function deletePersonaPost($idpost, $idpersona){
            $query = "DELETE FROM post WHERE idpost = ? AND persona = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii',$idpost, $idpersona);
            $stmt->execute();
            var_dump($stmt->error);
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
    }   
 ?>