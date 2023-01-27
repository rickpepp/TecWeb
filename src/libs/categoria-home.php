<?php if($templateParams["categoria"]==null): ?>filo" class=
    <p>CATEGORIA NON TROVATA</p>
<?php else: ?>
<div class="titolo">
    <h1><?php echo $templateParams["categoria"][0]["nomecategoria"];?> </h1>
    <input type="<?php echo $templateParams["categoria"][0]["tipoBottone"] ?>" value="<?php echo $templateParams["categoria"][0]["testoBottone"] ?>;" onclick="setCategorie(<?php echo $_GET['idcategoria'] ?>)" id="cat_smartphone_<?php echo $_GET['idcategoria'] ?>"/>
</div>
<?php if($templateParams["categoriePost"]==null): ?>
    <p>NESSUN POST TROVATO PER QUESTA CATEGORIA</p>
<?php else: ?>
<?php foreach($templateParams["categoriePost"] as $post):?>
    <div class="post">
    <div>
        <img src="<?php echo UPLOAD_PROF.$post["imgpersona"]?>" alt="Foto Profilo" class="icone" onclick="location.href='persona.php?idpersona=<?php echo $post["idpersona"]; ?>'"/>
        <div>
            <h2>
                <a href="../views/persona.php?idpersona=<?php echo $post["idpersona"]?>"><?php echo $post["nome"]." ".$post["cognome"] ?></a>
                <br />
                <?php echo substr($post["datapost"], 0, -9) ?>
            </h2>
        </div>
        <img src="<?php echo UPLOAD_DIR.$post["imgcategoria"]?>" alt="Categoria_<?php echo $categoria["nomecategoria"]; ?>" class="icone"/>
        </div>
    <p>
        <?php echo $post["testopost"] ?>
    </p>
    <?php if(strlen($post["imgpost"]) > 0):?>
        <img src="<?php echo UPLOAD_POST.$post["imgpost"]?>" alt="Immagine" class="post"/>
    <?php endif; ?>
    <div>
        <img src="../img/<?php include "check_like.php" ?>" alt="Bottone Like" class="icone" id="like_<?php echo $post["idpost"] ?>" onclick="like(<?php echo $post["idpost"] ?>)"/>
        <?php if($post["idpersona"] == $templateParams["mioprofilo"][0]["idpersona"]):?>
        <img src="<?php echo UPLOAD_DIR.$templateParams["iconaMod"]?>" alt="Modifica Post" class="icone" onclick="location.href='gestisci-post.php?action=2&id=<?php echo $post["idpost"]; ?>'"/>
        <img src="<?php echo UPLOAD_DIR.$templateParams["iconaElimina"]?>" alt="Elimina Post" class="icone" onclick="location.href='gestisci-post.php?action=3&id=<?php echo $post["idpost"]; ?>'"/>
        <?php endif;?>
        <img src="../img/icone/Commenti.png" alt="Bottone Commenti" class="icone" onclick="show_comments(<?php echo $post["idpost"] ?>)"/>
    </div>
</div>
<!-- Contenitore Commenti -->
<div class="invisible_container_commenti" id="<?php echo $post["idpost"] ?>"></div>
<?php endforeach; ?>
<?php endif; ?>
<?php endif; ?>