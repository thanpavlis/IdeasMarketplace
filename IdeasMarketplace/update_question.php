<?php

include 'functions.php';
if (isset($_SESSION['id'])) {
    $conn = sqldb();
    $sql = 'UPDATE questions SET question="' . $_POST['question'] . '", date=now() WHERE id=' . $_POST['id'];
    $result = $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>
