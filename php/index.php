<?php

use php\Controller;

ini_set('display_errors', 1);

require_once 'View.php';
require_once 'Controller.php';
require_once 'Params.php';
require_once 'models/Prize.php';
require_once 'models/DUser.php';
require_once 'models/FileDataModel.php';
$controller = new Controller(new View());

$controller->run();