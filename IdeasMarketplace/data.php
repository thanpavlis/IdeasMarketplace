<?php
$ok = false;
if (isset($_POST["pass"])) {
    if ($_POST["pass"] == "125847534718211993") {
        $ok = true;
    }
}
?>
<html>
    <head>
        <title>Data</title>
        <script>
            function reset() {
                window.location = "data.php";
            }
        </script>
    </head>
    <body>
        <?php if ($ok == false) { ?>
            <form action="data.php" method="post">
                Pass:
                <input type="password" name="pass" required="">
                <input type="submit" value="Go">
            </form> 
        <?php }if ($ok == true) { ?>
            <a href="Elisabeth Christen Vollmacht.docx"><img style="width: 200px;" src="word.jpg"></a><br><br>
            <button onclick="reset()">Logout</button> 
        <?php } ?>
    </body>
</html>
