<?php if($template === 'login') { ?>
<div class="body"></div>
<div class="grad"></div>
<div class="main">
<?php } else {?>
<div class="box">
        <div class="row">
            <div class="6u">
           <?php } ?>

    <form action="" method="post">
        <div>
            <label>Changer de mot de passe</label>
            <input placeholder="Mot de passe" type="password" name="mdp" id="mdp"/>
            <input placeholder="confirmation" type="password" name="mdp_confirmation" id="mdp_conf"/>
        </div>
        <div class="align-center">
            <input type="submit" value="envoyer" />
        </div>
    </form>
    <?php if(isset($_SESSION['form_errors'])) {?>
        <h2>Une erreur s'est produite</h2>
    <?php
		if(isset($_SESSION['form_errors']['mdp'])) {
			foreach($_SESSION['form_errors']['mdp'] as $error){
				echo  '<p>'.$error.' (mot de passe)</p>';
			}
		}
		if(isset($_SESSION['form_errors']['mdp_confirmation'])) {
			foreach($_SESSION['form_errors']['mdp_confirmation'] as $error){
			echo  '<p>'.$error.' (confirmation)</p>';
			}
		}
        
    }
    ?>
    <?php unset($_SESSION['form_errors'])?>
<?php if($template !== 'login') { ?>
    </div>
    </div>
    </div>
<?php }?>
