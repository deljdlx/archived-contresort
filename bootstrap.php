<?php

namespace Contresort;


function CSinclude($filepath) {
	include($filepath);
}


spl_autoload_register(function($className) {

	static $classIndex;

	if(!$classIndex) {
		$dir_iterator = new \RecursiveDirectoryIterator(__DIR__.'/source/core');
		$iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
		foreach ($iterator as $file) {

			if(strrpos($file, '.php')) {
				$index=str_ireplace('\CORE\\',
					'\\',
					strtoupper(str_replace('/', '\\', __NAMESPACE__.'\\'.preg_replace('`.*?core.(.*?)\.php`', '$1', (string) $file)))
				);
				$classIndex[$index]=(string) $file;
			}
		}
	}

	$normalizedClassName=strtoupper($className);

	if(isset($classIndex[$normalizedClassName])) {
		include($classIndex[$normalizedClassName]);
		return $classIndex[$normalizedClassName];
	}
	return false;
});










