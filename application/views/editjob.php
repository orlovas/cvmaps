<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php

echo validation_errors();

echo form_open('?c=backoffice&m=edit_job&id='.$id);

?>

<label for="title">Pareiga <small class="text-danger">(Privalomas laukas)</small></label><br />
<input type="text" name="title" id="title" value="<?php echo $title ?>" required><br />

<?php echo '
<div class="form-group">
<label for="category_id">Darbo sritis <small class="text-danger">(Privalomas laukas)</small></label>
<select class="form-control" name="category_id" id="category_id" required>';
foreach($categories as $category){

    echo '<option value="'.$category['category_id'].'" '.($category['category_id'] === $category_id ? "selected" : "").'>'.$category['category_name'].'</option>';
}
echo '</select>
</div>';
?>

<?php echo '
<div class="form-group">
<label for="city_id">Miestas arba savivaldybė <small class="text-danger">(Privalomas laukas)</small></label>
<select class="form-control" name="city_id" id="city_id" required>';
foreach($cities as $city){
    echo '<option value="'.$city['city_id'].'"  '.($city['city_id'] === $city_id ? "selected" : "").'>'.$city['city_name'].'</option>';
}
echo '</select>
</div>';
?>

<label for="address">Adresas <small class="text-danger">(Privalomas laukas)</small></label><br />
<input type="text" name="address" id="address" value="<?php echo $address ?>" required><br />

<label for="salary_from">Atlyginimas <small>(Atskaičius mokesčius)</small></label><br />
Nuo <input class="form-control salary" type="number" name="salary_from" id="salary_from" value="<?php echo ($salary_from === NULL ? "" : $salary_from) ?>">
Iki <input class="form-control salary" type="number" name="salary_to" id="salary_to" value="<?php echo ($salary_to === NULL ? "" : $salary_to) ?>"><br />


<label for="work_time_id">Darbo laikas</label>
<select name="work_time_id" id="work_time_id" required>
    <option value="1" <?php echo ($work_time_id === "1" ? "selected" : "") ?>>Pilna darbo diena</option>
    <option value="2" <?php echo ($work_time_id === "2" ? "selected" : "") ?>>Nepilna darbo diena</option>
</select>

<label for="url">URL <small class="text-danger">(Privalomas laukas)</small></label><br />
<input type="url" name="url" id="url" value="<?php echo $url ?>" required><br />

<input type="submit" value="Submit" />
<?php

echo form_close();
?>

<script>
    function initSearchBox(){
        var input = document.getElementById('address');
        var options = {componentRestrictions: {country: 'lt'}};
        var searchBox = new google.maps.places.SearchBox(input,options);
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }


            });
        });
    }
</script>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAJhXhTIxa5iUsy3FQA5bERrbbxdEZ7Cls&libraries=places&language=lt&region=LT&callback=initSearchBox"></script>
</body>
</html>
