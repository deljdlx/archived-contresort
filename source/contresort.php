<?php

//namespace Contresort;


function CSinclude($filepath, $variables=array()) {
	extract($variables);
	include($filepath);
}

function obinclude($filepath, $variables=array()) {
	ob_start();
	CSinclude($filepath, $variables);
	return ob_get_clean();
}


function bindParameters($values, $controller, $method) {

	$reflector=new \Reflectionmethod($controller, $method);
	$parameters=array();
	foreach($reflector->getParameters() as $parameter) {

		if(isset($values[$parameter->name])) {
			$parameters[]=$values[$parameter->name];
		}
		else {
			$value=null;
			if($parameter->isOptional()) {
				$value=$parameter->getDefaultValue();
			}
			$parameters[]=$value;
		}
	}
	return $parameters;
}


spl_autoload_register(function($className) {

	static $classIndex;

	if(!$classIndex) {
		$dir_iterator = new \RecursiveDirectoryIterator(__DIR__.'/core');
		$iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
		foreach ($iterator as $file) {

			if(strrpos($file, '.php')) {
				$index=str_ireplace('\core\\',
					'\\',
					strtoupper(str_replace('/', '\\', 'Contresort\\'.preg_replace('`.*?core.(.*?)\.php`', '$1', (string) $file)))
				);
				$classIndex[$index]=(string) $file;
			}
		}
	}

	$normalizedClassName=strtoupper($className);

	if(isset($classIndex[$normalizedClassName])) {
		CSinclude($classIndex[$normalizedClassName]);
		return $classIndex[$normalizedClassName];
	}
	return false;
});










