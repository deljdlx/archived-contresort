
<div class="container">
	<div class="starter-template">
	<h1><img src="resource/favicon.png"/> Démarrage rapide</h1>

		<h2>Initialisation</h2>
		<p>Une simple inclusion du fichier d'initialisation est suffisante pour utiliser Contresort</p>
		<?php
highlight_string('<?php
require("contresort_filepathroot/contresort.php");
$application=new \ContreSort\Application("namespace_de_l_application");
');
		?>


		<h2>Routing</h2>
		<p>
		Voici un exemple de route répondant "hello world" à tout les appels HTTP GET. Le paramètre à passer dans la méthode $application::get() est une regexp interprétable par les fonctions preg_*
		</p>
		<?php
		highlight_string('<?php

$application->get("#.*#")
	->addAction(function() {
		$this->output="Hello world";
	});
');
		?>



		<h2>Buffer de sortie</h2>
		<p>
			Le buffer de sortie est stocké dans l'attribut $output de la classe \ContreSort\Application.<br/>
			Pour accéder en lecture à cette variable utilisez la méthode <strong>\ContreSort\Application::getOutput()</strong>.<br/>
			Pour y accéder en écriture, utilisez la méthode <strong>\ContreSort\Application::setOutput($string)</strong>.
		</p>
		<p>Code pour afficher le buffer de sortie de l'application</p>
		<?php
		highlight_string('<?php
echo $application->getOutput();
');
		?>



		<h2>Fin d'éxécution et code de retour</h2>
		<p>
			Lorsque l'execution de l'application est terminée, vous pouvez accéder a son code de retour en utilisant cette méthode  <strong>\ContreSort\Application::getStatus()</strong>
		</p>
		<p>Exemple</p>
		<?php
		highlight_string('<?php
$application->stop();
exit($application->getStatus());
');
		?>


		<h2>Exemple complet</h2>
		<?php
		highlight_string('<?php
//initialisation du framework
require("contresort_filepathroot/contresort.php");

//instanciation d\'une nouvelle application
$application=new \ContreSort\Application("namespace_de_l_application");

//définition d\'une règle de route répondant à tout les appels GET
$application->get("#.*#")
	->addAction(function() { //définition de l\'action à éxécuter
		$this->output="<!doctype html><html><head><title>Exemple Contresort</title></head><body>Contresort is running</body></html>";
	});

//lancement d l\'application
$application->run();

//affichage du buffer de sortie
echo $application->getOutput();

//arrêt de l\'application et renvoie de son code de retour
$application->stop();
exit($application->getStatus());
');

			?>


</div>
</div>