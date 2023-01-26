<?php
    require_once 'bootstrap.php';
    require_once 'database.php';

    if (isset($_GET["id"])) {
        //Richiedi al server i commenti relativi al post con id passato in GET
        $comments = $dbh -> get_comments($_GET["id"]);

        //Mostra la sezione per scrivere commento
        echo '<div class="comment">
            <form>
                <span class="textarea" role="textbox" contenteditable id="span_comment"></span>
                <input type="button" class="comment" value="Invia" onclick="publish_comment('.$_GET["id"].')">
            </form> 
            </div>';

        //Mostra i commenti
        foreach ($comments as $comment) {
            echo '<div class="comment" id="comm_'.$comment["idcommento"].'">
                <div>
                    <img src="'.UPLOAD_PROF.$comment["imgpersona"].'" alt="Foto Profilo" class="icone"/>
                    <h3>'.$comment["nome"].' '.$comment["cognome"].'<br>'.$comment["data"].'</h3>
                </div>
                <p>
                    '.$comment["testocommento"].'
                </p>
            </div>';
        }
    }
?>