//Criptaggio password browser per evitare trasmissioni in chiaro
function formhashr(form, password,funzione) {
    //Salviamo il riferimento al paragrafo utile a trasmettere informazioni all'utente
    const msg = document.getElementById('informazioni');

    //Se prima registrazione controlla se tutti i campi non sono vuoti
    if(funzione === 'registrazione') {
        if(document.getElementsByName('nome_registrazione')[0].value === "") {
            msg.innerHTML = "Campo Nome Vuoto";
            return;
        } else if (document.getElementsByName('cognome_registrazione')[0].value === "") {
            msg.innerHTML = "Campo Cognome Vuoto";
            return;
        } else if (document.getElementsByName('email_registrazione')[0].value === "") {
            msg.innerHTML = "Campo Email Vuoto";
            return;
        } else if (document.getElementById('check_password').value !== document.getElementById('spassword').value) {
            msg.innerHTML = "Password non coincidono";
            return;
        }
    }

    //Crea campo input hidden con password criptata
    const password_registrazione = document.createElement("input");

    form.appendChild(password_registrazione);
    password_registrazione.name = "password_registrazione";
    password_registrazione.type = "hidden"
    password_registrazione.value = hex_sha512(password.value);
    password.value = "";

    //Infine viene inviato il form aggiornato
    form.submit();
 }

//Fornisce feedback sulla sicurezza della password
function controllo_password() {
    //Input password
    const input = document.getElementById('spassword');
    //Valore password
    const password = input.value;
    //Input conferma password
    const controllo = document.getElementById('check_password');
    //Pulsante di registrazione
    const but = document.getElementById('rbutton');
    //Paragrafo per le informazioni
    const msg = document.getElementById('informazioni');
    //Questo valore [0;4] indica la sicurezza della password inserita dall'utente, valori di sicurezza >= 2 saranno accettati ed inseriti nel DB
    let sicurezza = 0;
  
  
    //Controllo lunghezza password
    if (password.length > 8) {
        sicurezza += 1;
    }
  
    //Controllo presenza lettere (solo per lunghezze superiori a 4)
    if (password.match(/[a-z]/) && password.match(/[A-Z]/) && (password.length > 4)) {
        sicurezza += 1;
    }
  
    //Controllo presenza numeri (solo per lunghezze superiori a 4)
    if (password.match(/\d/) && (password.length > 4)) {
        sicurezza += 1;
    }
  
    //Controllo presenza caratteri speciali (solo per lunghezze superiori a 4)
    if (password.match(/[^a-zA-Z\d]/) && (password.length > 4)) {
        sicurezza += 1;
    }
  
    //Risultato
    if (sicurezza < 2) {
        //Password non accettabile
        input.style.boxShadow = '0px 15px 10px rgba(154, 3, 30, 0.4)';
        controllo.style.boxShadow = 'none'
        controllo.disabled = true;
        but.disabled = true;
        msg.innerHTML= 'Password troppo debole';
    } else if (sicurezza === 2) {
        //Password accetabile ma migliorabile
        input.style.boxShadow = '0px 15px 10px rgba(251, 139, 36, 0.4)';
        controllo.disabled = false;
        msg.innerHTML= 'Password mediamente sicura';
    } else if (sicurezza >= 3) {
        //Password buona
        input.style.boxShadow = '0px 15px 10px rgba(81, 186, 84, 0.4)';
        msg.innerHTML= 'Password molto sicura';
    }
  }

//Controlla che le password inserite siano uguali, per evitare spiacevoli sviste agli utenti
function password_uguali() {
    //Input conferma password
    const controllo = document.getElementById('check_password');
    //Valore password di conferma
    const password_controllo = controllo.value;
    //Valore password 1
    const password = document.getElementById('spassword').value;
    //Pulsante di registrazione
    const but = document.getElementById('rbutton');
    //Paragrafo informazioni
    const msg = document.getElementById('informazioni');

    if (password == password_controllo) {
        //Password uguali, OK
        controllo.style.boxShadow = '0px 15px 10px rgba(81, 186, 84, 0.4)';
        but.disabled = false;
        msg.innerHTML= '';
    } else {
        //Password non coincidono
        controllo.style.boxShadow = '0px 15px 10px rgba(154, 3, 30, 0.4)';
        but.disabled = true;
        msg.innerHTML= 'Password non coincidono';
    }
}