<?php
    require_once 'functions.php';

    if(isset($_POST["email_registrazione"])) {
        $email = $_POST["email_registrazione"];
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        // Crea una password usando la chiave appena creata.
        $email = hash('sha512', $email.$random_salt);
        $body="<!DOCTYPE html>
        <html lang=\"it\" class=\"one_column_everywhere\">
            <head>
                <title>TinkleArt - Recupero Email</title>
                <meta charset=\"UTF-8\"/>
                <meta name=\"viewport\" content=\"width=device-width,initial-scale=1.0\"/>
            </head>
            <body>
                <header>
                <h1>Cambio Password</h1>
                </header>
                <main>
                    <section class=\"centrato\">
                        <p>Premere il link sottostante per modificare la propria password</p>
                        <a href=\"http://localhost/views/reimposta_password.php?1=".$random_salt."&2=".$email."\">Vai alla pagina reimposta password</a>
                    </section>
                </main>
            </body>
        </html>";
        if(send_mail($_POST["email_registrazione"],"Messaggio di Prova",$body)) {
            echo "<script>window.location.assign('../views/pagina_post_recupero.php?title=Email Inviata con successo&msg=Aprire la mail nella casella di posta elettronica per cambiare la password del proprio account')</script>";
        } else {
            header('Location: ../views/pagina_post_recupero.php?title=Email non Inviata&msg=Email non inviata correttamente, controllare con attenzione i dati inseriti.');
        }
    }
?>