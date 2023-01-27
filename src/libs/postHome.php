
<h1 class ="web">Home</h1>
<?php foreach($templateParams["post"] as $post):?>
<div class="post">
    <div onclick="location.href='../views/persona.php?idpersona=<?php echo $post['idpersona']?>'">
        <img src="<?php echo UPLOAD_PROF.$post["imgpersona"]?>" alt="Foto Profilo" class="icone" />
        <div>
            <h2>
                <?php echo $post["nome"]." ".$post["cognome"] ?>
                <br />
                <?php echo substr($post["datapost"], 0, -9) ?>
            </h2>
        </div>
        <img src="<?php echo UPLOAD_DIR.$post["imgcategoria"]?>" alt="Categoria_Bigiotteria" class="icone"/>
    </div>
    <p>
        <?php echo $post["testopost"] ?>
    </p>
    <?php if($post["imgpost"]!= null){
        echo '<img src='.UPLOAD_POST.$post["imgpost"].' alt="Immagine" class="post"/>';
    }?>
    <div>
        <img src="../img/<?php include "check_like.php" ?>" alt="Bottone Like" class="icone" id="like_<?php echo $post["idpost"] ?>" onclick="like(<?php echo $post["idpost"] ?>)"/>
        <img src="../img/icone/Commenti.png" alt="Bottone Commenti" class="icone" onclick="show_comments(<?php echo $post["idpost"] ?>)"/>
    </div>
</div>
<!-- Contenitore Commenti -->
<div class="invisible_container_commenti" id="<?php echo $post["idpost"] ?>"></div>
<?php endforeach; ?>
                