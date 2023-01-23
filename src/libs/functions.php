<?php
    //Libreria utile per inviare email tramite SMTP
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
    
    //Inizio nuova sessione in modalità sicura
    function sec_session_start() {
        $session_name = 'session_id';
        $secure = false;
        $httponly = true;
        ini_set('session.use_only_cookies', 1);
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name);
        session_start();
        session_regenerate_id();
    }

    //Invio email tramite lib: phpmailer
    function send_mail($destinantion,$oggetto,$messaggio) {
        $mail = new PHPMailer(true);

        try {
            $mail -> isSMTP();
            $mail -> Host = gethostbyname('smtp.gmail.com');
            $mail -> SMTPAuth = true;

            //Inserire qui i dati della email
            $mail -> Username = '';
            $mail -> Password = '';

            //La mail è criptata, quindi non è trasmesso nessun dato in chiaro
            $mail -> STMPSecure = 'ssl';
            $mail -> Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );

            $mail->SMTPDebug = 2;
            $mail -> setFrom('tinkleart.prova@gmail.com');
            $mail -> addAddress($destinantion);
            $mail -> isHTML(true);

            $mail -> Subject = $oggetto;
            $mail -> Body = $messaggio;

            $mail -> send();
            //Email inviata
            return true;
        } catch (Exception $e) {
            //Email non inviata
            return false;
        }
    }

    //Generatore di password casuali (parametro lunghezza come input)
    function genera_codice($lenght) {
        //Simboli disponibili
        $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890%$&*#?';

        $pass = array(); 
        $combLen = strlen($comb) - 1; 

        for ($i = 0; $i < $lenght; $i++) {
            $n = rand(0, $combLen);
            $pass[] = $comb[$n];
        }
        
        return implode($pass);
    }
?>