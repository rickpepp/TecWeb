<?php
    require_once("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {
        $tipo = $_GET["t"];
        $idElemento = $_GET["e"];

        switch($tipo){
            case "c":
                $categorie = $dbh -> getCategorieSeguite($_SESSION["user_id"]);
                $count = 0;
                foreach($categorie as  $categoria){
                    if($categoria["idcategoria"] == $idElemento){
                        $dbh -> deleteCatSeguita($idElemento, $_SESSION["user_id"]);
                        break;
                    }else{
                        $count++;
                    }
                }
                if($count != 0){
                    $dbh -> insertCatSeguita($idElemento, $_SESSION["user_id"]);
                }

            case "f":
                $following = $dbh -> getFollowing($_SESSION["user_id"]);
                $count = 0;
                foreach($following as  $follow){
                    if($follow["idcategoria"] == $idElemento){
                        $dbh -> deleteCatSeguita($idElemento, $_SESSION["user_id"]);
                        break;
                    }else{
                        $count++;
                    }
                }
                if($count != 0){
                    $dbh -> insertCatSeguita($idElemento, $_SESSION["user_id"]);
                }
        }
    } else {
        header('login.php');
    }    
?>