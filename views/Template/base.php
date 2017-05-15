<!DOCTYPE html>
<html lang="fr">
<?php include 'header.php'; ?>
<body>
    <!-- Wrapper -->
    <div id="wrapper">
        <!-- Main -->
        <div id="main">
            <div class="inner">
                <?php if ($template !== "login") include 'top.php';?>

                <?= $content; ?>
            </div>
        </div>
        <?php if ($template !== "login") include 'menu.php';?>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <script src="js/skel.min.js"></script>
    <script src="js/util.js"></script>
    <!--[if lte IE 8]><script src="js/ie/respond.min.js"></script><![endif]-->
    <script src="js/main.js"></script>
    <script src="js/datepicker/js/jquery.plugin.js"></script>
    <script src="js/datepicker/js/jquery.datepick.min.js"></script>
    <script src="js/datepicker/js/jquery-datepick-fr.js"></script>
    <?php $js = "js/". $template .".js";
    if (file_exists($js)):?><script src="<?=$js?>"></script><?php endif; ?>
</body>
</html>