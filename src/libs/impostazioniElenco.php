<h1 class="web phone">Impostazioni </h1>
<a href="../template/privacy.html">
    <div>
        <img src="../img/icone/Privacy.png" alt="Bottone Privacy" />
        <h2>Privacy</h2>
    </div>
</a>
<a href="../views/following.php">
    <div>
        <img src="../img/icone/Amici.png" alt="Bottone Follow" />
        <h2>Following</h2>
    </div>
</a>
<a href="../views/categorie.php">
    <div>
        <img src="../img/icone/Tecnologia.png" alt="Bottone Categorie" />
        <h2>Categorie</h2>
    </div>
</a>
<hr />
<h2>Categorie Seguite</h2>
<ul>
    <?php foreach ($templateParams["categorieSeguite"] as $categoria): ?>
    <li>
        <a href="#">
            <div>
                <img src="<?php echo UPLOAD_DIR.$categoria["imgcategoria"]?>" alt="Bottone Categoria" />
                <h3><?php echo $categoria["nomecategoria"] ?></h3>
            </div>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<hr />
<a href="../libs/logout.php">
    <div>
        <img src="../img/icone/Logout.png" alt="Bottone Logout" />
        <h2>Logout</h2>
    </div>
</a>