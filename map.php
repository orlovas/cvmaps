<?php
header("Access-Control-Allow-Origin: *");
?>

<!doctype html>
<html lang="lt">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Darbo skelbimai žemėlapyje — CVMaps.lt</title>

  <meta name="description" content="">

  <style>
  html,body{font:100% 'Helvetia Neue',Arial,sans-serif; color:#2d2d2d; height:100%;}
  .container{margin:0 auto;max-width:1265px;width:100%;}
  </style>

  <link rel="stylesheet" href="static/css/main.css?v=1">
  <link rel="shortcut icon" href="static/images/favicon.ico?v=1">

  <script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script>

  <!--[if lte IE 8]>
    <link rel="stylesheet" href="static/css/main.ie.css">
    <script src="static/js/main.ie.js"></script>
  <![endif]-->
</head>
<body>
<!--<header class="header header--map">
  <div class="header__logo">
    <a href="">
      <img src="static/images/logo.svg" class="logo" width="210" height="37">
    </a>
  </div>
</header>-->
<div id="show_list" style="position: absolute;
top: 0px;
z-index: 999;
display: none;
background-color: white;
padding: 3px;
font-size: 0.75em;
margin: 10px;
cursor: pointer;">Parodyti paieškos langą ir skelbimų sąrašą</div>
<div class="window">
    <div class="window__search clearfix">
      <b>Darbo skelbimų paieška</b>
      <span class="link--white-underline toright" id="toggle_list">Paslėpti langą</span>
      <form id="search" action="" class="search-field">
        <input type="search" class="search-text" placeholder="Įveskite arba pasirinkite pareigą">
        <div class="search-dropdown" style="z-index:99;height:200px;overflow-y:scroll;box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.23);">
          <div class="sort-box-one-column" id="dd_category_id">
							<a href="#0">Nesvarbu</a>
							<hr>
							<a href="#29" title="Sezoninis darbas">Sezoninis darbas</a>
							<a href="#30">Darbas neįgaliesiems</a>
							<a href="#31">Papildomas darbas</a>
							<hr>
							<a href="#1">Administravimas</a>
							<a href="#2">Apsauga</a>
							<a href="#3">Apskaita, Finansai, Auditas</a>
							<a href="#4">Dizainas, Architektūra</a>
							<a href="#5">Energetika, Elektronika</a>
							<a href="#6">Informacinės technologijos</a>
							<a href="#7">Inžinerija, Mechanika</a>
							<a href="#8">Klientų aptarnavimas, Paslaugos</a>
							<a href="#9">Maisto gamyba</a>
							<a href="#10">Marketingas, Reklama</a>
							<a href="#11">Medicina, Sveikatos apsauga, Farmacija 	</a>
							<a href="#12">Nekilnojamasis turtas</a>
							<a href="#13">Pardavimų vadyba</a>
							<a href="#14">Personalo valdymas</a>
							<a href="#15">Pirkimai, Tiekimas</a>
							<a href="#16">Pramonė, Gamyba</a>
							<a href="#17">Prekyba - konsultavimas</a>
							<a href="#18">Sandėliavimas</a>
							<a href="#19">Statyba</a>
							<a href="#20">Švietimas, Mokymai, Kultūra</a>
							<a href="#21">Teisė</a>
							<a href="#22">Transporto vairavimas</a>
							<a href="#23">Transporto/logistikos vadyba</a>
							<a href="#24">Vadovavimas, Valdymas</a>
							<a href="#25">Valstybės tarnyba</a>
							<a href="#26">Viešbučiai</a>
							<a href="#27">Žemės ūkis, žuvininkystė</a>
							<a href="#28">Žiniasklaida, Viešieji ryšiai</a>
						</div>
        </div>
        <button type="button" class="dropdown" onclick="wdropdown()">
          <span class="dropdown-replace"></span>
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 386.257 386.257" style="enable-background:new 0 0 386.257 386.257;" xml:space="preserve" width="10px" height="10px">
          <polygon points="0,96.879 193.129,289.379 386.257,96.879 "/>
          </svg>
        </button>
        <button type="submit" class="search">
          <span class="search-replace"></span>
          <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve" width="18px" height="18px">
          <path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23
            s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92
            c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17
            s-17-7.626-17-17S14.61,6,23.984,6z"/>
            </svg>
          </button>
      </form>

    </div>
    <div class="window__details clearfix">
      <span>Rasta <span id="jobs-count"></span> skelbimų</span>
      <button class="btn--sort toright" onclick="wsort()">Rūšiavimo nustatymai</button>
    </div>
    <div class="window__sort">

      <div class="sort-row">
        <label for="city_id">Miestas</label>
        <select name="city_id" id="city_id">
          <option value="0" selected="selected">- Visi -</option>
          <optgroup label="Dideli miestai">
            <option value="1">Vilnius</option>
            <option value="3">Kaunas</option>
            <option value="5">Klaipėda</option>
            <option value="7">Šiauliai</option>
            <option value="9">Panevėžys</option>
          </optgroup>
          <optgroup label="Kiti miestai">
            <option value="11">Akmenė</option>
            <option value="13">Alytus</option>
            <option value="15">Anykščiai</option>
            <option value="17">Birštonas</option>
            <option value="19">Biržai</option>
            <option value="21">Druskininkai</option>
            <option value="23">Elektrėnai</option>
            <option value="25">Gargždai</option>
            <option value="26">Ignalina</option>
            <option value="28">Jonava</option>
            <option value="30">Joniškis</option>
            <option value="32">Jurbarkas</option>
            <option value="34">Kaišiadorys</option>
            <option value="36">Kalvarija</option>
            <option value="38">Kazlų Rūda</option>
            <option value="40">Kėdainiai</option>
            <option value="42">Kelmė</option>
            <option value="44">Kretinga</option>
            <option value="46">Kupiškis</option>
            <option value="48">Kuršėnai</option>
            <option value="49">Lazdijai</option>
            <option value="51">Lentvaris</option>
            <option value="52">Marijampolė</option>
            <option value="54">Mažeikiai</option>
            <option value="56">Molėtai</option>
            <option value="58">Naujoji Akmenė</option>
            <option value="59">Neringa</option>
            <option value="61">Pagėgiai</option>
            <option value="63">Pakruojis</option>
            <option value="65">Palanga</option>
            <option value="67">Pasvalys</option>
            <option value="69">Plungė</option>
            <option value="71">Prienai</option>
            <option value="73">Radviliškis</option>
            <option value="75">Raseiniai</option>
            <option value="77">Rietavas</option>
            <option value="79">Rokiškis</option>
            <option value="81">Šakiai</option>
            <option value="83">Šalčininkai</option>
            <option value="85">Šilalė</option>
            <option value="87">Šilutė</option>
            <option value="89">Širvintos</option>
            <option value="91">Skuodas</option>
            <option value="93">Švenčionys</option>
            <option value="95">Tauragė</option>
            <option value="97">Telšiai</option>
            <option value="99">Trakai</option>
            <option value="101">Ukmergė</option>
            <option value="103">Utena</option>
            <option value="105">Varėna</option>
            <option value="107">Vievis</option>
            <option value="108">Vilkaviškis</option>
            <option value="110">Visaginas</option>
            <option value="112">Zarasai</option>

          </optgroup>
          <optgroup label="Regionai">
            <option value="12">Akmenės r. sav.</option>
            <option value="14">Alytaus r. sav.</option>
            <option value="16">Anykščių r. sav.</option>
            <option value="18">Birštono sav.</option>
            <option value="20">Biržų r. sav.</option>
            <option value="22">Druskininkų sav.</option>
            <option value="24">Elektrėnų sav.</option>
            <option value="27">Ignalinos r. sav.</option>
            <option value="29">Jonavos r. sav.</option>
            <option value="31">Joniškio r. sav.</option>
            <option value="33">Jurbarko r. sav.</option>
            <option value="35">Kaišiadorių r. sav.</option>
            <option value="37">Kalvarijos r. sav.</option>
            <option value="4">Kauno r. sav.</option>
            <option value="39">Kazlų Rūdos r. sav.</option>
            <option value="41">Kėdainių r. sav.</option>
            <option value="43">Kelmės r. sav.</option>
            <option value="6">Klaipėdos r. sav.</option>
            <option value="45">Kretingos r. sav.</option>
            <option value="47">Kupiškio r. sav.</option>
            <option value="50">Lazdijų r. sav.</option>
            <option value="53">Marijampolės sav.</option>
            <option value="55">Mažeikių r. sav.</option>
            <option value="57">Molėtų r. sav.</option>
            <option value="60">Neringos r. sav.</option>
            <option value="62">Pagėgių sav.</option>
            <option value="64">Pakruojo r. sav.</option>
            <option value="66">Palangos r. sav.</option>
            <option value="10">Panevėžio r. sav.</option>
            <option value="68">Pasvalio r. sav.</option>
            <option value="70">Plungės r. sav.</option>
            <option value="72">Prienų r. sav.</option>
            <option value="74">Radviliškio r. sav.</option>
            <option value="76">Raseinių r. sav.</option>
            <option value="78">Rietavo sav.</option>
            <option value="80">Rokiškio r. sav.</option>
            <option value="82">Šakių r. sav.</option>
            <option value="84">Šalčininkų r. sav.</option>
            <option value="8">Šiaulių r. sav.</option>
            <option value="86">Šilalės r. sav.</option>
            <option value="88">Šilutės r. sav.</option>
            <option value="90">Širvintų r. sav.</option>
            <option value="92">Skuodo r. sav.</option>
            <option value="94">Švenčionių r. sav.</option>
            <option value="96">Tauragės r. sav.</option>
            <option value="98">Telšių r. sav.</option>
            <option value="100">Trakų r. sav.</option>
            <option value="102">Ukmergės r. sav.</option>
            <option value="104">Utenos r. sav.</option>
            <option value="106">Varėnos r. sav.</option>
            <option value="109">Vilkaviškio r. sav.</option>
            <option value="2">Vilniaus r. sav.</option>
            <option value="111">Visagino r. sav.</option>
            <option value="113">Zarasų r. sav.</option>
          </optgroup>
        </select>
      </div>

      <div class="sort-row">
        <label for="category_id">Darbo sritis</label>
        <select name="category_id" id="category_id">
          <option value="0" selected="selected">- Visi -</option>
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

      <div class="sort-row">
        <label for="salary">Alga (bruto)</label>
        <select name="salary" id="salary">
          <option value="0" selected="selected">- Nesvarbu -</option>
          <option value="380">Nuo 380 €</option>
          <!--<option value="350,380">Minimumas (380€)</option>-->
          <option value="500">Nuo 500 €</option>
          <option value="750">Nuo 750 €</option>
          <option value="1000">Nuo 1000 €</option>
          <option value="1200">Nuo 1200 €</option>
        </select>
      </div>

      <div class="sort-row">
        <label for="work_time">Darbo laikas</label>
        <select name="work_time" id="work_time">
          <option value="0" selected="selected">- Nesvarbu -</option>
          <option value="1">Pilna darbo diena</option>
          <option value="2">Nepilna darbo diena</option>
        </select>
      </div>

      <div class="sort-row">
        <label for="worker_type">Darbuotojo tipas</label>
        <select name="worker_type" id="worker_type">
          <option value="0" selected="selected">- Nesvarbu -</option>
          <option value="1">Darbuotojas</option>
          <option value="2">Laikinas darbuotojas</option>
          <option value="3">Praktikantas/Stažuotojas</option>
          <option value="4">Pagal sutartį</option>
          <option value="5">Valstybės tarnautojas</option>
        </select>
      </div>

      <div class="sort-checkbox-region">
        <label for="is_student" class="sort-checkbox"><input type="checkbox" name="is_student" id="is_student"> Darbas studentams</label>
        <label for="is_school" class="sort-checkbox"><input type="checkbox" name="is_school" id="is_school"> Darbas nepilnamečiams</label>
        <label for="is_pensioneer" class="sort-checkbox"><input type="checkbox" name="is_pensioneer" id="is_pensioneer"> Darbas pensisinkams</label>
        <label for="is_disabled" class="sort-checkbox"><input type="checkbox" name="is_disabled" id="is_disabled"> Darbas neįgaliems</label>
        <label for="is_shift" class="sort-checkbox"><input type="checkbox" name="is_shift" id="is_shift"> Pamaininis darbas</label>
        <label for="no_exp" class="sort-checkbox"><input type="checkbox" name="no_exp" id="no_exp"> Be patirties</label>
        <label for="only_premium" class="sort-checkbox"><input type="checkbox" name="only_premium" id="only_premium"> Tik premium</label>
        <label for="only_new" class="sort-checkbox"><input type="checkbox" name="only_new" id="only_new"> Tik naujasi</label>
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
	<div id="map"></div>


	<script src="static/js/markerclusterer.js" async></script>
	<script src="static/js/main.js?v=1"></script>
	<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAJhXhTIxa5iUsy3FQA5bERrbbxdEZ7Cls&libraries=places&language=lt&region=LT&callback=getMarkers"></script>
</body>
</html>
