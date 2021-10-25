<?php
include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 2) {
    $conn = sqldb();
    $sql = "SELECT auction_id FROM belongs WHERE user_id=" . $_SESSION['id'];
    $result = $conn->query($sql);
    $i = 0;
    $temp = false;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sql = "SELECT id,user_id,auction_id,comment,rate,date FROM comments WHERE auction_id=" . $row['auction_id'];
            $result1 = $conn->query($sql);
            if ($result1->num_rows > 0) {
                $temp = true;
                while ($row = $result1->fetch_assoc()) {
                    $sql = "SELECT id,img_temp,first_name,last_name FROM users WHERE id=" . $row['user_id'];
                    $res1 = $conn->query($sql);
                    $row1 = $res1->fetch_assoc();
                    $sql = "SELECT name FROM auctions WHERE id=" . $row['auction_id'];
                    $res2 = $conn->query($sql);
                    $row2 = $res2->fetch_assoc();
                    $info[$i] = array();
                    $info[$i]['id'] = $row['id'];
                    $info[$i]['username'] = $row1['first_name'] . ' ' . $row1['last_name'];
                    $info[$i]['user_id'] = $row1['id'];
                    $info[$i]['img_temp'] = $row1['img_temp'];
                    $info[$i]['auction_name'] = $row2['name'];
                    $info[$i]['comment'] = $row['comment'];
                    $info[$i]['rate'] = $row['rate'];
                    $info[$i]['date'] = $row['date'];
                    $i++;
                }
            }
        }
    }
    $conn->close();
    ?>
    <html>
        <head>
            <title>Διαχείριση Σχολίων</title>
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
                        <table id="com" class="table table-hover">
                            <?php if ($temp == true) { ?>
                                <thead>
                                    <tr>
                                        <th>A/A</th>
                                        <th>Id</th>
                                        <th>Εικόνα Χρήστη</th>
                                        <th>Όνομα Χρήστη</th>
                                        <th>Όνομα Δημοπρασίας</th>
                                        <th>Σχόλιο</th>
                                        <th>Βαθμολογία</th> 
                                        <th>Ημερομηνία Καταχώρησης<br>(yyyy-mm-dd  hh:mm:ss)</th> 
                                        <th>Επεξεργασία</th> 
                                    </tr>
                                </thead>
                                <?php
                                if (isset($info)) {
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
                                        echo "<td class='elipsis'>" . $info[$i]["username"] . "</td>";
                                        echo "<td class='elipsis'>" . $info[$i]["auction_name"] . "</td>";
                                        echo '<td class="elipsis">' . $info[$i]["comment"] . '</td>';
                                        ?>
                                        <td><input class="rating" data-show-clear="false" data-show-caption="false" data-disabled="true" data-size="xs" value="<?php echo $info[$i]["rate"]; ?>"> <?php echo str_repeat('&nbsp;', 17) . "<label>(" . $info[$i]['rate'] . ")</label>"; ?> </td>
                                            <?php
                                            echo '<td>' . $info[$i]["date"] . '</td>';
                                            echo '<td><a href="comment_edit.php?id=' . $info[$i]['id'] . '">Επεξεργασία</a></td>';
                                            echo "</tr>";
                                            $counter++;
                                        }
                                    }
                                } else {
                                    echo "<center><h1>Δεν υπάρχουν σχόλια για διαχείριση !</h1></center>";
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