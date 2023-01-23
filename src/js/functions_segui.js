function setFollowing(idElemento){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        document.getElementById(idElemento).innerHTML = this.responseText;
    }
    xhttp.open("GET", "setCatEPer.php?e="+idElemento+"&t=f", true);
    xhttp.send();
}

function setCategorie(idElemento){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        document.getElementById(idElemento).innerHTML = this.responseText;
    }
    xhttp.open("GET", "setCatEPer.php?e="+idElemento+"&t=c", true);
    xhttp.send();
}

