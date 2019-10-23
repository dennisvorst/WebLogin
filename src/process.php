<?php
require_once "class/Account.php";

session_start();

if (!empty($_POST))
{
    $keys = array_keys($_POST);
    foreach ($keys as $key)
    {
        ${$key} = $_POST[$key];
    }
} elseif (!empty($_GET))
{
    $keys = array_keys($_GET);
    foreach ($keys as $key)
    {
        ${$key} = $_GET[$key];
    }
}

$object = new Account();

if (isset($action))
{
    switch ($action)
    {
        case "doLogin" :
            if ($object->doLogin($_POST['username'], $_POST['password']))
            {
                $_SESSION['message'] = "Login successful";
                $_SESSION['isLoggedIn'] = True;
                $_SESSION['userId'] = $object->getUserId();
                $_SESSION['lastActive'] = date('Y-m-d H:i:s');
            }
            break;

        case "doLogout" :
            /* get the user_id from the session */
            /* logout the user */
            if (!isset($_SESSION['userId']) || $object->doLogout($_SESSION['userId']))
            {
                $_SESSION['message'] = "Logout successful";
                $_SESSION['isLoggedIn'] = False;
                unset($_SESSION['userId']);
                unset($_SESSION['lastActive']);
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

        case "deleteAccount" : 
            if ($object->deleteAccount($_POST['userId']))
            {
                
            }
            break;

        case "updateAccount" : 
            if ($object->deleteAccount($_POST['userId']))
            {
                
            }
            break;

        default :
            break;
    }
}

header("Location: index.php");
die();