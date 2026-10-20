<?php
/**
 * AMN International - front controller.
 * All public requests are routed through this file.
 */

define('BASE_PATH', __DIR__);

require BASE_PATH . '/app/bootstrap.php';

$app = new App\Core\App();
$app->run();
