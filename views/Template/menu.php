<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
        <!-- Menu -->
        <nav id="menu">
            <header class="major">
                <h2>Applifrais</h2>
            </header>
<?php
if($this->getUser()->is('ROLE_USER'))
{
?>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php?page=frais">Saisie fiche de frais</a></li>
                <li><a href="index.php?page=frais&action=mesfiches">Mes fiches de frais</a></li>
            </ul>
<?php
            }
            else
            {
?>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php?page=frais&action=validationFraisSelection">Validation fiche de frais</a></li>
            </ul>
            <?php
}
            ?>
            <br/>
            <header class="major">
                <h2>Rapports incidents</h2>
            </header>
            <ul>
                <li><a href="index.php">Lien</a></li>
                <li><a href="index.php">Lien</a></li>
            </ul>
            <br/>
<?php
            if($this->getUser()->is('ROLE_COMPTABLE'))
{
?>
            <header class="major">
                <h2>Administration</h2>
            </header>
            <ul>
                <li>
                    <span class="opener">Gérer utilisateurs</span>
                    <ul>
                        <li><a class="icon fa-group" href="?page=user&action=index"> Index des utilisateurs</a></li>
                        <li><a class="icon fa-user" href="?page=user&action=create"> Créer utilisateur</a></li>
                        <li><a class="icon fa-file-text" href="?page=user&action=import"> Importer utilisateurs</a></li>
                    </ul>
                </li>
            </ul>
            <?php
}
        ?>
<?php
            if($this->getUser()->is('ROLE_ADMIN'))
{
?>
            <header class="major">
                <h2>Administration</h2>
            </header>
            <ul>
                <li>
                    <span class="opener">Gérer utilisateurs</span>
                    <ul>
                        <li><a class="icon fa-group" href="?page=user&action=index"> Index des utilisateurs</a></li>
                        <li><a class="icon fa-user" href="?page=user&action=create"> Créer utilisateur</a></li>
                        <li><a class="icon fa-file-text" href="?page=user&action=import"> Importer utilisateurs</a></li>
                    </ul>
                </li>
            </ul>
            <?php
}
        ?>
            <br/>
            <header class="major">
                <h2>Tableau de bord</h2>
            </header>
            <ul>
                <li><a class="icon fa-home" href="index.php"> Accueil</a></li>
                <li><a class="icon fa-user" href="index.php?page=user&action=profile"> Voir mon profil</a></li>
                <li><a class="icon fa-key" href="index.php?page=password&action=change"> Changer de mot de passe</a></li>
            </ul>
        </nav>

		<!-- Footer -->
        <footer id="footer">
            <img class="menu-logo" src="img/app/logo-tr.png" alt=""/>
            <p class="copyright"> GSB &copy; <?= date('Y')?></a></p>
        </footer>
	</div>
</div>