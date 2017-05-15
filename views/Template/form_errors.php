<?php if(isset($fieldErrors)) : ?>
    <ul class="errors" id="<?=$field.'_error' ?>s">
        <?php foreach ($fieldErrors as $message) { ?>
            <li><span><?= $message ?></span></li>
        <?php } ?>
    </ul>
<?php endif; ?>