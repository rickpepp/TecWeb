<h1>Follower</h1>
<ul>
    <?php foreach($templateParams["followerElenco"] as $persona):?>
    <li>
        <a href="#">
            <img src="../img/<?php echo $persona["imgpersona"] ?>" alt="Foto Profilo" class="icone"/>
            <h2><?php echo $persona["nome"]." ".$persona["cognome"] ?></h2>
        </a>
        <input type="<?php echo $persona["tipoBottone"]?>" value="<?php echo $persona["testoBottone"]?>">
    </li>
    <?php endforeach; ?>
</ul>   