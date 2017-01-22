<!-- Banner -->
<br/>
<header>
<div class="box">
<p>Renseigner ma fiche de frais du mois de <?=$numMois;?> <?=$numAnnee?> :</p>
</div>
<form action="?page=frais&action=validerForfait" method="post">
	<fieldset class='align-fieldset fieldset-auto-width'>
		<legend>&nbsp;<a class='icon fa-pencil-square-o'> Eléments forfaitisés</a>&nbsp;</legend>
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
				</div>
				<?php
				}
		?>
		<?php
			foreach ($errors as $error) {
			?>
				<hr>
				<div class="box">
				<i class="icon icon-warning-sign"><?=$error;?></i>
				</div>
			<?php
			}
		?>
		<hr>
		<ul class="actions">
			<li><input type="submit" value="Valider" class="special" /></li>
			<li><input type="reset" value="Reset" /></li>
		</ul>
		</div>
	 </fieldset>
</form>

<?php
if (!empty($fraishf)){
?>
<h4>Descriptif des éléments hors forfait</h4>
<div id="element-horsforfait" class="table-wrapper">
	<table class="alt">
		<thead>
			<tr>
				<th>Date</th>
				<th>Libellé</th>
				<th>Montant</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
<?php
}
else
{
	?>
	<div class="box align-center">
		<i class="icon icon-warning-sign">Il n'y a aucun element frais enregistré.</i>
	</div>
	<?php
}
?>
		<?php
		foreach($fraishf as $frais) {
			?>
			<tr>
				<td><?=$frais['date'];?></td>
				<td><?=$frais['libelle'];?></td>
				<td><?=$frais['montant'];?></td>
				<td><a class='icon fa-trash-o' href="?page=frais&action=deleteFraisHf&id=<?=$frais['id'];?>"></a></td>
			</tr>
		<?php
		}
		?>
<?php
if (!empty($fraishf)){
?>
		</tbody>
	</table>
</div>
<?php
}
?>
<form id="element-horsforfaitform" action="?page=frais&action=validerCreationFrais#element-horsforfaitform" method="post">
	<fieldset class='align-fieldset fieldset-auto-width'>
		<legend>&nbsp;<a class='icon fa-plus'> Nouvel élément hors forfait</a>&nbsp;	</legend>
		<div class="align-center 4u">
			<div class="row uniform">
				<div class="6u 12u$(xsmall)">
					<p class="align-right">Date (jj/mm/aaaa) :</p>
				</div>
				<div class="6u$ 12u$(xsmall)">
					<input type="text" name="dateFrais" placeholder="01/01/<?=$numAnnee;?>" maxlength="10"/>
				</div>
				<div class="6u 12u$(xsmall)">
					<p class="align-right">Libellé :</p>
				</div>
				<div class="6u$ 12u$(xsmall)">
					<input type="text" name="libelle" maxlength="256"/>
				</div>
				<div class="6u 12u$(xsmall)">
					<p class="align-right">Montant :</p>
				</div>
				<div class="6u$ 12u$(xsmall)">
					<input type="text" name="montant"  placeholder="0" maxlength="10"/>
				</div>
			</div>
			<?php
			if (!empty($errorshf))
				echo '<hr><div class="box">';
			foreach ($errorshf as $error) {
			?>
				<i class="align-right icon icon-warning-sign"><strong>Attention !</strong> <?=$error;?></i><br/>
			<?php
			}
			if (!empty($errorshf))
				echo '</div>';
		?>
		<hr>
		<div class="12u$ align-center">
		<ul class="actions">
			<li><input type="submit" value="Valider" class="special"/></li>
			<li><input type="reset" value="Reset" /></li>
		</ul>
		</div>
	 </fieldset>
</form>
</header>