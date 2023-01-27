<h1>Follower</h1>
<ul>
    <?php foreach($templateParams["followerElenco"] as $persona):?>
    <li>
        <a href="../views/persona.php?idpersona=<?php echo $persona["idpersona"]?>">
            <img src="<?php echo UPLOAD_PROF.$persona["imgpersona"] ?>" alt="Foto Profilo" class="icone"/>
            <h2><?php echo $persona["nome"]." ".$persona["cognome"] ?></h2>
        </a>
        <?php if($persona["idpersona"] != $_SESSION["user_id"]){
            echo "<input type=".$persona["tipoBottone"]." value='".$persona["testoBottone"].";' onclick=\"setFollowing(". $persona['idpersona'].")\""." id=\"per_smartphone_".$persona['idpersona']."\">";
        }?>
    </li>
    <?php endforeach; ?>
</ul>   