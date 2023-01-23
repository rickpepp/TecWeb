<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {
        if($_POST["action"]==1){
            //Inserisco
            $testopost = htmlspecialchars($_POST["testopost"]);
            $datapost = date("Y-m-d-H-i-s");
            $persona = $_SESSION["user_id"];
            $categorietot = $dbh->getCategorieAll();
            $categorie_inserite = array();
            $nlike = 0;
            foreach($categorietot as $categoria){
                if(isset($_POST["categoria_".$categoria["idcategoria"]])){
                    array_push($categorie_inserite, $categoria["idcategoria"]);
                }
            }
            
            if ($testopost != null && count($categorie_inserite) != 0) {
                if(isset($_FILES["imgpost"]) && strlen($_FILES["imgpost"]["name"])>0){
                    list($result, $msg) = uploadImage(UPLOAD_POST, $_FILES["imgpost"]);
                } 
                else {
                    $result = 1;
                    $msg = "";
                }
                if($result != 0){
                    $imgpost = $msg;
                    $id = $dbh->insertPost($imgpost, $testopost, $datapost, $nlike, $persona);
                    if($id!=false){
                        foreach($categorie_inserite as $categoria){
                            $ris = $dbh->insertCategoryOfPost($id, $categoria);
                        }
                        $msg = "Inserimento completato correttamente!";
                    }
                    else{
                        $msg = "Errore in inserimento!";
                    }
                
                }
            }
            else{
                $msg = "Inserire una descrizione ed almeno una categoria!";
            }
            header("location: ../views/persona.php?formmsg=".$msg."&idpersona=".$persona);
        }

        if($_POST["action"]==2){
            //modifico
            $testopost = htmlspecialchars($_POST["testopost"]);
            $datapost = date("Y-m-d-H-i-s");
            $idpost = $_POST["idpost"];
            $persona = $_SESSION["user_id"];
            $categorietot = $dbh->getCategorieAll();
            $categorie_inserite = array();

            if(isset($_FILES["imgpost"]) && strlen($_FILES["imgpost"]["name"])>0){
                list($result, $msg) = uploadImage(UPLOAD_POST, $_FILES["imgpost"]);
            }
            else{
                $msg = $_POST["oldimg"];
                $result = 1;
            }
            if($result != 0){
                $imgpost = $msg;
                $dbh->updatePersonaPost($idpost, $imgpost, $testopost, $datapost, $persona);

                foreach($categorietot as $categoria){
                    if(isset($_POST["categoria_".$categoria["idcategoria"]])){
                    array_push($categorie_inserite, $categoria["idcategoria"]);
                    }
                }
                $categorievecchie = explode(",", $_POST["categorie"]);

                $categoriedaeliminare = array_diff($categorievecchie, $categorie_inserite);
                foreach($categoriedaeliminare as $categoria){
                    $ris = $dbh->deleteCategoryOfPost($idpost, $categoria);
                }
                $categoriedainserire = array_diff($categorie_inserite, $categorievecchie);
                foreach($categoriedainserire as $categoria){
                    $ris = $dbh->insertCategoryOfPost($idpost, $categoria);
                }

                $msg = "Modifica completata correttamente!";
            }
            header("location: ../views/persona.php?formmsg=".$msg."&idpersona=".$persona);

        }

        if($_POST["action"]==3){
            //cancello
            $idpost = $_POST["idpost"];
            $persona =  $_SESSION["user_id"];
            $dbh->deleteCategoriesOfPost($idpost);
            $dbh->deletePersonaPost($idpost, $persona);
            
            $msg = "Cancellazione completata correttamente!";
            header("location: ../views/persona.php?formmsg=".$msg."&idpersona=".$persona);
        }

    } else {
        header('location: ../views/login.php');
    }
?>