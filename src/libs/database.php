<?php 
    class DatabaseHelper{
        private $db;

        
        //Costruttore per l'accesso  al db
        public function __construct($servername, $username, $password, $dbname){
            $this->db = new mysqli($servername, $username, $password, $dbname);
            if ($this->db->connect_error) {
                die("Connection failed: " . $db->connect_error);
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
            $stmt = $this->db->prepare("SELECT idcategoria, nomecategoria, imgcategoria FROM segui_categoria, categoria WHERE persona=$idPersona AND categoria=idcategoria");
            ///bind
            $stmt->execute();
            $result = $stmt->get_result();
            $result->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
    }
 ?>