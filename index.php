<?php
/**
 * Index page
 * User: Ryan Guelzo
 * Date: 04/08/19
 */

//Error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//require the autoload
require '/home/ryanguel/config.php';
require_once('vendor/autoload.php');
require('model/validation.php');

session_start();

//Creates the instance of the base class
$f3 = Base::instance();

//define arrays
$f3->set('genderOption', array('Male', 'Female'));

//Specified the default route
$f3->route('GET|POST /', function (){

    $view = new Template();
    echo $view->render('views/home.html');
});

$f3->route('GET|POST /profile-start', function ($f3){
    $dbh = new Database();
    $f3->set('dbh', $dbh);

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        //get the form data
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $memberStatus = $_POST['premium'];

        //add to the hive
        $f3->set('firstname', $firstname);
        $f3->set('lastname', $lastname);
        $f3->set('age', $age);
        $f3->set('gender', $gender);
        $f3->set('phone', $phone);
        $f3->set('member', $memberStatus);



        if(validForm1()){
            if(empty($gender)) {
                $gender = 'No gender selected.';
            }
            if($memberStatus == '1'){
                $premium = new PremiumMemeber($firstname, $lastname, $age, $gender, $phone);
                $_SESSION['member'] = $premium;
            } else{
                $member = new Member($firstname, $lastname, $age, $gender, $phone);
                $_SESSION['member'] = $member;
            }

            $f3->reroute('/profile-continue');
        }

    }
//echo "here";
   $view = new Template();
   echo $view->render('views/personalinformation.html');
});

$f3->route('GET|POST /profile-continue', function ($f3){

    echo $f3->get('member');

    if(!empty($_POST)) {
        $email = $_POST['email'];
        $state = $_POST['state'];
        $seeking = $_POST['seeking'];
        $bio = $_POST['bio'];

        $f3->set('email', $email);
        $f3->set('state', $state);
        $f3->set('seeking', $seeking);
        $f3->set('bio', $bio);

        if (validEmail($email)) {
            $_SESSION['member']->setEmail($email);
            if (!empty($state)) {
                $_SESSION['member']->setState($state);
            } else {
                $_SESSION['member']->setState('No state selected.');
            }
            if (!empty($seeking)) {
                $_SESSION['member']->setSeeking($seeking);
            } else {
                $_SESSION['member']->setSeeking('No seeking selected.');
            }
            if (!empty($bio)) {
                $_SESSION['member']->setBio($bio);
            } else {
                $_SESSION['member']->setBio('No bio input.');
            }
            if(!$_SESSION['member'] instanceof PremiumMemeber){
                $_SESSION['indoor'] = "(No interests selected";
                $_SESSION['outdoor'] = " Premium Membership required.)";
                $f3->get('dbh')->insertMember($_SESSION['memeber'], $f3);
                $f3->reroute('/summary');
            } else{
                $f3->reroute('/profile-interests');
            }
        }
    }

    $view = new Template();
    echo $view->render('views/profileEntry.html');
});

$f3->route('GET|POST /profile-interests', function ($f3){

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $outdoor = $_POST['outdoor'];
        $indoor = $_POST['indoor'];

        if(!empty($indoor)){
            $f3->set('indoor', implode(", ", $indoor));
            $_SESSION['indoor'] = $f3->get('indoor');
        }
        else{
            $_SESSION['indoor'] = '(No indoor interests selected)';
        }
        if(!empty($outdoor)){
            $f3->set('outdoor', implode(", ", $outdoor));
            $_SESSION['outdoor']  = $f3->get('outdoor');
        }
        else{
            $_SESSION['outdoor'] = '(No outdoor interests selected)';
        }

        $f3->reroute('/summary');
    }

    $view = new Template();
    echo $view->render('views/interests.html');
});

$f3->route('GET|POST /summary', function ($f3){


    $view = new Template();
    echo $view->render('views/summary.html');
});
//Run fat-free
$f3->run();