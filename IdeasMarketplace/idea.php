<?php
if (isset($_GET['id'])) {
    include 'functions.php';
    $conn = sqldb();
    $sql = 'SELECT AVG(rate) as "mesos_oros" FROM comments WHERE auction_id=' . $_GET['id'];
    $mesos = $conn->query($sql);
    $mo = $mesos->fetch_assoc();
    $sql = 'SELECT id,user_id,comment,rate,date FROM comments WHERE auction_id=' . $_GET['id'] . ' ORDER BY date DESC';
    $result1 = $conn->query($sql);
    if (isset($_SESSION['id'])) {
        $sql = 'SELECT * FROM comments WHERE user_id=' . $_SESSION['id'] . ' AND auction_id=' . $_GET['id'];
        $result3 = $conn->query($sql);
    }
    $sql = 'SELECT email FROM users WHERE id=(SELECT user_id FROM belongs WHERE auction_id=' . $_GET['id'] . ')';
    $result4 = $conn->query($sql);
    $row4 = $result4->fetch_assoc();
    $i = 0;
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            $sql = 'SELECT id,first_name,last_name,img_temp FROM users WHERE id=' . $row['user_id'];
            $result2 = $conn->query($sql);
            $row1 = $result2->fetch_assoc();
            $info[$i] = array();
            $info[$i]['id'] = $row1['id'];
            $info[$i]['comment_id'] = $row['id'];
            $info[$i]['img_temp'] = $row1['img_temp'];
            $info[$i]['first_name'] = $row1['first_name'];
            $info[$i]['last_name'] = $row1['last_name'];
            $info[$i]['comment'] = $row['comment'];
            $info[$i]['rate'] = $row['rate'];
            $info[$i]['date'] = $row['date'];
            $i++;
        }
    }
    $sql = 'SELECT id,user_id,question,date FROM questions WHERE auction_id=' . $_GET['id'] . ' ORDER BY date DESC';
    $result5 = $conn->query($sql);
    $i = 0;
    if ($result5->num_rows > 0) {
        while ($row = $result5->fetch_assoc()) {
            $sql = 'SELECT id,first_name,last_name,img_temp FROM users WHERE id=' . $row['user_id'];
            $result2 = $conn->query($sql);
            $row1 = $result2->fetch_assoc();
            $questions[$i] = array();
            $questions[$i]['user_id'] = $row1['id'];
            $questions[$i]['img_temp'] = $row1['img_temp'];
            $questions[$i]['first_name'] = $row1['first_name'];
            $questions[$i]['last_name'] = $row1['last_name'];
            $questions[$i]['question_id'] = $row['id'];
            $questions[$i]['question'] = $row['question'];
            $questions[$i]['date'] = $row['date'];
            $sql = 'SELECT id,user_id,answer,date FROM answers WHERE question_id=' . $row['id'] . ' ORDER BY date ASC';
            $result6 = $conn->query($sql);
            if ($result6->num_rows > 0) {
                $j = 0;
                while ($row = $result6->fetch_assoc()) {
                    $sql = 'SELECT id,first_name,last_name,img_temp FROM users WHERE id=' . $row['user_id'];
                    $result2 = $conn->query($sql);
                    $row1 = $result2->fetch_assoc();
                    $questions[$i]['answers'][$j] = array();
                    $questions[$i]['answers'][$j]['user_id'] = $row1['id'];
                    $questions[$i]['answers'][$j]['img_temp'] = $row1['img_temp'];
                    $questions[$i]['answers'][$j]['first_name'] = $row1['first_name'];
                    $questions[$i]['answers'][$j]['last_name'] = $row1['last_name'];
                    $questions[$i]['answers'][$j]['answer_id'] = $row['id'];
                    $questions[$i]['answers'][$j]['answer'] = $row['answer'];
                    $questions[$i]['answers'][$j]['date'] = $row['date'];
                    $j++;
                }
            }
            $i++;
        }
    }
    $sql = 'SELECT name,offer_start,offer_end,winner,description,bid_start,bid_min,buy_price,unix_timestamp(offer_end) as end,'
            . ' (SELECT MAX(bid) FROM bids WHERE auction=auctions.id) as current'
            . ' FROM auctions WHERE id=' . $_GET['id'];
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row['current'] = ((!$row['current']) ? 0 : $row['current']);
        $mine = -1;
        if (isset($_SESSION['id'])) {
            $sql = 'SELECT bid FROM bids WHERE user=' . $_SESSION['id'] . ' AND auction=' . $_GET['id'];
            $result = $conn->query($sql);
            $row2 = $result->fetch_assoc();
            if ($row2['bid']) {
                $mine = $row2['bid'];
            }
        }
        $conn->close();
        ?>
        <html>
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="description" content="">
                <meta name="author" content="">
                <title><?= $row['name']; ?></title>
                <?php styles(); ?>
                <link href="css/auction.css" rel="stylesheet">
                <link href="css/jquery.classycountdown.min.css" rel="stylesheet">
                <link href="/css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />
                <script src="js/jquery.classycountdown.min.js"></script>
                <script src="js/jquery.knob.js"></script>
                <script src="js/jquery.throttle.js"></script>
                <script src="js/star-rating.min.js"></script>
                <style>
                    img.hot_rev{
                        height:280px;
                        width:250px;
                    }
                </style>
                <script>
        <?php if (isset($_SESSION['id'])) { ?>
                        function addComment() {
                            $.post("comment_insert.php", {user_id: <?php echo $_SESSION['id']; ?>, auction_id: <?php echo $_GET['id']; ?>, comment: $("#com").val(), rate: $("#stars").val()})
                                    .done(function (data) {
                                        location.reload();
                                    });
                        }
                        function addQuestion() {
                            $.post("question_insert.php", {user_id: <?php echo $_SESSION['id']; ?>, auction_id: <?php echo $_GET['id']; ?>, question: $("#que").val()})
                                    .done(function (data) {
                                        location.reload();
                                    });
                        }
                        function addAnswer(x) {
                            $.post("answer_insert.php", {user_id: <?php echo $_SESSION['id']; ?>, auction_id: <?php echo $_GET['id']; ?>, question_id: $("#q" + x).val(), answer: $("#answe" + x).val()})
                                    .done(function (data) {
                                        location.reload();
                                    });
                        }
                        function showMe(x) {
                            $("#quest").hide();
                            $("#b" + x).hide();
                            $("#" + x).show();
                        }
                        function editComment(x) {
                            $("#vathmos" + x).hide();
                            $("#sxolio" + x).hide();
                            $("#epexergasia" + x).show();
                        }
                        function updateComment(x) {
                            $.post("update_comment.php", {id: $("#comid" + x).val(), comment: $("#com" + x).val(), rate: $("#stars" + x).val()})
                                    .done(function (data) {
                                        location.reload();
                                    });
                        }
                        function deleteComment(x) {
                            $.post("delete_comment.php", {id: x})
                                    .done(function (data) {
                                        location.reload();
                                    });
                        }
                        function editQuestion(x) {
                            $("#que" + x).hide();
                            $("#epex_erw" + x).show();
                        }
                        function updateQuestion(x) {
                            $.post("update_question.php", {id: $("#queid" + x).val(), question: $("#question" + x).val()})
                                    .done(function (data) {
                                        location.reload();
                                    });
                        }
                        function deleteQuestion(x) {
                            $.post("delete_question.php", {id: x})
                                    .done(function (data) {
                                        location.reload();
                                    });
                        }
                        function editAnswer(x) {
                            $("#answ" + x).hide();
                            $("#epex_ap" + x).show();
                        }
                        function updateAnswer(x) {
                            $.post("update_answer.php", {id: $("#answid" + x).val(), answer: $("#answer" + x).val()})
                                    .done(function (data) {
                                        location.reload();
                                    });
                        }
                        function deleteAnswer(x) {
                            $.post("delete_answer.php", {id: x})
                                    .done(function (data) {
                                        location.reload();
                                    });
                        }
                        function updateCommentCancel(x) {
                            $("#vathmos" + x).show();
                            $("#sxolio" + x).show();
                            $("#epexergasia" + x).hide();
                        }
                        function updateQuestionCancel(x) {
                            $("#que" + x).show();
                            $("#epex_erw" + x).hide();
                        }
                        function updateAnswerCancel(x) {
                            $("#answ" + x).show();
                            $("#epex_ap" + x).hide();
                        }
                        function addAnswerCancel(x) {
                            $("#quest").show();
                            $("#b" + x).show();
                            $("#" + x).hide();
                        }
        <?php } ?>
                </script> 
            </head>
            <body>
                <?php show_header(); ?>
                <div class="brand">Marketplace Ιδεων</div>
                <div class="address-bar">ΠΛηροφορηση & ανταλλαγη ιδεων</div>
                <div class="container">
                    <div class="row">
                        <div class="box">
                            <div class="col-lg-12">
                                <h2 class="intro-text text-center"><?= $row['name']; ?></h2>
                                <hr>
                                <div class="col-lg-12">                                                    
                                    <?php if ($row['current'] == $row['buy_price']) { ?>
                                        <img class="img-responsive img-border img-left hot_rev" src="img/idea.JPG" alt="">
                                        <p><?= $row['description']; ?>
                                        </p><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>                      
                                        <?php
                                        if (isset($_SESSION['id'])) {
                                            if ($row['winner'] == $_SESSION['id']) {
                                                ?>
                                                <center><h1>ΣΥΓΧΑΡΗΤΗΡΙΑ ΑΥΤΗ Η ΔΗΜΟΠΡΑΣΙΑ ΕΧΕΙ ΗΔΗ ΕΞΑΓΟΡΑΣΘΕΙ ΕΠΙΤΥΧΩΣ ΑΠΟ ΕΣΑΣ !</h1></center>
                                            <?php } else { ?>
                                                <center><h1>ΛΥΠΟΥΜΑΣΤΕ ΑΥΤΗ Η ΔΗΜΟΠΡΑΣΙΑ ΕΧΕΙ ΗΔΗ ΕΞΑΓΟΡΑΣΘΕΙ ΑΠΟ ΑΛΛΟΝ !</h1></center>
                                                <?php
                                            }
                                        } else if ($row['winner'] != 0) {
                                            ?>
                                            <center><h1>ΛΥΠΟΥΜΑΣΤΕ ΑΥΤΗ Η ΔΗΜΟΠΡΑΣΙΑ ΕΧΕΙ ΗΔΗ ΕΞΑΓΟΡΑΣΘΕΙ !</h1></center>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div id="countdown" style="  margin: 0 auto 0 auto;
                                             max-width: 800px;
                                             width: calc(100%);
                                             padding: 30px;
                                             display: block;"></div><br>
                                        <img class="img-responsive img-border img-left hot_rev" src="img/idea.JPG" alt="">
                                        <p><?= $row['description']; ?></p><br><br><br><br><br>
                                        <div  id="check" style="display: none;">
                                            <center><h1> ΣΥΓΧΑΡΗΤΗΡΙΑ ΑΥΤΗ Η ΔΗΜΟΠΡΑΣΙΑ ΜΟΛΙΣ ΕΞΑΓΟΡΑΣΘΗΚΕ ΕΠΙΤΥΧΩΣ ΑΠΟ ΕΣΑΣ !</h1></center>
                                        </div>
                                        <div  id="checkfail" style="display: none;">
                                            <center><h1>ΛΥΠΟΥΜΑΣΤΕ ΑΥΤΗ Η ΔΗΜΟΠΡΑΣΙΑ ΜΟΛΙΣ ΕΞΑΓΟΡΑΣΘΗΚΕ ΑΠΟ ΑΛΛΟΝ !</h1></center>
                                        </div>
                                        <br><br><br><br><br><br><br><br><br><br>
                                        <center>
                                            <h2><b id="choice1" style="display:none;">ΛΥΠΟΥΜΑΣΤΕ Η ΔΗΜΟΠΡΑΣΙΑ ΕΛΗΞΕ ΚΑΙ ΔΕΝ ΚΕΡΔΗΘΗΚΕ ΑΠΟ ΚΑΝΕΝΑΝ !</b>
                                                <b id="choice2" style="display:none;">ΣΥΓΧΑΡΗΤΗΡΙΑ Η ΔΗΜΟΠΡΑΣΙΑ ΕΛΗΞΕ ΚΑΙ ΚΕΡΔΗΘΗΚΕ ΑΠΟ ΕΣΑΣ !</b>
                                                <b id="choice3" style="display:none;">ΛΥΠΟΥΜΑΣΤΕ Η ΔΗΜΟΠΡΑΣΙΑ ΕΛΗΞΕ ΚΑΙ ΚΕΡΔΗΘΗΚΕ ΑΠΟ ΑΛΛΟΝ !</b>
                                                <b id="choice4" style="display:none;">ΛΥΠΟΥΜΑΣΤΕ Η ΔΗΜΟΠΡΑΣΙΑ ΕΧΕΙ ΛΗΞΕΙ !</b>
                                            </h2>
                                        </center>
                                        <div id="info">                                            
                                            <h2>Τρέχουσα υψηλότερη προσφορά: <b id="current"><?= $row['current']; ?></b> <b id="win" <?php echo (($row['current'] == $mine) ? '' : 'style="display:none;"'); ?>> - Αυτή τη στιγμή κερδίζετε !</b> <b id="lose" <?php echo (($row['current'] == $mine) ? '' : 'style="display:none;"'); ?>> - Αυτή τη στιγμή κερδίζει άλλος !</b> <b id="none" <?php echo (($row['current'] == $mine) ? '' : 'style="display:none;"'); ?>> - Η δημοπρασία δεν έχει ακόμα "χτυπηθεί" από κανέναν !</b></h2> 
                                            <h3>Τιμή εξαγοράς: <b id="current"><?= $row['buy_price']; ?></b></h3> 
                                            <h3>Ελάχιστη προσφορά: <input type="number" id="bid" value="<?= $row['current'] + $row['bid_min']; ?>" min="<?= $row['current'] + $row['bid_min']; ?>" max="<?= $row['buy_price']; ?>"><a href="#" onclick="event.preventDefault();
                                                                bid()" class="button2">Κατάθεση</a></h3><br>                                   
                                            <h4>Περίοδος ενεργοποίησης δημοπρασίας: <u>από</u> <?= $row['offer_start']; ?> <u>έως</u> <?= $row['offer_end']; ?></h4>
                                            <h4>Μπορείτε να επικοινωνήσετε μαζί μου προσωπικά στο εξής e-mail: <?= $row4['email'] ?></h4>
                                        </div>
                                        <?php
                                    }
                                    ?> 
                                    <br><br>      
                                    <h2>Μέσος όρος βαθμολογίας ιδέας: <?php echo "(" . floatval($mo['mesos_oros']) . ")"; ?></h2>
                                    <h2>Σχόλια: (<?php
                                        if (isset($info)) {
                                            echo count($info);
                                        } else {
                                            echo "0";
                                        }
                                        ?>)</h2><br><br>
                                    <div id = "comments" class = "form-group">
                                        <?php
                                        if (isset($info)) {
                                            for ($i = 0; $i < count($info); $i++) {
                                                if ($info[$i]['img_temp'] == 1) {
                                                    ?>
                                                    <div>
                                                        <img style="height: 140px;width: 130px;float: left;margin : 0 20 20 20;" src="image_user.php?id=<?= $info[$i]['id'] ?>">
                                                    <?php } else {
                                                        ?>
                                                        <img style="height: 140px;width: 130px;float: left;margin : 0 20 20 20;" src="img/no_image.jpg">
                                                        <?php
                                                    }
                                                    echo "<h3>Σχόλιο από τον χρήστη " . $info[$i]['first_name'] . " " . $info[$i]['last_name'] . "</h3>";
                                                    echo "<h4>Ημερομηνία Καταχώρησης: " . $info[$i]['date'] . "</h4>";
                                                    ?>                                                
                                                    <?php
                                                    if (isset($_SESSION['id'])) {
                                                        if ($info[$i]['id'] == $_SESSION['id']) {
                                                            ?>
                                                            <button id="edit" class="btn btn-default" onclick="editComment(<?= $i ?>)">Επεξεργασία</button>   
                                                            <button id="delete"  class="btn btn-danger" onclick="deleteComment(<?= $info[$i]['comment_id'] ?>);">Διαγραφή</button> 
                                                            <?php
                                                        }
                                                    } else {
                                                        echo "<br>";
                                                    }
                                                    ?>
                                                    <h4 id="vathmos<?= $i ?>">Βαθμολογία που έβαλε ο χρήστης: <?php echo "(" . $info[$i]['rate'] . ")"; ?>  <input class="rating" data-show-clear="false" data-show-caption="false" data-disabled="true" data-size="xs" value="<?php echo $info[$i]['rate']; ?>"></h4>                            
                                                    <?php
                                                    echo "<h4 id='sxolio$i'>Σχόλιο: " . $info[$i]['comment'] . "</h4></img></div><br><br><br>";
                                                    if (isset($_SESSION['id'])) {
                                                        if ($info[$i]['id'] == $_SESSION['id']) {
                                                            ?>
                                                            <div id="epexergasia<?= $i ?>" style="display: none;">
                                                                <center>
                                                                    <form method='post' onsubmit="updateComment(<?= $i ?>);
                                                                                                    return false;">
                                                                        <h4>Επεξεργαστείτε το σχόλιο σας και την βαθμολογία σας</h4>
                                                                        <div id="opinion">
                                                                            <input id="comid<?= $i ?>" type="hidden" value="<?= $info[$i]['comment_id'] ?>">
                                                                            <textarea id = "com<?= $i ?>" rows = "7" cols = "60" placeholder = "Γράψτε το σχόλιο σας εδώ ..." required><?= $info[$i]['comment'] ?></textarea><br>
                                                                            <h4>Βαθμολογήστε την ιδέα:</h4>
                                                                            <input id = "stars<?= $i ?>" value="<?php echo $info[$i]['rate']; ?>" data-show-clear = "false" type = "number" class = "rating" min = 0 max = 5 step = 1>
                                                                            <br>
                                                                            <input class='btn btn-default' type = 'submit' value = 'Ενημέρωση'/>
                                                                            <input type='button'  class="btn btn-danger" onclick="updateCommentCancel(<?= $i ?>);" value='Άκυρο'>
                                                                        </div>
                                                                    </form>
                                                                </center>
                                                            </div>                                   
                                                            <?php
                                                        }
                                                    }
                                                }
                                            } else {
                                                echo "<center><h3>Δεν υπάρχουν σχόλια για την συγκεκριμένη ιδέα !</h3></center><br><br><br>";
                                            }
                                            ?>
                                        </div>
                                        <?php if (isset($_SESSION['id']) && ($result3->num_rows == 0)) { ?><br>
                                            <center>
                                                <form method='post' onsubmit="addComment(
                                                                            );
                                                                    return false;">
                                                    <h4>Προσθέστε τη γνώμη σας και την βαθμολογία σας για την παραπάνω ιδέα:(<u>μπορείτε να ψηφίσετε μόνο μια φορά)</u></h4>
                                                    <div id="opinion">
                                                        <textarea id = "com" rows = "7" cols = "60" placeholder = "Γράψτε το σχόλιο σας εδώ ..." required></textarea><br>
                                                        <h4>Βαθμολογήστε την ιδέα:</h4>
                                                        <input id = "stars" data-show-clear = "false" type = "number" class = "rating" min = 0 max = 5 step = 1>
                                                        <br>
                                                        <input class='btn btn-default' type = 'submit' value = 'Προσθήκη'/>
                                                    </div>
                                                </form>
                                            </center>
                                        <?php } ?>
                                    </div>
                                    <div id = "questions" class = "form-group">      
                                        <h2><center>Εδώ μπορείτε να μου κάνετε ερωτήσεις σχετικά με την ιδέα μου</center></h2><br><br>
                                        <?php
                                        $answ = 0;
                                        if (isset($questions)) {
                                            for ($i = 0; $i < count($questions); $i++) {
                                                if ($questions[$i]['img_temp'] == 1) {
                                                    ?>
                                                    <div>
                                                        <img style="height: 140px;width: 130px;float: left;margin : 0 20 20 20;" src="image_user.php?id=<?= $questions[$i]['user_id'] ?>">
                                                    <?php } else {
                                                        ?>
                                                        <img style="height: 140px;width: 130px;float: left;margin : 0 20 20 20;" src="img/no_image.jpg">
                                                        <?php
                                                    }
                                                    echo "<h3>Ερώτηση από τον χρήστη " . $questions[$i]['first_name'] . " " . $questions[$i]['last_name'] . "</h3>";
                                                    echo "<h4>Ημερομηνία Καταχώρησης: " . $questions[$i]['date'] . "</h4><br>";
                                                    if (isset($_SESSION['id'])) {
                                                        if ($questions[$i]['user_id'] == $_SESSION['id']) {
                                                            ?>
                                                            <button id="edit" class="btn btn-default" onclick="editQuestion(<?= $i ?>)">Επεξεργασία</button>   
                                                            <button id="delete"  class="btn btn-danger" onclick="deleteQuestion(<?= $questions[$i]['question_id'] ?>);">Διαγραφή</button><br><br><br>                                                    
                                                            <?php
                                                        }
                                                    }
                                                    echo "<h4 id='que$i'>" . $questions[$i]['question'] . "</h4></div><br>";
                                                    if (isset($_SESSION['id'])) {
                                                        if ($questions[$i]['user_id'] == $_SESSION['id']) {
                                                            ?>
                                                            <div id="epex_erw<?= $i ?>" style="display: none;">
                                                                <center>
                                                                    <form method='post' onsubmit="updateQuestion(<?= $i ?>);
                                                                                                    return false;">
                                                                        <input id="queid<?= $i ?>" type="hidden" value="<?= $questions[$i]['question_id'] ?>">
                                                                        <h4>Επεξεργαστείτε την ερώτηση σας για την παραπάνω ιδέα:</h4>
                                                                        <textarea id = "question<?= $i ?>" rows = "7" cols = "60" placeholder = "Γράψτε την ερώτηση σας εδώ ..." required><?= $questions[$i]['question'] ?></textarea><br>                        
                                                                        <br>
                                                                        <input class='btn btn-default' type = 'submit' value = 'Ενημέρωση'/>   
                                                                        <input type='button' class="btn btn-danger" onclick="updateQuestionCancel(<?= $i ?>);" value="Άκυρο"> 
                                                                    </form>
                                                                </center>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    if (isset($questions[$i]['answers'])) {
                                                        echo "<div name='answer' style='margin: 0 0 0 100;'>";
                                                        for ($j = 0; $j < count($questions[$i]['answers']); $j++) {
                                                            if ($questions[$i]['answers'][$j]['img_temp'] == 1) {
                                                                ?>                 
                                                                <img style="height: 140px;width: 130px;float: left;margin : 0 20 20 20;" src="image_user.php?id=<?= $questions[$i]['answers'][$j]['user_id'] ?>">
                                                            <?php } else {
                                                                ?>
                                                                <img style="height: 140px;width: 130px;float: left;margin : 0 20 20 20;" src="img/no_image.jpg">
                                                                <?php
                                                            }
                                                            echo "<h3>Απάντηση από τον χρήστη " . $questions[$i]['answers'][$j]['first_name'] . " " . $questions[$i]['answers'][$j]['last_name'] . "</h3>";
                                                            echo "<h4>Ημερομηνία Καταχώρησης: " . $questions[$i]['answers'][$j]['date'] . "</h4><br>";
                                                            if (isset($_SESSION['id'])) {
                                                                if ($questions[$i]['answers'][$j]['user_id'] == $_SESSION['id']) {
                                                                    ?>
                                                                    <button id="edit" class="btn btn-default" onclick="editAnswer(<?= $answ ?>)">Επεξεργασία</button>   
                                                                    <button id="delete"  class="btn btn-danger" onclick="deleteAnswer(<?= $questions[$i]['answers'][$j]['answer_id'] ?>);">Διαγραφή</button> 
                                                                    <?php
                                                                }
                                                            }
                                                            echo "<h4 id='answ$answ'>" . $questions[$i]['answers'][$j]['answer'] . "</h4><br><br><br>";
                                                            if (isset($_SESSION['id'])) {
                                                                if ($questions[$i]['answers'][$j]['user_id'] == $_SESSION['id']) {
                                                                    ?>
                                                                    <div id="epex_ap<?= $answ ?>" style="display: none;">
                                                                        <center>
                                                                            <form method='post' onsubmit="updateAnswer(<?= $answ ?>);
                                                                                                                    return false;">
                                                                                <input id="answid<?= $answ ?>" type="hidden" value="<?= $questions[$i]['answers'][$j]['answer_id'] ?>">
                                                                                <h4>Επεξεργαστείτε την απάντηση σας για την παραπάνω ιδέα:</h4>
                                                                                <textarea id = "answer<?= $answ ?>" rows = "7" cols = "60" placeholder = "Γράψτε την απάντηση σας εδώ ..." required><?= $questions[$i]['answers'][$j]['answer'] ?></textarea><br>                        
                                                                                <br>
                                                                                <input class='btn btn-default' type = 'submit' value = 'Ενημέρωση'/>                                                          
                                                                                <input type='button' class="btn btn-danger" onclick="updateAnswerCancel(<?= $answ ?>);" value="Άκυρο"> 
                                                                            </form>
                                                                        </center>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                            $answ++;
                                                        }
                                                        echo "</div>";
                                                    } else {
                                                        echo "<br><h3><center>Δεν υπάρχουν απαντήσεις για την συγκεκριμένη ερώτηση !</center></h3><br><br><br>";
                                                    }
                                                    if (isset($_SESSION['id'])) {
                                                        ?> 
                                                        <div id="<?= $i ?>" style="display:none;">    
                                                            <center>
                                                                <form method='post' onsubmit="addAnswer(<?= $i ?>);

                                                                                            return false;">
                                                                    <input id="q<?= $i ?>" type="hidden" value="<?= $questions[$i]['question_id'] ?>"/>
                                                                    <h4>Προσθέστε την απάντηση σας για την παραπάνω ερώτηση:</h4>
                                                                    <textarea id = "answe<?= $i ?>" rows = "7" cols = "60" placeholder = "Γράψτε την απάντηση σας εδώ ..." required></textarea><br>                        
                                                                    <br>
                                                                    <input class='btn btn-default' type = 'submit' value = 'Προσθήκη'/>
                                                                    <input type='button' class="btn btn-danger" onclick="addAnswerCancel(<?= $i ?>);" value="Άκυρο"> 
                                                                </form>
                                                            </center>  
                                                        </div>
                                                        <center><button id="b<?= $i ?>" class="btn btn-default" onclick="showMe(<?= $i ?>)">Απάντηση</button></center><br><br>
                                                        <?php
                                                    }
                                                    echo "<br><br><br><br><br>";
                                                }
                                            } else {
                                                echo "<br><br><br><h3><center>Δεν υπάρχουν ερωτήσεις για την συγκεκριμένη ιδέα !</center></h3><br><br><br>";
                                            }
                                            if (isset($_SESSION['id'])) {
                                                ?>
                                                <div id="quest">
                                                    <center>
                                                        <form method='post' onsubmit="addQuestion();

                                                                            return false;">
                                                            <h4>Προσθέστε την ερώτηση σας για την παραπάνω ιδέα:</h4>
                                                            <textarea id = "que" rows = "7" cols = "60" placeholder = "Γράψτε την ερώτηση σας εδώ ..." required></textarea><br>                        
                                                            <br>
                                                            <input class='btn btn-default' type = 'submit' value = 'Προσθήκη'/>                                                          
                                                        </form>
                                                    </center>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $('#countdown').ClassyCountdown({
                                end: new Date(<?= $row['end']; ?>).getTime(),
                                now: +new Date().getTime() / 1000,
                                labels: true,
                                style: {
                                    element: "",
                                    textResponsive: .5,
                                    days: {
                                        gauge: {
                                            thickness: .01,
                                            bgColor: "rgba(0,0,0,0.05)",
                                            fgColor: "#1abc9c"
                                        },
                                        textCSS: 'font-family:\'Open Sans\'; font-size:25px; font-weight:300; color:#34495e;'
                                    },
                                    hours: {
                                        gauge: {
                                            thickness: .01,
                                            bgColor: "rgba(0,0,0,0.05)",
                                            fgColor: "#2980b9"
                                        },
                                        textCSS: 'font-family:\'Open Sans\'; font-size:25px; font-weight:300; color:#34495e;'},
                                    minutes: {
                                        gauge: {
                                            thickness: .01,
                                            bgColor: "rgba(0,0,0,0.05)",
                                            fgColor: "#8e44ad"
                                        },
                                        textCSS: 'font-family:\'Open Sans\'; font-size:25px; font-weight:300; color:#34495e;'},
                                    seconds: {
                                        gauge: {
                                            thickness: .01,
                                            bgColor: "rgba(0,0,0,0.05)",
                                            fgColor: "#f39c12"
                                        },
                                        textCSS: 'font-family:\'Open Sans\'; font-size:25px; font-weight:300; color:#34495e;'
                                    }

                                },
                                onEndCallback: function () {
        <?php if (isset($_SESSION['id'])) { ?>
                                        var my_bid;
                                        $.post('last_bid.php', {auction: <?= $_GET['id']; ?>, user: <?= $_SESSION['id']; ?>}, function (data) {
                                            my_bid = parseInt(data);
                                        });
                                        $.post('bidCheck.php', {auction: <?= $_GET['id']; ?>}, function (data) {
                                            var n = parseInt(data);
                                            $('#info').hide();
                                            $('#countdown').hide();
                                            if (n == 0) {
                                                $('#choice1').show();
                                            } else if (n == my_bid) {
                                                $('#choice2').show();
                                                $.post("idea_win.php", {auction: <?= $_GET['id']; ?>, winner: <?= $_SESSION['id'] ?>}).done(function (data) {
                                                });
                                            }
                                            else {
                                                $('#choice3').show();
                                            }
                                        });
        <?php } else { ?>
                                        $('#info').hide();
                                        $('#countdown').hide();
                                        $('#choice4').show();
        <?php } ?>
                                }
                            });
                            var min =<?= $row['bid_min']; ?>;
                            var mine = <?= $mine; ?>;
                            function bid() {
        <?php if (isset($_SESSION['id'])) { ?>
                                    var bid = $("#bid").val();
                                    if (bid ><?= $row['buy_price']; ?>) {
                                        alert("Η τιμή της εξαγοράς είναι <?= $row['buy_price']; ?>!");
                                        return false;
                                    }
                                    else if (bid ==<?= $row['buy_price']; ?>) {
                                        $.post("idea_win.php", {auction: <?= $_GET['id']; ?>, winner: <?= $_SESSION['id'] ?>})
                                                .done(function (data) {
                                                });
                                    }
                                    $.post("bid.php", {auction: <?= $_GET['id']; ?>, bid: bid})
                                            .done(function (data) {
                                                mine = bid;
                                                if (mine ==<?= $row['buy_price']; ?>) {
                                                    $('#info').hide();
                                                    $('#countdown').hide();
                                                    $('#check').show();
                                                }
                                            });
        <?php } else { ?>
                                    window.location = '/login/';
        <?php } ?>
                            }
        <?php if (isset($_SESSION['id'])) { ?>
                                function doPoll() {
                                    $.post('bidCheck.php', {auction: <?= $_GET['id']; ?>}, function (data) {
                                        var n = parseInt(data);
                                        if (n == 0) {
                                            $('#none').show();
                                        } else {
                                            $(' #none').hide();
                                            if ((n != mine) && (n ==<?= $row['buy_price']; ?>)) {
                                                $('#info').hide();
                                                $('#countdown').hide();
                                                $('#checkfail').show();
                                            }
                                            else {
                                                if (n == mine) {
                                                    $("#win").show();
                                                    $("#lose").hide();
                                                }
                                                else {
                                                    $("#win").hide();
                                                    $("#lose").show();
                                                }
                                                $("#current").html(n);
                                                if (n >= $("#bid").val()) {
                                                    $("#bid").val(n + min);
                                                }
                                                $("#bid").attr("min", n + min);
                                            }
                                        }
                                        setTimeout(doPoll, 3000);
                                    });
                                }
                                doPoll();
        <?php } ?>
                        </script>
                        <?php show_footer(); ?>
                        </body>
                        </html>
                        <?php
                    } else {
                        header('Location: ' . "http://$_SERVER[HTTP_HOST]");
                        die();
                    }
                } else {
                    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
                    die();
                }                            