<?php
require __DIR__ . "/classes/gameController.php";
header("Content-Type: application/json");


$controller = new gameController;
$controller->handle();
?>