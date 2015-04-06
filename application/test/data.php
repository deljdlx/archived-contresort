<?php


define('CALL_FILEPATH', getcwd());
chdir(__DIR__);
define('APPLICATION_FILEPATH', __DIR__);
define('CONTRESORT_FILEPATH', realpath(__DIR__.'/../../source'));

require(CONTRESORT_FILEPATH.'/contresort.php');


$extension=new \Contresort\Extension('Contresort\DataAccess', '../../extension/contresort/dataaccess');
$connector=new Contresort\DataAccess\Connector('mysqli', array(
	'host'=>'127.0.0.1',
	'user'=>'root',
	'password'=>'',
	'port'=>3306,
	'database'=>'mysql'
));

$rows=$connector->queryAndFetch('SELECT * from user');
echo '<pre id="' . __FILE__ . '-' . __LINE__ . '" style="border: solid 1px rgb(255,0,0); background-color:rgb(255,255,255)">';
print_r($rows);
echo '</pre>';


die('EXIT '.__FILE__.'@'.__LINE__);