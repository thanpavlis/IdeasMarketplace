<?php
include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 2) {
    // Create connection
    $conn = sqldb();
    $sql = 'SELECT belongs.user_id FROM belongs,answers WHERE ((belongs.auction_id=answers.auction_id) AND (answers.id=' . $_GET['id'] . '))';
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if ($row['user_id'] == $_SESSION['id']) {





        $sql = 'SELECT user_id,auction_id,answer,date FROM answers WHERE id=' . $_GET['id'] . ';';
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            $conn->close();
            header('Location: ' . "http://$_SERVER[HTTP_HOST]/admin/answers.php");
            die();
        }
        $row = $result->fetch_assoc();
        $sql = 'SELECT first_name,last_name FROM users WHERE id=' . $row['user_id'] . ';';
        $result = $conn->query($sql);
        $row1 = $result->fetch_assoc();
        $sql = 'SELECT name FROM auctions WHERE id=' . $row['auction_id'] . ';';
        $result = $conn->query($sql);
        $row2 = $result->fetch_assoc();
        $conn->close();
        ?>
        <html>
            <head>
                <title>Επεξεργασία Απάντησης</title>
                <?php styles(); ?>
                <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
                <link href="/css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />
                <script src="/js/star-rating.min.js" type="text/javascript"></script>
                <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css">
                <script src="/js/jquery.datetimepicker.js"></script>
                <style>
                    .container {
                        margin-top:100px;
                        width:700px;
                    }
                </style>
                <script>
                    function deleteAnswer() {
                        $.post("answer_delete.php", {id: <?php echo $_GET['id']; ?>})
                                .done(function (data) {
                                    alert('ΕΠΙΤΥΧΗΣ ΔΙΑΓΡΑΦΗ ΑΠΑΝΤΗΣΗΣ!');
                                    window.location = "answers.php";
                                });
                    }
                    function saveAnswer() {
                        $.post("answer_update.php", {id: <?php echo $_GET['id'] ?>, answer: $("#ans").val(), date: $("#date").val()})
                                .done(function (data) {
                                    alert('ΕΠΙΤΥΧΗΣ ΕΝΗΜΕΡΩΣΗ ΑΠΑΝΤΗΣΗΣ!');
                                    window.location = "answers.php";
                                });
                    }
                </script>
            </head>
            <body>
                <?php show_header(); ?>
                <div class="container">
                    <div class="row">
                        <div class="box">
                            <center>
                                <h3>Επεξεργασία στοιχείων απάντησης</h3><a href="answers.php">Επιστροφή στη λίστα απαντήσεων</a><br><br>  
                            </center>
                            <br><br> 
                            <center>
                                <h4>Η απάντηση έγινε από τον χρήστη: <?php echo $row1['first_name'] . " " . $row1['last_name']; ?> </h4><br>
                                <h4>Η απάντηση έγινε για την δημοπρασία ιδέας: <?php echo $row2['name']; ?> </h4><br>
                                <form method='post' onsubmit="saveAnswer();
                                                return false;">   
                                    <h4>Προσθέστε την απάντηση σας:</h4>
                                    <textarea id="ans" rows="7" cols="60" placeholder="Γράψτε την απάντηση σας εδώ ..." required><?php echo $row['answer']; ?></textarea><br><br>                         
                                    <h4>Ημερομηνία Καταχώρησης(yyyy-mm-dd hh:mm:ss):</h4>
                                    <br><br>
                                    <div style="margin: 0 0 0 222;" class="col-sm-4">
                                        <input type="text" class="form-control" id="date" name="date" value="<?= $row['date'] ?>" required>
                                    </div>                                                
                                    <br><br><br><br><br>
                                    <button type="submit" onclick="saveAnswer();" class="btn btn-default">Αποθήκευση</button> 
                                    <button onclick="deleteAnswer();
                                                    return false;" class="btn btn-danger">Διαγραφή απάντησης</button> 
                                </form>
                            </center>
                        </div>
                    </div>
                </div>
                <script>
                    $('#date').datetimepicker();
                </script>
            </body>
        </html>
        <?php
    } else {
        $conn->close();
        header('Location: ' . "http://$_SERVER[HTTP_HOST]/404.html");
        die();
    }
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}    