<h1>Following</h1>
<ul>
    <?php foreach($templateParams["followingElenco"] as $persona):?>
    <li>
        <a href="#">
            <img src="<?php echo UPLOAD_PROF.$persona["imgpersona"] ?>" alt="Foto Profilo" class="icone"/>
            <h2><?php echo $persona["nome"]." ".$persona["cognome"] ?></h2>
        </a>
        <input type="button" value="Non seguire pi&ugrave;" onclick="setFollowing(<?php echo $persona['idpersona'] ?>)" id="<?php echo "per_smartphone_".$persona['idpersona']?>">
    </li>
    <?php endforeach; ?>
</ul>   
