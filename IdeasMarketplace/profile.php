<?php
include 'functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] >= 0) {
    // Create connection
    $conn = sqldb();

    $sql = 'SELECT * FROM users WHERE id=' . $_SESSION['id'] . ';';
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $first = $row["first_name"];
            $last = $row["last_name"];
            $email = $row["email"];
            $sex = $row["sex"];
            $age = $row["age"];
            $type = $row["type"];
        }
    }
    $conn->close();
    ?>
    <html>
        <head>
            <title>Επεξεργασία Προφίλ</title>
            <?php styles(); ?>
            <script>
                function updateClient() {
                    var owner = 0;
    <?php if ($type == 3) { ?>
                        owner = 3;
    <?php } ?>
                    if ($('#owner').is(":checked")) {
                        owner = 2;
                    }
                    $.post("profile_update.php", {id: <?= $_SESSION['id'] ?>, name: $("#name").val(), lname: $("#lname").val(), email: $("#email").val(), pass: $("#pass").val(), sex: $('input[name=sex]:checked', '#client').val(), age: $("#age").val(), owner: owner})
                            .done(function (data) {
                                alert('ΕΠΙΤΥΧΗΣ ΕΝΗΜΕΡΩΣΗ ΣΤΟΙΧΕΙΩΝ ΧΡΗΣΤΗ!');
                                window.location = "index.php";
                            });
                }
            </script>
        </head>
        <body>
            <?php show_header(); ?>
            <div class="container" style="width: 800px;">
                <div class="row">
                    <div class="box">
                        <center>
                            <h3>Επεξεργασία στοιχείων χρήστη - <?php echo '<b>' . $first . ' ' . $last . '</b>' ?> </h3><a href="index.php">Επιστροφή στην αρχική σελίδα</a>  
                        </center>
                        <form id="client" onsubmit="return false">
                            <div class="form-group">
                                <label for="name">Όνομα</label>
                                <input name="name" type="text" class="form-control" id="name" placeholder="Όνομα" value="<?php echo $first; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="lname">Επώνυμο</label>
                                <input name="lname" type="text" class="form-control" id="lname" placeholder="Επώνυμο" value="<?php echo $last; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input name="email" type="email" class="form-control" id="email" placeholder="Εισαγωγή email" value="<?php echo $email; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Νέος Κωδικός</label>
                                <input name="pass" type="password" class="form-control" id="pass" placeholder="Κωδικός">
                            </div>
                            <div class="form-group">
                                <label>Φύλο</label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="sex"  value="0" <?php echo (($sex == 0) ? "checked" : ""); ?> > Άντρας
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sex"  value="1" <?php echo (($sex == 1) ? "checked" : ""); ?>> Γυναίκα
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="age">Ηλικία</label>
                                <input name="age" type="number" class="form-control" id="age" placeholder="Ηλικία" min="1" max="200" value="<?php echo $age; ?>" required>
                            </div>
                            <?php if ($type != 3) { ?>
                                <div class="checkbox">
                                    <label>
                                        <input name="owner" id="owner" type="checkbox" value="1" <?php echo (($type == 2) ? "checked" : ""); ?>> Δημιουργός ιδεών
                                    </label>
                                </div>
                            <?php } ?>
                            <button type="submit" onclick="updateClient();
                                        return false;" class="btn btn-default">Αποθήκευση</button>
                        </form>
                    </div>
                </div>
            </div> 
            <?php if (($_SESSION['type'] != 3) && ($_SESSION['type'] != 2)) show_footer(); ?>
        </body>
    </html>
    <?php
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
} 