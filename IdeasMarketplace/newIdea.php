<?php
include 'functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] > 1) {
    if (isset($_POST['name'])) {
        $conn = sqldb();
        $sql = 'INSERT INTO auctions (name,description,bid_start,bid_min,buy_price,offer_start,offer_end) VALUES ("' . $_POST['name'] . '" ,"' . $_POST['description'] . '" ,' . $_POST['bid_start'] . ' ,' . $_POST['bid_min'] . ' ,' . $_POST['buy_price'] . ' ,"' . $_POST['start'] . '" ,"' . $_POST['end'] . '")';
        $result = $conn->query($sql);
        $sql = 'INSERT INTO belongs (user_id,auction_id) VALUES ("' . $_SESSION['id'] . '" ,"' . $conn->insert_id . '")';
        $result = $conn->query($sql);
        $conn->close();
        header('Location: ' . "http://$_SERVER[HTTP_HOST]/creator/");
        die();
    } else {
        ?>
        <html>
            <head>
                <title>Προσθήκη Νέας Δημοπρασίας Ιδέας</title>
                <?php styles(); ?>
                <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.css">
                <script src="/js/jquery.datetimepicker.js"></script>
            </head> 
            <body>
                <?php show_header(); ?>
                <div class="container">
                    <div class="row">
                        <div class="box">
                            <center>
                                <h2>Προσθήκη Νέας Δημοπρασίας Ιδέας</h2>
                                <a href="/creator">Επιστροφή στις δημοπρασίες ιδεών</a>
                            </center>
                            <br> <br>
                            <form class="form-horizontal" method='POST'>
                                <div class="form-group">
                                    <label for="name" class="col-sm-4 control-label">Όνομα</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Όνομα ιδέας ..." required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="desc" class="col-sm-4 control-label">Περιγραφή</label>
                                    <div class="col-sm-7">
                                        <textarea rows="5" cols="50" class="form-control" id="desc" name="description" placeholder="Περιγραφή ιδέας ..." required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="bid_start" class="col-sm-4 control-label">Προσφορά Εκκίνησης</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" id="bid_start" name="bid_start" min="1" max="100" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="bid_min" class="col-sm-4 control-label">Ελάχιστη Προσφορά</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" id="bid_min" name="bid_min" min="1" max="30" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="buy_price" class="col-sm-4 control-label">Τιμή Εξαγοράς</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" id="buy_price" name="buy_price" min="10" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="start" class="col-sm-4 control-label">Ημερομηνία Εκκίνησης</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="start" name="start" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="end" class="col-sm-4 control-label">Ημερομηνία Λήξης</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="end" name="end" required>
                                    </div>
                                </div>          
                                <br><br><br><br>
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-7">
                                        <button type="submit" class="btn btn-default">Αποθήκευση</button>
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
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
}