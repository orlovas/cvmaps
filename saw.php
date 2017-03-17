<?php

$data = [
    //[atlyginimas,atstumas,vid.atlyginimas,kred.r)]
    [600,30,826.56,0],
    [500,26,547.70,0],
    [380,5,341.80,0],
    [420,10,572.26,0],
    [450,12,490.22,0],
    [500,12,390.90,0],
    [520,32,605.12,0],
    [600,28,734.34,0],
    [400,9,434.05,0]
];

$weight = [0.50,0.25,0.18,0.07];

$dl = sizeof($data);

$mins = [];
$maxs = [];
for($i=0; $i<$dl; $i++){
    $maxs["wage"][$i] = $data[$i][0];
    $mins["distance"][$i] = $data[$i][1];
    $maxs["average"][$i] = $data[$i][2];
    $maxs["credit"][$i] = $data[$i][2];
}

$maxs["wage"] = max($maxs["wage"]);
$mins["distance"] = min($mins["distance"]);
$maxs["average"] = max($maxs["average"]);
$maxs["credit"] = max($maxs["credit"]);

$wage = [];
$distance = [];
$average = [];
$credit = [];

for($i=0; $i<$dl; $i++){
    $result = $data[$i][0] / $maxs["wage"];
    array_push($wage,$result);

    $result =  $mins["distance"] / $data[$i][1];
    array_push($distance,$result);

    $result = $data[$i][2] / $maxs["average"];
    array_push($average,$result);

    $result = $data[$i][3] / $maxs["credit"];
    array_push($credit,$result);
}

$points = [];

for($i=0; $i<$dl; $i++){
    $result =
        $weight[0] * $wage[$i]
        + $weight[1] * $distance[$i]
        + $weight[2] * $average[$i]
        + $weight[3] * $credit[$i];
    array_push($points,round($result,3));
}

echo "<pre>";
print_r($points);
echo "</pre>";
