function formhasha(form, password) {
    // Crea un elemento di input che verrà usato come campo di output per la password criptata.
    const password_accesso = document.createElement("input");
    // Aggiungi un nuovo elemento al tuo form.
    form.appendChild(password_accesso);
    password_accesso.name = "password_accesso";
    password_accesso.type = "hidden"
    password_accesso.value = hex_sha512(password.value);
    // Assicurati che la password non venga inviata in chiaro.
    password.value = "";
    // Come ultimo passaggio, esegui il 'submit' del form.
    form.submit();
 }