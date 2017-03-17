<?php
/*
LT
lat: 54.13 - 56.09
lng: 22.8 - 25.6
Vilnius
lat: 54.73 - 54.604
lng: 25.19 - 25.393
Kaunas
lat: 54.945 - 54.856
lng: 23.844 - 23.976
*/
$con = mysqli_connect("localhost","root","","cvmaps");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

mysqli_query($con, "SET NAMES 'utf8'");

$titles = [
"Pardavimų ir klientų aptarnavimo vadybininkas (-ė)",
"Serviso pamainos meistras",
"Administratorė (klientų aptarnavimas telefonu)",
"Metalinių konstrukcijų surinkėjas",
"Gamybos darbuotojas (gaminių iš metalo gamyba)",
"Granuliavimo linijos operatorius",
"Pardavėja - konsultantė",
"Kasininkas-pardavėjas Taikos pr. 84",
"Automobilių plovėjas - operatorius",
"Auklėtojos padėjėja",
"Pardavėjas parduotuvėje Bravo alco Papilėnų g., Vilniuje",
"Žurnalistas rusų kalba",
"Nekilnojamojo turto brokeris",
"Barmenas / barmenė restorane (patirtis nebūtina)",
"Buhalteriui - apskaitininkui",
"Pardavėja (-as) - konsultantė (-as)",
"Inžinieriaus sąmatininko padėjėjas",
"Greito maisto (kebabų) pagal vokiškas tradicijas paruošėja",
"Sandėlio darbuotojas",
"Pagalbinis darbuotojas",
"Baldų surinkėjas",
"Medinių krepšelių pynėjos-siuvėjos",
"Lazerinių stakliu operatorius",
"IT pardavėjas / konsultantas",
"Automobilių plovėjas-valytojas",
"Kasininkas-pardavėjas",
"Socialinio darbuotojo padėjėjas",
"Prekybos automatų aptarnavimo specialistas"];

for($i=227; $i<305; $i++){
    $lat = random_float(54.85600,54.94500);
    $lng = random_float(23.84400,23.97600);
    $k = array_rand($titles);
    $title = $titles[$k];

    if(mysqli_query($con,"INSERT INTO markers (lat, lng) VALUES ({$lat},{$lng})")){
        echo "=";
    } else {
        echo "notok".mysqli_error($con);
    }
}
/*
$mi = 100;
for($i=0; $i<205; $i++){
    $mi++;
    $k = array_rand($titles);
    $title = $titles[$k];
    $category = rand(1,31);
    $city = rand(1,2);
    if(mysqli_query($con,"INSERT INTO jobs(
      company_id,
      user_id,
      category_id,
      title,
      description,
      requirements,
      company_offer,
      address,
      city_id,
      marker_id,
      phone_id,
      email_id,
      website_id,
      work_time_id,
      worker_type_id,
      salary_from,
      salary_to,
      is_student,
      is_school,
      is_pensioneer,
      is_disabled,
      is_shift
    ) VALUES (
      1,
      1,
      {$category},
      '{$title}',
      'test',
      'test2',
      'test3',
      'adres',
      {$city},
      {$mi},
      1,
      1,
      1,
      1,
      1,
      500,
      1000,
      ".rand(0,1).",
      ".rand(0,1).",
      ".rand(0,1).",
      ".rand(0,1).",
      ".rand(0,1)."
      )")){
        echo "=";
    } else {
        echo "notok".mysqli_error($con);
    }
}
*/


function random_float ($min,$max) {
    return ($min + lcg_value()*(abs($max - $min)));
}