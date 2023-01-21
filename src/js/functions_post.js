//Funzione Tasto commenti
function show_comments(idpost) {
    //Contenitore Commenti
    const comdiv = document.getElementById(idpost);

    //Se i commenti non sono già aperti richiedi al server i commenti
    if (comdiv.className === "invisible_container_commenti") {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            comdiv.innerHTML = this.responseText;
        }
        xhttp.open("GET", "../libs/get_comments.php?id="+idpost);
        xhttp.send();
        comdiv.className = "visible_container_commenti";
    } else {
        //Altrimenti Cancella il contenuto di comdiv
        comdiv.innerHTML = '';
        comdiv.className = "invisible_container_commenti";
    }
}

//Pubblicazione Commenti
function publish_comment(idpost) {
    const span = document.getElementById("span_comment");

    if (span.textContent !== '') {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            //Refresh Commenti
            document.getElementById(idpost).className = "invisible_container_commenti";
            show_comments(idpost);
        }
        //Richiesta Ajax inserimento nuovo commento
        xhttp.open("GET", "../libs/publish_comment.php?id="+idpost+"&comm="+span.textContent);
        xhttp.send();
    }
}

//Inserimento o Cancellamento like a post
function like(idpost) {
    const like = document.getElementById("like_"+idpost);
    const xhttp = new XMLHttpRequest();

    xhttp.onload = function() {
        //Cambio del logo in base al risultato del DB
        if (this.responseText === 'piace') {
            //Ora risulta il like
            like.src = "../img/Like2.png";
        } else {
            //Ora non risulta il like
            like.src = "../img/Like.png";
        }
    }
    //Richiesta Ajax NOT like
    xhttp.open("GET", "../libs/set_like.php?p="+idpost);
    xhttp.send();
}