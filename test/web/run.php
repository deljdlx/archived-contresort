<?php

require(__DIR__.'/bootstrap.php');



$className=$_GET['className'];

$method=$_GET['method'];

$file='test/'.strtolower(preg_replace('`test$`', '', $className).'.test.php');

include($file);

$test=new $className();
$result=$test->runMethod($method);



header('Content-type: application/json');
echo json_encode($result);


