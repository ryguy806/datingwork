<?php

function validForm1()
{
    global $f3;
    $isValid = true;
    if (!validname($f3->get('firstname'))) {
        $isValid = false;
        $f3->set("errors['first']", "Not a valid first name, please enter a valid name.");
    }
    if (!validname($f3->get('lastname'))) {
    $isValid = false;
    $f3->set("errors['last']", "Not a valid last name, please enter a valid name.");
}
    if (!validAge($f3->get('age'))) {
        $isValid = false;
        $f3->set("errors['age']", "Please enter 1 or more.");
    }
    if (!validPhone($f3->get('phone'))) {
        $isValid = false;
        $f3->set("errors['phone']", "Please enter a valid phone number (123-456-7890).");
    }
    return $isValid;
}

function validForm3()
{
    global $f3;

    $isValid = true;
    if (!validOutdoor($f3->get('outdoor'))) {
        $isValid = false;
        $f3->set("errors['outdoor']", "Selection not valid.");
    }

    if (!validIndoor($f3->get('indoor'))) {
        $isValid = false;
        $f3->set("errors['indoor']", "Selection not valid.");
    }

    return $isValid;
}

function validName($name){
    return (!empty($name) && ctype_alpha($name));
}

function validAge($age){
    return !empty($age) && ctype_digit($age);
}

function validPhone($phone){
    return (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)
        || preg_match("/^[0-9]{10}/", $phone)) && (mb_strlen((string)$phone) == 10 ||
            mb_strlen((string)$phone) == 13);
}

function validEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validOutdoor($outdoor){

    $options = array('spellcasting', 'horse-riding', 'hiding', 'looting', 'fighting', 'healing');
    $verified = array();
    foreach ($options as $option) {
        if(isset($outdoor)){
            array_push($verified, $option);
        }
    }
    return sizeof($verified) > 0;
}

function validIndoor($indoor){

    $options = array('trading', 'dragon-chess', 'drinking', 'reading', 'pickpocketing', 'lock-picking',
        'story-telling', 'smithing');
    $verified = array();
    foreach ($options as $option) {
        if(isset($indoor)){
            array_push($verified, $option);
        }
    }
    return sizeof($verified) > 0;
}
