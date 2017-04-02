<?php

echo validation_errors();

echo form_open('?c=backoffice&m=add_job');

?>
    <div class="form-row">
    <label for="title">Pareiga <small class="text-danger">*</small></label>
    <input type="text" name="title" id="title" required>
    </div>

    <?php echo '
    <div class="form-row">
        <label for="category_id">Darbo sritis <small class="text-danger">*</small></label>
        <select name="category_id" id="category_id" required>';
        foreach($categories as $category){
            echo '<option value="'.$category['category_id'].'">'.$category['category_name'].'</option>';
        }
        echo '</select>
    </div>';
    ?>

    <div class="form-row">
        <label for="address">Adresas <small class="text-danger">*</small></label>
        <input type="text" name="address" id="address" placeholder="Darbo vietos adresas" required>
    </div>

    <?php echo '
    <div class="form-row">
        <label for="city_id">Miestas arba savivaldybė <small class="text-danger">*</small></label>
        <select name="city_id" id="city_id" required>';
        foreach($cities as $city){
            echo '<option value="'.$city['city_id'].'">'.$city['city_name'].'</option>';
        }
        echo '</select>
    </div>';
    ?>

    <?php echo '
    <div class="form-row">
        <label for="edu_id">Išsilavinimas <small class="text-danger">*</small></label>
        <select name="edu_id" id="edu_id" required>';
        foreach($educations as $education){
            echo '<option value="'.$education['id'].'">'.$education['name'].'</option>';
        }
        echo '</select>
    </div>';
    ?>

    <div class="form-row">
        <label for="salary_from">Atlyginimas <small>(EUR, Atskaičius mokesčius)</small></label>
        <div style="display: inline-block;width: 46%;margin-right: 20px;">
            <label style="display: inline-block;">Nuo <small class="text-danger">*</small></label> <input class="form-control salary" type="number" name="salary_from" id="salary_from">
        </div>
        <div style="display: inline-block;width:46%">
            <label style="display: inline-block;">Iki</label> <input class="form-control salary" type="number" name="salary_to" id="salary_to">
        </div>
    </div>


    <div class="form-row">
        <label for="work_time_id">Darbo laikas</label>
        <select name="work_time_id" id="work_time_id" required>
            <option value="1">Pilna darbo diena</option>
            <option value="2">Nepilna darbo diena</option>
        </select>
    </div>

    <div class="form-row">
        <label for="url">Puslapio adresas kitame skelbimo portale <small class="text-danger">*</small></label>
        <input type="url" name="url" id="url" placeholder="Pvz., http://www.darbo.lt/darbas/darbdavys.php?id=2017043003311249582420" required>
    </div>

    <div class="form-row">
        <small>* - privalomas laukas</small>
    </div>
    <div class="form-row">
        <input type="submit"  value="Paskelbti" class="btn btn--small btn--green" />
    </div>
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
