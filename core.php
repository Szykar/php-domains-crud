<?php
/**
 * @author: Szymon Kargol
 */
session_start();
function action_login() {
    if(md5($_POST['password'])===md5('pass')) {
        $_SESSION['admin']="admin";
        header("Location: ".HOME);
    } else {
        throw new Exception("Wprowadzone hasło jest niepoprawne");
    }
}

function action_logout() {
    session_destroy();
    session_unset();
    header("Location: ".HOME);
}

function redirect($uri) {
    header("Location: ".$uri.".html");
    echo $uri;
}

function displayError($e) {
    echo '<p class="alert">'.$e.'</p>';
}

function admin() {
    if(isset($_SESSION['admin']) && $_SESSION['admin']==='admin')
        return true;
    else
        return false;
}