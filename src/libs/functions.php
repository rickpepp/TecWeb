<?php
    require_once ("bootstrap.php");
    require_once ("database.php");

    
    function getCategorie($n){
        $categorieSeguite = $dbh->getCategorieSeguite($n);
        var_dump($categorieSeguite);
    }
    
?>