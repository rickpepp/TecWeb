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
            $stmt = $this->db->prepare("SELECT idcategoria, nomecategoria, imgcategoria FROM segui_categoria, categoria WHERE persona=? AND categoria=idcategoria");
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

        //Elenco delle persone seguite
        public function getFollowing($idPersona){
            $stmt = $this->db->prepare("SELECT idpersona, nome, cognome, imgpersona FROM segui_persona, persona WHERE personasegue=? AND personaseguita=idpersona");
            $stmt->bind_param('i',$idPersona);
            $stmt->execute();
            $result = $stmt->get_result();
            $result->fetch_all(MYSQLI_ASSOC);
            return $result;
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
    }
 ?>