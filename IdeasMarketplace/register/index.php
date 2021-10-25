<?php
include '../functions.php';
if (!isset($_SESSION['id'])) {
    $ok = false;
    if (isset($_POST['email'])) {
        $conn = sqldb();
        if (array_key_exists('owner', $_POST)) {
            $sql_ins = "INSERT INTO users(first_name,last_name,email,password,sex,age,type) VALUES ('" . $_POST['name'] . "','" . $_POST['lname'] . "','" . $_POST['email'] . "','" . md5($_POST['pass']) . "','" . $_POST['sex'] . "','" . $_POST['age'] . "','" . $_POST['owner'] . "')";
        } else {
            $sql_ins = "INSERT INTO users(first_name,last_name,email,password,sex,age,type) VALUES ('" . $_POST['name'] . "','" . $_POST['lname'] . "','" . $_POST['email'] . "','" . md5($_POST['pass']) . "','" . $_POST['sex'] . "','" . $_POST['age'] . "','0')";
        }
        $sql = "SELECT id FROM users WHERE email='" . $_POST['email'] . "'";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            $conn->query($sql_ins);
        } else {
            $ok = true;
        }
        $conn->close();
        if ($ok == false) {
            header('Location: ' . "http://$_SERVER[HTTP_HOST]");
            die();
        }
    }
    ?>
    <html>
        <head>
            <title>Εγγραφή</title>
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
                        <center><h1>Εγγραφή</h1></center>
                        <?php
                        if ($ok) {
                            ?>
                            <div class="alert alert-danger" role="alert">Το email χρησιμοποιείται ήδη!</div>
                        <?php } ?>
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="name">Όνομα</label>
                                <input name="name" type="text" class="form-control" id="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" placeholder="Όνομα" required>
                            </div>
                            <div class="form-group">
                                <label for="lname">Επώνυμο</label>
                                <input name="lname" type="text" class="form-control" id="lname" value="<?php if (isset($_POST['lname'])) echo $_POST['lname']; ?>" placeholder="Επώνυμο" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input name="email" type="email" class="form-control" id="exampleInputEmail1" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" placeholder="Εισαγωγή email" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Κωδικός</label>
                                <input name="pass" type="password" class="form-control" id="exampleInputPassword1" value="<?php if (isset($_POST['pass'])) echo $_POST['pass']; ?>" placeholder="Κωδικός" required>
                            </div>
                            <div class="form-group">
                                <label>Φύλο</label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="sex" id="inlineRadio1" value="0" <?php if (isset($_POST['sex'])) if ($_POST['sex'] == 0) echo 'checked'; ?> required> Άντρας
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sex" id="inlineRadio2" value="1" <?php if (isset($_POST['sex'])) if ($_POST['sex'] == 1) echo 'checked'; ?> required> Γυναίκα
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="age">Ηλικία</label>
                                <input name="age" type="number" class="form-control" id="age" value="<?php if (isset($_POST['age'])) echo $_POST['age']; ?>" placeholder="Ηλικία" min="1" max="200" required>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input name="owner" id="owner" type="checkbox" value="2" <?php if (isset($_POST['owner'])) if ($_POST['owner'] == 2) echo 'checked'; ?>>Είμαι δημιουργός ιδεών
                                </label>
                            </div>
                            <br>
                            <center><button type="submit" class="btn btn-default">Εγγραφή</button></center> 
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