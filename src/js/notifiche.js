function aggiorna_notifiche() {
    const xhttp = new XMLHttpRequest();
    const elenco_smartphone = document.getElementById("elenco_notifiche_smartphone");

    xhttp.onload = function() {
        //Cosa fare, ottenuto il risultato

        //Aggiungi Elenco Notifiche su pc
        document.getElementById("elenco_notifiche").innerHTML = this.responseText;

        //Aggiungi Elenco Notifiche su smartphone (Solo se necessario)
        if(elenco_smartphone !== null) {
          elenco_smartphone.innerHTML = this.responseText;
        }

        //Aggiorna icona notifiche in base a notifiche visualizzate oppure no
        if (document.getElementsByClassName("visualizzato_0")[0] === undefined) {
            document.getElementById("notificheButton").src = "../img/icone/Notifiche.png";
            document.getElementById("notificheButtonSmartphone").src = "../img/icone/Notifiche.png";
        } else {
            document.getElementById("notificheButton").src = "../img/icone/Notifiche Nuove.png";
            document.getElementById("notificheButtonSmartphone").src = "../img/icone/Notifiche Nuove.png";
        }
    }

    //Richiesta Ajax notifiche
    if(elenco_smartphone !== null) {
      xhttp.open("GET", "../libs/notifiche.php?device=smartphone");
    } else {
      xhttp.open("GET", "../libs/notifiche.php?device=pc");
    }

    xhttp.send();
}

//Tendina impostazioni
$(document).ready(function(){
    $("#impostazioniButton").click(function(){
      $("#impostazioni").slideToggle(true);
    }); 
});

//Tendina Notifiche
$(document).ready(function(){
  $("#notificheButton").click(function(){
    $("#notifiche").slideToggle(true);
  }); 
});

//Chiude i menù a tendina appena si usa la scrolling bar
//per evitare di lasciare fixed le notifiche e le impostazioni
window.addEventListener('scroll', function() {
    $("#impostazioni").slideUp("fast");
    $("#notifiche").slideUp("fast");
});

//Aggiorna notifiche all'avvio del sito
aggiorna_notifiche();

//Aggiornamento regolare ogni 5 secondi
setInterval(aggiorna_notifiche,5000);