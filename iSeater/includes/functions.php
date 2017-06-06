<?php

function hasAccess()
{
    if(!isset($_SESSION['is_valid_user']) or !$_SESSION['is_valid_user'])
    {
        $_SESSION['notValid'] = "You need to sign in to view the page.";
        header('Location: index.php');
    }

}