<!-- Banner -->
<br/>
<?php
	if (isset($validateHF))
	{
		?>

	<div class="box align-center">
		<i class="icon icon-warning-sign">Frais hors forfait validé!.</i>
	</div>
		<?php
	}
	if (isset($validateForfait))
	{
		?>

	<div class="box align-center">
		<i class="icon icon-warning-sign">Frais forfaitisé validé !</i>
	</div>
		<?php
	}
?>
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
	</fieldset>
</form>

<?php 
	if ($infos == true)
	{
		?>
	<div class="box align-center">
		<i class="icon icon-warning-sign">Aucune fiche frais est enregistré pour cette utilisateur.</i>
	</div>
			<?php
	}
?>
<?php
if ($infol == true)
{
	$idEtat = $lesInfosFicheFrais['idEtat'];
?>
<br/>
<form action="?page=frais&action=validerForfaitF&id=<?=$Visiteur['id']?>" method="post">
	<fieldset class='align-fieldset fieldset-auto-width'>
		<legend>&nbsp;<a class='icon fa-calendar'>Fiche de frais de Mr    <?php echo $Visiteur['nom'] ." ". $Visiteur['prenom']." ".$txtMois." ".$numDate['annee']?></a></legend>
		<div class="align-center 4u">
		<?php
				foreach($lesFrais as $frais) {
				$id 	  = $frais['idfrais'];
				$libelle  = $frais['libelle'];
				$quantite = $frais['quantite'];
				?>
				<div class="row uniform">
					<div class="6u 12u$(xsmall)">
						<p class="align-right"><?=$libelle;?> :</p>
					</div>
					<div class="6u$ 12u$(xsmall)">
						<input type="text" name="lesFrais[<?=$id;?>]" value="<?=$quantite?>" placeholder="0" maxlength="10"/>
					</div>
					<input type="hidden" name="date" id="hiddenField" value="<?php echo $monthr; ?>" /
				</div>
				<?php
				}
		?>
		<hr>
		<ul class="actions">
			<li><input type="submit" value="Modifier" class="special" /></li>
			<li><input type="reset" value="Reset" /></li>
		</ul>
		</div>
	 </fieldset>
</form>

<h4>Descriptif des éléments hors forfait</h4>
<div id="element-horsforfait" class="table-wrapper">
	<table class="alt">
		<thead>
			<tr>
				<th>Date</th>
				<th>Libellé</th>
				<th>Montant</th>
				<th>Situation</th>
				<th>Supprimer</th>
				<th>Valider</th>
			</tr>
		</thead>
		<tbody>

		<?php
		foreach($lesFraisHorsForfait as $frais) {
			?>
			<tr>
				<td><?=$frais['date'];?></td>
				<td><?=$frais['libelle'];?></td>
				<td><?=$frais['montant'];?></td>
				<td><?=$frais['etat'];?></td>
				<td><a class='icon fa-trash-o' href="?page=frais&action=deleteFraisHfF&id=<?=$frais['id'];?>"></a></td>
				<?php
				if($frais['idEtat'] == "VA" |$frais['idEtat'] == "RF"){
				?>
				<?php
			}else
			{
				?>
				<td><a class='icon fa-check' href="?page=frais&action=ValiderFraisHf&id=<?=$frais['id'];?>"></a></td>
				<?php
			}
			?>
			</tr>
		<?php
		}
		?>

				</tbody>
	</table>
</div>
<?php
}
?>
</div>
</header>