<?php


define('CALL_FILEPATH', getcwd());
chdir(__DIR__);
define('APPLICATION_FILEPATH', getcwd());


require('bootstrap.php');

require(CONTRSORT_FILEPATH.'/bootstrap.php');


$application=new \ContreSort\Application('Demo');


/*
$application->get(function() {
	$this->parameters[]='foobar';
	return true;
}, function($test) {
	$this->output='ok ça marche : '.$test;
});
*/



$application->cli('`.*`', function() {
	return 'hello cli';
});


$application->get('`(.*)`', function() {
	$this->output='<form method="post" action=""><input name="test" value="post ok"/><button>ok</button></form>';
})->also(function() {
	$this->output.='<hr/>';
})->also(function($string) {
	$this->output.='ça marche : '.$string;
})->also(function() {
	$this->addHeader('Content-type', 'text/html; charset="utf-8"');
});



$application->post('`.*`', function() {
	echo '<pre id="' . __FILE__ . '-' . __LINE__ . '" style="border: solid 1px rgb(255,0,0); background-color:rgb(255,255,255)">';
	print_r($_POST);
	echo '</pre>';
});



$application->get('`hello(?:/(?P<name>.*?)/(.*?)/(?P<phrase>.*))?`', function($name, $phrase) {
	return 'hello '.$name.' '.$phrase;
});




$application->run();


echo $application->getOutput();





exit(0);


