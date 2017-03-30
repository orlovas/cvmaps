<!doctype html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Darbo skelbimai žemėlapyje — CVMaps.lt</title>

    <meta name="description" content="">

    <style>
        html,body{font:100% 'Open Sans',Arial,sans-serif; color:#2d2d2d; height:100%;}
    </style>

    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/main.css?v=1">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>static/images/favicon.ico?v=1">

    <script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script>

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/main.ie.css">
    <script src="<?php echo base_url(); ?>static/js/main.ie.js"></script>
    <![endif]-->
</head>
<body>
<header class="header header--map" style="position:relative;z-index: auto">
    <div class="header__logo">
        <a href="">
            <img src="<?php echo base_url(); ?>static/images/logo.svg" class="logo" width="210" height="37">
        </a>
    </div>
    <nav class="header__menu">
        <ul>
            <li><a href="">Darbo skelbimai</a></li>
            <li><a href="">Darbdaviams</a></li>
            <li><a href="">Kontaktai</a></li>
        </ul>
    </nav>
    <div class="header__right">
        <a href="http://[::1]/cvm/index.php?c=user&m=logout">logout</a>
    </div>
</header>

<h2>Mano įmonė</h2>
<?php

echo validation_errors();

echo form_open_multipart('?c=backoffice&m=create_company');

?>

<label for="name">Pavadinimas <small class="text-danger">(Privalomas laukas)</small></label><br />
<input type="text" name="name" id="name" required><br />

<label for="average_salary">Vidutinis atlyginimas <small class="text-danger">(Privalomas laukas, iš rekvizitai.lt)</small></label><br />
<input type="text" name="average_salary" id="average_salary" required><br />

<label for="high_credit_rating">Stipriausi Lietuvoje <small>(Privalomas laukas)</small></label>
<select name="high_credit_rating" id="high_credit_rating" required>
    <option value="1">Taip</option>
    <option value="0" selected>Ne</option>
</select>
<br />
<label for="logo">Logotipas</label> <span id="enable_upload">Užkrauti logotipą</span>
<div id="upload"></div>
<input type="hidden" name="logo" id="logo" value="default.png">

<input type="submit" value="Submit" />
<?php

echo form_close();
?>

<script>
    $("#enable_upload").on("click",function(){
        $("#logo").remove();
        $("#upload").html('<input type="file" name="logo" id="logo">');
    });

</script>

</body>
</html>
