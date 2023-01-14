<!DOCTYPE html>
<html lang="it">
    <head>
        <title><?php echo $templateParams["titolo"]; ?></title>
        <link rel="icon" type="img/png" href="<?php echo UPLOAD_DIR.$templateParams["iconaTab"]?>"/>
        <link rel="stylesheet" type="text/css" href="../css/home.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta charset="UTF-8" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="../js/provaImpostazioni.js"></script>
        <script src="../js/functions_post.js"></script>
    </head>
    <body>
        <header>
            <a href="#" class="phone"><img src="../img/icone/Indietro.png" alt="Bottone Indietro"/></a>
            <img src="../img/icone/TinkleArt.png" alt="TinkleArt" class="logo"/>
            <form class="web">
                <label>Cerca<input type="text" value="Cerca"></label>
                <img src="../img/icone/Cerca.png" alt="Bottone Cerca">
            </form>
            <a href="aggiungipost.html" class="web bottone">+</a>
            <a  href="../views/home.php" class="web"><img src="../img/icone/Home.png" alt="Bottone Home" /></a>
            <a href="notifiche.html" class="web"><img src="../img/icone/Notifiche.png" alt="Bottone Notifiche" /></a>
            <a href="persona.html" class="web"><img src="../img/Foto.png" alt="Bottone Profilo" /></a>
            <a href="impostazioni.html" class="web"><img src="../img/icone/Menu.png" alt="Bottone Impostazioni" /></a>
        </header>
        <main>
            <aside class="web">
                <h1>Categorie</h1>
                <ul>
                    <?php foreach($templateParams["categorie"] as $categoria): ?>
                    <li>
                        <a href="#">
                            <div>
                                <img src="<?php echo UPLOAD_DIR.$categoria["imgcategoria"]; ?>" alt="Icona" class="icone"/>
                                <h2><?php echo $categoria["nomecategoria"]?></h2>
                            </div>
                        </a>
                        <input type="<?php echo $categoria["tipoBottone"] ?>" value="<?php echo $categoria["testoBottone"] ?>">
                    </li>
                    <?php endforeach; ?>
                    <li>
                        <input type="button" value="Altre" name="altro">
                    </li>
                </ul>
                <hr/>
                <h1>Following</h1>
                <ul>
                    <?php foreach($templateParams["following"] as $following): ?>
                    <li>
                        <a href="">
                            <div>
                                <img src="<?php echo '../img/'.$following["imgpersona"]; ?>" alt="Foto Profilo" class="icone"/>
                                <h2><?php echo $following["nome"]." ".$following["cognome"]; ?></h2>
                            </div>
                        </a>
                        <input type="button" value="Non seguire pi&ugrave;">    
                    </li>
                    <?php endforeach; ?>
                    <li>
                        <input type="button" value="Altri" name="altro">
                    </li>
                </ul> 
            </aside><section>
                <?php
                    if(isset($templateParams["section"])){
                        require($templateParams["section"]);
                    }
                ?>
            </section><!--
            <div id="notifiche" class="notifiche web">
                <ul>
                    
                    ?php foreach():?>
                    ?php endforeach;?>
                
                </ul>
            </div>
            <div id="impostazioni" class="impostazioni web">
                <ul>
                    <li>
                        <a href="#">
                            <div>
                                <img src="../img/icone/Privacy.png" alt="Icona Privacy" class="icone" />
                                <h2>Privacy</h2>
                            </div>
                        </a>
                    </li>
                    <li> onclick nel elemento della lista con funzione di uscire  dall'account e andare alla pagina successiva 
                        o funzione nell'href dell'a??
                        <a href="login.php" >
                            <div>
                                <img src="../img/icone/Logout.png" alt="Icona Logout" class="icone"/>
                                <h2>Logout</h2>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>-->
        </main>
        <footer>
            <a href="impostazioni.html"><img src="../img/icone/Menu.png" alt="Bottone Impostazioni" class="icone"></a>
            <a href="#"><img src="../img/icone/Notifiche.png" alt="Bottone Notifiche" class="icone"></a>
            <a href="aggiungipost.html" class="bottone">+</a>
            <a href="#"><img src="../img/icone/Cerca.png" alt="Bottone Cerca" class="icone"></a>
            <!--<a href="#"><img src="?php echo UPLOAD_DIR.$templateParams["login"][imgpersona]?>" alt="Bottone Profilo" class="icone"></a>-->
        </footer>
    </body>
</html>