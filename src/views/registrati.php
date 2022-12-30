<!DOCTYPE html>
<html lang="it" class="one_column_everywhere">
    <head>
        <title>TinkleArt - Registrati</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="../css/login.css"/>
        <link rel="icon" type="img/png" href="../img/Amici.png" />
        <script type="text/javascript" src="../js/sha512.js"></script>
        <script type="text/javascript" src="../js/forms_registrazione.js"></script>
    </head>
    <body>
        <header>
            <img src="../img/TinkleArt.png" alt="TinkleArt">
        </header>
        <main>
            <div>
                <form action="../libs/processo_registrazione.php" method="post" name="signup_form">
                    <h2>Crea un nuovo account</h2><br>
                    <label>Nome:<input type="text" name="nome_registrazione"></label><br>
                    <label>Cognome:<input type="text" name="cognome_registrazione"></label><br>
                    <label>Email:<input type="text" name="email_registrazione"></label><br>
                    <label>Password:<input type="password" name="password_registrazione" id="password"></label><br>
                    <label>Conferma Password:<input type="password" name="cpassword_registrazione"></label><br>
                    <footer>
                        <input type="button" value="Registrati" onclick="formhashr(this.form, this.form.password);">
                        <a href="login.html">Sono già registrato</a>
                    </footer>
                </form>
            </div>
        </main>
    </body>
</html>