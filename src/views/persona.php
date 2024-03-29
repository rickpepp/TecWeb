<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");


    sec_session_start();
    //Gestione del profilo diversificando se e' il profilo personale o di un altra persona
    if ($dbh -> login_check()) {
        $templateParams["titolo"] = "TinkleArt - Profilo Persona";
        $templateParams["css"] = "persona.css";
        $templateParams["iconaTab"] = "Home.png";
        $templateParams["iconaMod"] = "Modifica.png";
        $templateParams["iconaElimina"] = "Elimina.png";

        if(isset($_GET["formmsg"])){
            $templateParams["formmsg"] = $_GET["formmsg"];
        }
        
        //Aside
        $templateParams["categorie"] = $dbh -> getCategorie(3,$_SESSION['user_id']);
        $templateParams["following"] = $dbh -> getFollowingNum(4,$_SESSION['user_id']);

        //Section
        $templateParams["section"] = "persona-home.php";
        $templateParams["mioprofilo"] = $dbh -> getPersona($_SESSION["user_id"]);
        $templateParams["persona"] = $dbh -> getPersona($_GET["idpersona"]);
        if ($_SESSION["user_id"] != $_GET["idpersona"]) {
                $templateParams["persona_e_seguita"] =$dbh -> isFollowed($_SESSION["user_id"],$_GET["idpersona"]);
        }

        if (isset($_GET["visualizzato"]) && $_GET["visualizzato"] == 1) {
            $dbh -> seguitoVisualizzato($_SESSION["user_id"], $_GET["idpersona"]);
        }
        $templateParams["personaPost"] = $dbh -> getAllPersonaPost($_GET["idpersona"]);
        require '../libs/base.php';

    } else {
        header('location: ../views/login.php');
    }
?>