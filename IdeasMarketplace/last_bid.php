<?php

include 'functions.php';
$conn = sqldb();
$sql = "SELECT bid FROM bids WHERE auction=" . $_POST['auction'] . " AND user=" . $_POST['user'];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['bid']) {
        echo $row['bid'];
    } else {
        echo 0;
    }
}
?>