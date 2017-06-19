<!doctype html>
<html lang="lt">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Darbo skelbimai žemėlapyje — Workmaps</title>

  <link rel="icon" type="image/png" href="../../static/images/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="../../static/images/favicon-16x16.png" sizes="16x16">

  <style>
  html,body{font:100% 'Open Sans',Arial,sans-serif; color:#2d2d2d; height:100%;}
  </style>

  <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/main.css?v=1">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>static/images/favicon.ico?v=1">

  <script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script>

  <script>
    var CVMaps = {
      paths: {
        h: document.URL.substr(0,document.URL.lastIndexOf('/')) + "/",
        s: function(){return this.h + "static/"},
        i: function(){return this.s() + "images/"}
      }
    };
    var param = {
      jobs: 0,
      user_id: <?php echo (isset($user_id) ? $user_id : 0); ?>,
      company_id: <?php echo (isset($company->id) ? $company->id : 0); ?>,
      user_jobs_ids: <?php echo isset($user_jobs_ids) ? $user_jobs_ids : 0; ?>,
      show: "all",
      weights: {
        salary: 0.50,
        distance: 0.25,
        average_salary: 0.18,
        credit: 0.07
      }
    };
  </script>

</head>
<body>

<?php
  $this->load->view('errors/notifications');
?>

  <header class="header header--map">
    <div class="header__logo">
      <a href="">
        <img src="<?php echo base_url(); ?>static/images/logo.png" class="logo" width="128" height="30">
      </a>
    </div>
    <div class="header__menu">
      <ul>
        <li>
          <a href="">Žemėlapis</a>
        </li><li>
          <a href="">Darbdaviams</a>
        </li><li>
          <a href="">Pagalba</a>
        </li><li>
          <a href="">Kontaktai</a>
        </li>
      </ul>
    </div>
  </header>
  <section class="list">

  </section>
  <section class="on-map">
    <div class="search">
      <div id="toggle-list"><button onclick="toggleList()" class="toggle-list-icon"></button></div><button class="search-icon"></button><div class="search-form">
        <input id="autocomplete" type="text" placeholder="Mano gatvė, miestas">
      </div><div id="toggle-placeandmark">
        <button onclick="enableSetHomePosition()" class="set_home_position"></button>
      </div>
    </div>

    <div class="settings-window">
      <header>
        <h3>Paieškos kriterijų nustatymai</h3>
        <button onclick="toggleSettings()" class="toggle_settings">*/*</button>
      </header>
      <section class="settings-list clearfix">
        <div class="settings-left">
          <div class="settings-row">
            <label for="category_id">Darbo sritis</label>
            <select name="category_id" id="category_id">
              <option value="0" selected="selected">Visos darbo sritis</option>
              <?php
              foreach($categories as $category){
                echo '<option value="'.$category['category_id'].'">'.$category['category_name'].'</option>';
              }
              ?>
            </select>
          </div>

          <div class="settings-row">
            <label for="edu_id">Išsilavinimas</label>
            <select name="edu_id" id="edu_id">
              <option value="0" selected="selected">Visi išsilavinimo rūšiai</option>
              <?php
              foreach($educations as $education){
                echo '<option value="'.$education['id'].'">'.$education['name'].'</option>';
              }
              ?>
            </select>
          </div>

          <div class="settings-row">
            <label for="distance">Atstumas iki darbo vietos: <span id="distance_value">35</span> min.</label>
            <input type="range" class="sort-range" id="distance" name="distance" min="10" max="60" step="5" value="35">
          </div>

          <div class="settings-row">
            <label for="salary_from">Minimali alga per mėnesį: <span id="salary_from_value">100</span> €</label>
            <input type="range" class="sort-range" id="salary_from" name="salary_from" min="100" max="1200" step="50" value="100">
          </div>
        </div>
        <div class="settings-right">
          <div class="settings-row">
            <label for="weight_distance">Atstumo iki darbo svarbumas</label>
            <input type="range" id="weight_distance" value="0.50" max="0.99" min="0.01" step="0.01">
          </div>

          <div class="settings-row">
            <label for="weight_salary">Gero atlyginimo svarbumas</label>
            <input type="range" id="weight_salary" value="0.25" max="0.99" min="0.01" step="0.01">
          </div>

          <div class="settings-row">
            <label for="weight_average_salary">Aukšto vid. atlyginimo įmonėje svarbumas</label>
            <input type="range" id="weight_average_salary" value="0.17" max="0.99" min="0.01" step="0.01">
          </div>

          <div class="settings-row">
            <label for="weight_credit">Ekonominė būklė</label>
            <input type="range" id="weight_credit" value="0.08" max="0.99" min="0.01" step="0.01">
          </div>
        </div>
      </section>
    </div>

    <div class="window__list">
      <ul>
        <li>
          <a href="#" class="link--offer clearfix" title="">
            <div class="offer-logo">
              <div style="width:74px; height:50px; background:#ececec"></div>
            </div>
            <div class="offer-content">
              <h5 style="background:#c8ecff;width:200px;height:15px;"></h5>
              <div style="background:#dff4e1;width:70px;height:14px;" class="offer-salary"></div>
            </div>
          </a>
        </li><li>
          <a href="#" class="link--offer clearfix" title="">
            <div class="offer-logo">
              <div style="width:74px; height:50px; background:#ececec"></div>
            </div>
            <div class="offer-content">
              <h5 style="background:#c8ecff;width:200px;height:15px;"></h5>
              <div style="background:#dff4e1;width:70px;height:14px;" class="offer-salary"></div>
            </div>
          </a>
        </li><li>
          <a href="#" class="link--offer clearfix" title="">
            <div class="offer-logo">
              <div style="width:74px; height:50px; background:#ececec"></div>
            </div>
            <div class="offer-content">
              <h5 style="background:#c8ecff;width:200px;height:15px;"></h5>
              <div style="background:#dff4e1;width:70px;height:14px;" class="offer-salary"></div>
            </div>
          </a>
        </li><li>
          <a href="#" class="link--offer clearfix" title="">
            <div class="offer-logo">
              <div style="width:74px; height:50px; background:#ececec"></div>
            </div>
            <div class="offer-content">
              <h5 style="background:#c8ecff;width:200px;height:15px;"></h5>
              <div style="background:#dff4e1;width:70px;height:14px;" class="offer-salary"></div>
            </div>
          </a>
        </li><li>
          <a href="#" class="link--offer clearfix" title="">
            <div class="offer-logo">
              <div style="width:74px; height:50px; background:#ececec"></div>
            </div>
            <div class="offer-content">
              <h5 style="background:#c8ecff;width:200px;height:15px;"></h5>
              <div style="background:#dff4e1;width:70px;height:14px;" class="offer-salary"></div>
            </div>
          </a>
        </li>
      </ul>
    </div>

    <div class="window__pagination clearfix">
      <div class="pg-left">
        <div class="btn btn-pg btn-disabled" id="page-prev">« Ankstesnis</div>
      </div>
      <div class="pg-count"><span id="pg-current">1</span> / <span id="pg-total"></span></div>
      <div class="pg-right">
        <div class="btn btn-pg" id="page-next">Sekantis »</div>
      </div>
    </div>
  </section>


  <script>
    $(".settings-list").hide();
    $(".window__list").hide();
    $(".window__pagination").hide();

    function toggleSettings()
    {
      $(".settings-list").slideToggle();
      $(".settings-window").toggleClass("settings-toggled");
    }

    function toggleList()
    {
      $(".list").toggleClass("list-toggled");
      $(".window__list").slideToggle();
      $(".window__pagination").slideToggle();
    }
  </script>

  <div id="color-scale" style="display:none"><img src="<?php echo base_url(); ?>static/images/color-scale.png"></div>

 <div id="map"></div>

  <script src="<?php echo base_url(); ?>static/js/markerclusterer.min.js" async></script>
  <script src="<?php echo base_url(); ?>static/js/main.js?v=1.0"></script>
  <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAJhXhTIxa5iUsy3FQA5bERrbbxdEZ7Cls&libraries=places&language=lt&region=LT&callback=getMarkers"></script>
</body>
</html>