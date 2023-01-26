<?php 
    $post = $templateParams["post"]; 

    $azione = getAction($templateParams["azione"])
?>
<form action="../libs/processa-post.php" method="POST" enctype="multipart/form-data" >
    <h1>Gestisci Post</h1>
    <?php if($post==null): ?>
        <p>Post non trovato</p>
    <?php else: ?>
    <?php if(isset($templateParams["formmsg"])):?>
        <p><?php echo $templateParams["formmsg"]; ?></p>
    <?php endif; ?>
        <ul>
            <li>
                <?php if($templateParams["azione"]!=3): ?>
                <label for="imgpost">Img Post</label><input type="file" name="imgpost" id="imgpost" />
                <?php endif; ?>
            </li>
            <li>
                <?php if($templateParams["azione"]!=1): ?>
                <img src="<?php echo UPLOAD_POST.$post["imgpost"]; ?>" alt="" />
                <?php endif; ?>
            </li>
            <li>
                <?php if($templateParams["azione"]!=3): ?>
                <label for="testopost">Descrizione Post:</label><textarea id="testopost" name="testopost" maxlength="500"><?php echo $post["testopost"]; ?></textarea>
                <?php else: ?>
                    <p><?php echo $post["testopost"]; ?></p>
                <?php endif; ?>
                </li>
            <li>
                <ul>
                    <?php if($templateParams["azione"]!=3): ?>
                        <?php foreach($templateParams["categorietot"] as $categoria): ?>
                        <li>
                            <input type="checkbox" id="categoria_<?php echo $categoria["idcategoria"]; ?>" name="categoria_<?php echo $categoria["idcategoria"]; ?>" 
                            <?php 
                                if(in_array($categoria["idcategoria"], $post["categorie"])){ 
                                    echo ' checked="checked" '; 
                                } 
                            ?> />
                            <label for="categoria_<?php echo $categoria["idcategoria"]; ?>"><?php echo $categoria["nomecategoria"]; ?></label>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                <hr>
                <ul>
                    <?php if($templateParams["azione"]!=3): ?>
                        <?php foreach($templateParams["hashtagtot"] as $hashtag): ?>
                        <li>
                            <input type="checkbox" id="hashtag_<?php echo $hashtag["idhashtag"]; ?>" name="hashtag_<?php echo $hashtag["idhashtag"]; ?>" 
                            <?php 
                                if(in_array($hashtag["idhashtag"], $post["hashtags"])){ 
                                    echo ' checked="checked" '; 
                                } 
                            ?> />
                            <label for="hashtag_<?php echo $hashtag["idhashtag"]; ?>">#<?php echo $hashtag["nomehashtag"]; ?></label>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </li>
            <li>
                <input class="altro" type="button" name="submitAnnulla" onclick="location.href='persona.php?idpersona=<?php echo $templateParams["mioprofilo"][0]["idpersona"];?>'" value="Annulla" />
                <input type="submit" name="submitSalva" value="<?php echo $azione; ?>" />
            </li>
        </ul>    

        <?php if($templateParams["azione"]!=1): ?>
            <input type="hidden" name="idpost" value="<?php echo $post["idpost"]; ?>" />
            <input type="hidden" name="categorie" value="<?php echo implode(",", $post["categorie"]); ?>" />
            <input type="hidden" name="hashtags" value="<?php echo implode(",", $post["hashtags"]); ?>" />
            <input type="hidden" name="oldimg" value="<?php echo $post["imgpost"]; ?>" />
        <?php endif;?>

        <input type="hidden" name="action" value="<?php echo $templateParams["azione"]; ?>" />
    <?php endif;?>
</form>   