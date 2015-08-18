<?php



chdir(__DIR__);


require(realpath(__DIR__.'/../../source').'/contresort.php');

error_reporting(E_ALL);



function getPage($page, $application) {
	$layout=new \Contresort\Presentation\View\Layout();
	$layout->setApplication($application);
	$layout->setPage($page);
	return $layout->render('template/layout/default.php');
}



//$test=new Contresort\Request();


$application=new \ContreSort\Application('Contresort\Presentation');
$application->addHeader('Content-type', 'text/html; charset="utf-8"');
//$application->addHeader('Etag', md5('fohosg'));


$application->addExitAction(function() {
	//echo $this->getEnvironment()->getURL().' => '.$this->getStatus();
});


$application->get(function($application) {

	if(preg_match('`apropos`', $application->getEnvironment()->getURL())) {
		//$this->parameters['aString']='hello world';
		return true;
	}
	return false;
})->name('apropos')
	->addAction(function($aString) {
		//echo $string;
		//exit();
		//$this->getEnvironment()->getRequest();
		return true;
	})
	->addAction(function() {
		$this->output=getPage('apropos.php', $this);
		return true;
	})
	->builder('?/apropos')
;

$application->get('`\?/manifeste`')->name('manifeste')
	->addAction(function() {
		$this->output=getPage('manifeste.php', $this);
		return true;
	})
	->builder(function() {
		return '?/manifeste';
	})
;



/*
$application->get('`\?/(documentation)`', function($test) {
	print_r($test);
	//$this->output=getPage('documentation.php', $this);
	return true;
});
*/



$application->get('`\?/documentation`')->name('documentation')
	->addAction(function() {
		$this->output=getPage('documentation.php', $this);
		return true;
	})
	->builder(function() {
		return '?/documentation';
	})
;


$application->get('`\?/extension`')->name('extension')
	->addAction(function() {
		$this->output=getPage('extension.php', $this);
		return true;
	})
	->builder(function() {
		return '?/extension';
	})
;


$application->get('`\?/demarrage`')->name('demarrage')
	->addAction(function() {
		$this->output=getPage('demarrage.php', $this);
		return true;
	})
	->builder('?/demarrage')
;

$application->get('`.*`')->name('index')
	->addPreAction(function() {})
	->addPostAction(function() {})
	->addAction(function() {
		$this->output=getPage('index.php', $this);;
		return true;
	})
	->builder('?');

$application->run();


echo $application->getOutput();


$application->stop();
exit($application->getStatus());


