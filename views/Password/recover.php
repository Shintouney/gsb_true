<div class="body"></div>
<div class="grad"></div>
<div class="main">


    <?php if (isset($invalid_id)) { ?><h3><?=$invalid_id ;?> n'est pas un identifiant connu</h3><?php }; ?>
    <form action="" method="post" >
        <div>
            <label for="username">Demander un nouveau mot de passe :</label>
        </div>
        <div>
            <input placeholder="identifiant" type="text" id="username" name="username" required="required"/>

            <input type="submit" value="envoyer" />
        </div>
    </form>
</div>

