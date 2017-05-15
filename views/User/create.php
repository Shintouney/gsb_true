<div class="box">
    <form action="" method="post">
        <div class="row">
            <div class="6u">
                <?php  $form = isset($_SESSION['form']) ? $_SESSION['form'] : array();
                if (isset($_SESSION['form'])) {
                    if(isset($_SESSION['form']['commune']) || isset($user)){
                        $_SESSION['ajax_selected'] = $_SESSION['form']['commune'];
                    }
                    unset($_SESSION['form']);
                } else if(isset($user)) {
                    $_SESSION['ajax_selected'] = $user->getCommune()->getId();
                }
                ?>
                <?php  $errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : array();
                if(isset($_SESSION['form_errors'])) unset($_SESSION['form_errors']);
                ?>

                <fieldset class="align-fieldset fieldset-auto-width">
                    <legend> <span class='icon fa-pencil-square-o'>identifiants</span></legend>
                    <div>
                        <label for="login">Login :</label>
                        <input type="text" id="login"
                               name="login" required
                            <?= isset($user)  ? ' value="' . $user->getLogin() . '"' :
                            (isset($form['login']) ?' value="' . $form['login'] . '"' : ''); ?>/>
                        <?php  $field = 'login'; if (isset($errors[$field])) {$fieldErrors = $errors[$field];  include "views/Template/form_errors.php";}
                        ?>
                    </div>
                    <div>
                        <label for="mdp">Mot de passe :</label>
                        <input type="password" id="mdp" name="mdp"<?= isset($user)? "": " required" ?>/>
                        <?php $field = 'mdp'; if (isset($errors[$field])) {$fieldErrors = $errors[$field]; include "views/Template/form_errors.php";} ?>
                    </div>
                    <div>
                        <label for="mdp_confirmation">Mot de passe (confirmation) :</label>
                        <input type="password" id="mdp_confirmation" name="mdp_confirmation"<?= isset($user)? "": " required" ?>/></div>
                    <?php $field = 'mdp_conf'; if (isset($errors[$field])) {$fieldErrors = $errors[$field]; include "views/Template/form_errors.php";} ?>
                    <div>
                        <label for="email">Email :</label>
                        <input type="email" id="email"
                               name="email" required
                            <?= isset($user) ? ' value="' . $user->getEmail() .'"' :
                                (isset($form['email']) ? ' value="' .$form['email']. '"' : ''); ?>/>
                        <?php $field = 'email';  if(isset($errors[$field])) {$fieldErrors = $errors[$field]; include "views/Template/form_errors.php";} ?>
                    </div>
                    <div>
                        <label for="role">Fonction :</label>
                        <select name="role" id="role" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role->getNom(); ?>"
                                    <?php if(isset($user) &&  $role->getNom() === $user->getRole()->getNom())
                                        {$selected = $role->getNom();}
                                         else if (isset($form['role']) &&   $role->getNom() === $form['role'])
                                         {$selected = $role->getNom();}
                                    if (isset($selected) && $selected === $role->getNom()) {echo 'selected';}
                                    else if (!isset($selected) && $role->getNom() === 'ROLE_VISITEUR') {echo  ' selected';}
                                    ?>>
                                    <?= $role->getLibelle(); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php $field = 'role';
                        if(isset($errors[$field])) {$fieldErrors = $errors[$field];
                            include "views/Template/form_errors.php";} ?>
                    </div>
                    <div>
                        <label for="date_embauche">Date d'embauche :</label>
                        <input class="span2 datepicker" id="date_embauche" name="date_embauche" size="16"
                               type="text" <?= isset($user) ? ' value="' . $user->getDateEmbauche() . '"' :
                            (isset($form['date_embauche'])? ' value="' .$form['date_embauche']. '"' : ''); ?>/>
                    </div>
                </fieldset>
            </div>
            <div class="6u">
                <fieldset class='align-fieldset fieldset-auto-width'>
                    <legend><span class='icon fa-pencil-square-o'>données personnelles</span></legend>
                    <div>
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom"
                               name="nom" required
                            <?= isset($user) ? ' value="' . $user->getNom() . '"' :
                                (isset($form['nom'])? ' value="' .$form['nom']. '"' :''); ?>/>
                    </div>
                    <div>
                        <label for="prenom">Prénom :</label>
                        <input type="text" id="prenom"
                               name="prenom" required
                            <?= isset($user) ? ' value="' . $user->getPrenom() . '"' :
                                (isset($form['prenom'])? ' value="' .$form['prenom']. '"' : ''); ?>/>
                    </div>
                    <div>
                        <label for="telephone">Tel :</label>
                        <input type="text" id="telephone"
                               name="telephone"
                            <?= isset($user) ? ' value="' . $user->getTelephone() . '"' :
                                (isset($form['telephone'])? ' value="' .$form['telephone']. '"' : ''); ?>/>
                    </div>
                    <div>
                        <label for="adresse">Adresse :</label>
                        <input type="text" id="adresse" required
                               name="adresse" <?= isset($user) ? ' value="' . $user->getAdresse() . '"' :
                            (isset($form['adresse'])? ' value="' .$form['adresse']. '"' : ''); ?>/>
                    </div>
                    <div>
                        <label for="commune">Commune :</label>
                        <span id="commune_message"
                            <?= isset($communes)|| isset($form['commune']) ? 'style="display:none"'  : 'style="display:inline"'; ?>>
                            choisir un code postal...</span>

                    </div>
                </fieldset>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="12u">
                <input class="12u special" type="submit" value="<?= isset($user) ? "Modifier utilisateur" : "Créer utilisateur"; ?>"/>
            </div>
        </div>
    </form>
</div>
