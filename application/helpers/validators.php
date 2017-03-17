<?php

require_once './lib/htmlpurifier-4.8.0/HTMLPurifier.auto.php';

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateToken($length = 16){
    $bytes = openssl_random_pseudo_bytes($length, $cstrong);
    return bin2hex($bytes);
}

function correctInput($input){
    if(!isset($input) || empty($input) || $input == ""){
        return false;
    }

    if(is_null($input) || $input == "null" || $input == "NULL"){
        return false;
    }

    return true;
}

function correctCheckbox($checkbox){
    if(!isset($checkbox)){
        return false;
    }

    return true;
}

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

function isValidPassword($pass){
    if(mb_strlen($pass) < 8){
        return false;
    } else {
        return true;
    }
}

function isValidInteger($int){
    if(filter_var($int, FILTER_VALIDATE_INT)){
        return $int;
    } else {
        return false;
    }
}

function isValidFloat($float){
    if(filter_var($float, FILTER_VALIDATE_FLOAT)){
        return true;
    } else {
        return false;
    }
}

function isValidWebsite($website){
    if(filter_var($website, FILTER_VALIDATE_URL)){
        return true;
    } else {
        return false;
    }
}

function isValidAddress($address){
    return true;
}

function isValidPhone($phone) {
    $phone = preg_replace('/\s+/', '', $phone);

    if(strlen($phone)<12){
        return false;
    }

    if(substr($phone, 0, 1) != "+"){
        return false;
    }

    return true;
}


function emailValid($email) {
    // _^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,}))\.?)(?::\d{2,5})?(?:[/?#]\S*)?$_iuS

    return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email);
}

function isValidEmail($email) {
    filter_var($email, FILTER_SANITIZE_EMAIL);

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    } else {
        echo "Bad email";
        return false;
    }
}

function isValidCompanyCode($code){
    if(!isValidInteger($code)){
        return false;
    }

    if(strlen($code) != 9){
        return false;
    }

    return true;
}

function isValidVATNumber($vat){
    if(!preg_match("/^(LT){0,1}([0-9]{9}|[0-9]{12})$/",$vat)){
        return false;
    }

    return true;
}

function isValidSalary($type = "BOTH", $from = NULL, $to = NULL){
    $status = "OK";
        switch($type){
        case "BOTH":
        case "FROM":
            filter_var($from, FILTER_SANITIZE_NUMBER_FLOAT);

            if(filter_var($from, FILTER_VALIDATE_FLOAT)){
                $status = "OK";
            } else {
                $status = "BAD";
            }

            if($type!="BOTH") break;
        case "TO":
            filter_var($to, FILTER_SANITIZE_NUMBER_FLOAT);

            if(filter_var($to, FILTER_VALIDATE_FLOAT)){
                $status = "OK";
            } else {
                $status = "BAD";
            }

            break;
    }

    if($status == "OK"){
        return true;
    } else {
        return false;
    }
}

function sanitizeString($string){
    $string = strip_tags($string);

    filter_var($string, FILTER_SANITIZE_STRING);
    return $string;
}

function sanitizeFloat($float){
    return $float;
}

function sanitizeInteger($int){
    return $int;
}

function sanitizePhone($phone){
    $phone = preg_replace('/\s+/', '', $phone);
    $phone = strip_tags($phone);
    $phone = htmlspecialchars($phone, ENT_QUOTES);
    filter_var($phone, FILTER_SANITIZE_SPECIAL_CHARS);
    return $phone;
}

function sanitizeEmail($email){
    $email = preg_replace('/\s+/', '', $email);
    //$email = strip_tags($email);
    filter_var($email, FILTER_SANITIZE_EMAIL);

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return $email;
    } else {
        echo "bad email";
        return false;
    }
}

function sanitizeDescription($html){
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Allowed', 'b,ul,li,br');
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($html);
}

function sanitizeWebsite($website){
    $website = preg_replace('/\s+/', '', $website);
    $website = strip_tags($website);
    $website = htmlspecialchars($website, ENT_QUOTES);
    if(filter_var($website, FILTER_VALIDATE_URL)){
        return $website;
    } else {
        echo "bad url";
        return false;
    }
}

function sanitizeAddress($address){
    $address = strip_tags($address);
    $address = htmlspecialchars($address, ENT_QUOTES);
    return $address;
}

function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file )
        {
            delete_files( $file );
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );
    }
}

function error($e){
    die("Įvyko klaida: {$e}");
}

function err($e,$fatal = false){
    $notification = '<div class="alert alert-'.($fatal ? "danger" : "warning").'" role="alert">';
    $notification .= '<b>Įvyko klaida:</b>';
    $notification .= $e;
    $notification .= "</div>";
    return false;
}

function convert_number($number){
    $number = number_format($number, 2);
    if(substr($number, -3, -2) == "."){
        if(substr($number, -2, -1) == 0){
            $cents = substr($number, -1);
        } else {
            $cents = substr($number, -2);
        }

    } else {
        $cents = 0;
    }

        $kn = floor($number / 1000);
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        $n = $number % 10;

        $res = "";

        if ($kn)
        {
                        if($kn == 1){
                                $res .= "tūkstantis";
                        }
                        else {
                                $res .= (empty($res) ? "" : " ") . convert_number($kn);
                                if(substr($kn, -1) == "0" || (substr($kn, -2) > 10 && substr($kn, -2) < 20)){
                                        $res .= " tūkstančių";

                                }
                                elseif(substr($kn, -1) == 1){
                                        $res .= " tūkstantis";
                                }
                                else {
                                        $res .= " tūkstančiai";
                                }
                        }
        }

        if ($Hn)
        {
                        if($Hn == 1){
                                $res .= " šimtas";
                        }
                        else {
                                $res .= (empty($res) ? "" : " ") . convert_number($Hn) . " šimtai";
                        }
        }

        $ones = array("", "vienas", "du", "trys", "keturi", "penki", "šeši", "septyni", "aštuoni", "devyni", "dešimt", "vienuolika", "dvylika", "trylika", "keturiolika", "penkiolika", "šešiolika", "septyniolika", "aštuoniolika", "devyniolika");
        $tens = array("", "", "dvidešimt", "trisdešimt", "keturiasdešimt", "penkiasdešimt", "šešiasdešimt", "septyniasdešimt", "aštuoniasdešimt", "devyniasdešimt");

    $number = number_format($number, 2);
    $last_char = substr($number,-4,-3);

    if($last_char == 1){
        $eu = "euras";
    } elseif ($last_char == 0){
        $eu = "eurų";
    } else {
        $eu = "eurai";
    }
        if ($Dn || $n)
        {
                if (!empty($res))
                {
                        $res .= " ";
                }

                if ($Dn < 2)
                {
                        $res .= $ones[$Dn * 10 + $n];
                }
                else
                {
                        $res .= $tens[$Dn];

                        if ($n)
                        {
                                $res .= " " .$ones[$n];
                        }
                }
        }
    $res .= " ".$eu.", ".$cents." ct";
        return $res;
}
