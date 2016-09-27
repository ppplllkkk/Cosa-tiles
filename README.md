# Cosas-utiles
Snipets y funciones que ayudan en el día a día

#liberar git en producción
-crear rama para liberar
-vaciar rama en local
-añadir archivos de liberacion
-respaldar produccion
-hacer el commit y psuh de la rama
listo

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
    
    
    // CONFIGURACION CORRECTA DE PHPMAILER PARA GMAIL CON SMTP-RELAY PUERTO 587 CON TLS
    
    $mail = new PHPMailer();
        $mail->IsSMTP();
        //$mail->SMTPAuth = true;
        /* NECESARIO PARA GMAIL */
        $mail->SMTPSecure = 'tls';
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';


        for ($i=0,$n=count($to);$i<$n;$i++)
        $mail->AddAddress($to[$i]);

        $mail->AddAddress("silva.luis.i.18@gmail.com");
        $mail->AddAddress("luis.silva@codice.com");
        $mail->AddAddress("cris@codice.com");

        $mail->Body = $message;
        $mail->Subject = $subject;


        /*$mail->Host = "mail.codice.com";
        $mail->Port     = 25;*/
        $mail->Host     = "smtp-relay.gmail.com";
        $mail->Port     = 587;
        $mail->From = "contacto@starmedica.com";
        $mail->FromName = "Hospitales Star Médica";
        /*$mail->Username = "li.silva.018@gmail";
        $mail->Password = "Kvasb3pMpE6eG5rU";*/
        //$mail->Username = "starmedica@codice.com";
        //$mail->Password = "c0d1c32375";
        $result = $mail->Send();
        
        
/* EJEMPLO DE AJAX CON SERVICIO REST API */

$.ajax({
                            url:'payu',
                            type: 'POST',
                            data: $("#datosTarjeta").serialize(),
                            success:function(data){
                                console.log(data);

                                  alertaObj = {
                                                titulo:'Pago Tarjeta de crédito',
                                                mensaje:'Redirigiendo a sitio web de PayU para validar el proceso de compra.',
                                                btnOk:false,
                                                preload:true
                                            };
                                  parent.postMessage({ type: "alertaApp",alerta : alertaObj, timeout:6000, redirect: '<?php echo $link; ?>webapp/cuenta.html' }, "*");

                                  setTimeout(function(){
                                    parent.postMessage({ type: "openExternal",url:'http://www.payulatam.com/mexico/comercios/como-funciona-el-sistema-de-pago-electronico' }, "*");
                                  },4000);

                            },
                            error:function (){
                                alert('error en el envio del ajax');
                            }
                        });
