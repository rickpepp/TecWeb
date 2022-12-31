<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
    
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

    function send_mail($destinantion,$oggetto,$messaggio) {
        $mail = new PHPMailer(true);

        try {
            $mail -> isSMTP();
            $mail -> Host = gethostbyname('smtp.gmail.com');
            $mail -> SMTPAuth = true;
            $mail -> Username = '';
            $mail -> Password = '';
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

            $mail -> setFrom('');

            $mail -> addAddress($destinantion);

            $mail -> isHTML(true);

            $mail -> Subject = $oggetto;
            $mail -> Body = $messaggio;

            $mail -> send();
            return true;
        } catch (Exception $e) {
            return false;
        }

        
    }
?>