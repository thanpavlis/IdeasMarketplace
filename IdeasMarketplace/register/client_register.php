<?php

include '../functions.php';
// Create connection
$conn = sqldb();
if (array_key_exists('owner', $_POST)) {
    $sql_ins = "INSERT INTO users(first_name,last_name,email,password,sex,age,type) VALUES ('" . $_POST['name'] . "','" . $_POST['lname'] . "','" . $_POST['email'] . "','" . md5($_POST['pass']) . "','" . $_POST['sex'] . "','" . $_POST['age'] . "','" . $_POST['owner'] . "')";
} else {
    $sql_ins = "INSERT INTO users(first_name,last_name,email,password,sex,age,type) VALUES ('" . $_POST['name'] . "','" . $_POST['lname'] . "','" . $_POST['email'] . "','" . md5($_POST['pass']) . "','" . $_POST['sex'] . "','" . $_POST['age'] . "','0')";
}

$sql = "SELECT id FROM users WHERE email='" . $_POST['email'] . "'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $conn->query($sql_ins);
}

$conn->close();
header('Location: ' . "http://$_SERVER[HTTP_HOST]");
die();
