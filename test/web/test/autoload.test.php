<?php

//include(__DIR__.'/../bootstrap.php');


class AutoloadTest extends \Contresort\Test\Launcher
{



	public function testSimple() {
		echo 'Load simple class';
		return true;
	}
	
	public function testNamespace() {
		echo 'Load class in sub-folder';
		return true;
	}
}


