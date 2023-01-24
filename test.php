<?php

// LOGIN

$url= "https://054911-sales-enterprise.creatio.com/ServiceModel/AuthService.svc/Login";

$cookie_file = 'cookies.txt';
$cookies = Array();

if (! file_exists($cookie_file) || ! is_writable($cookie_file)){
    echo 'Cookie file missing or not writable.';
    exit;
}
$auth_arguments = array(
    "UserName" => "asiradoew@gmail.com",
    "UserPassword" => "Artem@121488"
);

$auth_request = curl_init($url);
curl_setopt($auth_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($auth_request, CURLOPT_HEADER, true);
curl_setopt($auth_request, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($auth_request, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($auth_request, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($auth_request, CURLOPT_COOKIE, $cookies);
curl_setopt($auth_request, CURLOPT_HEADERFUNCTION, "curlResponseHeaderCallback");
curl_setopt($auth_request, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json"
));

$json_arguments = json_encode($auth_arguments);
curl_setopt($auth_request, CURLOPT_POSTFIELDS, $json_arguments);
curl_setopt ($auth_request, CURLOPT_COOKIEJAR, realpath($cookie_file));
$result = curl_exec($auth_request);

$BPMLOADER = explode("=",$cookies[0][1]);
$BPMLOADER = $BPMLOADER[1];
var_dump('$BPMLOADER' . $BPMLOADER);

$ASPXAUTH = explode("=",$cookies[1][1]);
$ASPXAUTH = $ASPXAUTH[1];
var_dump('$ASPXAUTH' . $ASPXAUTH);

$CSRF= explode("=",$cookies[2][1]);
$bpmcsrf=$CSRF[1];
var_dump('$bpmcsrf' . $bpmcsrf);

$SessionId = explode("=",$cookies[4][1]);
$SessionId = $SessionId[1];
var_dump('$SessionId' . $SessionId);

function curlResponseHeaderCallback($ch, $headerLine) {
    global $cookies;
    if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookie) == 1)
        $cookies[] = $cookie;
    return strlen($headerLine);

}

var_dump($result);

// REQUEST

//$cookie_string = 'Cookie: SsoSessionId='. $SessionId .'; BPMCSRF='. $bpmcsrf .'; BPMLOADER='. $BPMLOADER .'; .ASPXAUTH=' . $ASPXAUTH .';';
$cookie_string = 'BPMCSRF='. $bpmcsrf .';';

$data = [
    "Contact" => "Test User",
    "FullJobTitle" => "Developer",
    "Email" => "testuser1@gmail.com",
    "MobilePhone" => "9230068367253",
    "LeadName" => "Need for our products / Alexander Wilson, Alpha Business"
];

$data = json_encode($data);

$url = "https://054911-sales-enterprise.creatio.com/0/odata/Lead";
//$url = "https://054911-sales-enterprise.creatio.com/0/odata/Contact";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_ENCODING, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION , 1);
curl_setopt($ch, CURLOPT_MAXREDIRS, '');

$headers = array(
    'Content-Type: application/json',
    'Accept: application/json',
    'Cookie: BPMCSRF='. $bpmcsrf .';',
    'BPMCSRF: '. $bpmcsrf,
    'Content-Length: '. strlen($data),
    'ForceUseSession: true',
);

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);
curl_setopt($ch, CURLOPT_COOKIEFILE, realpath($cookie_file));
curl_setopt($ch, CURLOPT_COOKIEJAR, realpath($cookie_file));

$output = curl_exec($ch);

var_dump($output);

curl_close($ch);