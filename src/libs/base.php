<!DOCTYPE html>
<html lang="it">
    <head>
        <title><?php echo $templateParams["titolo"]; ?></title>
        <link rel="icon" type="img/png" href="<?php echo UPLOAD_DIR.$templateParams["iconaTab"]?>"/>
        <link rel="stylesheet" type="text/css" href="../css/home.css" />
        <link rel="stylesheet" type="text/css" href="../css/<?php if(isset($templateParams["css"])){
                        echo $templateParams["css"];}?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta charset="UTF-8" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="../js/functions_post.js"></script>
        <script src="../js/notifiche.js"></script>
        <script src="../js/functions_segui.js"></script>
    </head>
    <body>
        <header>
            <a href="javascript:history.go(-1)" class="phone"><img src="../img/icone/Indietro.png" alt="Bottone Indietro"/></a>
            <img src="../img/icone/TinkleArt.png" alt="TinkleArt" class="logo"/>
            <form class="web">
                <label>Cerca<input type="text" value="Cerca"></label>
                <img src="../img/icone/Cerca.png" alt="Bottone Cerca">
            </form>
            <a href="../libs/gestisci-post.php" class="web bottone">+</a>
            <a href="../views/home.php" class="web"><img src="../img/icone/Home.png" alt="Bottone Home" /></a>
            <a class="web"><img src="../img/icone/Notifiche.png" alt="Bottone Notifiche" id="notificheButton"/></a>
            <a href="#" class="web"><img src="<?php echo "../img/".$_SESSION['imgpersona']?>" alt="Bottone Profilo" /></a>
            <a class="web"><img src="../img/icone/Menu.png" alt="Bottone Impostazioni" id="impostazioniButton"/></a>
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
                        <input type="<?php echo $categoria["tipoBottone"] ?>" value="<?php echo $categoria["testoBottone"] ?>" onclick="setCategorie(<?php echo $categoria['idcategoria']?>)" id="<?php echo "cat_".$categoria['idcategoria']?>" />
                    </li>
                    <?php endforeach; ?>
                    <li>
                        <input type="button" value="Altre" name="altro" onclick='location.href="../views/categorie.php"'/>
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
                        <input type="button" value="Non seguire pi&ugrave;" onclick="setFollowing(<?php echo $following['idpersona']?>)" id="<?php echo "per_".$following['idpersona']?>" />    
                    </li>
                    <?php endforeach; ?>
                    <li>
                        <input type="button" value="Altri" name="altro" onclick='location.href="../views/following.php"' />
                    </li>
                </ul> 
            </aside><section>
                <?php
                    if(isset($templateParams["section"])){
                        require($templateParams["section"]);
                    }
                ?>
            </section>
            <!-- Notifiche esempio -->
            <div id="notifiche" class="notifiche web">
                <ul id="elenco_notifiche">
                    <!-- Elenco delle notifiche -->
                </ul>
            </div>
            <!-- Impostazioni -->
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
                    <li>
                        <a href="../libs/logout.php" >
                            <div>
                                <img src="../img/icone/Logout.png" alt="Icona Logout" class="icone"/>
                                <h2>Logout</h2>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </main>
        <footer>
            <a href="../views/impostazioni.php"><img src="../img/icone/Menu.png" alt="Bottone Impostazioni" class="icone"></a>
            <a href="notifiche_smartphone.php"><img src="../img/icone/Notifiche.png" alt="Bottone Notifiche" class="icone" id="notificheButtonSmartphone"></a>
            <a href="aggiungipost.html" class="bottone">+</a>
            <a href="#"><img src="../img/icone/Cerca.png" alt="Bottone Cerca" class="icone"></a>
            <a href="#"><img src="<?php echo "../img/".$_SESSION['imgpersona']?>" alt="Bottone Profilo" class="icone"></a>
        </footer>
    </body>
</html>