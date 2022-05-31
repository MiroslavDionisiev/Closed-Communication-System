<?php

namespace CCS;

use CCS\Helpers as Help;
use CCS\Models\DTOs as DTOs;

require_once('config.php');
require_once(APP_ROOT . '/Models/DTOs/ResponseDto.php');

foreach (glob(APP_ROOT . '/Controllers/*.php') as $file) {
    require_once($file);
};
foreach (glob(APP_ROOT . '/Helpers/*.php') as $file) {
    require_once($file);
};

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$uri = trim(strtok(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '?'));

$requestMethod = $_SERVER['REQUEST_METHOD'];

session_start();

### FOR TESTING PURPOSES ###
use CCS\Models\Entities\Teacher;
require_once(APP_ROOT . '/Models/Entities/Teacher.php');
$u = new Teacher();
$u->role = 'ADMIN';
$_SESSION['user'] = $u;
### FOR TESTING PURPOSES ###

$routes = array_filter(ROUTES, function($k) use ($requestMethod) { return str_starts_with($k, $requestMethod); }, ARRAY_FILTER_USE_KEY);

foreach ($routes as $key => $conf) {
    $split = explode(' ', $key);
    $routeMethod = $split[0];
    $route = $split[1];

    if (preg_match("|" . $route . "|mi", $uri)) {
        if ($routeMethod !== $requestMethod) {
            echo json_encode(
                new DTOs\ResponseDtoError(
                    405,
                    "Method not allowed."
                ),
                JSON_FLAGS
            );
            exit();
        }

        if (isset($conf['authenticate']) && $conf['authenticate']) {

            if (!Help\AuthorizationManager::authenticate()) {
                echo json_encode(
                    new DTOs\ResponseDtoError(
                        401,
                        "User not authenticated."
                    ),
                    JSON_FLAGS
                );
                exit();
            }

            if (isset($conf['authorize']) && !Help\AuthorizationManager::authorize($conf['authorize'])) {
                echo json_encode(
                    new DTOs\ResponseDtoError(
                        403,
                        "User doesn't have necessary authority."
                    ),
                    JSON_FLAGS
                );
                exit();
            }
        }

        $controller = $conf['controller'];
        $controllerMethod = $conf['controllerMethod'];

        call_user_func(array("CCS\\Controllers\\" . $controller, $controllerMethod));
        exit();
    }
}

echo json_encode(
    new DTOs\ResponseDtoError(
        404,
        "Resource {$uri} not found"
    ),
    JSON_FLAGS
);
