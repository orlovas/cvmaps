<div class="form-in-popup">
<?php

echo validation_errors();

echo form_open_multipart('?c=backoffice&m=edit_company&id='.$company->id);

?>

    <div class="form-column" style="width:95px">
        <input type="hidden" name="logo" id="logo" value="<?php echo $company->logo ?>">
        <?php echo '<img src="static/images/l/'.$company->logo.'">'; ?>

        <div class="form-row">
            <div id="enable_upload"><img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDU2MSA1NjEiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDU2MSA1NjE7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8ZyBpZD0ic3luYyI+CgkJPHBhdGggZD0iTTI4MC41LDc2LjVWMGwtMTAyLDEwMmwxMDIsMTAydi03Ni41Yzg0LjE1LDAsMTUzLDY4Ljg1LDE1MywxNTNjMCwyNS41LTcuNjUsNTEtMTcuODUsNzEuNGwzOC4yNSwzOC4yNSAgICBDNDcxLjc1LDM1Nyw0ODQuNSwzMjEuMyw0ODQuNSwyODAuNUM0ODQuNSwxNjguMywzOTIuNyw3Ni41LDI4MC41LDc2LjV6IE0yODAuNSw0MzMuNWMtODQuMTUsMC0xNTMtNjguODUtMTUzLTE1MyAgICBjMC0yNS41LDcuNjUtNTEsMTcuODUtNzEuNGwtMzguMjUtMzguMjVDODkuMjUsMjA0LDc2LjUsMjM5LjcsNzYuNSwyODAuNWMwLDExMi4yLDkxLjgsMjA0LDIwNCwyMDRWNTYxbDEwMi0xMDJsLTEwMi0xMDJWNDMzLjV6IiBmaWxsPSIjOTk5OTk5Ii8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" /> <small>Keisti</small></div>
        </div>
        <div id="upload"></div>
    </div>
    <div class="form-column">
        <div class="form-row">
            <label for="name">Pavadinimas <small class="text-danger">*</small></label>
            <input type="text" name="name" id="name" value='<?php echo $company->name ?>' required>
        </div>

        <div class="form-row">
            <label for="average_salary">Vidutinis atlyginimas <small class="text-danger">(EUR, *)</small></label>
            <input type="text" name="average_salary" id="average_salary" value="<?php echo $company->average_salary ?>" required>
        </div>

        <div class="form-row">
            <label for="high_credit_rating">Stipriausi Lietuvoje <small class="text-danger">*</small></label>
            <select name="high_credit_rating" id="high_credit_rating" required>
                <option value="1" <?php echo ($company->high_credit_rating === "1" ? "selected" : "") ?>>Taip</option>
                <option value="0" <?php echo ($company->high_credit_rating === "0" ? "selected" : "") ?>>Ne</option>
            </select>
        </div>
        <div class="form-row">
        <small>* - privalomas laukas</small>
        </div>
        <div class="form-row">
            <input type="submit" value="Išsaugoti" class="btn btn--small btn--green" />
        </div>
    </div>

<?php

echo form_close();
?>
</div>

<script>
    $("#enable_upload").on("click",function(){
        $("#logo").remove();
        $("#upload").html('<div class="form-row"><input type="file" name="logo" id="logo" style="width:80px"></div>');
    });

</script>