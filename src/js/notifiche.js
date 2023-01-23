function aggiorna_notifiche() {
    const xhttp = new XMLHttpRequest();

    xhttp.onload = function() {
        //Cosa fare, ottenuto il risultato

        //Aggiungi Elenco Notifiche
        document.getElementById("elenco_notifiche").innerHTML = this.responseText;

        //Aggiorna icona notifiche in base a notifiche visualizzate oppure no
        if (document.getElementsByClassName("visualizzato_0")[0] === undefined) {
            document.getElementById("notificheButton").src = "../img/icone/Notifiche.png";
        } else {
            document.getElementById("notificheButton").src = "../img/icone/Notifiche Nuove.png";
        }
    }

    //Richiesta Ajax notifiche
    xhttp.open("GET", "../libs/notifiche.php");
    xhttp.send();
}

//Aggiorna notifiche all'avvio del sito
aggiorna_notifiche();

//Aggiornamento regolare ogni 5 secondi
setInterval(aggiorna_notifiche,5000);