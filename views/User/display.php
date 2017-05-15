<div class="box">
    <div class="row">
        <div class="8u -2u">
            <table id="users">
                <tr>
                    <th>Nom :</th>
                    <td><?= $user->getNom() ?> </td>
                </tr>
                <tr>
                    <th>Prénom :</th>
                    <td><?= $user->getPrenom(); ?> </td>
                </tr>
                <tr>
                    <th>Login :</th>
                    <td><?= $user->getLogin(); ?> </td>
                </tr>
                <tr>
                    <th>Email :</th>
                    <td><?= $user->getEmail(); ?> </td>
                </tr>
                <tr>
                    <th>Rôle:</th>
                    <td><?= $user->getRole()->getLibelle(); ?></td>
                </tr>
                <tr>
                    <th>Téléphone :</th>
                    <td><?= $user->getTelephone(); ?></td>
                </tr>
                <tr>
                    <th>Adresse :</th>
                    <td><?= $user->getAdresse(); ?></td>
                </tr>
                <tr>
                    <th>Commune :</th>
                    <td><?= $user->getCommune()->getNom(); ?></td>
                </tr>
                <tr>
                    <th>Date d'embauche :</th>
                    <td><?= $user->getDateEmbauche('d M Y'); ?> </td>
                </tr>
            </table>
        </div>
    </div>
    <?php if ($pageName !== "Mon profil" && $this->getUser()->isAdmin()) { ?>
        <hr/>
        <div class="row">
            <div class="6u -3u">
                <ul class="actions">
                    <li><a class="special" href="?page=user">retour à la liste</a></li>
                    <li><a class="special" href="?page=user&action=update&id=<?= $user->getId(); ?>">Modifier</a></li>
                </ul>
            </div>
        </div>
    <?php } ?>
</div>