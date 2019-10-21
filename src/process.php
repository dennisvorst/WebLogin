<?php

require_once "class/Account.php";

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
            $object->doLogout($user_id);
            break;
        case "doLogin" :
            /** verify the user and password combination */

            /** if correct create a succesful login record */

            /** if not create a false login record */
            break;
        case "doRegister" :
            $object->doRegister($_POST);



            break;
        default :
            break;
    }
}

//header("Location: index.php");
//die();