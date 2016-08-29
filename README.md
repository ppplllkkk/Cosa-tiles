# Cosa-tiles
Snipets y funciones que ayudan en el día a día

public function caracteres_especiales($url)
    {
         $url = strtolower($url);
         //Reemplazamos caracteres especiales latinos
         $find = array('á','é','í','ó','ú','â','ê','î','ô','û','ã','õ','ç','ñ',')','(');
         $repl = array('a','e','i','o','u','a','e','i','o','u','a','o','c','n','','');
         $url = str_replace($find, $repl, $url);
         //Añadimos los guiones
         $find = array(' ', '&amp;', '\r\n', '\n','+');
         $url = str_replace($find, '-', $url);
         //Eliminamos y Reemplazamos los demas caracteres especiales
         $find = array('/[^a-z0-9\-&lt;&gt;]/', '/[\-]+/', '/&lt;{^&gt;*&gt;/');
         $repl = array('', '_', '');
         $url = preg_replace($find, $repl, $url);
         return $url;
    }

    public function truncar_texto($string, $limit, $break=".", $pad="…")
    {
        if(strlen($string) <= $limit)
            return $string;
        if(!(empty($string)) && !(empty($limit)))
        {
            $breakpoint = strpos($string, $break, $limit);

            if( $breakpoint !== false)
            {
                if($breakpoint < strlen($string) - 1)
                {
                    $string = substr($string, 0, $breakpoint) . $pad;
                }
            }
            return $string;
        }
        else
            return false;
    }
    
    
    // CONFIGURACION CORRECTA DE PHPMAILER PARA GMAIL
    
    $mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "email@gmail.com";
$mail->Password = "password";
$mail->SetFrom("example@gmail.com");
$mail->Subject = "Test";
$mail->Body = "hello";
$mail->AddAddress("email@gmail.com");

 if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
    echo "Message has been sent";
 }
