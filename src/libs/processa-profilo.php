<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    if ($dbh -> login_check()) {
            $descrizione = htmlspecialchars($_POST["descrizione"]);
            $persona = $_SESSION["user_id"];
            //controlla che sia stata caricata l'immagine altrimenti recupera quella precedente
            if(isset($_FILES["imgpersona"]) && strlen($_FILES["imgpersona"]["name"])>0){
                list($result, $msg) = uploadImage(UPLOAD_PROF, $_FILES["imgpersona"]);
            }
            else{
                $msg = $_POST["oldimg"];
                $result = 1;
            }
            if($result != 0){
                $imgpersona = $msg;
                $dbh->updatePersona($persona, $imgpersona, $descrizione);
                $msg = "Modifica completata correttamente!";
            }
            header("location: ../views/persona.php?formmsg=".$msg."&idpersona=".$persona);

    } else {
        header('location: ../views/login.php');
    }

?>