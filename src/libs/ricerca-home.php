<h1>Effettua una ricerca</h1>
<form>
    <ul>
        <li>
            <label for="search"><img src="<?php echo UPLOAD_DIR.$templateParams["iconaCerca"]?>" alt="cerca" /></label><input type="search" id="search" name="search" placeholder="ricerca per categoria o per nome utente" onkeyup="show_search(this.value)" />
        </li>
    </ul>
</form>
<div id="livesearch"></div>