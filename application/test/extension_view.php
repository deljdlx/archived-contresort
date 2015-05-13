<?php


chdir(__DIR__);

require(realpath(__DIR__.'/../../source/contresort.php'));

$extension=new \Contresort\Extension('Contresort\View', '../../extension/contresort/view');


class Test extends \Contresort\View\Widget
{

}


$application=new \Contresort\Application('test');
$application->get('`.*`')->addAction(function() {

	$layout=new \Contresort\View\Layout();
	$layout->assign('#main', 'hello world');

	$domBuffer='
	<div>
	<div id="test">
		<div class="content"><span>first child 1</span></div>
		<div class="content">test <span>first child 2</span></div>
		<div class="content">test <div>non </div><span>first child</span></div>
	</div>
	out of select
	</div>
	';
	
	$layout->loadHTML($domBuffer);

	
	

	$this->output=$layout->render();
	return true;
});

$application->run();
echo $application->getOutput();
$application->stop();

