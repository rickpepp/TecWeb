<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {
        if($_POST["action"]==1){
            //Inserimento
            $testopost = htmlspecialchars($_POST["testopost"]);
            $datapost = date("Y-m-d-H-i-s");
            $persona = $_SESSION["user_id"];
            $categorietot = $dbh->getCategorieAll();
            $hashtagtot = $dbh->getHashtagAll();
            $categorie_inserite = array();
            $hashtag_inseriti = array();
            $nlike = 0;
            foreach($categorietot as $categoria){
                if(isset($_POST["categoria_".$categoria["idcategoria"]])){
                    array_push($categorie_inserite, $categoria["idcategoria"]);
                }
            }
            foreach($hashtagtot as $hashtag){
                if(isset($_POST["hashtag_".$hashtag["idhashtag"]])){
                    array_push($hashtag_inseriti, $hashtag["idhashtag"]);
                }
            }
            //controlla che sia presente il testo ed almeno una categoria
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
                        foreach($hashtag_inseriti as $hashtag){
                            $ris = $dbh->insertHashtagOfPost($id, $hashtag);
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
            //modifica
            $testopost = htmlspecialchars($_POST["testopost"]);
            $datapost = date("Y-m-d-H-i-s");
            $idpost = $_POST["idpost"];
            $persona = $_SESSION["user_id"];
            $categorietot = $dbh->getCategorieAll();
            $categorie_inserite = array();
            $hashtagtot = $dbh->getHashtagAll();
            $hashtag_inseriti = array();

            if(isset($_FILES["imgpost"]) && strlen($_FILES["imgpost"]["name"])>0){
                list($result, $msg) = uploadImage(UPLOAD_POST, $_FILES["imgpost"]);
            }
            else{
                $msg = $_POST["oldimg"];
                $result = 1;
            }
            if($result != 0){
                $imgpost = $msg;

                foreach($categorietot as $categoria){
                    if(isset($_POST["categoria_".$categoria["idcategoria"]])){
                    array_push($categorie_inserite, $categoria["idcategoria"]);
                    }
                }
                //controlla che sia presente il testo ed almeno una categoria
                if ($testopost != null && count($categorie_inserite) != 0) {

                    $id = $dbh->updatePersonaPost($idpost, $imgpost, $testopost, $datapost, $persona);
                    $categorievecchie = explode(",", $_POST["categorie"]);
    
                    $categoriedaeliminare = array_diff($categorievecchie, $categorie_inserite);
                    foreach($categoriedaeliminare as $categoria){
                        $ris = $dbh->deleteCategoryOfPost($idpost, $categoria);
                    }
                    $categoriedainserire = array_diff($categorie_inserite, $categorievecchie);
                    foreach($categoriedainserire as $categoria){
                        $ris = $dbh->insertCategoryOfPost($idpost, $categoria);
                    }
    
                    foreach($hashtagtot as $hashtag){
                        if(isset($_POST["hashtag_".$hashtag["idhashtag"]])){
                        array_push($hashtag_inseriti, $hashtag["idhashtag"]);
                        }
                    }
                    $hashtagvecchi = explode(",", $_POST["hashtags"]);
    
                    $hashtagdaeliminare = array_diff($hashtagvecchi, $hashtag_inseriti);
                    foreach($hashtagdaeliminare as $hashtag){
                        $ris = $dbh->deleteHashtagOfPost($idpost, $hashtag);
                    }
    
                    $hashtagdainserire = array_diff($hashtag_inseriti, $hashtagvecchi);
                    foreach($hashtagdainserire as $hashtag){
                        $ris = $dbh->insertHashtagOfPost($idpost, $hashtag);
                    }
    
                    $msg = "Modifica completata correttamente!";
                    
                }
                else {
                    $msg = "Inserire una descrizione ed almeno una categoria";
                }
            }
            header("location: ../views/persona.php?formmsg=".$msg."&idpersona=".$persona);

        }

        if($_POST["action"]==3){
            //cancellazione
            $idpost = $_POST["idpost"];
            $persona =  $_SESSION["user_id"];
            $dbh->deleteCategoriesOfPost($idpost);
            $dbh->deleteAllHashtagOfPost($idpost);
            $dbh->deletePersonaPost($idpost, $persona);
            
            $msg = "Cancellazione completata correttamente!";
            header("location: ../views/persona.php?formmsg=".$msg."&idpersona=".$persona);
        }

    } else {
        header('location: ../views/login.php');
    }
?>