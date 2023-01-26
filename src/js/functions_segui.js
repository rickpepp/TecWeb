//Cambia pulsante
function setPulsante(risposta,bottone,bottone_smartphone) {
    if (risposta === "Si"){
        if (bottone !== null) {
            bottone.type = "button";
            bottone.value = 'Non seguire più';
        }
        
        //Settare i valori solo se i pulsanti smartphone sono presenti
        if (bottone_smartphone !== null) {
            bottone_smartphone.type = "button";
            bottone_smartphone.value = 'Non seguire più';
        }
    } else {
        if (bottone !== null) {
            bottone.type = "submit";
            bottone.value = "Segui";
        }
        
        //Settare i valori solo se i pulsanti smartphone sono presenti
        if (bottone_smartphone !== null) {
            bottone_smartphone.type = "submit";
            bottone_smartphone.value = 'Segui';
        }
    }
}

//Ajax pulsante segui/non seguire più following
function setFollowing(idElemento){
    const xhttp = new XMLHttpRequest();
    const bottone =  document.getElementById("per_"+idElemento);
    const bottone_smartphone =  document.getElementById("per_smartphone_"+idElemento);

    xhttp.onload = function() {
        setPulsante(this.responseText,bottone,bottone_smartphone);
    }

    xhttp.open("GET", "../libs/setCatEPer.php?e="+idElemento+"&t=f");
    xhttp.send();
}

//Ajax pulsante segui/non seguire più categorie
function setCategorie(idElemento){
    const xhttp = new XMLHttpRequest();
    const bottone =  document.getElementById("cat_"+idElemento);
    const bottone_smartphone =  document.getElementById("cat_smartphone_"+idElemento);

    xhttp.onload = function() {
        setPulsante(this.responseText,bottone,bottone_smartphone);
    }

    xhttp.open("GET", "../libs/setCatEPer.php?e="+idElemento+"&t=c");
    xhttp.send();
}

