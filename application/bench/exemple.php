<?php
require("../../source/contresort.php");



$application=new \ContreSort\Application("namespace_de_l_application");
$application->get("#.*#")
	->addAction(function() {
		$this->output="<!doctype html><html><head><title>Exemple Contresort</title></head><body>Contresort is running ".uniqid()."</body></html>";
		return true;
	})
	->eTag('t0')
	//->expires(gmdate("D, d M Y H:i:s", time() + 120).' GMT')
	//->cacheVisibility('public')
	//->maxAge(600)
;


$application->run();

echo $application->getOutput();
exit($application->getStatus());