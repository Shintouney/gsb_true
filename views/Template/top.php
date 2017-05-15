<!-- Header -->
<header id="header">
	<a class="logo"><strong><?= isset($pageName) ? $pageName : 'Accueil';?> -</strong> Connecté en tant que <strong><?=$activeRole;?></strong></a>
	<ul class="icons">
	<li><a class="icon fa-user"><span class="logo"> <?=$nom;?> <?=$prenom;?></span></a></li>
	<li><a href="?action=logout" class="icon fa-power-off"><span class="logo"> Déconnexion</span></a></li>
	</ul>
</header>