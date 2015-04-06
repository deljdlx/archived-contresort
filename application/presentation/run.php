<?php


define('CALL_FILEPATH', getcwd());
chdir(__DIR__);
define('APPLICATION_FILEPATH', __DIR__);
define('CONTRESORT_FILEPATH', realpath(__DIR__.'/../../source'));

require(CONTRESORT_FILEPATH.'/contresort.php');

error_reporting(E_ALL);



function getPage($page, $application) {
	$layout=new \Contresort\Presentation\View\Layout();
	$layout->setApplication($application);
	$layout->setPage($page);
	return $layout->render('template/layout/default.php');
}





$application=new \ContreSort\Application('Contresort\Presentation');
$application->addHeader('Content-type', 'text/html; charset="utf-8"');

$application->get('`\?/apropos`')
	->addAction(function() {
		$this->output=getPage('apropos.php', $this);
	})
	->name('apropos')
	->builder('?/apropos')
;

$application->get('`\?/manifeste`')
	->addAction(function() {
		$this->output=getPage('manifeste.php', $this);
	})
	->name('manifeste')
	->builder(function() {
		return '?/manifeste';
	})
;

$application->get('`\?/documentation`')
	->addAction(function() {
		$this->output=getPage('documentation.php', $this);
	})
	->name('documentation')
	->builder(function() {
		return '?/documentation';
	})
;

$application->get('`\?/extension`')
	->addAction(function() {
		$this->output=getPage('extension.php', $this);
	})
	->name('extension')
	->builder(function() {
		return '?/extension';
	})
;


$application->get('`\?/demarrage`')
	->addAction(function() {
		$this->output=getPage('demarrage.php', $this);

	})
	->name('demarrage')
	->builder('?/demarrage')
;

$application->get('`.*`')
	->addAction(function() {
		$this->output=getPage('index.php', $this);;
	})
	->name('index')
	->builder('?');

$application->run();


echo $application->getOutput();


exit($application->getStatus());


