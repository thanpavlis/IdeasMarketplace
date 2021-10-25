<?php

include 'functions.php';
if (isset($_SESSION['id'])) {
    $conn = sqldb();
    $sql = 'DELETE FROM answers WHERE id=' . $_POST['id'];
    $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>