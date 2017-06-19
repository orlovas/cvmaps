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
        .bo-input input{
            position: relative;
            width: 100%;
            font-size: 17px;
            padding: 8px 10px;
            border: 1px solid #cdcdcd;
        }

        .success input{
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

    </style>
</head>
<body>

<section class="bo-window">
    <div class="progress-bar"><div class="progress-bar-fill" style="width: 0"></div></div>
    <h1 class="bo-heading">Naujos įmonės registracija</h1>

    <?php

    echo validation_errors();

    echo form_open('?c=backoffice&m=add_company');

    ?>

    <div class="bo-input">
        <label for="number">Įmonės kodas</label>
        <input type="text" name="number" id="number" maxlength="9" autocomplete="off" required>
        <div class="spinner-icon" style="display: none;"></div>
        <div class="success-icon" style="display: none;"></div>
    </div>


        <div class="bo-input" id="company-input-block">
            <label for="company">Pavadinimas</label>
            <input type="text" name="company" id="company" required>
            <div class="spinner-icon" style="display: none;"></div>
            <div class="success-icon" style="display: none;"></div>
        </div>


        <div class="bo-input" id="salary-input-block">
            <label for="salary">Vidutinis atlyginimas</label>
            <input type="text" name="salary" id="salary" required>
            <div class="spinner-icon" style="display: none;"></div>
            <div class="success-icon" style="display: none;"></div>
        </div>


    <div class="ui input">
        <input type="submit" value="Paskelbti" />
    </div>
    <?php

    echo form_close();
    ?>
</section>


</body>
</html>



<script>
    var csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
    $("#company-input-block").hide();
    $("#salary-input-block").hide();
    $("form").on("input", function(e) {
        e.preventDefault();
        var number = $("#number").val();
        $("#company").val("");
        $("#salary").val("");
        $("#salary").parent().removeClass("success");
        $("#company").parent().removeClass("success");
        $("#number").parent().removeClass("success");
        $("#number").siblings(".success-icon").hide();
        $("#company").siblings(".success-icon").hide();
        $("#salary").siblings(".success-icon").hide();
        $("#number").siblings(".spinner-icon").hide();
        $(".progress-bar-fill").css("width","0%");

        if(number.length == 9){
            $("#number").next(".spinner-icon").show();
            $.ajax({
                method: "POST",
                url: "localhost/cvm/?c=company&m=searchByNumber",
                data: {"<?php echo $this->security->get_csrf_token_name(); ?>":csrfHash, number: number }
            }).done(function(v){
                $("#company-input-block").slideDown();
                $("#salary-input-block").slideDown();

                if(v.csrfHash !== undefined){
                    csrfHash = v.csrfHash;
                }

                if(v.name !== null){
                    $(".progress-bar-fill").css("width","40%");
                    $("#company").val(v.name);
                    $("#company").parent().addClass("success");
                    $("#number").parent().addClass("success");
                    $("#number").siblings(".spinner-icon").hide();
                    $("#number").siblings(".success-icon").show();
                    $("#company").siblings(".success-icon").show();
                }

                if(v.salary !== null){
                    $(".progress-bar-fill").css("width","60%");
                    $("#salary").val(v.salary);
                    $("#salary").parent().addClass("success");
                    $("#salary").siblings(".success-icon").show();
                }
            });
        } else {
            $("#company").val("");
            $("#salary").val("");
            $("#salary").parent().removeClass("success");
            $("#company").parent().removeClass("success");
            $("#number").parent().removeClass("success");
            $("#number").siblings(".success-icon").hide();
            $("#company").siblings(".success-icon").hide();
            $("#salary").siblings(".success-icon").hide();
            $("#number").siblings(".spinner-icon").hide();
        }
    });
</script>

