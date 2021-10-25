<?php

include 'functions.php';
if (isset($_SESSION['type'])) {
    $conn = sqldb();
    $sql = 'UPDATE auctions SET winner="' . $_POST['winner'] . '" WHERE id=' . $_POST['auction'];
    $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>
