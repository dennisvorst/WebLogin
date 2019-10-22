<?php
require_once "class/Account.php";

session_start();


if (!empty($_POST))
{
//    print_r($_POST);
    $keys = array_keys($_POST);
    foreach ($keys as $key)
    {
        ${$key} = $_POST[$key];
    }
} elseif (!empty($_GET))
{
//    print_r($_GET);
    $keys = array_keys($_GET);
    foreach ($keys as $key)
    {
        ${$key} = $_GET[$key];
    }
}

//print_r($keys);

$object = new Account();
$user_id = 1;

if (isset($action))
{
    switch ($action)
    {
        case "doLogout" :
            /* get the user_id from the session */
            /* logout the user */
            if ($object->doLogout($user_id))
            {
                $_SESSION['message'] = "Logout successful";
            } else {
                $_SESSION['message'] = "Logout failed";
            }
            break;
        case "doLogin" :
            if ($object->doLogin($_POST['username'], $_POST['password']))
            {
                $_SESSION['message'] = "Login successful";
            } else {
                $_SESSION['message'] = "Login failed";
            }
            break;
        case "doRegister" :
            if ($object->doRegister($_POST))
            {
                $_SESSION['message'] = "Your registration was successful.";
            } else {
                $_SESSION['message'] = "There was an error processing Your registration.";
            }
            break;
        case "sendPassword" :
            if ($object->sendPassword($_POST['username']))
            {

                $_SESSION['message'] = "If the email address or username provided by you was in our system an email was sent to you.";
            }

            break;
        default :
            break;
    }
}

header("Location: index.php");
die();