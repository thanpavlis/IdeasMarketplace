<?php

include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 3) {
    $conn = sqldb();
    $sql = 'UPDATE questions SET question="' . $_POST['question'] . '", date="' . $_POST['date'] . '" WHERE id=' . $_POST['id'];
    $result = $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>
