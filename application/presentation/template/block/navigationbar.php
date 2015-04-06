<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?=$urlindex;?>">Accueil</a>
		</div>

		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li<?php if($selectedItem=='apropos') {echo  ' class="active"';}?>><a href="<?=$urlapropos;?>">A propos</a></li>
				<li<?php if($selectedItem=='demarrage') {echo  ' class="active"';}?>><a href="<?=$urldemarrage;?>">Démarrage rapide</a></li>
				<li<?php if($selectedItem=='documentation') {echo  ' class="active"';}?>><a href="<?=$urldocumentation;?>">Documentation</a></li>
				<li<?php if($selectedItem=='extension') {echo  ' class="active"';}?>><a href="<?=$urlextension;?>">Extensions</a></li>
				<li<?php if($selectedItem=='manifeste') {echo  ' class="active"';}?>><a href="<?=$urlmanifeste;?>">Manifeste</a></li>
			</ul>
		</div><!--/.nav-collapse -->

	</div>
</nav>