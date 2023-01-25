<?php
    require_once ("../libs/bootstrap.php");
    require_once ("../libs/functions.php");

    sec_session_start();

    $categorietot = $dbh->getCategorieAll();
    $personatot = $dbh->getPersonaAll();
    $hashtagtot = $dbh->getHashtagAll();
    $valore=$_GET["valore"];
    
    //controlla che sia stata inserita almeno una lettera
    if(strlen($valore)>0) {
        $string = '<ul>';
        $hint="";
        //confronta la parola con tutte le categorie ed in caso affermativo aggiorna la variabile con le categorie trovate
        foreach($categorietot as $categoria) {
            if (stristr($categoria["nomecategoria"],$valore)) {
                if ($hint=="") {
                    $hint="<li><a href='categoria.php?idcategoria=" . 
                    $categoria["idcategoria"] . "'>" . "<img src='" . 
                    UPLOAD_DIR.$categoria["imgcategoria"] . "' alt='Categoria_'" . 
                    $categoria["nomecategoria"] . " />" . $categoria["nomecategoria"] . 
                    "</a><input type='submit' value='Segui'></li>";
                } else {
                    $hint= $hint . "<li><a href='categoria.php?idcategoria=" . 
                    $categoria["idcategoria"] . "'>" . "<img src='" . 
                    UPLOAD_DIR.$categoria["imgcategoria"] . "' alt='Categoria_'" . 
                    $categoria["nomecategoria"] . " />" . $categoria["nomecategoria"] . 
                    "</a><input type='submit' value='Segui'></li>";
                }
            }
        }
        //confronta con tutti i nomi e cognomi
        foreach($personatot as $persona) {
            $nomecognome = $persona["nome"] . " " . $persona["cognome"];
            $cognomenome = $persona["cognome"] . " " . $persona["nome"];
            if (stristr($nomecognome,$valore) || stristr($cognomenome,$valore)) {
                if ($hint=="") {
                    $hint="<li><a href='persona.php?idpersona=" . 
                    $persona["idpersona"] . "'>" . "<img src='" . 
                    UPLOAD_PROF.$persona["imgpersona"] . "' alt='Foto Profilo'" . 
                    " />" . $persona["nome"] . " " . $persona["cognome"] .
                    "</a>";
                } else {
                    $hint= $hint . "<li><a href='persona.php?idpersona=" . 
                    $persona["idpersona"] . "'>" . "<img src='" . 
                    UPLOAD_PROF.$persona["imgpersona"] . "' alt='Foto Profilo'" . 
                    " />" . $persona["nome"] . " " . $persona["cognome"] . 
                    "</a>";
                }
                if ($persona["idpersona"] != $_SESSION["user_id"]) {
                    $hint = $hint . "<input type='submit' value='Segui'></li>";
                }
                
            }
        }
        //confronta con tutti gli hashtag
        foreach($hashtagtot as $hashtag) {
            if (stristr($hashtag["nomehashtag"],$valore)) {
                if ($hint=="") {
                    $hint="<li><a href='hashtag.php?idhashtag=" . 
                    $hashtag["idhashtag"] . "'> #" . $hashtag["nomehashtag"] .
                    "</a>";
                } else {
                    $hint= $hint . "<li><a href='hashtag.php?idhashtag=" . 
                    $hashtag["idhashtag"] . "'> #" . $hashtag["nomehashtag"] .
                    "</a>";
                }
            }
        }
        $string = $string . $hint;
        $string = $string . '</ul>';
    } 

    if ($hint=="") {
        $string = "<ul><li>nessuna parola trovata</li></ul>";
    } 
    echo $string;
?> 

