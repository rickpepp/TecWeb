<!DOCTYPE html>
<html lang="it" class="one_column_everywhere">
    <head>
        <title>TinkleArt - Recupero Email</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="icon" type="img/png" href="../img/icone/Amici.png" />
        <link rel="stylesheet" href="../css/login.css"/>
    </head>
    <body>
        <header>
            <img src="../img/icone/TinkleArt.png" alt="TinkleArt"/>
        </header>
        <main>
            <section>
                <form method="post" action="../libs/processo_recupero_password.php">
                    <h2>Inserisci mail per recupero password</h2><br>
                    <label>Email<input type="text" name="email_registrazione" required/><br/></label>
                    <footer class="recupera">
                        <input type="submit" value="Recupera" class="verde"/>
                        <a href="../views/login.php">Torna alla pagina di accesso</a>
                    </footer>
                </form>
            </section>
        </main>
    </body>
</html>