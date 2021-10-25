<?php

include 'functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] >= 0) {
    // Create connection
    $conn = sqldb();
    if ($_POST['pass'] != '') {//periptwsh allaghs password
        $sql = 'UPDATE users SET first_name="' . $_POST['name'] . '", last_name="' . $_POST['lname'] . '", email="' . $_POST['email'] . '", password="' . md5($_POST['pass']) . '", sex=' . $_POST['sex'] . ', age=' . $_POST['age'] . ', type=' . $_POST['owner'] . ' WHERE id=' . $_POST['id'];
    } else {//periptwsh pou to password paramenei idio
        $sql = 'UPDATE users SET first_name="' . $_POST['name'] . '", last_name="' . $_POST['lname'] . '", email="' . $_POST['email'] . '", sex=' . $_POST['sex'] . ', age=' . $_POST['age'] . ', type=' . $_POST['owner'] . ' WHERE id=' . $_POST['id'];
    }
    $result = $conn->query($sql);
    $conn->close();
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['type'] = $_POST['owner'];
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>
