<h1>Follower</h1>
<ul>
    <?php foreach($templateParams["followerElenco"] as $persona):?>
    <li>
        <a href="#">
            <img src="<?php echo UPLOAD_PROF.$persona["imgpersona"] ?>" alt="Foto Profilo" class="icone"/>
            <h2><?php echo $persona["nome"]." ".$persona["cognome"] ?></h2>
        </a>
        <input type="<?php echo $persona["tipoBottone"]?>" value="<?php echo $persona["testoBottone"]?>"  onclick="setFollowing(<?php echo $persona['idpersona']?>)" id="<?php echo "per_smartphone_".$persona['idpersona']?>">
    </li>
    <?php endforeach; ?>
</ul>   