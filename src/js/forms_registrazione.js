function formhashr(form, password) {
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

 function controllo_password() {
    // Initialize variables
    const input = document.getElementById('spassword');
    const password = input.value;
    const testo = document.getElementById('sicurezza');
    const controllo = document.getElementById('check_password');
    let sicurezza = 0;
    let consiglio = "";
  
    // Check password length
    if (password.length < 8) {
        consiglio += "Make the password longer. ";
    } else {
        sicurezza += 1;
    }
  
    // Check for mixed case
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
        sicurezza += 1;
    } else {
        consiglio += "Use both lowercase and uppercase letters. ";
    }
  
    // Check for numbers
    if (password.match(/\d/)) {
        sicurezza += 1;
    } else {
        consiglio += "Include at least one number. ";
    }
  
    // Check for special characters
    if (password.match(/[^a-zA-Z\d]/)) {
        sicurezza += 1;
    } else {
        consiglio += "Include at least one special character. ";
    }
  
    // Return results
    if (sicurezza < 2) {
        input.style.boxShadow = '0px 15px 10px rgba(154, 3, 30, 0.4)';
        controllo.disabled = true;
    } else if (sicurezza === 2) {
        input.style.boxShadow = '0px 15px 10px rgba(251, 139, 36, 0.4)';
        controllo.disabled = false;
    } else if (sicurezza >= 3) {
        input.style.boxShadow = '0px 15px 10px rgba(81, 186, 84, 0.4)';
    }
  }

function password_uguali() {
    const controllo = document.getElementById('check_password');
    const password_controllo = controllo.value;
    const password = document.getElementById('spassword').value;
    if (password == password_controllo) {
        controllo.style.boxShadow = '0px 15px 10px rgba(81, 186, 84, 0.4)';
    } else {
        controllo.style.boxShadow = '0px 15px 10px rgba(154, 3, 30, 0.4)';
    }
}