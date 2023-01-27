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
            <section class="centrato">
                <h1><?php echo $_GET["title"] ?></h1>
                <p><?php echo $_GET["msg"] ?></p>
                <a href="../views/login.php">Torna alla pagina di accesso</a>
            </section>
        </main>
    </body>
</html>