<?php
require "../bootstrap.php";
use Src\Controller\WebsiteController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

if ($uri[1] !== 'websites') {
    $file = 'index.html';
    if (file_exists($file)) {
        header('Location: Domain');
    } else {
       // throw new \Slim\Exception\NotFoundException($request, $response);
       exit();
    }
}

// the website and time arguements are checked else assigned a default value:
$website = null;
$time = null;
if (isset($uri[2]) && trim($uri[2])!=="") {
    $time = htmlspecialchars_decode(trim($uri[2]));
}
else{
    $time = json_decode(file_get_contents('sites.json'), true)['defaultdate'].date("dmY");
}
if (isset($uri[3])){
    $website = htmlspecialchars_decode(trim($uri[3]));
}

$requestMethod = $_SERVER["REQUEST_METHOD"];
$controller = new WebsiteController($requestMethod, $time, $website);
$controller->processRequest();
