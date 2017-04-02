<?php

echo validation_errors();

echo form_open('?c=backoffice&m=edit_job&id=');

?>
    <div class="form-row">
        <label for="edit-title">Pareiga <small class="text-danger">*</small></label>
        <input type="text" name="title" id="edit-title" value="" required>
    </div>

    <?php echo '
    <div class="form-row">
        <label for="edit-category_id">Darbo sritis <small class="text-danger">*</small></label>
        <select class="form-control" name="category_id" id="edit-category_id" required>';
        foreach($categories as $category){

            echo '<option value="'.$category['category_id'].'">'.$category['category_name'].'</option>';
        }
        echo '</select>
    </div>';
    ?>

    <?php echo '
    <div class="form-row">
    <label for="edit-city_id">Miestas arba savivaldybė <small class="text-danger">*</small></label>
    <select class="form-control" name="city_id" id="edit-city_id" required>';
    foreach($cities as $city){
        echo '<option value="'.$city['city_id'].'">'.$city['city_name'].'</option>';
    }
    echo '</select>
    </div>';
    ?>

    <?php echo '
        <div class="form-row">
            <label for="edit-edu_id">Išsilavinimas <small class="text-danger">*</small></label>
            <select name="edu_id" id="edit-edu_id" required>';
    foreach($educations as $education){
        echo '<option value="'.$education['id'].'">'.$education['name'].'</option>';
    }
    echo '</select>
        </div>';
    ?>

    <div class="form-row">
        <label for="edit-address">Adresas <small class="text-danger">*</small></label>
        <input type="text" name="address" id="edit-address" value="" required>
    </div>

    <div class="form-row">
        <label for="edit-salary_from">Atlyginimas <small>(EUR, Atskaičius mokesčius)</small></label>
        <div style="display: inline-block;width: 46%;margin-right: 20px;">
            <label style="display: inline-block;">Nuo <small class="text-danger">*</small></label> <input class="form-control salary" type="number" name="salary_from" id="edit-salary_from">
        </div>
        <div style="display: inline-block;width:46%">
            <label style="display: inline-block;">Iki</label> <input class="form-control salary" type="number" name="salary_to" id="edit-salary_to">
        </div>
    </div>

    <div class="form-row">
        <label for="edit-work_time_id">Darbo laikas</label>
        <select name="work_time_id" id="edit-work_time_id" required>
            <option value="1">Pilna darbo diena</option>
            <option value="2" >Nepilna darbo diena</option>
        </select>
    </div>

    <div class="form-row">
        <label for="edit-url">URL <small class="text-danger">*</small></label>
        <input type="url" name="url" id="edit-url" value="" required>
    </div>

    <div class="form-row">
        <input type="submit"  value="Išsaugoti" class="btn btn--small btn--green" />
    </div>
<?php

echo form_close();
?>