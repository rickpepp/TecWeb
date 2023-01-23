<?php
    require_once 'bootstrap.php';
    require_once 'database.php';
    require_once 'functions.php';

    sec_session_start();

    //Funzione che restituisce il logo se la notifica è stata visualizzata oppure no
    function logo_notifica($value) {
        if ($value == 0) {
            echo "NotificaNuova";
        } else {
            echo "NotificaVista";
        }
    }

    //Controllo se l'utente è loggato
    if ($dbh -> login_check()) {
        $risultato_db = $dbh -> get_notifiche($_SESSION["user_id"]);
    } else {
        header("../views/login.php");
    }
    

    //Una volta ottenuto dal db le notifiche, le riorganizzo in un array $risultato
    $i=0;

    //Parte Commenti
    foreach ($risultato_db["commenti"] as $commento) {
        $risultato[$i]["tipo"] = "commento";
        $risultato[$i]["nome"] = $commento["nome"];
        $risultato[$i]["cognome"] = $commento["cognome"];
        $risultato[$i]["oggetto"] = $commento["post"];
        $risultato[$i]["data"] = $commento["data"];
        $risultato[$i]["post"] = $commento["post"];
        $risultato[$i]["visualizzato"] = $commento["visualizzato"];
        $i++; 
    }

    //Parte Like
    foreach ($risultato_db["likes"] as $like) {
        $risultato[$i]["tipo"] = "like";
        $risultato[$i]["nome"] = $like["nome"];
        $risultato[$i]["cognome"] = $like["cognome"];
        $risultato[$i]["oggetto"] = $like["post"];
        $risultato[$i]["data"] = $like["data"];
        $risultato[$i]["post"] = $like["post"];
        $risultato[$i]["visualizzato"] = $like["visualizzato"];
        $i++; 
    }

    //Parte seguiti
    foreach ($risultato_db["seguiti"] as $seguito) {
        $risultato[$i]["tipo"] = "seguito";
        $risultato[$i]["nome"] = $seguito["nome"];
        $risultato[$i]["cognome"] = $seguito["cognome"];
        $risultato[$i]["oggetto"] = $seguito["personasegue"];
        $risultato[$i]["data"] = $seguito["data"];
        $risultato[$i]["visualizzato"] = $seguito["visualizzato"];
        $i++; 
    }

    //Ordino l'array in base alla data in ordine decrescente
    array_multisort(array_column($risultato,"data"),SORT_DESC,$risultato);

    //Aggiungo le notifiche (max 7)
    for ($i=0; $i < 7 && $i < count($risultato); $i++) {
        //HTML diverso in base alla notifica
        switch ($risultato[$i]["tipo"]) {
            //Notifica commento
            case "commento":
                echo '<li>
                    <a href="../views/get_single_post.php?id='.$risultato[$i]["post"].'">
                        <div>
                            <img src="../img/';
                            logo_notifica($risultato[$i]["visualizzato"]);
                            echo '.png" alt="Icona Notifica" class="icone visualizzato_'.$risultato[$i]["visualizzato"].'"/>
                            <h2>'.$risultato[$i]["nome"].' '.$risultato[$i]["cognome"].' ha commentato un tuo post</h2>
                        </div>
                    </a>
                </li>';
                break;

            //Notifica Like
            case "like":
                echo '<li>
                    <a href="../views/get_single_post.php?id='.$risultato[$i]["post"].'">
                        <div>
                            <img src="../img/';
                            logo_notifica($risultato[$i]["visualizzato"]);
                            echo '.png" alt="Icona Notifica" class="icone visualizzato_'.$risultato[$i]["visualizzato"].'"/>
                            <h2>'.$risultato[$i]["nome"].' '.$risultato[$i]["cognome"].' ha messo like ad un tuo post</h2>
                        </div>
                    </a>
                </li>';
                break;
            
            //Notifica Seguito
            case "seguito":
                echo '<li>
                    <a href="#">
                        <div>
                            <img src="../img/';
                            logo_notifica($risultato[$i]["visualizzato"]);
                            echo '.png" alt="Icona Notifica Nuova" class="icone visualizzato_'.$risultato[$i]["visualizzato"].'" />
                            <h2>'.$risultato[$i]["nome"].' '.$risultato[$i]["cognome"].' ha iniziato a seguirti</h2>
                        </div>
                    </a>
                </li>';
                break;
        }
    }

?>