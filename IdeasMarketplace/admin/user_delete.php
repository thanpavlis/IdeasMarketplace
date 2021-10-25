<?php

include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 3) {
    $conn = sqldb();
    $sql = 'DELETE FROM users WHERE id=' . $_POST['id'] . ';';
    $conn->query($sql);
    $sql = 'DELETE FROM comments WHERE user_id=' . $_POST['id'] . ';';
    $conn->query($sql);
    $sql = 'DELETE FROM bids WHERE user=' . $_POST['id'] . ';';
    $conn->query($sql);
    $sql = 'SELECT auction_id FROM belongs WHERE user_id=' . $_POST['id'] . ';';
    $result = $conn->query($sql);
    $sql = 'DELETE FROM belongs WHERE user_id=' . $_POST['id'] . ';';
    $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sql = 'DELETE FROM auctions WHERE id=' . $row['auction_id'] . ';';
            $conn->query($sql);
        }
    }
    $sql = 'DELETE FROM questions WHERE user_id=' . $_POST['id'] . ';';
    $conn->query($sql);
    $sql = 'DELETE FROM answers WHERE user_id=' . $_POST['id'] . ';';
    $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>