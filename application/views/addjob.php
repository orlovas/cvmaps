<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php

echo validation_errors();

echo form_open('backoffice/add_job');

?>

<label for="title">Pareiga <small class="text-danger">(Privalomas laukas)</small></label><br />
<input type="text" name="title" id="title" required><br />

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
<input type="text" name="address" id="address" required><br />

<label for="description">Darbo aprašymas <small class="text-danger">(Privalomas laukas)</small></label><br />
<textarea name="description" id="description" cols="30" rows="10" required></textarea><br />

<label for="requirements">Reikalavimai</label><br />
<textarea name="requirements" id="requirements" cols="30" rows="10"></textarea><br />

<label for="offer">Įmonė siūlo</label><br />
<textarea name="offer" id="offer" cols="30" rows="10"></textarea><br />

<label for="salary_from">Atlyginimas <small>(Atskaičius mokesčius)</small></label><br />
Nuo <input class="form-control salary" type="number" name="salary_from" id="salary_from">
Iki <input class="form-control salary" type="number" name="salary_to" id="salary_to"><br />

<?php echo '
<div class="form-group">
<label for="worker_type_id">Darbuotojo tipas <small class="text-danger">(Privalomas laukas)</small></label>
<select class="form-control" name="worker_type_id" id="worker_type_id" required>';
foreach($worker_types as $worker_type){
echo '<option value="'.$worker_type['id'].'">'.$worker_type['type'].'</option>';
}
echo '</select>
</div>';
?>

<label for="work_time_id">Darbo laikas</label>
<select name="work_time_id" id="work_time_id" required>
    <option value="1">Pilna darbo diena</option>
    <option value="2">Nepilna darbo diena</option>
</select>

<div class="special">
    <div class="checkbox">
        <label><input type="checkbox" name="no_exp" value="1">
        Be patirties</label>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" name="is_shift" value="1">
        Darbas pamainomis</label>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" name="student" value="1">
        Darbas tinka studentams</label>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" name="school" value="1">
        Darbas tinka mokiniams / paaugliams</label>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" name="disabled" value="1">
        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMS4xLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDU3My40NDUgNTczLjQ0NSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTczLjQ0NSA1NzMuNDQ1OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTE3NC43MjcsNzkuNTZjLTMuMDYtNi45MzYtNC41OS0xNC40ODQtNC41OS0yMi42NDRjMC03Ljc1MiwxLjUzLTE1LjA5Niw0LjU5LTIyLjAzMiAgICBzNy4xNC0xMi45NTQsMTIuMjQtMTguMDU0YzUuMS01LjEsMTEuMTE4LTkuMTgsMTguMDU0LTEyLjI0UzIxOS41MDUsMCwyMjcuNjY1LDBjNy43NTIsMCwxNS4wOTYsMS41MywyMi4wMzIsNC41OSAgICBzMTMuMDU2LDcuMTQsMTguMzYsMTIuMjRzOS4zODQsMTEuMTE4LDEyLjI0LDE4LjA1NHM0LjI4NCwxNC4yOCw0LjI4NCwyMi4wMzJjMCwxNS45MTItNS41MDgsMjkuNDc4LTE2LjUyNCw0MC42OTggICAgcy0yNC40OCwxNi44My00MC4zOTIsMTYuODNjLTguMTYsMC0xNS43MDgtMS41My0yMi42NDQtNC41OWMtNi45MzYtMy4wNi0xMi45NTQtNy4xNC0xOC4wNTQtMTIuMjQgICAgQzE4MS44NjcsOTIuNTE0LDE3Ny43ODcsODYuNDk2LDE3NC43MjcsNzkuNTZ6IE0zOTcuMTg5LDM5Mi45MDRjLTYuOTM2LTEuNjMzLTEzLjE1Ny0wLjYxMS0xOC42NjUsMy4wNjEgICAgcy05LjA3OCw4Ljc3MS0xMC43MSwxNS4zMDFjLTEuMjI0LDYuMTE5LTMuMDYsMTIuMDM1LTUuNTA4LDE3Ljc0OGMtNS4zMDQsMTQuMjc5LTEyLjc1LDI3LjMzNi0yMi4zMzgsMzkuMTY4ICAgIHMtMjAuNjA1LDIyLjAzMy0zMy4wNDgsMzAuNmMtMTIuNDQ0LDguNTY4LTI2LjExMiwxNS4xOTktNDEuMDA0LDE5Ljg5M2MtMTQuODkyLDQuNjg5LTMwLjI5NCw3LjAzNy00Ni4yMDYsNy4wMzcgICAgYy0yMC44MDgsMC00MC4zOTItMy44NzUtNTguNzUyLTExLjYyN2MtMTguMzYtNy43NTQtMzQuMzc0LTE4LjE1Ni00OC4wNDItMzEuMjEzYy0xMy42NjgtMTMuMDU5LTI0LjQ4LTI4LjQ1OS0zMi40MzYtNDYuMjA1ICAgIGMtNy45NTYtMTcuNzQ4LTExLjkzNC0zNi44MjQtMTEuOTM0LTU3LjIyNWMwLTE1LjA5NiwyLjM0Ni0yOS42ODIsNy4wMzgtNDMuNzU4czExLjMyMi0yNy4xMzMsMTkuODktMzkuMTY4ICAgIGM4LjU2OC0xMi4wMzcsMTguNzY4LTIyLjY0NSwzMC42LTMxLjgyNWMxMS44MzItOS4xOCwyNS4wOTItMTYuNjI2LDM5Ljc4LTIyLjMzOGM2LjUyOC0yLjA0LDExLjIyLTYuMTIsMTQuMDc2LTEyLjI0ICAgIHMzLjA2LTEyLjI0LDAuNjEyLTE4LjM2cy02LjgzNC0xMC41MDYtMTMuMTU4LTEzLjE1OGMtNi4zMjQtMi42NTItMTIuNzUtMi45NTgtMTkuMjc4LTAuOTE4ICAgIGMtMTkuMTc2LDcuMzQ0LTM2LjcyLDE3LjEzNi01Mi42MzIsMjkuMzc2Yy0xNS45MTIsMTIuMjQtMjkuNTgsMjYuMzE2LTQxLjAwNCw0Mi4yMjhzLTIwLjE5NiwzMy4yNS0yNi4zMTYsNTIuMDIgICAgYy02LjEyLDE4Ljc2Ny05LjE4LDM4LjE0Ni05LjE4LDU4LjE0YzAsMjYuOTMsNS4zMDQsNTIuMTIxLDE1LjkxMiw3NS41ODJjMTAuNjA4LDIzLjQ1OSwyNC45OSw0My45NjEsNDMuMTQ2LDYxLjUwNiAgICBjMTguMTU2LDE3LjU0MywzOS4zNzIsMzEuNDE2LDYzLjY0OCw0MS42MTVjMjQuMjc2LDEwLjIwMSw1MC4yODYsMTUuMzAxLDc4LjAzLDE1LjMwMWMyMS4yMTYsMCw0MS42MTYtMy4wNjEsNjEuMi05LjE4ICAgIGMxOS41ODMtNi4xMjEsMzcuNzM5LTE0Ljc5MSw1NC40NjgtMjYuMDFjMTYuNzI3LTExLjIxOSwzMS40MTYtMjQuNzg3LDQ0LjA2Mi00MC42OTljMTIuNjQ4LTE1LjkxMiwyMi40NC0zMy40NTUsMjkuMzc2LTUyLjYzMSAgICBjMi44NTctNi45MzksNS4zMDQtMTQuNjg5LDcuMzQ0LTIzLjI1NmMxLjYzNC02LjkzOCwwLjYxMi0xMy4wNTktMy4wNi0xOC4zNjFDNDA5LjQyOCwzOTguMDA4LDQwNC4xMjYsMzk0LjUzNywzOTcuMTg5LDM5Mi45MDR6ICAgICBNNTUxLjQxMiw0ODYuNTQxbC05My42MzYtMTYyLjc5M2MtMy4yNjQtNi45MzgtOC4xNjEtMTIuNDQ1LTE0LjY4OC0xNi41MjVjLTYuNTI4LTQuMDgtMTMuODcyLTYuMTE5LTIyLjAzMi02LjExOWgtMTA0LjA0ICAgIGwtMTIuMjQtNTkuOTc2aDgxLjM5OGM0Ljg5NSwwLjQwOCw5LjU4OC0wLjQwOCwxNC4wNzYtMi40NDhjNC40ODUtMi4wNCw3Ljk1My01LjMwNCwxMC40MDEtOS43OTIgICAgYzMuMjY3LTUuNzEyLDMuOTc5LTExLjgzMiwyLjE0NS0xOC4zNmMtMS44MzYtNi41MjgtNS44MTUtMTEuNDI0LTExLjkzNy0xNC42ODhjLTMuMjY0LTIuMDQtNi45MzYtMy4wNi0xMS4wMTYtMy4wNkgyOTQuMzcgICAgbC01LjUwNy0yOC4xNTJjMC0wLjgxNi0wLjEwMy0xLjMyNi0wLjMwNy0xLjUzcy0wLjMwNy0wLjcxNC0wLjMwNy0xLjUzbC0wLjYxMS0xLjgzNmMtMi44NTYtOS4zODQtOC4yNjItMTcuMTM2LTE2LjIxOC0yMy4yNTYgICAgcy0xNy4yMzgtOS4xOC0yNy44NDYtOS4xOGMtMTMuMDU2LDAtMjQuMDcyLDQuMzg2LTMzLjA0OCwxMy4xNThjLTguOTc2LDguNzcyLTEzLjQ2NCwxOS4yNzgtMTMuNDY0LDMxLjUxOCAgICBjMCwyLjA0LDAuMjA0LDMuNjcyLDAuNjEyLDQuODk2aC0wLjYxMmwzMy42NiwxNjIuMThjMS42MzIsOS4zODUsNi4xMiwxNy4wMzUsMTMuNDY0LDIyLjk1MSAgICBjNy4zNDQsNS45MTYsMTUuOTEyLDguODczLDI1LjcwNCw4Ljg3M2MyLjg1NiwwLDUuMS0wLjIwMyw2LjczMi0wLjYxMWgxMzguOTI1bDgwLjc4NCwxNDAuNzYgICAgYzIuNDQ2LDUuMzAzLDYuMTE4LDkuNTg4LDExLjAxNiwxMi44NTRjNC44OTYsMy4yNjQsMTAuNjA5LDQuODk2LDE3LjEzNiw0Ljg5NmM4LjE2LDAsMTUuMTk4LTIuODU0LDIxLjExNC04LjU2OCAgICBjNS45MTYtNS43MTMsOC44NzQtMTIuNjQ2LDguODc0LTIwLjgwOUM1NTQuNDc0LDQ5NC45MDIsNTUzLjQ1Myw0OTAuNjIxLDU1MS40MTIsNDg2LjU0MXoiIGZpbGw9IiMwMDAwMDAiLz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" /> Darbas tinka neįgaliesiems</label>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" name="pensioner" value="1">
        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDQ3OC41OTYgNDc4LjU5NiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDc4LjU5NiA0NzguNTk2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTExMy4xMjYsMTA4Ljg2N2MxOC4wMjEsMCwzNS4xODktOS4xOCw0NS4yMy0yNC4xNGMxMC4xODQtMTUuMTczLDEyLjAyMS0zNS4wMTEsNC43OTgtNTEuOCAgICBjLTcuMDM3LTE2LjM1Ni0yMi4wNzQtMjguNTMxLTM5LjU1OS0zMS45MzhDMTA1LjcxMi0yLjQ5NSw4Ni44OTMsMy4zNiw3NC4xNjcsMTYuMzk0QzYxLjQzMywyOS40MzcsNTYuMDMxLDQ4LjQyNSw1OS45NjUsNjYuMjI3ICAgIGMzLjg0MiwxNy4zODIsMTYuMzc2LDMyLjExMywzMi44OTEsMzguNzQyQzk5LjI4NywxMDcuNTUsMTA2LjE5OCwxMDguODY3LDExMy4xMjYsMTA4Ljg2N3oiIGZpbGw9IiMwMDAwMDAiLz4KCQk8Zz4KCQkJPHBhdGggZD0iTTE3NS44MzMsMjQzLjU4QzE3Ny4wNDQsMjQwLjIyMiwxNzUuNzUsMjQyLjc4MiwxNzUuODMzLDI0My41OEwxNzUuODMzLDI0My41OHoiIGZpbGw9IiMwMDAwMDAiLz4KCQkJPHBhdGggZD0iTTIxNC4yNywyMjYuNTI4YzAuODc2LTguMTMxLDEuMDcyLTE2LjI5MiwxLjE5NS0yNC40NjJjLTAuMjMtNy4yOTMtMC4zNDEtMTQuNjgxLTEuNDk5LTIxLjkwMiAgICAgYy0xLjQwOS0xMS4yNDgtNC4wNjEtMjIuNTA0LTguMzE4LTMzLjAyNWwtMS42OTctNC4xNzljLTEuOTczLTQuMTQxLTQuMDUzLTguMTYtNi4yOTMtMTIuMTYyICAgICBjLTQuNzM0LTcuNzA0LTEwLjIyMi0xNC43ODYtMTYuNjkyLTIxLjExM2MtMi4xMjgtMS45NTktNC4xOTYtMy45MS02LjM3OS01LjYxNWMtMi4xMTgtMS43ODktNC4zMzgtMy4zMjItNi40NTctNC44NTUgICAgIGMtMi43NDktMS43OTEtNS40OTctMy41NjMtOC4zNzktNS4xMzZjLTE0LjMyMSwxNS42NzEtMzYuMDUsMjMuMTY0LTU2Ljk4OCwxOS41ODNjLTEzLjc3MS0yLjM1NS0yNi41MjUtOS40MTctMzUuOTA4LTE5Ljc1OCAgICAgYy0wLjc2NywwLjQxNC0xLjUzOCwwLjg0LTIuMzEsMS4zMDNjLTIuMTIzLDEuMTc4LTQuMjI3LDIuNTg5LTYuNDEzLDQuMDExYy0yLjEyMSwxLjUzMy00LjM0LDMuMDY0LTYuNDYxLDQuODUyICAgICBjLTIuMTg1LDEuNzA0LTQuMjUzLDMuNjU0LTYuMzgzLDUuNjEyYy02LjQ3OCw2LjMyNS0xMS45NzIsMTMuNDA4LTE2LjcwNiwyMS4xMTVjLTIuMjQzLDQuMDAxLTQuMzIsOC4wMjMtNi4yOTUsMTIuMTY0ICAgICBsLTEuNjk3LDQuMThjLTQuMjU3LDEwLjUyNC02LjkwMiwyMS43NzktOC4zMTQsMzMuMDI4Yy0xLjE1OCw3LjIyMS0xLjI1NywxNC42MDktMS40OTQsMjEuOTAxICAgICBjMC4xMDMsNi40MzIsMC4zMjIsMTIuODIxLDAuNzMyLDE5LjI0MWMwLjI5NSw0LjYyNSwwLjkwNCw5LjIzMiwxLjQxLDEzLjgzOWMwLjk1Myw4LjQzMSw4LjU3MiwxNC44ODEsMTcuMDQ5LDE0LjM3NCAgICAgYzguNDc0LTAuNTA5LDE1LjI1NS03Ljc5MiwxNS4xOC0xNi4yNzVsLTAuMDA2LTAuNzc1Yy0wLjAzMy0zLjg0OS0wLjE4Ny03LjcxNS0wLjA0NS0xMS41NjEgICAgIGMwLjIwOC01LjY3MiwwLjU1Mi0xMS4yOTksMS4wMjMtMTYuOTU1YzAuNzctNS43OCwxLjM4NS0xMS43MDcsMi45NDQtMTcuMzQyYzEuODA5LTcuOTQ5LDQuNDUyLTE1LjkwMSw4LjMwNy0yMy4xMDRsMS4zOS0yLjY0NSAgICAgbDEuNTQyLTIuNDkxYzAuOTYxLTEuNzA2LDIuMTUzLTMuMTgxLDMuMjE0LTQuNzM0YzAuMDg5LTAuMTE0LDAuMTc5LTAuMjIzLDAuMjY4LTAuMzM3bC0xMi41NzQsODcuODc4ICAgICBjLTIuMTA0LDE1LjI1MSwxLjM1OSwzMC45NjMsOS45Myw0My43NzdsOC4zMzEsMTc1LjY3M2MwLjQ2OCw5LjI1LDcuOTAxLDE3LjA1NiwxNy4xNCwxNy44ODcgICAgIGM5LjEwMiwwLjgxOCwxNy43MjEtNS4yNTksMTkuOTcxLTE0LjEyNGMwLjMxNi0xLjI0NywwLjUwNC0yLjUyNiwwLjU2Mi0zLjgxMmw1Ljk4Ny0xMzMuNTJsNi4yMjEsMTMzLjU1MyAgICAgYzAuNDYxLDkuMjUsNy44ODcsMTcuMDYyLDE3LjEyNSwxNy45YzkuMTAxLDAuODI2LDE3LjcyNy01LjI0MiwxOS45ODItMTQuMTA2YzAuMzE3LTEuMjQ3LDAuNTA2LTIuNTI2LDAuNTY0LTMuODEyICAgICBsNy45ODQtMTc0Ljc4OWM1LjYwOS04LjEwNCw5LjMyMi0xNy41MywxMC42MzctMjcuNThjMi41NSwzLjYyNiw3LjY1NCw0LjY0MSwxMS4zODcsMi4yNDRjMS42NzgtMS4wNzgsMi45NDEtMi43NSwzLjUxMi00LjY2MyAgICAgYzAuMzA5LTEuMDM4LDAuMzA3LTIuMDU2LDAuMzY5LTMuMTI0YzAuMDgyLTEuNCwwLjQwNS0yLjc4NCwwLjk1NC00LjA3NWM2LjU1LDIuMzEzLDEzLjk3LDAuMTA5LDE4LjIwMi01LjM5OCAgICAgYzMuMjcyLDIuMjEzLDUuNDI4LDUuOTU4LDUuNDI4LDEwLjE5OHYyMTEuMDZjMCw0LjU3MiwzLjgxNiw4LjM4OSw4LjM4OCw4LjM4OWM0LjU3MiwwLDguMzg4LTMuODE2LDguMzg4LTguMzg5VjI1My40MSAgICAgQzIzMi4yNzUsMjQxLjI5NSwyMjQuODI3LDIzMC44OTIsMjE0LjI3LDIyNi41Mjh6IE0xODEuMTQ0LDIzNC40OTFjLTIuMjgzLDIuNjU4LTQuMDk5LDUuNzI5LTUuMzExLDkuMDg5ICAgICBjLTAuMDgyLTAuNzk4LTAuMTY5LTEuNTk3LTAuMjgzLTIuMzk3bC0xMi40NDktODYuOTk5YzAuOTU3LDEuMzU4LDEuOTgxLDIuNjg0LDIuODI5LDQuMTg4bDEuNTQyLDIuNDkybDEuMzkyLDIuNjQ2ICAgICBjMy44NTQsNy4yMDQsNi41MDMsMTUuMTU1LDguMzEyLDIzLjEwNWMxLjU2LDUuNjM3LDIuMTcsMTEuNTYxLDIuOTQ5LDE3LjM0MWMwLjQ2OSw1LjY1NiwwLjgxNCwxMS4yODIsMS4wMjMsMTYuOTUzICAgICBjMC4xNDMsMy44NDYtMC4wMTEsNy43MTItMC4wNDMsMTEuNTYxbC0wLjAwOSwwLjg1MUMxODEuMDkzLDIzMy43MTYsMTgxLjExNywyMzQuMTAzLDE4MS4xNDQsMjM0LjQ5MXoiIGZpbGw9IiMwMDAwMDAiLz4KCQk8L2c+CgkJPHBhdGggZD0iTTM0NC4wNDksMTA4Ljg2N2MxOC4wMjEsMCwzNS4xODktOS4xOCw0NS4yMy0yNC4xNGMxMC4xODQtMTUuMTczLDEyLjAyMS0zNS4wMTEsNC43OTctNTEuOCAgICBjLTcuMDM3LTE2LjM1Ni0yMi4wNzQtMjguNTMxLTM5LjU1OS0zMS45MzhjLTE3Ljg4My0zLjQ4NC0zNi43MDMsMi4zNzEtNDkuNDI4LDE1LjQwNSAgICBjLTEyLjczNiwxMy4wNDMtMTguMTM3LDMyLjAzMS0xNC4yMDEsNDkuODMzYzMuODQyLDE3LjM4MiwxNi4zNzUsMzIuMTEzLDMyLjg5MSwzOC43NDIgICAgQzMzMC4yMDksMTA3LjU1LDMzNy4xMjEsMTA4Ljg2NywzNDQuMDQ5LDEwOC44Njd6IiBmaWxsPSIjMDAwMDAwIi8+CgkJPGc+CgkJCTxwYXRoIGQ9Ik00NDUuMjYsMjI1Ljg5NmMwLjg1NS03LjkxNiwxLjAwOC0xNS44NzYsMS4xMjctMjMuODNjLTAuMjI5LTcuMjkzLTAuMzQyLTE0LjY4MS0xLjQ5OC0yMS45MDIgICAgIGMtMS40MS0xMS4yNDctNC4wNjItMjIuNTA0LTguMzE4LTMzLjAyNWwtMS42OTctNC4xNzljLTEuOTc3LTQuMTQtNC4wNTEtOC4xNi02LjI5My0xMi4xNjIgICAgIGMtNC43MzQtNy43MDMtMTAuMjIzLTE0Ljc4Ni0xNi42OTEtMjEuMTEzYy0yLjEyOS0xLjk1OS00LjE5Ny0zLjkxLTYuMzc5LTUuNjE1Yy0yLjExOS0xLjc4OS00LjMzOC0zLjMyMi02LjQ1Ny00Ljg1NSAgICAgYy0yLjc1LTEuNzkxLTUuNDk4LTMuNTYzLTguMzc5LTUuMTM2Yy0xNC4zMjIsMTUuNjcxLTM2LjA1MSwyMy4xNjQtNTYuOTg4LDE5LjU4M2MtMTMuNzcxLTIuMzU1LTI2LjUyNS05LjQxNy0zNS45MDgtMTkuNzU4ICAgICBjLTAuNzY4LDAuNDE0LTEuNTM3LDAuODQtMi4zMTEsMS4zMDNjLTIuMTIzLDEuMTc4LTQuMjI3LDIuNTg5LTYuNDEyLDQuMDExYy0yLjEyMSwxLjUzMy00LjM0LDMuMDY0LTYuNDYxLDQuODUyICAgICBjLTIuMTg2LDEuNzA0LTQuMjU0LDMuNjU0LTYuMzgzLDUuNjEyYy02LjQ4LDYuMzIxLTExLjk3MywxMy40MDktMTYuNzA3LDIxLjExNWMtMi4yNDQsNC4wMDEtNC4zMTgsOC4wMjQtNi4yOTUsMTIuMTY0ICAgICBsLTEuNjk3LDQuMThjLTQuMjU2LDEwLjUyNC02LjkwNCwyMS43NzktOC4zMTMsMzMuMDI4Yy0xLjE1Nyw3LjIyMS0xLjI1NywxNC42MDktMS40OTMsMjEuOTAxICAgICBjMC4xMDMsNi40MzIsMC4zMjIsMTIuODIsMC43MywxOS4yNDFjMC4yOTUsNC42MjUsMC45MDYsOS4yMzMsMS40MTEsMTMuODM5YzAuOTUyLDguNDMxLDguNTcxLDE0Ljg4MSwxNy4wNDgsMTQuMzc0ICAgICBjOC40NzUtMC41MDksMTUuMjU2LTcuNzkyLDE1LjE4Mi0xNi4yNzVsLTAuMDA4LTAuNzc1Yy0wLjAzMS0zLjg0OS0wLjE4OC03LjcxNS0wLjA0NS0xMS41NjEgICAgIGMwLjIwOS01LjY3MiwwLjU1NS0xMS4yOTksMS4wMjMtMTYuOTU1YzAuNzctNS43OCwxLjM4Ni0xMS43MDcsMi45NDUtMTcuMzQyYzEuODA5LTcuOTQ5LDQuNDUxLTE1LjkwMSw4LjMwNy0yMy4xMDRsMS4zODktMi42NDUgICAgIGwxLjU0My0yLjQ5MWMwLjk2MS0xLjcwNiwyLjE1Mi0zLjE4MSwzLjIxMy00LjczNGMwLjA5LTAuMTE0LDAuMTgtMC4yMjMsMC4yNy0wLjMzN2wtMTIuNTczLDg3Ljg4MSAgICAgYy0wLjMzNiwyLjQzNS0wLjUzLDQuOTY0LTAuNTg5LDcuNTI0Yy02LjczOSwyMS4wOTQtMTIuMzg3LDQyLjU1Mi0xNi42NDksNjQuMjgzYy00Ljg2OCwyNC44MTQtNy45NTcsNTAuMDQ2LTguMjc0LDc1LjM1ICAgICBjLTAuMDM5LDMuMjM3LTAuMTExLDYuMjgyLDEuOTUxLDguOTg0YzEuODEsMi4zNzEsNC42ODQsMy43OTMsNy42NjYsMy43OTNoMzEuOTg0bDIuODIyLDU5LjUxNSAgICAgYzAuNDY3LDkuMjUsNy45MDIsMTcuMDU2LDE3LjE0MSwxNy44ODdjOS4xLDAuODE4LDE3LjcyMS01LjI1OSwxOS45NzEtMTQuMTI0YzAuMzE0LTEuMjQ3LDAuNTA0LTIuNTI2LDAuNTYxLTMuODEybDIuNjY4LTU5LjQ2NiAgICAgaDYuNzdsMi43NzEsNTkuNDk5YzAuNDYxLDkuMjUsNy44ODcsMTcuMDYyLDE3LjEyNSwxNy45MDFjOS4xMDIsMC44MjUsMTcuNzI3LTUuMjQzLDE5Ljk4Mi0xNC4xMDcgICAgIGMwLjMxNi0xLjI0NywwLjUwNi0yLjUyNiwwLjU2Mi0zLjgxMmwyLjcxOS01OS40OGgzMS42NDhjMi42NTIsMCw1LjI0Mi0xLjEyNSw3LjA1MS0zLjA2NWMxLjkyOC0yLjA2MywyLjYwNC00LjYyNSwyLjU4OC03LjM4NSAgICAgYy0wLjAzNy02LjQ2Ny0wLjI2Mi0xMi45MzQtMC42NDgtMTkuMzljLTAuNzY4LTEyLjcxNy0yLjE3OC0yNS4zOTItNC4xLTM3Ljk4NWMtMy41MzMtMjMuMTMzLTguNzc5LTQ1Ljk5NS0xNS4yODktNjguNDY3ICAgICBjLTAuNjY2LTIuMzAxLTEuMzQ4LTQuNTk4LTIuMDQxLTYuODkxYzIuMDUzLDQuMTQ2LDcuMjU2LDUuODc1LDExLjM3MSwzLjc0M2MxLjg0Ni0wLjk1NywzLjI5OS0yLjYwMiw0LjAxOC00LjU1MyAgICAgYzAuMzkzLTEuMDY1LDAuNDg4LTIuMTMxLDAuNTItMy4yNTZjMC4wNDUtMS40OTUsMC4zNjMtMi45NzksMC45MzktNC4zNThjNS44NTksMC4yOTcsMTEuMjE5LTIuNTgzLDE0LjI4NS03LjIyMSAgICAgYzUuMzczLDEuMzE5LDkuMzc1LDYuMTYzLDkuMzc1LDExLjkzN3YyMTEuMDZjMCw0LjU3MSwzLjgxNiw4LjM4OCw4LjM4OSw4LjM4OHM4LjM4OS0zLjgxNSw4LjM4OS04LjM4OHYtMjExLjA2ICAgICBDNDY3LjgxMSwyNDAuNDM4LDQ1OC4xNiwyMjguODY3LDQ0NS4yNiwyMjUuODk2eiBNNDEwLjUxLDI0Ny4yMjVjLTAuNjM5LDIuNTYzLTEuMTI1LDUuNDc4LTAuNzcxLDguMTIgICAgIGMtMC45MTItMi45NzEtMS44MjYtNS44NjctMi43MzItOC42NzVjLTAuMTAyLTEuODE5LTAuMjctMy42NDctMC41MzMtNS40ODZsLTEyLjQ0OS04Ni45OTljMC45NTcsMS4zNTgsMS45ODIsMi42ODQsMi44Myw0LjE4OCAgICAgbDEuNTQxLDIuNDkybDEuMzkzLDIuNjQ2YzMuODU0LDcuMjA1LDYuNTAyLDE1LjE1NSw4LjMxMSwyMy4xMDVjMS41NjQsNS42MzYsMi4xNzQsMTEuNTYxLDIuOTQ5LDE3LjM0MSAgICAgYzAuNDczLDUuNjU1LDAuODE2LDExLjI4MiwxLjAyNSwxNi45NTNjMC4xNDEsMy44NDYtMC4wMTQsNy43MTItMC4wNDUsMTEuNTYxbC0wLjAwOCwwLjg1MWMtMC4wMTQsMi4zNCwwLjQ5Niw0LjU4LDEuNDAyLDYuNjE4ICAgICBDNDEyLjEyNywyNDIuMjIzLDQxMS4xNDUsMjQ0LjY3OSw0MTAuNTEsMjQ3LjIyNXoiIGZpbGw9IiMwMDAwMDAiLz4KCQkJPHBhdGggZD0iTTQwOS43MzYsMjU1LjM0M0M0MDkuNjg4LDI1NC45NzgsNDA4LjgyNCwyNTIuMzczLDQwOS43MzYsMjU1LjM0M0w0MDkuNzM2LDI1NS4zNDN6IiBmaWxsPSIjMDAwMDAwIi8+CgkJPC9nPgoJPC9nPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" /> Darbas tinka pensininkams
        </label>
    </div>
</div>

<label for="company">Įmonė</label><br />
<input type="text" id="company" name="company" required><br />

<label for="phone">Tel. numeris <small class="text-danger">(Privalomas laukas)</small></label><br />
<input type="tel" name="phone" id="phone" required><br />

<label for="email">El. paštas</label><br />
<input type="email" name="email" id="email"><br />

<label for="website">Tinklapis</label><br />
<input type="url" name="website" id="website"><br />

<input type="submit" value="Submit" />
<?php

echo form_close();
?>
</body>
</html>
