<?php

include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 3) {
    $conn = sqldb();

    $sql = 'DELETE FROM comments WHERE id=' . $_POST['id'] . ';';
    $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>