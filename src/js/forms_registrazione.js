function formhashr(form, password) {
    const msg = document.getElementById('informazioni');

    if(document.getElementsByName('nome_registrazione')[0].value === "") {
        msg.innerHTML = "Campo Nome Vuoto";
    } else if (document.getElementsByName('cognome_registrazione')[0].value === "") {
        msg.innerHTML = "Campo Cognome Vuoto";
    } else if (document.getElementsByName('email_registrazione')[0].value === "") {
        msg.innerHTML = "Campo Email Vuoto";
    } else if (document.getElementById('check_password').value !== document.getElementById('spassword').value) {
        msg.innerHTML = "Password non coincidono";
    } else {
        // Crea un elemento di input che verrà usato come campo di output per la password criptata.
        const password_registrazione = document.createElement("input");
        // Aggiungi un nuovo elemento al tuo form.
        form.appendChild(password_registrazione);
        password_registrazione.name = "password_registrazione";
        password_registrazione.type = "hidden"
        password_registrazione.value = hex_sha512(password.value);
        // Assicurati che la password non venga inviata in chiaro.
        password.value = "";
        // Come ultimo passaggio, esegui il 'submit' del form.
        form.submit();
    }  
 }

 function controllo_password() {
    // Initialize variables
    const input = document.getElementById('spassword');
    const password = input.value;
    const controllo = document.getElementById('check_password');
    const but = document.getElementById('rbutton');
    const msg = document.getElementById('informazioni');
    let sicurezza = 0;
  
  
    // Check password length
    if (password.length > 8) {
        sicurezza += 1;
    }
  
    // Check for mixed case
    if (password.match(/[a-z]/) && password.match(/[A-Z]/) && (password.length > 4)) {
        sicurezza += 1;
    }
  
    // Check for numbers
    if (password.match(/\d/) && (password.length > 4)) {
        sicurezza += 1;
    }
  
    // Check for special characters
    if (password.match(/[^a-zA-Z\d]/) && (password.length > 4)) {
        sicurezza += 1;
    }
  
    // Return results
    if (sicurezza < 2) {
        input.style.boxShadow = '0px 15px 10px rgba(154, 3, 30, 0.4)';
        controllo.style.boxShadow = 'none'
        controllo.disabled = true;
        but.disabled = true;
        msg.innerHTML= 'Password troppo debole';
    } else if (sicurezza === 2) {
        input.style.boxShadow = '0px 15px 10px rgba(251, 139, 36, 0.4)';
        controllo.disabled = false;
        msg.innerHTML= 'Password mediamente sicura';
    } else if (sicurezza >= 3) {
        input.style.boxShadow = '0px 15px 10px rgba(81, 186, 84, 0.4)';
        msg.innerHTML= 'Password molto sicura';
    }
  }

function password_uguali() {
    const controllo = document.getElementById('check_password');
    const password_controllo = controllo.value;
    const password = document.getElementById('spassword').value;
    const but = document.getElementById('rbutton');
    const msg = document.getElementById('informazioni');
    if (password == password_controllo) {
        controllo.style.boxShadow = '0px 15px 10px rgba(81, 186, 84, 0.4)';
        but.disabled = false;
        msg.innerHTML= '';
    } else {
        controllo.style.boxShadow = '0px 15px 10px rgba(154, 3, 30, 0.4)';
        but.disabled = true;
        msg.innerHTML= 'Password non coincidono';
    }
}