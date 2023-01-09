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

    //Funzione per avere l'elenco di tutte le categorie
    public function getCategorie(){
        $stmt = $this->db->prepare("SELECT * FROM categoria ");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
        console.log($result);
    }
    public function getCategorieSeguite(){

    }
 }