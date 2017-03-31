<div class="form-in-popup">
    <span class="auth-title">Prisijungimas</span>
    <form enctype="multipart/form-data" action="?c=user&m=login" method="post" accept-charset="utf-8">
        <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <div class="form-row">
            <label for="email">El. paštas</label>
            <input type="email" name="email" value="">
        </div>

        <div class="form-row">
            <label for="password">Slaptažodis</label>
            <input type="password" name="password" value="">
        </div>

        <div class="form-row">
            <input type="submit" value="Prisijungti" class="btn btn--small btn--green"/>
        </div>
    </form>
    <hr class="tiny-hr">
    <span class="auth-title" id="toggle-reg" style="color: #0288d1; cursor: pointer">Registracija</span>
    <form action="?c=user&m=register" id="registration" method="post" accept-charset="utf-8">
        <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <div class="form-row">
            <label for="email">El. paštas <small class="text-danger">*</small></label>
            <input type="email" name="email">
        </div>

        <div class="form-row">
            <label for="password">Slaptažodis <small class="text-danger">*</small></label>
            <input type="password" name="password">
        </div>

        <div class="form-row">
            <label for="password">Slaptažodis (dar kartą) <small class="text-danger">*</small></label>
            <input type="password" name="password_repeat">
        </div>

        <div class="form-row">
            <label for="name">Įmonės pavadinimas <small class="text-danger">*</small></label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-row">
            <label for="average_salary">Įmonės vidutinis atlyginimas <small class="text-danger">*</small></label>
            <input type="text" name="average_salary" id="average_salary" required>
        </div>

        <div class="form-row">
            <label for="high_credit_rating">Įmonė yra "Stipriausi Lietuvoje" sąraše <small class="text-danger">*</small></label>
            <select name="high_credit_rating" id="high_credit_rating" required>
                <option value="1">Taip</option>
                <option value="0" selected>Ne</option>
            </select>
        </div>

        <div class="form-row">
            <label for="logo">Logotipas</label>
            <span id="enable_upload" style="color: #0288d1; cursor: pointer; font-size: 0.8em">Užkrauti logotipą</span>
            <div id="upload"></div>
            <input type="hidden" name="logo" id="logo" value="default.png">
        </div>

        <div class="form-row">
            <small>* - privalomas laukas</small>
        </div>

        <div class="form-row">
            <input type="submit" value="Registruotis" class="btn btn--small btn--green" />
        </div>
    </form>

</div>

<script>
    $("#enable_upload").on("click",function(){
        $("#logo").remove();
        $("#upload").html('<input type="file" name="logo" id="logo">');
    });

    $("#registration").hide();

    $("#toggle-reg").on("click", function(){
       $("#registration").slideDown();
    });
</script>