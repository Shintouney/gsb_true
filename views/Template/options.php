<?php foreach ($choices as $choice): ?>
<option value="<?=$choice['value']; ?>"
    <?php if(isset($_SESSION['ajax_selected']) &&  $_SESSION['ajax_selected'] == $choice['value']) {echo " selected";}?>>
    <?=$choice{'label'}; ?>
</option>
<?php endforeach;  unset($_SESSION['ajax_selected']) ?>


