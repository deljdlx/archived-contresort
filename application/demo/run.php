<?php


define('CALL_FILEPATH', getcwd());
chdir(__DIR__);
define('APPLICATION_FILEPATH', getcwd());


require('bootstrap.php');

require(CONTRESORT_FILEPATH.'/bootstrap.php');


function getNavigationBar($application) {

	$anchors=array(
		'urlindex'=>$application->getURL('index'),
		'urlapropos'=>$application->getURL('apropos'),
	);

	$buffer=obinclude('template/block/navigationbar.php', $anchors);
	return $buffer;
}



$application=new \ContreSort\Application('Demo');

$application->get('`\?/apropos`')
	->execute(function() {

		$this->output=obinclude('template/layout/default.php', array(
			'navigationBar'=>getNavigationBar($this),
			'content'=>obinclude('template/page/apropos.php')
		));

	})->execute(function() {
		$this->addHeader('Content-type', 'text/html; charset="utf-8"');
	})->name('apropos')
	->builder(function() {
		return '?/apropos';
	})
;



$application->get('`.*`')
	->execute(function() {
		$this->output=obinclude('template/layout/default.php', array(
			'navigationBar'=>getNavigationBar($this),
			'content'=>obinclude('template/page/index.php')
		));
	})->execute(function() {
		$this->addHeader('Content-type', 'text/html; charset="utf-8"');
	})->name('index')
	->builder('?');






/*
$application->get(function() {
	$this->parameters[]='foobar';
	return true;
}, function($test) {
	$this->output='ok ça marche : '.$test;
});
*/


/*
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

*/



$application->run();


echo $application->getOutput();





exit(0);


