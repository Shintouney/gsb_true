
<div class="body"></div>
		<div class="grad"></div>
		<div class="header">
			<div>GSB<span>Login</span></div>
	</div>
		<br>
		<div class="login">
			<form action="" method="post">
			<input placeholder="username" type="text" name="login" id="login"/>
			<input placeholder="password" type="password" name ="mdp" id="mdp"/>
			<input type="submit" action="index.php?login" value="connexion"/>
			</form>
			<?php echo $error; ?>
		</div>