<?php

if(isset($_GET['error'])){
    echo '<div class="notification notification--error">Įvyko klaida: ';
    switch($_GET['error']){
        case "bad_registration":
            echo "blogai užpildyti registracijos laukai.";
            break;
        case "bad_login":
            echo "vartotojas neegzistuoja arba įvestas neteisingas slaptažodis.";
            break;
        case "bad_company_edit":
            echo "blogai užpildyti įmonės informacijos laukai.";
            break;
        case "bad_add_job":
            echo "blogai užpildyti skelbimo laukai.";
            break;
        case "bad_edit_job":
            echo "blogai užpildyti skelbimo laukai.";
            break;
        case "bad_delete_job":
            echo "nepavyko pašalinti skelbimą.";
            break;
        default:
            break;
    }
    echo '</div>';
}

if(isset($_GET['success'])){
    echo '<div class="notification notification--success" onshow="alert()">';
    switch($_GET['success']){
        case "registration":
            echo "Jus sekmingai sukūrėte paskyrą. Dabar galite prisijungti su savo el. paštu ir slaptažodžiu";
            break;
        case "login":
            echo "Jus sekmingai prisijungėte.";
            break;
        default:
            echo "Veiksmas sekmingai atliktas.";
            break;
    }
    echo '</div>';
}