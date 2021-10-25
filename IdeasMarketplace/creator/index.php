<?php
include '../functions.php';
if (isset($_SESSION['type']) && $_SESSION['type'] == 2) {
    $conn = sqldb();
    $ok = true;
    $sql = 'SELECT auction_id FROM belongs WHERE user_id=' . $_SESSION['id'];
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
// output data of each row
        while ($row = $res->fetch_assoc()) {
            $sql = 'SELECT id,name,description,offer_start,offer_end,
(SELECT MAX(bid) FROM bids WHERE auction=id) as max,
(SELECT first_name FROM bids,users
 WHERE auction=auctions.id AND users.id=bids.user ORDER BY bid DESC LIMIT 1)
 as winner FROM auctions WHERE id=' . $row['auction_id'];
            $result[] = $conn->query($sql);
        }
    } else {
        $ok = false;
    }
    $conn->close();
    ?>   
    <html>
        <head>
            <title>Διαχείριση Ιδεών</title>
            <?php styles(); ?>
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
            <div class="container" style="width: 1300px;">
                <div class="row">
                    <div class="box">
                        <table class="table table-hover">
                            <?php if ($ok == true) { ?>
                                <thead>
                                    <tr>
                                        <th>A/A</th>
                                        <th>Id</th>
                                        <th>Όνομα</th>
                                        <th>Περιγραφή</th>
                                        <th>Ημερομηνία εκκίνησης</th>
                                        <th>Ημερομηνία λήξης</th>
                                        <th>Τρέχουσα μεγαλύτερη προσφορά</th>
                                        <th>Όνομα νικητή</th>
                                        <th>Επεξεργασία</th>
                                    </tr>
                                </thead>
                                <?php
                                $counter = 1;
                                foreach ($result as $value) {
                                    if ($value->num_rows > 0) {
                                        while ($row = $value->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $counter . "</td>";
                                            echo "<td>" . $row["id"] . "</td>";
                                            echo "<td class='elipsis'>" . $row["name"] . "</td>";
                                            echo "<td class='elipsis'>" . $row["description"] . "</td>";
                                            echo "<td>" . $row["offer_start"] . "</td>";
                                            echo "<td>" . $row["offer_end"] . "</td>";
                                            if ($row["max"] == "") {
                                                echo "<td>------</td>";
                                            } else {
                                                echo "<td>" . $row["max"] . "</td>";
                                            }
                                            if ($row["winner"] == "") {
                                                echo "<td>------</td>";
                                            } else {
                                                echo "<td>" . $row["winner"] . "</td>";
                                            }
                                            echo '<td><a href="idea_edit.php?id=' . $row["id"] . '">Επεξεργασία</a></td>';
                                            echo "</tr>";
                                            $counter++;
                                        }
                                    }
                                }
                            } else {
                                echo "<center><h1>Δεν υπάρχουν δημοπρασίες ιδεών για διαχείριση, πρέπει πρώτα να εισάγετε !</h1></center>";
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