<?php
// Method: POST, PUT, GET etc

ini_set('display_errors', 1);
error_reporting(E_ALL);

//Variables
//CONST URL = "https://10.97.38.163:8543/PaymentsMonitorService/estatus-karpay/consulta-estatus";
CONST URL_VAL = "https://10.97.38.80:8543/PaymentsMonitorServiceSPID/estatus-karpay/consulta-estatus";
//CONST URL_VAL = "tcp://10.97.38.80:8543/PaymentsMonitorServiceSPEI/estatus-karpay/consulta-estatus";
//CONST URL_LOGIN = "https://10.97.38.163:8543/PaymentsMonitorServiceSPEI/estatus-karpay/login";
CONST URL_LOGIN = "https://10.97.38.163:8543/PaymentsMonitorServiceSPEI_S/estatus-karpay/login";
//CONST URL = "https://10.97.38.163:8543/PaymentsMonitorServiceSPEI/estatus-karpay/consulta-estatus";
/** URL SPEI DEV */
CONST URL = "https://10.97.38.163:8543/PaymentsMonitorServiceSPEI/estatus-karpay/consulta-devolucion";
//CONST URL = "https://10.97.38.163:8543/PaymentsMonitorServiceSPID_S/estatus-karpay/consulta-devolucion";
/** URL SPID DEV */
//CONST URL = "https://10.97.38.163:8543/PaymentsMonitorServiceSPID/estatus-karpay/consulta-devolucion";

$url_val = str_replace("https","tcp",URL);

$serviceSp = fsockopen($url_val, 8543, $errno, $errstr, 10);			
if ($serviceSp === false)
{
    echo "NOT AVAILABLE: ".$errno." ".$errstr;
    $estatus = -1;  // Service is down				
}				
else 
{			
    echo "ALL GOOD!!";	
    $estatus = 1;
}

/** BODY CONSULTA SPEI - SPID */
//$data = array("folio" => 197669,"tipoOperacion" => "SPID","idioma" => "ES");

/** BODY CONSULTA DEVOLUCION SPEI */
//$data = array("folio" => 197669,"tipoOperacion" => "FXD","codigoDev" => "RR020","idioma" => "ES");

/** BODY CONSULTA DEVOLUCION SPID */
$data = array("folio" => 197669,"tipoOperacion" => "FXD","codigoDev" => "OPERACION SIN FONDOS","idioma" => "ES");

$dataLogin = array("username" => "CMS-API","password" => "0123456789");

function CallAPI($method, $url, $data = false,$token ="",$jsonBody = 0,$pnw = 0)
{
    $curl = curl_init();


    $data = ($jsonBody == 1 && !empty($data))?json_encode($data):$data;

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data && $jsonBody == 0)
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            elseif($data && $jsonBody == 1)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);    
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data && $jsonBody == 0)
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            elseif($data && $jsonBody == 1)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                   
            break;
        case "GET":
            curl_setopt($curl, CURLOPT_HTTPGET, 1);
            break;
    }

    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    if($jsonBody == 0 && $method !== "PUT")
        $url = $url."?". http_build_query($data);

    if(!empty($token))
    {
        $customAuth = ($pnw == 1)?"acess_token: ":"Authorization: Bearer ";
        $autorizathion = $customAuth.$token;
        $headers = 
        [
            "accept: application/json",
            $autorizathion,
            "Content-Type: application/json"           
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
    }
    else
    {
        $headers = 
        [
            "accept: application/json",
            "Content-Type: application/json"           
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
    }

    echo "<br>----------------------------------------------------------HEADERS-------------------------------------------------------------------------------- <br>";
    echo "<pre>";
    print_r($headers);
    echo "</pre>";

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

    $result = curl_exec($curl); 

    if($result === false)
    {
        $errorArr['message'] = "Curl error: ".curl_error($curl);
        $errorArr['error'] = 1;
        $result = json_encode($errorArr);
        curl_close($curl);
        return $result;
    }
    else
    {
        curl_close($curl);
        return $result;
    }
}

echo "INICIA PRUEBA DE CURL: ";

$result_login = CallAPI("POST",URL_LOGIN,$dataLogin,"",1,0);

$result_login = json_decode($result_login,true);

$result = CallAPI("POST",URL,$data,$result_login["jwt"],0,0);

$result = json_decode($result,true);

echo "<br>----------------------------------------------------------BODY LOGIN-------------------------------------------------------------------------------- <br>";
echo "<pre>";
print_r($dataLogin);
echo "</pre>";

echo "<br>----------------------------------------------------------RESULT LOGIN-------------------------------------------------------------------------------- <br>";
echo "<pre>";
print_r($result_login);
echo "</pre>";

echo "<br>----------------------------------------------------------ACCESS TOKEN-------------------------------------------------------------------------------- <br>";
echo $result_login['jwt'];

echo "<br>----------------------------------------------------------URL ENDPOINT-------------------------------------------------------------------------------- <br>";
echo "URL: ".URL;

echo "<br>----------------------------------------------------------BODY TEST ENDPOINT-------------------------------------------------------------------------------- <br>";
echo "<pre>";
print_r($data);
echo "</pre>";

echo "------------------------------------------------------------RESULT TEST ENDPOINT---------------------------------------------------------------------------- <br>";
echo "<pre>";
print_r($result);
echo "</pre>";

echo "------------------------------------------------------------RESULT TEST VAR---------------------------------------------------------------------------- <br>";
echo  "VAR: ".$result["estatus"]["estatus_message"];



?>
