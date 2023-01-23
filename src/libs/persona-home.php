<div class="info">
    <?php if(isset($templateParams["formmsg"])):?>
        <p><?php echo $templateParams["formmsg"]; ?></p>
    <?php endif; ?>
    <div class="modifica">
        <img src="<?php echo UPLOAD_PROF.$templateParams["persona"][0]["imgpersona"]?>" alt="Foto Profilo"/>
        <?php if($templateParams["persona"][0]["idpersona"] == $templateParams["mioprofilo"][0]["idpersona"]):?>
            <img src="<?php echo UPLOAD_DIR.$templateParams["iconaMod"]?>" alt="Modifica Profilo" class="icone" onclick="location.href='gestisci-profilo.php?idpersona=<?php echo $templateParams["persona"][0]["idpersona"]; ?>'"/>
        <?php endif; ?>
        <div>
            <h2><?php echo $templateParams["persona"][0]["nome"]?>
                <br />
                <?php echo $templateParams["persona"][0]["cognome"]?></h2>
        </div>
    </div>
    <p>
    <?php if (strlen($templateParams["persona"][0]["descrizione"] > 0)):?>
        <?php echo $templateParams["persona"][0]["descrizione"]; ?>
    <?php else:?>
        Inserire Descrizione
    <?php endif;?>
    </p>
    <input type="button" value="FOLLOWER">
</div>
<?php foreach($templateParams["personaPost"] as $post):?>
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
    </div>
    <p>
        <?php echo $post["testopost"] ?>
    </p>
    <?php if(strlen($post["imgpost"]) > 0):?>
        <img src="<?php echo UPLOAD_POST.$post["imgpost"]?>" alt="Immagine" class="post"/>
    <?php endif; ?>
    <div>
        <img src="../img/<?php include "check_like.php" ?>" alt="Bottone Like" class="icone" id="like_<?php echo $post["idpost"] ?>" onclick="like(<?php echo $post["idpost"] ?>)"/>
        <?php if($templateParams["persona"][0]["idpersona"] == $templateParams["mioprofilo"][0]["idpersona"]):?>
        <img src="<?php echo UPLOAD_DIR.$templateParams["iconaMod"]?>" alt="Modifica Post" class="icone" onclick="location.href='gestisci-post.php?action=2&id=<?php echo $post["idpost"]; ?>'"/>
        <img src="<?php echo UPLOAD_DIR.$templateParams["iconaElimina"]?>" alt="Elimina Post" class="icone" onclick="location.href='gestisci-post.php?action=3&id=<?php echo $post["idpost"]; ?>'"/>
        <?php endif;?>
        <img src="../img/icone/Commenti.png" alt="Bottone Commenti" class="icone" onclick="show_comments(<?php echo $post["idpost"] ?>)"/>
    </div>
</div>
<!-- Contenitore Commenti -->
<div class="invisible_container_commenti" id="<?php echo $post["idpost"] ?>"></div>
<?php endforeach; ?>