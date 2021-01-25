<?php

    $number = (isset($_POST['number']))? htmlspecialchars($_POST['number'], ENT_QUOTES, 'utf-8') : '';
    $password = (isset($_POST['password']))? htmlspecialchars($_POST['password'], ENT_QUOTES, 'utf-8'): '';

    if ($number == '') {
        header("Location:./index.html");
        exit;
    }
    if ($password == '') {
        header("Location:./index.html");
        exit;
    }

    if ($number=='123456'&&$password=='password01') {
        //ログイン許可ad
        session_start();
        $_SESSION['admin_login'] = true;
        header("Location:./index.php");
    } else {
        //間違っているのでログイン不可
        header("Location:./index.html");
        exit;
    }
?>