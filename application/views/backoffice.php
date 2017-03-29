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
<h2>Mano skelbimai</h2>
<table>
    <?php foreach ($jobs as $item):?>
    <tr>
        <td>
            <b<a href="index.php?c=backoffice&m=edit_job&id=<?php echo $item['id'] ?>"><?php echo $item['title']; ?></a></b>
            <div><small><?php echo $item['address']; ?></small></div>
        </td>
        <td></td>
        <td></td>
        <td><a href="index.php?c=backoffice&m=edit_job&id=<?php echo $item['id'] ?>">edit</a></td>

    </tr>
    <?php endforeach; ?>
</table>

    <div id="map" style="height: 50%; width: 50%"></div>

    <script type='text/javascript'>
    <?php
        $js_array = json_encode($markers);
        echo "var c = ". $js_array . ";\n";
    ?>
    </script>

    <script src="<?php echo base_url(); ?>static/js/main_backoffice.js?v=1"></script>
	<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAJhXhTIxa5iUsy3FQA5bERrbbxdEZ7Cls&libraries=places&language=lt&region=LT&callback=initMap"></script>
</body>
</html>
