<?php

$link = mysqli_connect("localhost", "root", "");
mysqli_select_db($link, "ideas");
$sql = "SELECT image FROM users WHERE id=" . $_GET['id'];
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
mysqli_close($link);
header("Content-type: image/*");
echo $row['image'];
?>