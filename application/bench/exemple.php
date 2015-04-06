<?php
require("../../source/contresort.php");



$application=new \ContreSort\Application("namespace_de_l_application");
$application->get("#.*#")
	->addAction(function() {
		$this->output="<!doctype html><html><head><title>Exemple Contresort</title></head><body>Contresort is running</body></html>";
	});


$application->run();

echo $application->getOutput();
exit($application->getStatus());