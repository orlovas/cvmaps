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
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/jquery-ui.theme.min.css">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>static/images/favicon.ico?v=1">

    <script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script>
</head>
<body>

</body>
</html>
<?php

echo validation_errors();

echo form_open('?c=backoffice&m=add_job');

?>

    <div class="form-row">
        <label for="url">Puslapio adresas kitame skelbimo portale <small class="text-danger">*</small></label>
        <input type="url" name="url" id="url" placeholder="Pvz., http://www.darbo.lt/darbas/darbdavys.php?id=2017043003311249582420" required>
    </div>


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

    <div class="form-row">
        <label for="salary_from">Atlyginimas <small>(EUR, Atskaičius mokesčius)</small></label>
        <div style="display: inline-block;width: 46%;margin-right: 20px;">
            <label style="display: inline-block;">Nuo <small class="text-danger">*</small></label> <input class="form-control salary" type="number" name="salary_from" id="salary_from" required>
        </div>
        <div style="display: inline-block;width:46%">
            <label style="display: inline-block;">Iki</label> <input class="form-control salary" type="number" name="salary_to" id="salary_to">
        </div>
    </div>

    <div class="form-row">
        <label for="expire">Galioja iki</label>
        <input type="text" name="expire" id="expire">
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


<script src="static/js/jquery-ui.min.js"></script>
<script src="static/js/datepicker-lt.js"></script>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAJhXhTIxa5iUsy3FQA5bERrbbxdEZ7Cls&libraries=places&language=lt&region=LT"></script>

<script>
    $( function() {
        $.datepicker.setDefaults( $.datepicker.regional[ "lt" ] );
        $( "#expire" ).datepicker({
            minDate: 0,
            maxDate: "+3M",
            dateFormat: "yy-mm-dd"
        });
    } );

    var csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";

    $("#url").on("input", function() {
        var url = $("#url").val();
        $("#category_id option[value=1]").prop('selected', true);
        $("#city_id option[value=1]").prop('selected', true);
        $("#salary_from").val("");
        $("#salary_to").val("");
        $("#address").val("");
        $("#title").val("");
        $.ajax({
            method: "POST",
            url: "localhost/cvm/?c=parser&m=get",
            data: {"<?php echo $this->security->get_csrf_token_name(); ?>":csrfHash, url: url }
        }).done(function( v ) {
            if(v.website === undefined){
                return;
            }

            if(v.csrfHash !== undefined){
                csrfHash = v.csrfHash;
            }

            if(v.title !== null){
                $("#title").val(v.title);
            } else {
                $("#title").val("");
            }

            if(v.category.cvm !== null && v.category !== null){
                $("#category_id option[value="+ v.category.cvm.category_id+"]").prop('selected', true);
            } else {
                $("#category_id option[value=1]").prop('selected', true);
            }

            if(v.city_id !== null && v.city !== null){
                $("#city_id option[value="+ v.city_id.city_id+"]").prop('selected', true);
            } else {
                $("#city_id option[value=1]").prop('selected', true);
            }

            if(v.salary !== null || v.salary !== "" || v.salary !== 0){
                $("#salary_from").val(v.salary);
            } else {
                $("#salary_from").val("");
            }

            if(v.expire !== null || v.expire !== "" || v.expire !== 0){
                $("#expire").datepicker("setDate", v.expire);
            } else {
                $("#expire").datepicker("setDate", 30);
            }


        });
    });
</script>

<script>
    setTimeout(function() { initAddressBox(); }, 1000);
    function initAddressBox(){
        var input = document.getElementById('address');
        var options = {componentRestrictions: {country: 'lt'}};
        var searchBox = new google.maps.places.SearchBox(input,options);

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
