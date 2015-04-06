<?php

class RouterTest extends \Contresort\Test\Launcher
{
	public function testMethod() {
		echo 'Instanciate a new Application';
		$application=new \ContreSort\Application('Demo');


		$application->route(null, '`.*`', function() {
			$this->output='hello';
		});

		$application->run();
		if($application->getOutput()=='hello') {
			return true;
		}
		else {
			return false;
		}
	}

}

