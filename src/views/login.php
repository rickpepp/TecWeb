<!DOCTYPE html>
<html lang="it" class="login">
    <head>
        <title>TinkleArt - Login</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="icon" type="img/png" href="../img/icone/Amici.png" />
        <link rel="stylesheet" href="../css/login.css"/>
        <script type="text/javascript" src="../js/sha512.js"></script>
        <script type="text/javascript" src="../js/forms_accesso.js"></script>
        <script type="text/javascript" src="../js/forms_registrazione.js"></script>
    </head>
    <body>
        <header>
            <img src="../img/icone/TinkleArt.png" alt="TinkleArt"/>
        </header>
        <main>
            <section>
                <form action="../libs/processo_login.php" method="post" name="login_form">
                    <h2>Effettua l'accesso</h2><br/>
                    <label>Email:<input type="text" name="email_accesso"/></label><br/>
                    <label>Password:<input type="password" name="password_accesso" id="lpassword"/></label>
                    <footer>
                        <input type="button" value="Accedi" class="rosso" onclick="formhasha(this.form, this.form.lpassword);" id="accedi"/><br/>
                        <p id="informazioni_accedi"></p>
                        <br/><a href="recupera_password.php">Password dimenticata</a>
                    </footer>
                </form>
                <footer>
                    <hr/>
                    <input class="verde" onclick="window.location.href='registrati.php';" type="button" value="Registrati"/>
                </footer>
            </section>
            <hr/>
            <aside>
                <form action="../libs/processo_registrazione.php" method="post" name="signup_form">
                    <h2>Crea un nuovo account</h2><br/>
                    <label>Nome:<input type="text" name="nome_registrazione" required/></label><br/>
                    <label>Cognome:<input type="text" name="cognome_registrazione"/></label><br/>
                    <label>Email:<input type="text" name="email_registrazione"/></label><br/>
                    <label>Password:<input type="password" name="password_registrazione" id="spassword" onkeyup="controllo_password()"/></label><br/>
                    <label>Conferma Password:<input type="password" name="cpassword_registrazione" id="check_password" onkeyup="password_uguali()" disabled/></label><br/>
                    <footer>
                        <p id="informazioni"></p>
                        <input type="button" value="Registrati" class="verde" id="rbutton" onclick="formhashr(this.form, this.form.spassword,'registrazione');"/>
                    </footer>
                </form>
            </aside>
        </main>
        <footer id='informativa'>
            <span onclick="hide();">&times;</span>
            <p>Questo sito utilizza unicamente cookie tecnici. Per scoprirne di più vai al link <a href="../template/privacy.html">Informativa sulla privacy.</a></p>
        </footer>
    </body>
</html>