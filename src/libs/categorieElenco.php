<h1>Categorie</h1>
<ul>
    <?php foreach ($templateParams["categorieElenco"] as $categoria):?>
    <li>
        <a href="#">
            <img src="<?php echo UPLOAD_DIR.$categoria["imgcategoria"] ?>" alt="<?php echo 'Categoria_'.$categoria["nomecategoria"] ?>" class="icone" />
            <h2><?php echo $categoria["nomecategoria"] ?></h2>
        </a>
        <input type="<?php echo $categoria["tipoBottone"] ?>" value="<?php echo $categoria["testoBottone"] ?>" onclick="setCategorie(<?php echo $categoria['idcategoria']?>)" id="cat_smartphone_<?php echo  $categoria['idcategoria']?>"/>
    </li>
    <?php endforeach; ?>
</ul>