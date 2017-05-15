<div class="box">
    <div class="row">
        <div class="12u">
            <table class="table table-responsive" id="users">
                <tr>
                    <th>Login :</th>
                    <th>Prénom :</th>
                    <th>Nom :</th>
                    <th>Rôle :</th>
                    <th>Ville :</th>
                    <th>Actions :</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?=$user->getLogin()?></td>
                        <td><?=$user->getPrenom(); ?> </td>
                        <td><?=$user->getNom()?></td>
                        <td><?=$user->getRole()->getLibelle()?></td>
                        <td><?=$user->getCommune()->getNom()?> (<?=$user->getCommune()->getCodePostal()?>)</td>
                        <td><a class="special" href="?page=user&action=display&id=<?=$user->getId();?>">voir</a></td>
                        <td><a class="special" href="?page=user&action=update&id=<?=$user->getId();?>">modifier</a></td>
                        <td>
                            <form action="?page=user&action=delete" method="post" class="inline-form" style="">
                                <input type="hidden" name="id" value="<?=$user->getId()?>">
                                <button class="delete" type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="12u">
            <ul class="actions">
                <li><a class="special" href="?page=user&action=create">nouvel utilisateur</a></li>
                <li><a class="special" href="?page=user&action=import">importer liste utilisateurs</a></li>
            </ul>
        </div>
    </div>
</div>
