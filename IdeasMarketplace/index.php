<?php
include 'functions.php';
$conn = sqldb();
$sql = 'SELECT id,name FROM auctions WHERE ((now()>=offer_start) AND (now()<=offer_end) AND (winner=0))';
$result = $conn->query($sql);
$sql = 'SELECT id,name FROM auctions WHERE ((now()>offer_end) OR (winner!=0))';
$res = $conn->query($sql);
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
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">
        <link href="/css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />
        <script src="/js/star-rating.min.js" type="text/javascript"></script>
        <style>
            img.hot_rev{
                height:180px;
                width:200px;
            }
            .cube {
                display:inline-block;
                margin-left:20px;
                text-align: center;
            }
            .space1{
                padding: 0 0 0 100px;
            }
            .space2{
                padding: 0 0 0 50px;
            }
        </style>
    </head>
    <body>
        <?php show_header(); ?>
        <div class="brand">Marketplace Ιδεών</div>
        <div class="address-bar">ΠΛηροφορηση & ανταλλαγη ιδεων</div>
        <div class="container">
            <div class="row">
                <div class="box">
                    <div class="col-lg-12 text-center">
                        <h2 class="brand-before">
                            <small>Καλώς ήρθατε στην </small>
                        </h2>
                        <h1 class="brand-name">ηλεκτρονική πύλη πληροφόρησης και ανταλλαγής ιδεών</h1>
                        <hr class="tagline-divider">
                        <h2>
                            <small>Project:
                                <strong>Επικοινωνία Ανθρώπου–Υπολογιστή</strong>
                            </small>
                        </h2>
                    </div>
                </div>
            </div>
            <?php if ($result->num_rows > 0) { ?>
                <div class="row">
                    <div class="box space1" >
                        <hr>
                        <h2 class="intro-text text-center"><b><a href="/ideas.php">Τελευταιες Ενεργες Δημοπρασιες Ιδεων</a></b></h2>
                        <hr>
                        <?php
                        $coun = 0;
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="cube">
                                <p><a href="/idea.php?id=<?= $row['id']; ?>"><img class="img-responsive img-border hot_rev" src="/img/lamp.png" />
                                        <br><b><?= mb_strimwidth($row['name'], 0, 20, "..."); ?></b></a>
                                </p>
                                <br><br>
                            </div>
                            <?php
                            $coun++;
                            if ($coun == 4) {
                                echo "<br>";
                                $coun = 0;
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php } if ($res->num_rows > 0) { ?>
                <div class="row">
                    <div class="box space1" >
                        <hr>
                        <h2 class="intro-text text-center"><b><a href="/expideas.php">Κερδισμενες - Ληγμενες Δημοπρασιες Ιδεων</a></b></h2>
                        <hr>
                        <?php
                        $coun = 0;
                        // output data of each row
                        while ($row = $res->fetch_assoc()) {
                            ?>
                            <div class="cube">
                                <p><a href="/idea.php?id=<?= $row['id']; ?>"><img class="img-responsive img-border hot_rev" src="/img/lamp.png" />
                                        <br><b><?= $row['name']; ?></b></a>
                                </p>
                                <br><br>
                            </div>
                            <?php
                            $coun++;
                            if ($coun == 4) {
                                echo "<br>";
                                $coun = 0;
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php show_footer(); ?>
    </body>
</html>
