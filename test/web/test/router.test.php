<?php

class RouterTest extends \Contresort\Test\Launcher
{
	public function testMatchAll() {

		echo 'Test match all route';
		$application=new \ContreSort\Application('Demo');

		$application->get('`.*`')
			->addAction(function() {
			$this->output='hello';
			return true;
		});

		$application->run();
		if($application->getOutput()=='hello') {
			return true;
		}
		else {
			return false;
		}
	}


	public function testOneArgument() {
		echo 'Catch parameters "test"';

		$_SERVER['REQUEST_URI']='/test=hello';

		$application=new \ContreSort\Application('Demo');
		$application->get('`test=(.*)`')
			->addAction(function($match) {
				$this->output=$match;
				return true;
			});

		$application->run();
		echo "<br/>".'Parameter "test" value : '.$application->getOutput();

		if($application->getOutput()=='hello') {
			return true;
		}
		else {
			return false;
		}
	}

	public function testManyArguments() {
		echo 'Catch parameters';

		$_SERVER['REQUEST_URI']='/test1=hello&test2=world';

		$application=new \ContreSort\Application('Demo');
		$application->get('`test1=(.*?)&.*?test2=(.*)`')
			->addAction(function($match0, $match1) {
				$this->output=$match0.' '.$match1;
				return true;
			});

		$application->run();
		echo "<br/>".'Parameter "test" value : '.$application->getOutput();

		if($application->getOutput()=='hello world') {
			return true;
		}
		else {
			return false;
		}
	}
}

