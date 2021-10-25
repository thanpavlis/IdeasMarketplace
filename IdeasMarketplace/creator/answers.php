<?php
include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 2) {
// Create connection
    $conn = sqldb();
    $sql = "SELECT auction_id FROM belongs WHERE user_id=" . $_SESSION['id'];
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sql = "SELECT id,user_id,auction_id,answer,date FROM answers WHERE auction_id=" . $row['auction_id'];
            ;
            $result1 = $conn->query($sql);
            $i = 0;
            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_assoc()) {
                    $sql = "SELECT id,img_temp,first_name,last_name FROM users WHERE id=" . $row1['user_id'];
                    $res1 = $conn->query($sql);
                    $row2 = $res1->fetch_assoc();
                    $sql = "SELECT name FROM auctions WHERE id=" . $row1['auction_id'];
                    $res2 = $conn->query($sql);
                    $row3 = $res2->fetch_assoc();
                    $info[$i] = array();
                    $info[$i]['id'] = $row1['id'];
                    $info[$i]['username'] = $row2['first_name'] . ' ' . $row2['last_name'];
                    $info[$i]['user_id'] = $row2['id'];
                    $info[$i]['img_temp'] = $row2['img_temp'];
                    $info[$i]['auction_name'] = $row3['name'];
                    $info[$i]['answer'] = $row1['answer'];
                    $info[$i]['date'] = $row1['date'];
                    $i++;
                }
            }
        }
    }
    $conn->close();
    ?>
    <html>
        <head>
            <title>Διαχείριση Απαντήσεων</title>
            <?php styles(); ?>
            <link href="/css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />
            <script src="/js/star-rating.min.js" type="text/javascript"></script>
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
                        <?php if (isset($info)) { ?>
                            <table id="com" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>A/A</th>
                                        <th>Id</th>
                                        <th>Εικόνα Χρήστη</th>
                                        <th>Όνομα Χρήστη</th>
                                        <th>Όνομα Δημοπρασίας</th>
                                        <th>Απάντηση</th>
                                        <th>Ημερομηνία Καταχώρησης<br>(yyyy-mm-dd  hh:mm:ss)</th> 
                                        <th>Επεξεργασία</th> 
                                    </tr>
                                </thead>
                                <?php
                                $counter = 1;
                                for ($i = 0; $i < count($info); $i++) {
                                    echo '<tr>';
                                    echo '<td>' . $counter . '</td>';
                                    echo '<td>' . $info[$i]["id"] . '</td>';
                                    if ($info[$i]["img_temp"] == 1) {
                                        ?>
                                        <td><img style="height: 140px;width: 130px;" src="../image_user.php?id=<?= $info[$i]['user_id'] ?>"></img></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td><img style="height: 140px;width: 130px;" src="../img/no_image.jpg"></img></td>
                                            <?php
                                        }
                                        echo '<td class="elipsis">' . $info[$i]["username"] . '</td>';
                                        echo '<td class="elipsis">' . $info[$i]["auction_name"] . '</td>';
                                        echo '<td class="elipsis">' . $info[$i]["answer"] . '</td>';
                                        echo '<td>' . $info[$i]["date"] . '</td>';
                                        echo '<td><a href="answer_edit.php?id=' . $info[$i]['id'] . '">Επεξεργασία</a></td>';
                                        echo "</tr>";
                                        $counter++;
                                    }
                                } else {
                                    echo "<center><h1>Δεν υπάρχουν απαντήσεις για διαχείριση !</h1></center>";
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