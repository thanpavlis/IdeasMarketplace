<?php
include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 3) {
    // Create connection
    $conn = sqldb();
    $sql = 'SELECT user_id,auction_id,comment,rate,date FROM comments WHERE id=' . $_GET['id'] . ';';
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        $conn->close();
        header('Location: ' . "http://$_SERVER[HTTP_HOST]/admin/comments.php");
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
            <title>Επεξεργασία Σχολίου</title>
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
                function deleteComment() {
                    $.post("comment_delete.php", {id: <?php echo $_GET['id']; ?>})
                            .done(function (data) {
                                alert('ΕΠΙΤΥΧΗΣ ΔΙΑΓΡΑΦΗ ΣΧΟΛΙΟΥ!');
                                window.location = "comments.php";
                            });
                }
                function saveComment() {
                    $.post("comment_update.php", {id: <?php echo $_GET['id'] ?>, comment: $("#com").val(), rate: $("#stars").val(), date: $("#date").val()})
                            .done(function (data) {
                                alert('ΕΠΙΤΥΧΗΣ ΕΝΗΜΕΡΩΣΗ ΣΧΟΛΙΟΥ!');
                                window.location = "comments.php";
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
                            <h3>Επεξεργασία στοιχείων σχολίου</h3><a href="comments.php">Επιστροφή στη λίστα σχολίων</a><br><br>  
                        </center>
                        <br><br> 
                        <center>
                            <h4>Το σχόλιο έγινε από τον χρήστη: <?php echo $row1['first_name'] . " " . $row1['last_name']; ?> </h4><br>
                            <h4>Το σχόλιο έγινε για την δημοπρασία ιδέας: <?php echo $row2['name']; ?> </h4><br>
                            <form method='post' onsubmit="saveComment();
                                        return false;">   
                                <h4>Προσθέστε το σχόλιο σας:</h4>
                                <textarea id="com" rows="7" cols="60" placeholder="Γράψτε το σχόλιο σας εδώ ..." required><?php echo $row['comment']; ?></textarea><br><br>                         
                                <h4>Βαθμολογήστε το ξενοδοχείο:</h4>
                                <input id="stars" data-show-clear="false" type="number" value="<?php echo $row['rate']; ?>" class="rating" min=0 max=5 step=1><br><br>                                
                                <label>Ημερομηνία Καταχώρησης (yyyy-mm-dd hh:mm:ss):</label>
                                <br><br>
                                <div style="margin: 0 0 0 222;" class="col-sm-4">
                                    <input type="text" class="form-control" id="date" name="date" value="<?= $row['date'] ?>" required>
                                </div>                                                
                                <br><br><br><br><br>
                                <button type="submit" onclick="saveComment();" class="btn btn-default">Αποθήκευση</button> 
                                <button onclick="deleteComment();
                                            return false;" class="btn btn-danger">Διαγραφή σχολίου</button> 
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
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}    