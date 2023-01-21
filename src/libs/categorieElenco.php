<h1>Categorie</h1>
<ul>
    <?php foreach ($templateParams["categorieElenco"] as $categoria):?>
    <li>
        <a href="#"><img src="<?php echo UPLOAD_DIR.$categoria["imgcategoria"] ?>" alt="<?php echo 'Categoria_'.$categoria["nomecategoria"] ?>" class="icone" /><?php echo $categoria["nomecategoria"] ?></a>
        <input type="<?php echo $categoria["tipoBottone"] ?>" value="<?php echo $categoria["testoBottone"] ?>">
    </li>
    <?php endforeach; ?>
</ul>