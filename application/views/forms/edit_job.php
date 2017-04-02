<?php

echo validation_errors();

echo form_open('?c=backoffice&m=edit_job&id=');

?>

<label for="title">Pareiga <small class="text-danger">(Privalomas laukas)</small></label><br />
<input type="text" name="title" id="title" value="" required><br />

<?php echo '
<div class="form-group">
<label for="category_id">Darbo sritis <small class="text-danger">(Privalomas laukas)</small></label>
<select class="form-control" name="category_id" id="category_id" required>';
foreach($categories as $category){

    echo '<option value="'.$category['category_id'].'">'.$category['category_name'].'</option>';
}
echo '</select>
</div>';
?>

<?php echo '
<div class="form-group">
<label for="city_id">Miestas arba savivaldybė <small class="text-danger">(Privalomas laukas)</small></label>
<select class="form-control" name="city_id" id="city_id" required>';
foreach($cities as $city){
    echo '<option value="'.$city['city_id'].'">'.$city['city_name'].'</option>';
}
echo '</select>
</div>';
?>

<label for="address">Adresas <small class="text-danger">(Privalomas laukas)</small></label><br />
<input type="text" name="address" id="address" value="" required><br />

<label for="salary_from">Atlyginimas <small>(Atskaičius mokesčius)</small></label><br />
Nuo <input class="form-control salary" type="number" name="salary_from" id="salary_from" ">
Iki <input class="form-control salary" type="number" name="salary_to" id="salary_to"><br />


<label for="work_time_id">Darbo laikas</label>
<select name="work_time_id" id="work_time_id" required>
    <option value="1">Pilna darbo diena</option>
    <option value="2" >Nepilna darbo diena</option>
</select>

<label for="url">URL <small class="text-danger">(Privalomas laukas)</small></label><br />
<input type="url" name="url" id="url" value="" required><br />

<input type="submit" value="Submit" />
<?php

echo form_close();
?>

<script>
    setTimeout(function() { initAddressBox(); }, 4000);
    function initAddressBox(){
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