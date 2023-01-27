<!DOCTYPE html>
<html lang="it" class="one_column_everywhere">
    <head>
        <title>TinkleArt - Reimposta Password</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="../css/login.css"/>
        <link rel="icon" type="img/png" href="../img/icone/Amici.png" />
        <script type="text/javascript" src="../js/sha512.js"></script>
        <script type="text/javascript" src="../js/forms_registrazione.js"></script>
    </head>
    <body>
        <header>
            <img src="../img/icone/TinkleArt.png" alt="TinkleArt"/>
        </header>
        <main>
            <section>
                <form method="post" action="../libs/processo_reimposta_password.php">
                    <h2>Reimposta la nuova password</h2><br/>
                    <label>Codice di Recupero<input type="text" name="codice_recupero"/></label><br/>
                    <label>Password<input type="password" name="password_registrazione" id="spassword" onkeyup="controllo_password()"/></label><br/>
                    <label>Conferma Password<input type="password" name="cpassword_registrazione" id="check_password" onkeyup="password_uguali()" disabled/></label><br/>
                    <input type="hidden" name="recupero" value="<?php echo $_POST["recupero"] ?>"/>
                    <footer class="recupera">
                        <p id="informazioni"></p>
                        <input type="button" class="verde"  value="Reimposta" id="rbutton" onclick="formhashr(this.form, this.form.spassword,'reimpostazione');"/>
                        <a href="login.php">Torna alla pagina di accesso</a>
                    </footer>
                </form>
            </section>
        </main>
    </body>
</html>