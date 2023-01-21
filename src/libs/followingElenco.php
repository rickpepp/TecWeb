<h1>Following</h1>
<ul>
    <?php foreach($templateParams["followingElenco"] as $persona):?>
    <li>
        <a href="#">
            <img src="../img/<?php echo $persona["imgpersona"] ?>" alt="Foto Profilo" class="icone"/>
            <h2><?php echo $persona["nome"]." ".$persona["cognome"] ?></h2>
        </a>
        <input type="button" value="Non seguire pi&ugrave;">
    </li>
    <?php endforeach; ?>
</ul>   