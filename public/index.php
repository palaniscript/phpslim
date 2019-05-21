<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Kolkata");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require '../vendor/autoload.php';
require '../config.php';
// Setup custom Twig view
$twigView = new \Slim\Views\Twig();
$app = new \Slim\Slim(array(
    'debug' => true,
    'view' => $twigView,
    'templates.path' => '../templates/',
));
class AllCapsMiddleware extends \Slim\Middleware
{
    public function call(){
        $app = $this->app;
        $this->next->call();
        $req = $app->request;
        $headers = $app->request->headers;
        $whitelist = array("/authentication", "/customers");
        $user = new models\User();
        if(!in_array($app->request()->getPathInfo(), $whitelist) && !$user->authenticateSession($headers->get('Authorization'))){
            $res = $app->response();
            $res->status(401);
            $body = $res->getBody();
            $res->setBody("");
        }
    }
}
if($app->request->isOptions()) {
    return true;
}
// Automatically load router files
$routers = glob('../routers/*.router.php');
foreach ($routers as $router) {
    require $router;
}
function buildBindedQuery($fields){
    end($fields);
    $lastField = key($fields);
    $bindString = ' ';
    foreach($fields as $field => $data){
        $bindString .= $field . '=:' . $field;
        $bindString .= ($field === $lastField ? ' ' : ',');
    }
    return $bindString;
}
function generateJWT($payload){
    // Create token header as a JSON string
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    // Create token payload as a JSON string
    $payload = json_encode($payload);
    // Encode Header to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    // Encode Payload to Base64Url String
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);
    // Encode Signature to Base64Url String
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    return $jwt;
}
function base64UrlDecode($data)
{
    $urlUnsafeData = strtr($data, '-_', '+/');
    $paddedData = str_pad($urlUnsafeData, strlen($data) % 4, '=', STR_PAD_RIGHT);
    return base64_decode($paddedData);
}
function verifyJWT($algo, $jwt, $secret)
{
    list($headerEncoded, $payloadEncoded, $signatureEncoded) = explode('.', $jwt);
    $dataEncoded = "$headerEncoded.$payloadEncoded";
    $signature = base64UrlDecode($signatureEncoded);
    $rawSignature = hash_hmac($algo, $dataEncoded, $secret, true);
    return hash_equals($rawSignature, $signature);
}
function isExists($array, $key, $val){
    foreach ($array as $item)
        if(isset($item[$key]) && $item[$key] == $val)
            return true;

    return false;
}
$app->add(new \AllCapsMiddleware());
$app->run();