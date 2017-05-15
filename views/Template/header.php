<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Portail GSB</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->

    <?php if ($template === "login") {?><link rel="stylesheet" href="css/login/style.css"><?php
    }else { ?>

    <link rel="stylesheet" href="css/app/main.css" />
    <link rel="stylesheet" href="css/app/style.css" />
    <link rel="stylesheet" href="js/datepicker/css/jquery.datepick.css">
    <link rel="stylesheet" href="js/datepicker/css/fix.css">
    <link rel="stylesheet" href="css/<?= isset($template) && file_exists('css/'.$template.'/style.css') ? $template : 'default' ?>/style.css">
    <?php } ?>
    

    <!-- Optional theme -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>