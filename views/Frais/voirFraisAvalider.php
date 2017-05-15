<!-- Banner -->
<br/>
<header>
<div class="box">
<p>Valider les fiches de frais du mois écoulé</p>
<form  action="?page=frais&action=voirFraisAValider" method="post">
	<fieldset class='align-fieldset fieldset-auto-width'>
	<legend>&nbsp;<a class='icon fa-calendar'> Selectionner le l'utilisateur et le mois</a>&nbsp;</legend>

		<div class="3u$ align-center uniform">
			<p>Visiteur</p>
			<div class="select-wrapper">
				<select name="utilisateur">
				<?php
					foreach ($lesVisiteurs as $visiteur) {
						?>
					<option value="<?=$visiteur['id'];?>"><?php echo $visiteur['nom']." ".$visiteur['prenom']; ?></option>
					<?php				}
				?>
				</select>
				<br/>
				<p>Mois</p>
			</div>
						<div class="select-wrapper">
				<select name="MoisSelect">
				<?php
					foreach ($lesMois as $mois) {
						if ($mois['mois'] == $moisASelectionner) {
							?>
							<option selected value="<?=$mois['mois'];?>">- <?php echo  $mois['numMois']."/".$mois['numAnnee']; ?> -</option>
							<?php
						}else{
						?>
							<option value="<?=$mois['mois'];?>"><?php echo  $mois['numMois']."/".$mois['numAnnee']; ?></option>
						<?php
						}
					}
					?>
				</select>
			</div>

			<hr>
			<div class="12u$ align-center">
			<ul class="actions">
				<li><input type="submit" value="Valider" class="special"/></li>
				<li><input type="reset" value="Reset" /></li>
			</ul>
				</div>
		</div>
</div>
	</fieldset>
</form>

</header>