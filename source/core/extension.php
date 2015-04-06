<?php

namespace Contresort;

class Extension
{

	protected $namespace;
	protected $filepath;

	public function __construct($namespace, $filepath) {
			$this->namespace=$namespace;
			$this->filepath=$filepath;

		spl_autoload_register(
			array($this, 'autoload')
		);
	}


	protected function autoload($className) {
		$className=str_replace('\\', '/', strtolower($className));

		$namespace=$this->namespace;
		$folder=$this->filepath;

		$namespace=strtolower(str_replace('\\', '/', $namespace));
		$filepath=strtolower($folder.preg_replace('`^'.$namespace.'`', '', $className)).'.php';


		if(is_file($filepath)) {
			include($filepath);
			return;
		}
	}

}