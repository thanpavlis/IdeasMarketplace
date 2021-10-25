<?php

include 'functions.php';
$conn = sqldb();
$sql = "SELECT MAX(bid) as m FROM bids WHERE auction=" . $_POST['auction'];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['m']) {
        echo $row['m'];
    } else {
        echo 0;
    }
}
?>