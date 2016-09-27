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

		$whitelist = array("/login", "/logout");

		$user = new models\User();
		if(!in_array($app->request()->getPathInfo(), $whitelist) && !$user->authenticateSession($headers->get('Authentication'))){
			$res = $app->response();
			$res->status(401);
			$body = $res->getBody();
			$res->setBody("");
		}
	}
}

// Automatically load router files
$routers = glob('../routers/*.router.php');
foreach ($routers as $router) {
    require $router;
}

$app->add(new \AllCapsMiddleware());
$app->run();
