<?php
include '../functions.php';
if (!isset($_SESSION['id'])) {
    $ok = true;
    if (isset($_POST['email'])) {
        $conn = sqldb();
        $sql = "SELECT id,type,first_name,img_temp FROM users WHERE email='" . $_POST['email'] . "' AND password='" . md5($_POST['pass']) . "'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['id'] = $row['id'];
            $_SESSION['type'] = $row['type'];
            $_SESSION['name'] = $row['first_name'];
            $_SESSION['img_temp'] = $row['img_temp'];
            if ($row['type'] == 2) {
                header('Location: ' . "http://$_SERVER[HTTP_HOST]/creator");
                die();
            } else if (($row['type'] == 0) || ($row['type'] == 1)) {
                header('Location: ' . "http://$_SERVER[HTTP_HOST]");
                die();
            } else if ($row['type'] == 3) {
                header('Location: ' . "http://$_SERVER[HTTP_HOST]/admin");
                die();
            }
        } else {
            $ok = false;
        }
        $conn->close();
    }
    ?>
    <html>
        <head>
            <title>Είσοδος</title>
            <?php styles(); ?>
            <style>
                .container {
                    margin-top:100px;
                    width:400px;
                }
            </style>
        </head>
        <body>
            <?php show_header(); ?>
            <div class="container">
                <div class="row">
                    <div class="box">
                        <center><h2>Είσοδος</h2></center>
                        <?php if (!$ok) { ?>
                            <div class="alert alert-danger" role="alert">Λάθος email ή κωδικός!</div>
                        <?php } ?>  
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input name="email" type="email" class="form-control" id="exampleInputEmail1" placeholder="Εισαγωγή Email" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Κωδικός</label>
                                <input name="pass" type="password" class="form-control" id="exampleInputPassword1" placeholder="Κωδικός" required>
                            </div>
                            <center><button type="submit" class="btn btn-default">Είσοδος</button></center>
                        </form>
                    </div>
                </div>
            </div>
            <?php show_footer(); ?>
        </body>
    </html>
    <?php
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}