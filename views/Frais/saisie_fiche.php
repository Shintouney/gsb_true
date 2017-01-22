<!-- Banner -->
<br/>
<header>
<div class="box">
<p>Renseigner ma fiche de frais du mois de <?=$numMois;?> <?=$numAnnee?> :</p>
</div>
<form action="?page=frais&action=validerForfait" method="post">
	<fieldset class='align-fieldset fieldset-auto-width'>
		<legend>Eléments forfaitisés</legend>
		<div class="align-center 4u">
		<?php
				foreach($lesFrais as $frais){
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
		<hr>
		<div class="12u$ align-center">
		<ul class="actions">
			<li><input type="submit" value="Valider" class="special" /></li>
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
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>27/01/2017</td>
				<td>Petit dejeuner.</td>
				<td>29.99</td>
				<td><a class='icon fa-trash-o' href="index.php"></a></td>
			</tr>
			<tr>
				<td>27/01/2017</td>
				<td>Petit dejeuner</td>
				<td>19.99</td>
				<td><a class='icon fa-trash-o' href="index.php"></a></td>
			</tr>
			<tr>
				<td>27/01/2017</td>
				<td> Morbi faucibus arcu accumsan lorem.</td>
				<td>29.99</td>
				<td><a class='icon fa-trash-o' href="index.php"></a></td>
			</tr>
			<tr>
				<td>27/01/2017</td>
				<td>Vitae integer tempus condimentum.</td>
				<td>19.99</td>
				<td><a class='icon fa-trash-o' href="index.php"></a></td>
			</tr>
			<tr>
				<td>27/01/2017</td>
				<td>Ante turpis integer aliquet porttitor.</td>
				<td>29.99</td>
				<td><a class='icon fa-trash-o' href="index.php"></a></td>
			</tr>
		</tbody>
	</table>
</div>
<form action="" method="post">
	<fieldset class='align-fieldset fieldset-auto-width'>
		<legend>Nouvel élément hors forfait</legend>
		<div class="align-center 4u">
			<div class="row uniform">
				<div class="6u 12u$(xsmall)">
					<p class="align-right">Date (jj/mm/aaaa) :</p>
				</div>
				<div class="6u$ 12u$(xsmall)">
					<input type="text" value="" placeholder="01/01/<?=$numAnnee;?>" maxlength="10"/>
				</div>
				<div class="6u 12u$(xsmall)">
					<p class="align-right">Libellé :</p>
				</div>
				<div class="6u$ 12u$(xsmall)">
					<input type="text" value="" maxlength="256"/>
				</div>
				<div class="6u 12u$(xsmall)">
					<p class="align-right">Montant :</p>
				</div>
				<div class="6u$ 12u$(xsmall)">
					<input type="text" value="" placeholder="0" maxlength="10"/>
				</div>
			</div>
		</div>
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