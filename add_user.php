<?php
    if ($_POST['pass'] !== $_POST['pass_again']) {
        $msg = "１回目と２回目のパスワードが違うみたいだ！";
        header("Location: create_account.php?msg=$msg");
        exit;
    }
    $severname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "test";
    $db = new mysqli($severname, $username, $password, $dbname);
    $sql = "insert into user (name, pass) values('".$_POST['name']."','".$_POST['pass']."')";
    $db->query($sql);
    header("Location: login.php")
?>