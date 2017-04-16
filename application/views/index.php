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
  <!--[if IE]>
    <style>.sort-row{padding-bottom:0!important}</style>
    <script src="<?php echo base_url(); ?>static/js/main.ie.js"></script>
  <![endif]-->
</head>
<body>

<header class="header header--map">
  <div class="header__logo">
    <a href="">
      <img src="<?php echo base_url(); ?>static/images/logo.png" class="logo" width="201" height="32">
    </a>
  </div>
  <?php if(!is_null($company->id)): ?>
  <div class="header__menu">
    <ul>
      <li id="create-job"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDUxMCA1MTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMCA1MTA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8ZyBpZD0iYWRkLWNpcmNsZSI+CgkJPHBhdGggZD0iTTI1NSwwQzExNC43NSwwLDAsMTE0Ljc1LDAsMjU1czExNC43NSwyNTUsMjU1LDI1NXMyNTUtMTE0Ljc1LDI1NS0yNTVTMzk1LjI1LDAsMjU1LDB6IE0zODIuNSwyODAuNWgtMTAydjEwMmgtNTF2LTEwMiAgICBoLTEwMnYtNTFoMTAydi0xMDJoNTF2MTAyaDEwMlYyODAuNXoiIGZpbGw9IiNGRkZGRkYiLz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" style="vertical-align: middle;"/></li>
      <li id="jobs-switcher-my"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDUxMCA1MTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMCA1MTA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8ZyBpZD0id29yayI+CgkJPHBhdGggZD0iTTQ1OSwxMTQuNzVIMzU3di01MWwtNTEtNTFIMjA0bC01MSw1MXY1MUg1MWMtMjguMDUsMC01MSwyMi45NS01MSw1MXYyODAuNWMwLDI4LjA1LDIyLjk1LDUxLDUxLDUxaDQwOCAgICBjMjguMDUsMCw1MS0yMi45NSw1MS01MXYtMjgwLjVDNTEwLDEzNy43LDQ4Ny4wNSwxMTQuNzUsNDU5LDExNC43NXogTTMwNiwxMTQuNzVIMjA0di01MWgxMDJWMTE0Ljc1eiIgZmlsbD0iI0ZGRkZGRiIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" style="vertical-align: middle;"/></li>
      <li id="jobs-switcher-all" style="display: none;"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDUxMCA1MTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMCA1MTA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8ZyBpZD0id29yayI+CgkJPHBhdGggZD0iTTQ1OSwxMTQuNzVIMzU3di01MWwtNTEtNTFIMjA0bC01MSw1MXY1MUg1MWMtMjguMDUsMC01MSwyMi45NS01MSw1MXYyODAuNWMwLDI4LjA1LDIyLjk1LDUxLDUxLDUxaDQwOCAgICBjMjguMDUsMCw1MS0yMi45NSw1MS01MXYtMjgwLjVDNTEwLDEzNy43LDQ4Ny4wNSwxMTQuNzUsNDU5LDExNC43NXogTTMwNiwxMTQuNzVIMjA0di01MWgxMDJWMTE0Ljc1eiIgZmlsbD0iI0ZGREE0NCIvPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" style="vertical-align: middle;"/></li>
    </ul>
  </div>
  <?php endif; ?>

  <div class="header__right">
    <?php if(is_null($company->id)): ?>
    <a class="btn btn--medium btn--silver" id="not-authorized"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgNDU5IDQ1OSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDU5IDQ1OTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJleGl0LXRvLWFwcCI+CgkJPHBhdGggZD0iTTE4MS4wNSwzMjEuM2wzNS43LDM1LjdsMTI3LjUtMTI3LjVMMjE2Ljc1LDEwMmwtMzUuNywzNS43bDY2LjMsNjYuM0gwdjUxaDI0Ny4zNUwxODEuMDUsMzIxLjN6IE00MDgsMEg1MSAgICBDMjIuOTUsMCwwLDIyLjk1LDAsNTF2MTAyaDUxVjUxaDM1N3YzNTdINTFWMzA2SDB2MTAyYzAsMjguMDUsMjIuOTUsNTEsNTEsNTFoMzU3YzI4LjA1LDAsNTEtMjIuOTUsNTEtNTFWNTEgICAgQzQ1OSwyMi45NSw0MzYuMDUsMCw0MDgsMHoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K"width="14" style="vertical-align: middle;
margin-bottom: 2px;
margin-right: 3px;" /> Prisijungti / Registruotis</a>
      <div class="authorize">
        <?php $this->load->view('forms/auth'); ?>
      </div>
    <?php else: ?>
        <div class="btn btn--medium btn--silver" id="authorized">
          <?php echo $company->name; ?>
          <a href="index.php?c=user&m=logout"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDMzMCAzMzAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMzMCAzMzA7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iMTZweCIgaGVpZ2h0PSIxNnB4Ij4KPGc+Cgk8cGF0aCBkPSJNMjQ1LjYwOCw4NC4zOTJjLTUuODU2LTUuODU3LTE1LjM1NS01Ljg1OC0yMS4yMTMtMC4wMDFjLTUuODU3LDUuODU4LTUuODU4LDE1LjM1NSwwLDIxLjIxM0wyNjguNzg5LDE1MEg4NS4wMDIgICBjLTguMjg0LDAtMTUsNi43MTYtMTUsMTVzNi43MTYsMTUsMTUsMTVoMTgzLjc4NWwtNDQuMzkyLDQ0LjM5MmMtNS44NTgsNS44NTgtNS44NTgsMTUuMzU1LDAsMjEuMjEzICAgYzIuOTI5LDIuOTI5LDYuNzY4LDQuMzkzLDEwLjYwNyw0LjM5M2MzLjgzOSwwLDcuNjc4LTEuNDY0LDEwLjYwNi00LjM5M2w2OS45OTgtNjkuOTk4YzUuODU4LTUuODU3LDUuODU4LTE1LjM1NSwwLTIxLjIxMyAgIEwyNDUuNjA4LDg0LjM5MnoiIGZpbGw9IiMwMDAwMDAiLz4KCTxwYXRoIGQ9Ik0xNTUsMzMwYzguMjg0LDAsMTUtNi43MTYsMTUtMTVzLTYuNzE2LTE1LTE1LTE1SDQwVjMwaDExNWM4LjI4NCwwLDE1LTYuNzE2LDE1LTE1cy02LjcxNi0xNS0xNS0xNUgyNSAgIGMtOC4yODQsMC0xNSw2LjcxNi0xNSwxNXYzMDBjMCw4LjI4NCw2LjcxNiwxNSwxNSwxNUgxNTV6IiBmaWxsPSIjMDAwMDAwIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" width="14" style="opacity: 0.5;vertical-align: middle;
margin-bottom: 2px;
margin-right: 3px; margin-left:10px;"/></a>
        </div>
      <div class="edit-company">
        <?php $this->load->view('forms/edit_company'); ?>
      </div>
    <?php endif; ?>
  </div>
</header>
<div id="show_list" class="btn btn--medium btn--green" style="position: absolute;
z-index: 999;
display: none;
margin: 10px;
cursor: pointer;">Parodyti paieškos langą ir skelbimų sąrašą</div>
<div class="window" id="jobs">
    <span style="margin-left:10px;color:silver;">></span><input id="pac-input" class="controls" type="text" placeholder="Mano gatvė, miestas"><button onclick="enableSetHomePosition()" class="set_home_position"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" class="set_home_position_icon" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve"><path d="M255,0C155.55,0,76.5,79.05,76.5,178.5C76.5,311.1,255,510,255,510s178.5-198.9,178.5-331.5C433.5,79.05,354.45,0,255,0z     M255,242.25c-35.7,0-63.75-28.05-63.75-63.75s28.05-63.75,63.75-63.75s63.75,28.05,63.75,63.75S290.7,242.25,255,242.25z"/></svg></button>
    <div class="window__sort">
      <div class="sort-row">
        <div class="select-field">
          <select name="category_id" id="category_id">
            <option value="0" selected="selected">Visos darbo sritis</option>
            <option value="29">Sezoninis darbas</option>
            <option value="30">Darbas neįgaliesiems</option>
            <option value="31">Papildomas darbas</option>
            <option value="1">Administravimas</option>
            <option value="2">Apsauga</option>
            <option value="3">Apskaita, Finansai, Auditas</option>
            <option value="4">Dizainas, Architektūra</option>
            <option value="5">Energetika, Elektronika</option>
            <option value="6">Informacinės technologijos</option>
            <option value="7">Inžinerija, Mechanika</option>
            <option value="8">Klientų aptarnavimas, Paslaugos</option>
            <option value="9">Maisto gamyba</option>
            <option value="10">Marketingas, Reklama</option>
            <option value="11">Medicina, Sveikatos apsauga, Farmacija 	</option>
            <option value="12">Nekilnojamasis turtas</option>
            <option value="13">Pardavimų vadyba</option>
            <option value="14">Personalo valdymas</option>
            <option value="15">Pirkimai, Tiekimas</option>
            <option value="16">Pramonė, Gamyba</option>
            <option value="17">Prekyba - konsultavimas</option>
            <option value="18">Sandėliavimas</option>
            <option value="19">Statyba</option>
            <option value="20">Švietimas, Mokymai, Kultūra</option>
            <option value="21">Teisė</option>
            <option value="22">Transporto vairavimas</option>
            <option value="23">Transporto/logistikos vadyba</option>
            <option value="24">Vadovavimas, Valdymas</option>
            <option value="25">Valstybės tarnyba</option>
            <option value="26">Viešbučiai</option>
            <option value="27">Žemės ūkis, žuvininkystė</option>
            <option value="28">Žiniasklaida, Viešieji ryšiai</option>
          </select>
          </div>
      </div>
   <div class="sort-row">
        <div class="sort-column">
          <div class="select-field">
            <select name="edu_id" id="edu_id">
              <option value="0" selected="selected">Visi išsilavinimo rūšiai</option>
              <option value="1">Aukštasis neuniversitetinis</option>
              <option value="2">Aukštasis universitetinis</option>
              <option value="3">Aukštesnysis išsilavinimas</option>
              <option value="4">Magistras</option>
              <option value="5">Nebaigtas vidurinis</option>
              <option value="6">Profesinė mokykla</option>
              <option value="7">Specialusis vidurinis</option>
              <option value="8">Vidurinis</option>
            </select>
          </div>
        </div>
        <div class="sort-column">
          <div class="select-field">
            <select name="work_time_id" id="work_time_id">
              <option value="0" selected="selected">Darbo laikas nesvarbus</option>
              <option value="1">Pilna darbo diena</option>
              <option value="2">Nepilna darbo diena</option>
            </select>
          </div>
        </div>
      </div>

      <div class="sort-row">
        <div class="sort-column">
          <label for="salary_from">Alga nuo <span id="salary_from_value">100</span> €</label>
          <input type="range" class="sort-range" id="salary_from" name="salary_from" min="100" max="1200" step="50" value="100">
        </div>
        <div class="sort-column">
          <label for="distance">Laikas iki darbo <span id="distance_value">35</span> min.</label>
          <input type="range" class="sort-range" id="distance" name="distance" min="10" max="60" step="5" value="35">
        </div>
        </div>
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
              <div style="background:#ececec;width:100px;height:16px;" class="offer-company"></div>
              <div style="background:#dff4e1;width:70px;height:14px;" class="offer-salary"></div>
            </div>
            <div class="offer-right offer-right-inactive"><div class="offer-gauge"></div></div>
          </a>
        </li><li>
          <a href="#" class="link--offer clearfix" title="">
            <div class="offer-logo">
              <div style="width:74px; height:50px; background:#ececec"></div>
            </div>
            <div class="offer-content">
              <h5 style="background:#c8ecff;width:200px;height:15px;"></h5>
              <div style="background:#ececec;width:100px;height:16px;" class="offer-company"></div>
              <div style="background:#dff4e1;width:70px;height:14px;" class="offer-salary"></div>
            </div>
            <div class="offer-right offer-right-inactive"><div class="offer-gauge"></div></div>
          </a>
        </li><li>
          <a href="#" class="link--offer clearfix" title="">
            <div class="offer-logo">
              <div style="width:74px; height:50px; background:#ececec"></div>
            </div>
            <div class="offer-content">
              <h5 style="background:#c8ecff;width:200px;height:15px;"></h5>
              <div style="background:#ececec;width:100px;height:16px;" class="offer-company"></div>
              <div style="background:#dff4e1;width:70px;height:14px;" class="offer-salary"></div>
            </div>
            <div class="offer-right offer-right-inactive"><div class="offer-gauge"></div></div>
          </a>
        </li><li>
          <a href="#" class="link--offer clearfix" title="">
            <div class="offer-logo">
              <div style="width:74px; height:50px; background:#ececec"></div>
            </div>
            <div class="offer-content">
              <h5 style="background:#c8ecff;width:200px;height:15px;"></h5>
              <div style="background:#ececec;width:100px;height:16px;" class="offer-company"></div>
              <div style="background:#dff4e1;width:70px;height:14px;" class="offer-salary"></div>
            </div>
            <div class="offer-right-inactive"><div class="offer-gauge"></div></div>
          </a>
        </li><li>
          <a href="#" class="link--offer clearfix" title="">
            <div class="offer-logo">
              <div style="width:74px; height:50px; background:#ececec"></div>
            </div>
            <div class="offer-content">
              <h5 style="background:#c8ecff;width:200px;height:15px;"></h5>
              <div style="background:#ececec;width:100px;height:16px;" class="offer-company"></div>
              <div style="background:#dff4e1;width:70px;height:14px;" class="offer-salary"></div>
            </div>
            <div class="offer-right offer-right-inactive"><div class="offer-gauge"></div></div>
          </a>
        </li>
      </ul>
    </div>
  <div class="window__sort__footer clearfix">
      <div class="pg-left">
        <div class="btn btn-pg" id="cancel-sort">Atšaukti filtrus</div>
      </div>
      <div class="pg-count"></div>
      <div class="pg-right">
        <div class="btn btn-pg" id="window_sort_close">Rodyti skelbimus</div>
      </div>
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
  </div>

  <div class="hide-window" id="toggle_list">Paslėpti langą <img style="padding: 0 5px;vertical-align: top;" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDI5Mi4zNjIgMjkyLjM2MiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjkyLjM2MiAyOTIuMzYyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTI4Ni45MzUsMTk3LjI4NkwxNTkuMDI4LDY5LjM3OWMtMy42MTMtMy42MTctNy44OTUtNS40MjQtMTIuODQ3LTUuNDI0cy05LjIzMywxLjgwNy0xMi44NSw1LjQyNEw1LjQyNCwxOTcuMjg2ICAgQzEuODA3LDIwMC45LDAsMjA1LjE4NCwwLDIxMC4xMzJzMS44MDcsOS4yMzMsNS40MjQsMTIuODQ3YzMuNjIxLDMuNjE3LDcuOTAyLDUuNDI4LDEyLjg1LDUuNDI4aDI1NS44MTMgICBjNC45NDksMCw5LjIzMy0xLjgxMSwxMi44NDgtNS40MjhjMy42MTMtMy42MTMsNS40MjctNy44OTgsNS40MjctMTIuODQ3UzI5MC41NDgsMjAwLjksMjg2LjkzNSwxOTcuMjg2eiIgZmlsbD0iIzgwYmFkYSIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" /></div>

  <div class="weights">
    <b>Paieškos kriterijų nustatymas</b>
    <form action="">
      <label for="weight_distance">Atstumas</label>
      <input type="range" id="weight_distance" value="0.50" max="0.99" min="0.01" step="0.01">

      <label for="weight_salary">Atlyginimas</label>
      <input type="range" id="weight_salary" value="0.25" max="0.99" min="0.01" step="0.01">

      <label for="weight_average_salary">Vid. atlyginimas</label>
      <input type="range" id="weight_average_salary" value="0.17" max="0.99" min="0.01" step="0.01">

      <label for="weight_credit">Ekonominė būklė</label>
      <input type="range" id="weight_credit" value="0.08" max="0.99" min="0.01" step="0.01">
    </form>
    <div id="weights_reset" class="btn btn--small btn--silver" style="width: 100%;padding: 5px;margin-top: 10px;">Atšaukti pakeitimus</div>
  </div>

  <div class="window" id="add_job" style="display: none;">
    <?php $this->load->view('forms/add_job'); ?>
  </div>

  <div class="window" id="edit_job" style="display: none;">
    <?php $this->load->view('forms/edit_job'); ?>
  </div>
    <div id="color-scale" style="display:none"><img src="static/images/color-scale.png"></div>
	<div id="map"></div>
	<script src="<?php echo base_url(); ?>static/js/markerclusterer.js" async></script>

	<script src="<?php echo base_url(); ?>static/js/main.js?v=1"></script>
	<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAJhXhTIxa5iUsy3FQA5bERrbbxdEZ7Cls&libraries=places&language=lt&region=LT&callback=getMarkers"></script>
</body>
</html>