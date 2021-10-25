<?php

include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 2) {
    $conn = sqldb();
    $sql = 'UPDATE comments SET comment="' . $_POST['comment'] . '", rate="' . $_POST['rate'] . '", date="' . $_POST['date'] . '" WHERE id=' . $_POST['id'];
    $result = $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>
