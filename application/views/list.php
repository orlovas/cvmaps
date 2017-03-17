<!doctype html>
<html lang="lt">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Document</title>

	<meta name="description" content="">

	<style>
	html,body{font:100% 'Helvetia Neue',Arial,sans-serif; color:#2d2d2d;background:#f4f4f4;}
	.container{margin:0 auto;max-width:1040px;width:100%;padding:0 20px;background:#fff;-webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.25);
-moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.25);
box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.25);}
	</style>

	<link rel="stylesheet" href="http://localhost/cvm/static/css/main.css?v=1">
	<link rel="shortcut icon" href="http://localhost/cvm/static/images/favicon.ico?v=1">

	<!--[if lte IE 8]>
		<link rel="stylesheet" href="http://localhost/cvm/static/css/main.ie.css?v=1">
	<![endif]-->


	<!--[if lte IE 8]>
		<script src="http://localhost/cvm/static/js/html5shiv.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">
		<header class="header">
			<div class="header__logo">
				<a href="">
					<img src="http://localhost/cvm/static/images/logo.svg" class="logo" width="210" height="37">
				</a>
			</div>
			<div class="header__menu">
				<a href="">Sukurti darbo skelbimą</a>
				<a href="">Sukurti CV</a>
				<a href="">Prisijungti/Registruoti</a>
			</div>
			<div class="header__switch">
				<a href="" class="btn btn--green btn--small" title="Pereiti į žemėlapį"><img src="http://localhost/cvm/static/images/svg/map.svg" width="23" height="17"> Žemėlapis</a>
			</div>

		</header>

		<nav class="menu">
			<ul>
				<li><a href="">Titulinis</a></li>
				<li><a href="">Apie CVMaps</a></li>
				<li><a href="">Visi skelbimai</a></li>
				<li><a href="">Skelbimų kategorijos</a></li>
				<li><a href="">Įmonėms</a></li>
				<li><a href="">Kontaktai</a></li>
				<li class="toright nomargin"><a href="" class="smaller"><img src="http://localhost/cvm/static/images/svg/sitemap.svg" width="14" height="12" class="middle"> Svetainės žemėlapis</a></li>
				<li class="toright"><a href="" class="smaller"><img src="http://localhost/cvm/static/images/svg/disabled.svg" width="14" height="16" class="bottom"> Versija neįgaliesiems</a></li>
			</ul>

		</nav>

		<div class="big-search">
			<h1>Darbo pasiūlymų paieška <small>tarp 10135 skelbimų visoje Lietuvoje</small></h1>
			<!--<span class="big-search__help">Pagalba</span>-->
			<form class="search-form" action="">
				<input type="search" class="search-text" placeholder="Pareigos, įmonė, raktažodis, ID...">
				<input type="submit" class="btn__search" value="Rodyti">
			</form>
			<div class="fast-search">
				<a href="">Darbas Vilniuje</a><a href="">Darbas studentams</a><a href="">Darbas nepilnu etatu</a><a href="">Be patirties</a><a href="">Maximoje</a><a href="">Praktika</a><a href="">Sezoninis</a>


			</div>
		</div>
<?php
/*

$url = "http://locahost/cvm/";
if($page = $this->input->get('page', TRUE)){
	$url .= "?page=" . $page;
}
if($city_id = $this->input->get('city_id', TRUE)){
		$url .= "&city_id=" . $city_id;

}
echo $url."<br>";

function if_param_defined($param){
	if(isset($_GET[$param])){
		return 1;
	} else {
		return 0;
	}
}

echo $url.str_replace("city_id=100","city_id=99",$url);
*/
$_p = 1;
$_qt = 0;
$_order_by = 0;
$_city_id = 0;
$_category_id = 0;
$_edu_id = 0;
$_salary = 0;
$_new = 0;
$_premium = 0;
$_work_time = 0;
$_worker_type_id = 0;
$_is_student = 0;
$_is_school = 0;
$_is_pensioneer = 0;
$_is_disabled = 0;
$_is_shift = 0;
$_no_exp = 0;
$_url = "/puslapis[$_p]/fraze[$_qt]/rusiavimas[$_order_by]/miestas[$_city_id]/kategorija[$_category_id]/issilavinimas[$_edu_id]/alga[$_salary]/naujas[$_new]/premium[$_premium]/darbolaikas[$_work_time]/darbuotojotipas[$_worker_type_id]/studentas[$_is_student]/mokinys[$_is_school]/pensininkas[$_is_pensioneer]/neigalus[$_is_disabled]/pamainomis[$_is_shift]/bepatirties[$_no_exp]";
?>
		<div class="sort">
			<ul class="sort-list">
				<li class="sort-container">
					<span class="sort-name">Regionas</span>
					<div class="sort-dd">
						<div class="sort-header">Pasirinkta: <b>Visa Lietuva</b></div>
						<div class="sort-box sort-box-one-column">
							<a href="">Visa Lietuva</a>
							<hr>
							<a href="<?php echo $_url; ?>">Vilnius</a>
							<a href="/darbas/puslapis[1]/miestas[2]/">Kaunas</a>
							<a href="q/j/1/0/order_by/5">Klaipėda</a>
							<a href="q/j/1/0/order_by/7">Šiauliai</a>
							<a href="q/j/1/0/order_by/9">Panevėžys</a>
							<hr>
							<a href="q/j/1/0/order_by/11">Akmenė</a>
							<a href="q/j/1/0/order_by/12">Akmenės r. sav.</a>
							<a href="q/j/1/0/order_by/13">Alytus</a>
							<a href="q/j/1/0/order_by/14">Alytaus r. sav.</a>
							<a href="q/j/1/0/order_by/15">Anykščiai</a>
							<a href="q/j/1/0/order_by/16">Anykščių r. sav.</a>
							<a href="q/j/1/0/order_by/17">Birštonas</a>
							<a href="q/j/1/0/order_by/18">Birštono sav.</a>
							<a href="q/j/1/0/order_by/19">Biržai</a>
							<a href="q/j/1/0/order_by/20">Biržų r. sav.</a>
							<a href="q/j/1/0/order_by/21">Druskininkai</a>
							<a href="q/j/1/0/order_by/22">Druskininkų sav.</a>
							<a href="q/j/1/0/order_by/23">Elektrėnai</a>
							<a href="q/j/1/0/order_by/24">Elektrėnų sav.</a>
							<a href="q/j/1/0/order_by/25">Gargždai</a>
							<a href="q/j/1/0/order_by/26">Ignalina</a>
							<a href="q/j/1/0/order_by/27">Ignalinos r. sav.</a>
							<a href="q/j/1/0/order_by/28">Jonava</a>
							<a href="q/j/1/0/order_by/29">Jonavos r. sav.</a>
							<a href="q/j/1/0/order_by/30">Joniškis</a>
							<a href="q/j/1/0/order_by/31">Joniškio r. sav.</a>
							<a href="q/j/1/0/order_by/32">Jurbarkas</a>
							<a href="q/j/1/0/order_by/33">Jurbarko r. sav.</a>
							<a href="q/j/1/0/order_by/34">Kaišiadorys</a>
							<a href="q/j/1/0/order_by/35">Kaišiadorių r. sav.</a>
							<a href="q/j/1/0/order_by/36">Kalvarija</a>
							<a href="q/j/1/0/order_by/37">Kalvarijos r. sav.</a>
							<a href="q/j/1/0/order_by/4">Kauno r. sav.</a>
							<a href="q/j/1/0/order_by/38">Kazlų Rūda</a>
							<a href="q/j/1/0/order_by/39">Kazlų Rūdos r. sav.</a>
							<a href="q/j/1/0/order_by/40">Kėdainiai</a>
							<a href="q/j/1/0/order_by/41">Kėdainių r. sav.</a>
							<a href="q/j/1/0/order_by/42">Kelmė</a>
							<a href="q/j/1/0/order_by/43">Kelmės r. sav.</a>
							<a href="q/j/1/0/order_by/44">Kretinga</a>
							<a href="q/j/1/0/order_by/45">Kretingos r. sav.</a>
							<a href="q/j/1/0/order_by/6">Klaipėdos r. sav.</a>
							<a href="q/j/1/0/order_by/46">Kupiškis</a>
							<a href="q/j/1/0/order_by/47">Kupiškio r. sav.</a>
							<a href="q/j/1/0/order_by/48">Kuršėnai</a>
							<a href="q/j/1/0/order_by/49">Lazdijai</a>
							<a href="q/j/1/0/order_by/50">Lazdijų r. sav.</a>
							<a href="q/j/1/0/order_by/51">Lentvaris</a>
							<a href="q/j/1/0/order_by/52">Marijampolė</a>
							<a href="q/j/1/0/order_by/53">Marijampolės sav.</a>
							<a href="q/j/1/0/order_by/54">Mažeikiai</a>
							<a href="q/j/1/0/order_by/55">Mažeikių r. sav.</a>
							<a href="q/j/1/0/order_by/56">Molėtai</a>
							<a href="q/j/1/0/order_by/57">Molėtų r. sav.</a>
							<a href="q/j/1/0/order_by/58">Naujoji Akmenė</a>
							<a href="q/j/1/0/order_by/59">Neringa</a>
							<a href="q/j/1/0/order_by/60">Neringos r. sav.</a>
							<a href="q/j/1/0/order_by/61">Pagėgiai</a>
							<a href="q/j/1/0/order_by/62">Pagėgių sav.</a>
							<a href="q/j/1/0/order_by/63">Pakruojis</a>
							<a href="q/j/1/0/order_by/64">Pakruojo r. sav.</a>
							<a href="q/j/1/0/order_by/65">Palanga</a>
							<a href="q/j/1/0/order_by/66">Palangos r. sav.</a>
							<a href="q/j/1/0/order_by/10">Panevėžio r. sav.</a>
							<a href="q/j/1/0/order_by/67">Pasvalys</a>
							<a href="q/j/1/0/order_by/68">Pasvalio r. sav.</a>
							<a href="q/j/1/0/order_by/69">Plungė</a>
							<a href="q/j/1/0/order_by/70">Plungės r. sav.</a>
							<a href="q/j/1/0/order_by/71">Prienai</a>
							<a href="q/j/1/0/order_by/72">Prienų r. sav.</a>
							<a href="q/j/1/0/order_by/73">Radviliškis</a>
							<a href="q/j/1/0/order_by/74">Radviliškio r. sav.</a>
							<a href="q/j/1/0/order_by/75">Raseiniai</a>
							<a href="q/j/1/0/order_by/76">Raseinių r. sav.</a>
							<a href="q/j/1/0/order_by/77">Rietavas</a>
							<a href="q/j/1/0/order_by/78">Rietavo sav.</a>
							<a href="q/j/1/0/order_by/79">Rokiškis</a>
							<a href="q/j/1/0/order_by/80">Rokiškio r. sav.</a>
							<a href="q/j/1/0/order_by/81">Šakiai</a>
							<a href="q/j/1/0/order_by/82">Šakių r. sav.</a>
							<a href="q/j/1/0/order_by/83">Šalčininkai</a>
							<a href="q/j/1/0/order_by/84">Šalčininkų r. sav.</a>
							<a href="q/j/1/0/order_by/8">Šiaulių r. sav.</a>
							<a href="q/j/1/0/order_by/85">Šilalė</a>
							<a href="q/j/1/0/order_by/86">Šilalės r. sav.</a>
							<a href="q/j/1/0/order_by/87">Šilutė</a>
							<a href="q/j/1/0/order_by/88">Šilutės r. sav.</a>
							<a href="q/j/1/0/order_by/89">Širvintos</a>
							<a href="q/j/1/0/order_by/90">Širvintų r. sav.</a>
							<a href="q/j/1/0/order_by/91">Skuodas</a>
							<a href="q/j/1/0/order_by/92">Skuodo r. sav.</a>
							<a href="q/j/1/0/order_by/93">Švenčionys</a>
							<a href="q/j/1/0/order_by/94">Švenčionių r. sav.</a>
							<a href="q/j/1/0/order_by/95">Tauragė</a>
							<a href="q/j/1/0/order_by/96">Tauragės r. sav.</a>
							<a href="q/j/1/0/order_by/97">Telšiai</a>
							<a href="q/j/1/0/order_by/98">Telšių r. sav.</a>
							<a href="q/j/1/0/order_by/99">Trakai</a>
							<a href="q/j/1/0/order_by/100">Trakų r. sav.</a>
							<a href="q/j/1/0/order_by/101">Ukmergė</a>
							<a href="q/j/1/0/order_by/102">Ukmergės r. sav.</a>
							<a href="q/j/1/0/order_by/103">Utena</a>
							<a href="q/j/1/0/order_by/104">Utenos r. sav.</a>
							<a href="q/j/1/0/order_by/105">Varėna</a>
							<a href="q/j/1/0/order_by/106">Varėnos r. sav.</a>
							<a href="q/j/1/0/order_by/107">Vievis</a>
							<a href="q/j/1/0/order_by/108">Vilkaviškis</a>
							<a href="q/j/1/0/order_by/109">Vilkaviškio r. sav.</a>
							<a href="q/j/1/0/order_by/2">Vilniaus r. sav.</a>
							<a href="q/j/1/0/order_by/110">Visaginas</a>
							<a href="q/j/1/0/order_by/111">Visagino r. sav.</a>
							<a href="q/j/1/0/order_by/112">Zarasai</a>
							<a href="q/j/1/0/order_by/113">Zarasų r. sav.</a>
						</div>
					</div>
				</li><li class="sort-container">
					<span class="sort-name">Darbo sritis</span>
					<div class="sort-dd">
						<div class="sort-header">Pasirinkta: <b>Visos</b></div>
						<div class="sort-box sort-box-one-column">
							<a href="q/j/1/0/order_by/0/0">Nesvarbu</a>
							<hr>
							<a href="q/j/1/0/order_by/0/29">Sezoninis darbas</a>
							<a href="q/j/1/0/order_by/0/30">Darbas neįgaliesiems</a>
							<a href="q/j/1/0/order_by/0/31">Papildomas darbas</a>
							<hr>
							<a href="q/j/1/0/order_by/0/1">Administravimas</a>
							<a href="q/j/1/0/order_by/0/2">Apsauga</a>
							<a href="q/j/1/0/order_by/0/3">Apskaita, Finansai, Auditas</a>
							<a href="q/j/1/0/order_by/0/4">Dizainas, Architektūra</a>
							<a href="q/j/1/0/order_by/0/5">Energetika, Elektronika</a>
							<a href="q/j/1/0/order_by/0/6">Informacinės technologijos</a>
							<a href="q/j/1/0/order_by/0/7">Inžinerija, Mechanika</a>
							<a href="q/j/1/0/order_by/0/8">Klientų aptarnavimas, Paslaugos</a>
							<a href="q/j/1/0/order_by/0/9">Maisto gamyba</a>
							<a href="q/j/1/0/order_by/0/10">Marketingas, Reklama</a>
							<a href="q/j/1/0/order_by/0/11">Medicina, Sveikatos apsauga, Farmacija 	</a>
							<a href="q/j/1/0/order_by/0/12">Nekilnojamasis turtas</a>
							<a href="q/j/1/0/order_by/0/13">Pardavimų vadyba</a>
							<a href="q/j/1/0/order_by/0/14">Personalo valdymas</a>
							<a href="q/j/1/0/order_by/0/15">Pirkimai, Tiekimas</a>
							<a href="q/j/1/0/order_by/0/16">Pramonė, Gamyba</a>
							<a href="q/j/1/0/order_by/0/17">Prekyba - konsultavimas</a>
							<a href="q/j/1/0/order_by/0/18">Sandėliavimas</a>
							<a href="q/j/1/0/order_by/0/19">Statyba</a>
							<a href="q/j/1/0/order_by/0/20">Švietimas, Mokymai, Kultūra</a>
							<a href="q/j/1/0/order_by/0/21">Teisė</a>
							<a href="q/j/1/0/order_by/0/22">Transporto vairavimas</a>
							<a href="q/j/1/0/order_by/0/23">Transporto/logistikos vadyba</a>
							<a href="q/j/1/0/order_by/0/24">Vadovavimas, Valdymas</a>
							<a href="q/j/1/0/order_by/0/25">Valstybės tarnyba</a>
							<a href="q/j/1/0/order_by/0/26">Viešbučiai</a>
							<a href="q/j/1/0/order_by/0/27">Žemės ūkis, žuvininkystė</a>
							<a href="q/j/1/0/order_by/0/28">Žiniasklaida, Viešieji ryšiai</a>
						</div>
					</div>
				</li><li class="sort-container">
					<span class="sort-name">Išsilavinimas</span>
					<div class="sort-dd">
						<div class="sort-header">Pasirinkta: <b>Nesvarbu</b></div>
						<div class="sort-box sort-box-one-column">
							<a href="">Nesvarbu</a>
							<a href="">Aukštasis neuniversitetinis</a>
							<a href="">Aukštasis universitetinis</a>
							<a href="">Aukštesnysis išsilavinimas</a>
							<a href="">Nebaigtas vidurinis</a>
							<a href="">Profesinė mokykla</a>
							<a href="">Specialusis vidurinis</a>
							<a href="">Vidurinis</a>
							<a href=""></a>
						</div>
					</div>
				</li><li class="sort-container nomargin">
					<span class="sort-name">Atlyginimas</span>
					<div class="sort-dd">
						<div class="sort-header">Pasirinkta: <b>Nesvarbu</b></div>
						<div class="sort-box sort-box-one-column">
							<a href="">Nesvarbu</a>
							<a href="">Minimumas</a>
							<a href="">Iki 350 €</a>
							<a href="">350 - 500 €</a>
							<a href="">500 - 750 €</a>
							<a href="">750 - 1000 €</a>
							<a href="">Nuo 1000 €</a>
						</div>
					</div>
				</li>
			</ul>
			<div class="sort-controls">
				<a href="" class="btn btn--medium btn--grey">Daugiau nustatymų</a>
				<span class="toright">
					<a href="" class="sort-cancel">Atšaukti filtrus</a>
					<a href="" class="btn btn--medium btn--blue">Filtruoti skelbimus</a>
				</span>
			</div>

			<!--<label class="sort-checkbox noleftmargin"><a href=""><input type="checkbox"> Nauji skelbimai</a></label> <label class="sort-checkbox"><a href=""><input type="checkbox"> Premium skelbimai</a></label>-->
		</div>
		

		<div class="joblist">
			<div class="joblist__header">
				<span>Rasta darbo skelbimų <small><a href="">parodyti žemėlapyje</a></small></span>
				<div class="sort-dd"><span>Visa Lietuva</span></div>
			</div>

            <?php foreach ($jobs as $item):?>
			<div class="job link--job clearfix">

					<div class="job-status">
						<?php if(!empty($item['premium'])): ?>
						<img src="http://localhost/cvm/static/images/svg/premium.svg" class="logo" width="19" height="19">
						<?php endif; ?>
						<?php if($item['updated'] != '0000-00-00 00:00:00'){ ?>
								<?php if(strtotime($item['updated']) > strtotime('-2 hours')){ ?>
						<img src="http://localhost/cvm/static/images/svg/new.svg" class="logo" width="19" height="19">
								<?php } ?>
						<?php } ?>
					</div>
		            <div class="job-logo">
		              <a href=""><img src="http://localhost/cvm/static/images/l/<?php echo $item["logo"];?>" width="74"></a>
		            </div>
		            <div class="job-content">
		              <h5><a href=""><?php echo $item["title"];?></a></h5>
		              <div class="job-company"><a href=""><?php echo $item["company"]; ?></a></div>
		              <?php if(isset($item['salary_from']) || isset($item['salary_to'])) { ?>
						<div class="job-salary job-salary--rounded">
							<?php
								if (isset($item['salary_from']) && !isset($item['salary_to'])) {
									echo 'Nuo ' . $item['salary_from'];
								} elseif (!isset($item['salary_from']) && isset($item['salary_to'])) {
									echo 'Iki ' . $item['salary_to'];
								} elseif (isset($item['salary_from']) && isset($item['salary_to'])) {
									echo $item['salary_from'] . " - " . $item['salary_to'];
								}
								echo ' €';
							?>
						</div>
						  <?php
							}
						  ?>

		            </div>
		            <div class="job-address">
						Kalvarijų g. 107, Vilnius<br/><a href=""><img src="http://localhost/cvm/static/images/svg/map.svg" class="logo" width="17" height="12"> Parodyti žemėlapyje</a></div>
		            <div class="job-action"><a href="" class="btn btn--medium btn--blue">Žiūrėti skelbimą</a></div>
			</div>
            <?php endforeach;?>
		</div>

	</div>


	<script src="http://localhost/cvm/static/js/main.js?v=1" async></script>
	<script>

	</script>
</body>
</html>