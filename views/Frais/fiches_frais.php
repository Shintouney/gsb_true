<!-- Banner -->
<br/>
<header>
<form  action="?page=frais&action=voirEtatFrais" method="post">
	<fieldset class='align-fieldset fieldset-auto-width'>
	<legend>&nbsp;<a class='icon fa-calendar'> Selectionner le mois</a>&nbsp;</legend>
	<?php
	if (!empty($lesMois)) {
	?>
		<div class="3u$ align-center uniform">
			<div class="select-wrapper">
			<?php
			?>
				<select name="listMois">
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
		<?php	
	}else {
	?>
			<div class="box align-center">
		<i class="icon icon-warning-sign">Aucune fiche frais est enregistré, saisissez une fiche frais.</i>
	</div>
	<?php
	}
	?>
	</fieldset>
</form>
<?php
if (!empty($fraishf))
{
?>
<fieldset class='align-fieldset fieldset-auto-width'>
	<legend>&nbsp;<a class='icon fa-file-text-o'> Fiche de frais du mois de <?=$txtMois;?> <?=$numDate['annee'];?></a>&nbsp;</legend>
	<h4 class="align-left">Informations</h4>
	<div class="box">
		<ul class="alt align-left">
			<li><strong>Etat :</strong> <?=$infoFiche['libEtat'];?> depuis le <strong><?=$dateModif;?></strong></li>
			<li><strong>Montant validé :</strong> <?=$infoFiche['montantValide'];?></li>
		</ul>
		</div>
		<h4 class="align-left">Descriptif des éléments hors forfait <?=$infoFiche['nbJustificatifs'];?> justificatifs reçus</h4>
		<div id="element-horsforfait" class="table-wrapper">
	<table class="alt">
		<thead>
			<tr>
				<th>Date</th>
				<th>Libellé</th>
				<th>Montant</th>
			</tr>
		</thead>
			<?php
			foreach($fraishf as $frais) {
			?>
			 <tr>
                <td><?=$frais['date']?></td>
                <td><?=$frais['libelle']?></td>
                <td><?=$frais['montant']?></td>
             </tr>
			<?php
			}
			?>
		<tbody>
	</tbody>
	</table>
<?php
}
else {
?>
	<div class="align-center box">
	<p>Aucune fiche hors forfait n'est enregistré pour ce mois.</p>
	</div>
<?php
}
?>
</fieldset>
</header>