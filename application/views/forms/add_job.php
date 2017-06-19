<!doctype html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Darbo skelbimai žemėlapyje — Workmaps</title>

    <link rel="icon" type="image/png" href="../../static/images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../../static/images/favicon-16x16.png" sizes="16x16">

    <link rel="shortcut icon" href="<?php echo base_url(); ?>static/images/favicon.ico?v=1">

    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,600" rel="stylesheet">

    <script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script>

    <style>
        *,
        *:before,
        *:after {
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }
        body {
            background: #f8f8f8;
            font-family: sans-serif;
        }

        .bo-window {
            background-color: #fff;
            padding: 30px;
            max-width: 500px;
            width: 100%;
        }

        .bo-heading {
            font-size: 24px;
            font-family: 'Fira Sans', serif;
            font-weight: 600;
            color: #33658a;
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 1px solid #cdcdcd;
        }

        label {
            display: block;
            font-size: 15px;
            color: #444;
            margin: 5px 0;
        }


        .bo-input{
            position: relative;
            margin: 5px 0 15px 0;
        }
        .bo-input input,
        .bo-input select{
            position: relative;
            width: 100%;
            font-size: 17px;
            padding: 8px 10px;
            border: 1px solid #cdcdcd;
        }

        .success input,
        .success select{
            border-color: #6ac259!important;
            transition: 0.1s all;
            color: #6ac259!important;
        }

        .success-icon{
            background-image: url("static/images/success.png");
            display: block;
            width: 16px;
            height: 16px;
            position: absolute;
            bottom: 0;
            right: 0;
            margin: 12px 10px;
        }

        .success-icon_dropdown{
            margin-right: 26px;
        }

        @keyframes spinner {
            to {transform: rotate(360deg);}
        }

        .spinner-icon:before {
            content: '';
            box-sizing: border-box;
            width: 16px;
            height: 16px;
            position: absolute;
            bottom: 0;
            right: 0;
            margin: 12px 10px;
            border-radius: 50%;
            border: 2px solid #ccc;
            border-top-color: #333;
            animation: spinner .6s linear infinite;
        }

        .spinner-icon_dropdown:before {
            content: '';
            box-sizing: border-box;
            width: 16px;
            height: 16px;
            position: absolute;
            bottom: 0;
            right: 0;
            margin: 12px 26px;
            border-radius: 50%;
            border: 2px solid #ccc;
            border-top-color: #333;
            animation: spinner .6s linear infinite;
        }

        .progress-bar-fill {
            background-color: #6ac259;
            height: 4px;
            transition: all 1s;
        }

        .progress-bar{
            margin: -30px 0 30px -30px;
            background-color: #e9e9e9;
            height: 4px;
            width: 500px;
        }

        :required{

        }

    </style>

    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/jquery-ui.theme.min.css">
</head>
<body>

<section class="bo-window">
    <div class="progress-bar"><div class="progress-bar-fill" style="width: 0"></div></div>
    <h1 class="bo-heading">Naujo darbo skelbimo pildymas</h1>
<?php

echo validation_errors();

echo form_open('?c=backoffice&m=add_job');

?>

    <div class="bo-input">
        <label for="url">Puslapio adresas kitame skelbimo portale <small class="text-danger">*</small></label>
        <input type="url" name="url" id="url" required>
        <div class="success-icon" style="display: none;"></div>
    </div>


    <div class="bo-input">
    <label for="title">Pareiga <small class="text-danger">*</small></label>
    <input type="text" name="title" id="title" required>
        <div class="spinner-icon" style="display: none;"></div>
        <div class="success-icon" style="display: none;"></div>
    </div>

    <?php echo '
    <div class="bo-input">
        <label for="category_id">Darbo sritis <small class="text-danger">*</small></label>
        <select name="category_id" id="category_id" required>';
        foreach($categories as $category){
            echo '<option value="'.$category['category_id'].'">'.$category['category_name'].'</option>';
        }
        echo '</select>
        <div class="spinner-icon_dropdown" style="display: none;"></div>
        <div class="success-icon success-icon_dropdown" style="display: none;"></div>
    </div>';
    ?>

    <div class="bo-input">
        <label for="address">Adresas <small class="text-danger">*</small></label>
        <input type="text" name="address" id="address" placeholder="Darbo vietos adresas" required>
        <div class="spinner-icon" style="display: none;"></div>
        <div class="success-icon" style="display: none;"></div>
    </div>

    <?php echo '
    <div class="bo-input">
        <label for="city_id">Miestas arba savivaldybė <small class="text-danger">*</small></label>
        <select name="city_id" id="city_id" required>';
        foreach($cities as $city){
            echo '<option value="'.$city['city_id'].'">'.$city['city_name'].'</option>';
        }
        echo '</select>
        <div class="spinner-icon_dropdown" style="display: none;"></div>
        <div class="success-icon success-icon_dropdown" style="display: none;"></div>
    </div>';
    ?>

    <div class="bo-input">
        <label for="salary_from">Atlyginimas <small>(EUR, Atskaičius mokesčius)</small></label>
        <div style="display: inline-block;width: 46%;margin-right: 20px;">
            <label style="display: inline-block;">Nuo <small class="text-danger">*</small></label> <input class="form-control salary" type="number" name="salary_from" id="salary_from" required>
        </div>
        <div style="display: inline-block;width:46%">
            <label style="display: inline-block;">Iki</label> <input class="form-control salary" type="number" name="salary_to" id="salary_to">
        </div>
    </div>

    <div class="bo-input">
        <label for="expire">Galioja iki</label>
        <input type="text" name="expire" id="expire">
        <div class="spinner-icon" style="display: none;"></div>
        <div class="success-icon" style="display: none;"></div>
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
        $("#title").parent().removeClass("success");
        $("#city_id").parent().removeClass("success");
        $("#category_id").parent().removeClass("success");
        $("#expire").parent().removeClass("success");
        $("#salary_from").parent().removeClass("success");
        $("#salary_to").parent().removeClass("success");
        $("#address").parent().removeClass("success");
        $("#category_id").siblings(".success-icon").hide();
        $("#city_id").siblings(".success-icon").hide();
        $("#title").siblings(".success-icon").hide();
        $("#expire").siblings(".success-icon").hide();
        $("#category_id option[value=1]").prop('selected', true);
        $("#city_id option[value=1]").prop('selected', true);
        $("#salary_from").val("");
        $("#salary_to").val("");
        $("#address").val("");
        $("#title").val("");
        $("#expire").val("");

        if(url.length > 10) {
            $("#title").next(".spinner-icon").show();
            $("#category_id").next(".spinner-icon_dropdown").show();
            $("#city_id").next(".spinner-icon_dropdown").show();
            $("#expire").next(".spinner-icon").show();

            $.ajax({
                method: "POST",
                url: "localhost/cvm/?c=parser&m=get",
                data: {"<?php echo $this->security->get_csrf_token_name(); ?>": csrfHash, url: url}
            }).done(function (v) {
                $("#title").next(".spinner-icon").hide();
                $("#category_id").next(".spinner-icon_dropdown").hide();
                $("#city_id").next(".spinner-icon_dropdown").hide();
                $("#expire").next(".spinner-icon").hide();
                if (v.website === undefined) {
                    return;
                }

                if (v.csrfHash !== undefined) {
                    csrfHash = v.csrfHash;
                }

                if (v.title !== null) {
                    $("#title").val(v.title);
                    $("#title").parent().addClass("success");
                    $("#title").siblings(".success-icon").show();
                } else {
                    $("#title").val("");
                }

                if (v.category.cvm !== null && v.category !== null) {
                    $("#category_id option[value=" + v.category.cvm.category_id + "]").prop('selected', true);
                    $("#category_id").parent().addClass("success");
                    $("#category_id").siblings(".success-icon").show();
                } else {
                    $("#category_id option[value=1]").prop('selected', true);
                }

                if (v.city_id !== null && v.city !== null) {
                    $("#city_id option[value=" + v.city_id.city_id + "]").prop('selected', true);
                    $("#city_id").parent().addClass("success");
                    $("#city_id").siblings(".success-icon").show();
                } else {
                    $("#city_id option[value=1]").prop('selected', true);
                }

                if (v.salary !== null || v.salary !== "" || v.salary !== 0) {
                    $("#salary_from").val(v.salary);
                    $("#salary_from").parent().addClass("success");
                    $("#salary_from").siblings(".success-icon").show();
                } else {
                    $("#salary_from").val("");

                }

                if (v.expire !== null || v.expire !== "" || v.expire !== 0) {
                    $("#expire").datepicker("setDate", v.expire);
                    $("#expire").parent().addClass("success");
                    $("#expire").siblings(".success-icon").show();
                } else {
                    $("#expire").datepicker("setDate", 30);

                }

            });
        }
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

</body>
</html>