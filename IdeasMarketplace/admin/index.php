<?php
include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 3) {

// Create connection
    $conn = sqldb();
    $sql = "SELECT id,first_name,last_name,email,type,sex,age,img_temp  FROM users WHERE type!=3;";
    $result = $conn->query($sql);
    $conn->close();
    ?>
    <html>
        <head>
            <title>Διαχείριση Χρηστών</title>
            <?php styles(); ?>
            <script>
                function addClient() {
                    $('input[name=email]').val("");
                    $('input[name=pass]').val("");
                    $("#newclient").show();
                }
                function saveClient() {
                    var owner = 0;
                    if ($('#owner').is(":checked")) {
                        owner = 2;
                    }
                    $.post("/register/client_register.php", {name: $("#name").val(), lname: $("#lname").val(), email: $("#email").val(), pass: $("#pass").val(), sex: $('input[name=sex]:checked', '#newclient').val(), age: $("#age").val(), owner: owner})
                            .done(function (data) {
                                location.reload();
                            });
                }
            </script> 
            <style>
                td.elipsis {
                    max-width: 100px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
            </style>
        </head> 
        <body>
            <?php show_header(); ?>
            <div class="container">
                <div class="row">
                    <div class="box">
                        <button class="btn btn-default" onclick="addClient()">Προσθήκη Χρήστη</button><br><br>
                        <form id="newclient" class="form-inline" style="display:none;" onsubmit="saveClient();
                                    return false;"> 
                            <div class="form-group">
                                <label for="name">Όνομα</label><br>
                                <input name="name" type="text" class="form-control" id="name" placeholder="Όνομα" size="30" required>
                            </div><br>
                            <div class="form-group">
                                <label for="lname">Επώνυμο</label><br>
                                <input name="lname" type="text" class="form-control" id="lname" placeholder="Επώνυμο" size="30" required>
                            </div><br>
                            <div class="form-group">
                                <label for="email">Email</label><br>
                                <input name="email" type="email" class="form-control" id="email" placeholder="Εισαγωγή email" size="30" required>
                            </div><br>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Κωδικός</label><br>
                                <input name="pass" type="password" class="form-control" id="pass" placeholder="Κωδικός" size="30" required>
                            </div><br>
                            <div class="form-group">
                                <label for="age">Ηλικία</label><br>
                                <input name="age" type="number" class="form-control" id="age" placeholder="Ηλικία" min="18" max="100" required>
                            </div><br>
                            <div class="form-group">
                                <label>Φύλλο</label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="sex"  value="0" required> Άντρας
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sex"  value="1" required> Γυναίκα
                                </label>
                            </div><br><br>
                            <div class="form-group">
                                <input type="checkbox" name="owner" id="owner"> Δημιουργός Ιδεών
                            </div><br><br>
                            <button class="btn btn-default" type="submit">Αποθήκευση Χρήστη</button>
                        </form>
                        <?php if ($result->num_rows > 0) { ?>
                            <table id="clients" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>A/A</th>
                                        <th>Id</th>
                                        <th>Ηλεκτρονική Διεύθυνση</th>
                                        <th>Εικόνα Χρήστη</th>
                                        <th>'Ονομα</th>
                                        <th>Επώνυμο</th>
                                        <th>Φύλλο</th>
                                        <th>Ηλικία</th>
                                        <th>Είναι Δημιουργός Ιδεών</th>
                                        <th>Επεξεργασία</th>
                                    </tr>
                                </thead>
                                <?php
                                $counter = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $counter . '</td>';
                                    echo '<td>' . $row["id"] . '</td>';
                                    echo '<td class="elipsis">' . $row["email"] . '</td>';
                                    if ($row["img_temp"] == 1) {
                                        ?>
                                        <td><img style="height: 100px;width: 100px;" src="../image_user.php?id=<?= $row['id'] ?>"></img></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td><img style="height: 100px;width: 100px;" src="../img/no_image.jpg"></img></td>
                                            <?php
                                        }
                                        echo '<td class="elipsis">' . $row["first_name"] . '</td>';
                                        echo '<td class="elipsis">' . $row["last_name"] . '</td>';
                                        if ($row["sex"] == 0) {
                                            echo '<td>Άντρας</td>';
                                        } else {
                                            echo '<td>Γυναίκα</td>';
                                        }
                                        echo '<td>' . $row["age"] . '</td>';
                                        if ($row["type"] == 2) {
                                            echo '<td class="icons"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></td>';
                                        } else {
                                            echo '<td class="icons"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>';
                                        }
                                        echo '<td><a href="user_edit.php?id=' . $row["id"] . '">Επεξεργασία</a></td>';
                                        echo "</tr>";
                                        $counter++;
                                    }
                                } else {
                                    echo "<center><h1>Δεν υπάρχουν χρήστες για διαχείριση !</h1></center>";
                                }
                                ?>
                        </table>
                    </div>
                </div>
            </div>
        </body>
    </html>
    <?php
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}