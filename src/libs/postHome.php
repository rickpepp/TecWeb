<section>
    <h1 class ="web" >Home</h1>
    <?php foreach($templateParams["post"] as $post):?>
    <div class="post">
        <div>
            <img src="../img/<?php echo $post["imgpersona"]?>" alt="Foto Profilo" class="icone" />
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
        <img src="../img/<?php echo $post["imgpost"]?>" alt="Immagine" class="post"/>
        <div>
            <img src="../img/Like.png" alt="Bottone Like" class="icone"/>
            <img src="../img/icone/Commenti.png" alt="Bottone Commenti" class="icone"/>
        </div>
    </div>
    <?php endforeach; ?>
</section>
                