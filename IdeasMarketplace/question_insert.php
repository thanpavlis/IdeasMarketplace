<?php

include 'functions.php';
if (isset($_SESSION['id'])) {
    $conn = sqldb();
    $sql = "INSERT INTO questions(user_id,auction_id,question,date) VALUES ('" . $_POST['user_id'] . "','" . $_POST['auction_id'] . "','" . $_POST['question'] . "',now())";
    $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>