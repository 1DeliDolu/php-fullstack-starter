<?php
define('_IAD', true);
define('_AJAX', true);

require('includes/bootstrap.php');
require('config/config.php');
require('includes/autoload.php');

use IAD\classes\cookie;
use IAD\classes\db;
use IAD\classes\session;
use IAD\classes\stmt;

function getRequestHeadersSafe(): array
{
    if (function_exists('getallheaders')) {
        return getallheaders();
    }

    if (function_exists('apache_request_headers')) {
        return apache_request_headers();
    }

    $headers = [];
    foreach ($_SERVER as $key => $value) {
        if (!str_starts_with($key, 'HTTP_')) {
            continue;
        }

        $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
        $headers[$header] = $value;
    }

    return $headers;
}

try {
    $exceptions = [];
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if (preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?$#', $origin)) {
        header("Access-Control-Allow-Origin: $origin");
        header('Vary: Origin');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Accept, Content-Type, X-Requested-With');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
        http_response_code(204);
        exit;
    }

    // auslesen aller http-Header
    $headers = getRequestHeadersSafe();
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        throw new Exception('Nur POST-Requests sind erlaubt!', 0x80000004);
    }
    $session = new Session(secure: false, savepath: '/tmp');
    // $_POST & php://input, da es gelegentlich probleme bei AJAX und POST gibt
    $request = [
        'headers' => $headers,
        'data'  => array_merge( $_POST, json_decode(file_get_contents('php://input'), true) ?? [] )
    ];
    $response = '';
    // prüfe welcher key in data liegt (z. B. mvc)
    if(array_key_exists('mvc', $request['data'])) {
        // binde mvc-controller ein und führe, wenn vorhanden, __construct aus
        # header.display    -> use IAD\mvc\header\Controller | include_once "mvc\header\controller.php";
        $mvc = array_combine(['name', 'method'], explode('.',  $request['data']['mvc']));
        $namespace = sprintf('IAD\\mvc\\%s\\Controller', strtolower(trim($mvc['name'])));
        if(!class_exists($namespace)) {
            throw new Exception(sprintf('MVC %s nicht gefunden!', $namespace), 0x80000006);
        }
        // namespace: IAD\mvc\header\Controller
        $ctrl = new $namespace($request);
        // alternate per include -> es ist jedoch nur ein einziges MVC nutzbar
        # $path = sprintf('mvc/%s/controller.php', strtolower(trim($mvc['name'#')));
        # include_once $path;
        # $ctrl = new Controller($request);
        // führe, wenn vorhanden, mvc-methode aus und schreibe die antwort auf response
        if(!method_exists($ctrl, trim($mvc['method']))) {
            throw new Exception(sprintf('Methode %s in Klasse %s nicht gefunden!', $mvc['method'], $namespace), 0x80000007);
        }
        // $method = trim($mvc['method']);
        // $response = $ctrl->$method();
        $response = $ctrl->{trim($mvc['method'])}();
    }elseif(array_key_exists('class', $request['data'])){
        $class = array_combine(['name', 'method'], explode('.',  $request['data']['class']));
        $namespace = sprintf('IAD\\classes\\%s', strtolower(trim($class['name'])));
        if(!class_exists($namespace)) {
            throw new Exception(sprintf('Klasse %s nicht gefunden!', $namespace), 0x80000007);
        }
        $instance = new $namespace($request);
        $response = $instance->{trim($class['method'])}();
    }else{
        // kein gültiger key in data
        throw new Exception('Ungültige Anfrage!', 0x80000005);
    }
    // $response = $_POST;
    // prüfung welche antwort vom Client akzeptiert wird
    if(str_starts_with($headers['Accept'], '*/*') || str_starts_with($headers['Accept'], 'text/html')) {
        echo $response;
    }elseif(str_starts_with($headers['Accept'], 'application/json')) {
        echo json_encode([
            'html' => $response,
            'exceptions' => $exceptions
        ]);
    }

}catch(Exception $e){
    var_dump($e);
}
