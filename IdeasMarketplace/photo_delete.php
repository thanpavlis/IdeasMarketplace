<?php

include 'functions.php';
if (isset($_SESSION['type'])) {
    $conn = sqldb();
    $sql = "UPDATE users SET img_temp=0 WHERE id=" . $_SESSION['id'];
    $conn->query($sql);
    $conn->close();
    $_SESSION['img_temp'] = 0;
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>
