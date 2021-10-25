<?php

include 'functions.php';
$conn = sqldb();
$sql = "SELECT MAX(bid) as m FROM bids WHERE auction=" . $_POST['auction'];
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['m'] && $row['m']>= $_POST['bid']) {
    die();
}

$sql = "UPDATE bids SET bid=" . $_POST['bid'] . " WHERE user=" . $_SESSION['id'] . " AND auction=" . $_POST['auction'];
mysqli_query($conn, $sql);

if (mysqli_affected_rows($conn) != 1) {
    $sql = "INSERT INTO bids(auction,user,bid) VALUES(" . $_POST['auction'] . "," . $_SESSION['id'] . "," . $_POST['bid'] . " )";
    mysqli_query($conn, $sql);
}

mysqli_close($conn);
