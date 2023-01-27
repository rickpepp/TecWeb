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
        <script src="../js/ricerca.js"></script>
    </head>
    <body>
        <header>
            <a href="javascript:history.go(-1)" class="phone"><img src="../img/icone/Indietro.png" alt="Bottone Indietro"/></a>
            <img src="../img/icone/TinkleArt.png" alt="TinkleArt" class="logo" onclick="location.href='../views/home.php'"/>
            <div class="web cerca" onclick="location.href='ricerca.php'">
                <a href="ricerca.php" class="web cerca">Cerca</a>
                <img src="../img/icone/Cerca.png" alt="Bottone Cerca">
            </div>
            <a href="../views/gestisci-post.php?action=1" class="web bottone">+</a>
            <a href="../views/home.php" class="web"><img src="../img/icone/Home.png" alt="Bottone Home" /></a>
            <a class="web"><img src="../img/icone/Notifiche.png" alt="Bottone Notifiche" id="notificheButton"/></a>
            <a href="../views/persona.php?idpersona=<?php echo $_SESSION["user_id"]?>" class="web">
                <img src="<?php echo UPLOAD_PROF.$_SESSION['imgpersona']?>" alt="Bottone Profilo" />
            </a>
            <a class="web"><img src="../img/icone/Menu.png" alt="Bottone Impostazioni" id="impostazioniButton"/></a>
        </header>
        <main>
            <aside class="web">
                <h2>Categorie</h2>
                <ul>
                    <?php foreach($templateParams["categorie"] as $categoria): ?>
                    <li>
                        <a href="../views/categoria.php?idcategoria=<?php echo $categoria["idcategoria"]?>">
                            <div>
                                <img src="<?php echo UPLOAD_DIR.$categoria["imgcategoria"]; ?>" alt="Icona" class="icone"/>
                                <h3><?php echo $categoria["nomecategoria"]?></h3>
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
                <h2>Following</h2>
                <ul>
                    <?php foreach($templateParams["following"] as $following): ?>
                    <li>
                        <a href="../views/persona.php?idpersona=<?php echo $following["idpersona"]?>">
                            <div>
                                <img src="<?php echo UPLOAD_PROF.$following["imgpersona"]; ?>" alt="Foto Profilo" class="icone"/>
                                <h3><?php echo $following["nome"]." ".$following["cognome"]; ?></h3>
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
                        <a href="../template/privacy.html">
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
            <a href="gestisci-post.php?action=1" class="bottone">+</a>
            <a href="ricerca.php"><img src="../img/icone/Cerca.png" alt="Bottone Cerca" class="icone"></a>
            <a href="../views/persona.php?idpersona=<?php echo $_SESSION["user_id"]?>">
                <img src="<?php echo UPLOAD_PROF.$_SESSION['imgpersona']?>" alt="Bottone Profilo" class="icone">
            </a>
        </footer>
    </body>
</html>