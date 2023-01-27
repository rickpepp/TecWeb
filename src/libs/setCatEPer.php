<?php
    require_once("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    //Controlla se la variabile GET è istanziata per poter apportare modifiche ai bottoni segui
    if ($dbh -> login_check()&& isset($_GET["t"]) && isset($_GET["e"])) {
        $tipo = $_GET["t"]; //Tiene conto di che tipodi  bottone c'è in partenza
        $idElemento = $_GET["e"]; //Tiene traccia dell'elemento da modificare

        switch($tipo){
            case "c": //Caso in cui  si vuole seguire o no una categoria
                $categorie = $dbh -> getCategorieSeguite($_SESSION["user_id"]);
                //Controlla se segue qualche categoria e nel caso non ne seguisse nessuna fa direttamente l'inserimento
                if($categorie->num_rows == 0){
                    $dbh -> insertCatSeguita($idElemento, $_SESSION["user_id"]);
                    echo "Si";
                    return;
                }else{
                    foreach($categorie as  $categoria){
                        if($categoria["idcategoria"] == $idElemento){
                            $dbh -> deleteCatSeguita($idElemento, $_SESSION["user_id"]);
                            echo "Non";
                            return;
                        }
                    }
                    $dbh -> insertCatSeguita($idElemento, $_SESSION["user_id"]);
                    echo "Si";
                    return;
                }

            case "f": //Caso in cui si vuole seguire o no una persona 
                $following = $dbh -> getFollowing($_SESSION["user_id"]);
                    foreach($following as $persona){
                        if($persona["idpersona"] == $idElemento){
                            $dbh -> deletePerSeguita($idElemento, $_SESSION["user_id"]);
                            echo "Non";
                            return;
                        }
                    }
                    $dbh -> insertPerSeguita($idElemento, $_SESSION["user_id"]);
                    echo "Si";
                    return;
        }
    } else {
        header('location: ../views/login.php');
    }    
?>