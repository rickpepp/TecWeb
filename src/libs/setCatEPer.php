<?php
    require_once("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()&& isset($_GET["t"]) && isset($_GET["e"])) {
        $tipo = $_GET["t"];
        $idElemento = $_GET["e"];

        switch($tipo){
            case "c":
                $categorie = $dbh -> getCategorieSeguite($_SESSION["user_id"]);
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

            case "f":
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
        header('login.php');
    }    
?>