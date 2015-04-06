<?php


define('CALL_FILEPATH', getcwd());
chdir(__DIR__);
define('APPLICATION_FILEPATH', getcwd());


define('CONTRESORT_FILEPATH', realpath(__DIR__.'/../../source'));

require(CONTRESORT_FILEPATH.'/contresort.php');



$application=new \ContreSort\Application('Demo');
$application->addHeader('Content-type', 'text/html; charset="utf-8"');

$application->get('`.*`')
	->addAction(function() {
		$this->output='hello world';
	})
;



$application->run();


echo $application->getOutput();





exit(0);


