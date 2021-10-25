<?php

include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 3) {
    $conn = sqldb();

    $sql = 'DELETE FROM auctions WHERE id=' . $_POST['id'];
    $conn->query($sql);
    $sql = 'DELETE FROM bids WHERE auction=' . $_POST['id'];
    $conn->query($sql);
    $sql = 'DELETE FROM comments WHERE auction_id=' . $_POST['id'];
    $conn->query($sql);
    $sql = 'DELETE FROM belongs WHERE auction_id=' . $_POST['id'];
    $conn->query($sql);
    $sql = 'DELETE FROM questions WHERE auction_id=' . $_POST['id'];
    $conn->query($sql);
    $sql = 'DELETE FROM answers WHERE auction_id=' . $_POST['id'];
    $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>