function formhasha(form, password) {
   const msg = document.getElementById('informazioni_accedi');

   //Controllo che i campi non siano stati lasciati vuoti
   if (document.getElementsByName('email_accesso')[0].value === "") {
      msg.innerHTML = "Campo Email Vuoto";
   } else if (document.getElementsByName('password_accesso')[0].value === "") {
      msg.innerHTML = "Campo Password Vuoto";
   } else {
      //Crea campo input hidden con password criptata
      const password_accesso = document.createElement("input");

      form.appendChild(password_accesso);
      password_accesso.name = "password_accesso";
      password_accesso.type = "hidden"
      password_accesso.value = hex_sha512(password.value);
      password.value = "";

      //Invia il form definitivamente
      form.submit();
   }
 }

//Rende funzionante la x presente nel footer per la privacy
function hide() {
   const footer = document.getElementById('informativa');
   footer.style.display = 'none';
}

//Nella pagina login, con l'invio preme il pulsante accedi
document.addEventListener("keypress", function(event) {
   if (event.key === "Enter") {
      event.preventDefault();
      document.getElementById("accedi").click();
   }
 });