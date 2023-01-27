<?php 
    $persona = $templateParams["persona"]; 
?>
<form action="../libs/processa-profilo.php" method="POST" enctype="multipart/form-data" >
    <h1>Modifica Profilo</h1>
        <ul>
            <li>
                <label class="imgp" for="imgpersona">Inserisci immagine</label><input type="file" name="imgpersona" id="imgpersona" />
            </li>
            <li>
                <img class="profilo" src="<?php echo UPLOAD_PROF.$persona[0]["imgpersona"]; ?>" alt="" />
            </li>
            <li>
                <label for="descrizione">Descrizione:</label><textarea id="descrizione" name="descrizione" maxlength="500"><?php echo $persona[0]["descrizione"]; ?></textarea>
            </li>
            <li>
                <input type="button" name="submitAnnulla" onclick="location.href='persona.php?idpersona=<?php echo $templateParams["mioprofilo"][0]["idpersona"];?>'" value="Annulla" />
                <input type="submit" name="submitSalva" value="Salva" />
            </li>
        </ul>    
        <input type="hidden" name="oldimg" value="<?php echo $persona[0]["imgpersona"]; ?>" />
</form>   