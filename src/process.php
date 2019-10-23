<?php
require_once "class/Account.php";

session_start();

/** transfer the posted values */
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

/** init */
$query = "";
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
                $_SESSION['lastActive'] = new DateTime();
            }
            break;

        case "doLogout" :
            /* get the user_id from the session */
            /* logout the user */
            if (!isset($_SESSION['userId']) || $object->doLogout($_SESSION['userId']))
            {
                $_SESSION['message'] = "Logout successful";
                unset($_SESSION['isLoggedIn']);
                unset($_SESSION['userId']);
                unset($_SESSION['lastActive']);
            }
            break;

        case "doRegister" :
            if ($object->doRegister($_POST))
            {
                $_SESSION['message'] = "Your registration was successful.";
            } else {
                $_SESSION['message'] = "There was an error processing your registration.";
                $_SESSION['errors'] = $object->getErrors();
            }
            break;

        case "sendPassword" :
            if ($object->sendPassword($_POST['username']))
            {

                $_SESSION['message'] = "If the email address or username provided by you was in our system an email was sent to you.";
            }
            break;

        case "deleteAccount" :
            if ($object->deleteAccount($_SESSION['userId']))
            {
                $_SESSION['message'] = "Deletion of your account was successful";
                unset($_SESSION['isLoggedIn']);
                unset($_SESSION['userId']);
                unset($_SESSION['lastActive']);
            }
            break;

        case "updateAccount":
            $_SESSION['message'] = "Your account was updated.";
            $query = "?action=showProperties";

            print_r($_POST);
            break;

        case "updateAccount" :
            break;

        default :
            break;
    }
}

header("Location: index.php" . $query);
die();