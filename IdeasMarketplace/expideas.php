<?php
include 'functions.php';
$conn = sqldb();
$sql = 'SELECT id,name,winner FROM auctions WHERE ((now()>offer_end) OR (winner!=0))';
$result = $conn->query($sql);
$conn->close();
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Marketplace Ιδεών</title>
        <?php styles(); ?>
        <style>
            .container {
                width:1200px;
            }
        </style>
    </head>
    <body>
        <?php show_header(); ?>
        <div class="brand">Marketplace Ιδεών</div>
        <div class="address-bar">ΠΛηροφορηση & ανταλλαγη ιδεων</div>
        <div class="container">
            <?php
            if ($result->num_rows > 0) {
                ?>
                <div class="row">
                    <div class="box">
                        <div class="col-lg-12">
                            <h2 class="intro-text text-center"><b>Κερδισμενες - Ληγμενες Δημοπρασιες Ιδεων</b></h2>
                            <hr>
                        </div>
                    </div>
                </div>
                <?php
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="row">
                        <div class="box">
                            <div class="col-lg-12">         
                                <p><a href="/idea.php?id=<?= $row['id']; ?>"><img class="img-responsive img-border img-left" style="width:280px;height: 250px;" src="img/idea.JPG" alt=""><b><?= $row['name']; ?></b></a>
                                    <br>
                                    <?php
                                    if ($row['winner'] == 0) {
                                        echo "<p>ΛΗΓΜΕΝΗ</p>";
                                    } else {
                                        echo "<p>ΕΞΑΓΟΡΑΣΜΕΝΗ</p>";
                                    }
                                    ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php show_footer(); ?>
</body>
</html>