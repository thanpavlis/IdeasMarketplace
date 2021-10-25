<?php

include 'functions.php';
if (isset($_SESSION['id'])) {
    $conn = sqldb();
    $sql = 'DELETE FROM questions WHERE id=' . $_POST['id'];
    $conn->query($sql);
    $sql = 'DELETE FROM answers WHERE question_id=' . $_POST['id'];
    $conn->query($sql);
    $conn->close();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>