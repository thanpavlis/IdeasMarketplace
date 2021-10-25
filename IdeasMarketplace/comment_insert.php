<?php

include 'functions.php';
if (isset($_SESSION['id'])) {
    $conn = sqldb();
    $sql = "INSERT INTO comments(user_id,auction_id,comment,rate,date) VALUES ('" . $_POST['user_id'] . "','" . $_POST['auction_id'] . "','" . $_POST['comment'] . "','" . $_POST['rate'] . "',now())";
    $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>