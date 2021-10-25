<?php
include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 2) {
    $conn = sqldb();
    $id = $_GET['id'];
    $sql = 'SELECT user_id FROM belongs WHERE auction_id=(SELECT id FROM auctions WHERE id=' . $id . ')';
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $conn->close();
        header('Location: ' . "http://$_SERVER[HTTP_HOST]/creator");
        die();
    }
    if ($row['user_id'] == $_SESSION['id']) {
        if (isset($_POST['name'])) {
            $sql = 'UPDATE auctions SET name="' . $_POST['name'] . '", description="' . $_POST['description'] . '", bid_start=' . $_POST['bid_start'] . ', bid_min=' . $_POST['bid_min'] . ', buy_price=' . $_POST['buy_price'] . ', offer_start="' . $_POST['start'] . '", offer_end="' . $_POST['end'] . '" WHERE id=' . $id;
            echo $sql;
            $conn->query($sql);
            $conn->close();
            header('Location: ' . "http://$_SERVER[HTTP_HOST]/creator");
            die();
        } else {
            $sql = 'SELECT name,description,bid_start,bid_min,buy_price,offer_start,offer_end FROM auctions WHERE id=' . $id;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $conn->close();
            ?>
            <html>
                <head>
                    <title>Επεξεργασία Δημοπρασίας</title>
                    <?php styles();
                    ?>
                    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css">
                    <script src="/js/jquery.datetimepicker.js"></script>
                    <script>
                        function deleteAuction() {
                            $.post("idea_delete.php", {id: <?= $_GET['id']; ?>})
                                    .done(function (data) {
                                        alert('ΕΠΙΤΥΧΗΣ ΔΙΑΓΡΑΦΗ ΤΗΣ ΔΗΜΟΠΡΑΣΙΑΣ !');
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
                                    <h2>Επεξεργασία Ιδέας</h2>
                                    <a href="/creator">Επιστροφή στις δημοπρασίες μου</a>
                                </center>
                                <br> <br>
                                <form class="form-horizontal" method='post'>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-4 control-label">Όνομα</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>" placeholder="Όνομα" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="desc" class="col-sm-4 control-label">Περιγραφή</label>
                                        <div class="col-sm-7">
                                            <textarea rows="5" cols="50" class="form-control" id="desc" name="description" required><?php echo $row['description']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="bid_start" class="col-sm-4 control-label">Προσφορά Εκκίνησης</label>
                                        <div class="col-sm-2">
                                            <input type="number" class="form-control" id="bid_start" name="bid_start" min="1" max="100" value="<?php echo $row['bid_start']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="bid_min" class="col-sm-4 control-label">Ελάχιστη Προσφορά</label>
                                        <div class="col-sm-2">
                                            <input type="number" class="form-control" id="bid_min" name="bid_min" min="1" max="30" value="<?php echo $row['bid_min']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="buy_price" class="col-sm-4 control-label">Τιμή Εξαγοράς</label>
                                        <div class="col-sm-2">
                                            <input type="number" class="form-control" id="buy_price" name="buy_price" min="10" value="<?php echo $row['buy_price']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="start" class="col-sm-4 control-label">Ημερομηνία Εκκίνησης</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="start" name="start" value="<?php echo $row['offer_start']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="end" class="col-sm-4 control-label">Ημερομηνία Λήξης</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="end" name="end" value="<?php echo $row['offer_end']; ?>" required>
                                        </div>
                                    </div>     
                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-7">
                                            <button type="submit" class="btn btn-default">Αποθήκευση</button>
                                            <button onclick="deleteAuction();
                                                    return false;" class="btn btn-danger">Διαγραφή δημοπρασίας</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script>
                        $('#start').datetimepicker();
                        $('#end').datetimepicker();
                    </script>
                </body>
            </html>
            <?php
        }
    } else {
        header('Location: ' . "http://$_SERVER[HTTP_HOST]/creator");
        die();
    }
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}
?>