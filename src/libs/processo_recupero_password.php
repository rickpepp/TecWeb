<?php
    require_once 'database.php';
    require_once 'functions.php';
    require_once 'bootstrap.php';

    if(isset($_POST["email_registrazione"])) {
        $email = $_POST["email_registrazione"];

        //Generiamo un codice di recupero utile alla nuova impostazione della password
        $recupero = genera_codice(124);
        $recupero2 = genera_codice(4);

        if($dbh->aggiungi_recupero($email,$recupero.$recupero2)) {
            //HTML body email, con codice di recupero in elemento hidden
            $body="<!DOCTYPE html>
            <html lang=\"it\" class=\"one_column_everywhere\">
                <head>
                    <title>TinkleArt - Recupero Email</title>
                    <meta charset=\"UTF-8\"/>
                    <meta name=\"viewport\" content=\"width=device-width,initial-scale=1.0\"/>
                </head>
                <body>
                    <header>
                    <h1>Tinkleart</h1>
                    </header>
                    <main>
                        <h2>Cliccare il pulsante sottostante per modificare la propria password</h2>
                        <section>
                            <form method=\"post\" action=\"http://localhost/views/reimposta_password.php\">
                                <p>Questo link è valido per 10 minuti, il codice di recupero è il seguente: $recupero2</p>
                                <input type=\"hidden\" name=\"recupero\" value=\"$recupero\">
                                <button type=\"submit\">
                                Vai alla pagina reimposta password
                                </button>
                            </form>
                        </section>
                    </main>
                </body>
            </html>";

            if(send_mail($_POST["email_registrazione"], "Messaggio di Prova", $body)) {
                //Email inviata con successo
                echo "<script>window.location.assign('../views/pagina_informativa.php?title=Email Inviata con successo&msg=Aprire la mail nella casella di posta elettronica per cambiare la password del proprio account')</script>";
            } else {
                //Email NON inviata con successo
                header('Location: ../views/pagina_informativa.php?title=Email non Inviata&msg=Email non inviata correttamente, controllare con attenzione i dati inseriti.');
            }
        } else {
            //Email non esistente nel DB
            header('Location: ../views/pagina_informativa.php?title=Account non esistente&msg=L\'email inserita non corrisponde a nessun account, se si desidera usare la mail corrente procedere con la fase di registrazione.');
        }
    }
?>