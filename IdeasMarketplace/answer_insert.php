<?php

include 'functions.php';
if (isset($_SESSION['id'])) {
    $conn = sqldb();
    $sql = "INSERT INTO answers(user_id,question_id,auction_id,answer,date) VALUES ('" . $_POST['user_id'] . "','" . $_POST['question_id'] . "','" . $_POST['auction_id'] . "','" . $_POST['answer'] . "',now())";
    $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>