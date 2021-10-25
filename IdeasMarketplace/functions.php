<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

function show_header() {
    if (isset($_SESSION['id'])) {
        $conn = sqldb();
        $sql = "SELECT first_name FROM users WHERE id=" . $_SESSION['id'];
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
        $conn->close();
    }
    ?>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Αρχική</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php
                    if (isset($_SESSION['id'])) {
                        if ($_SESSION['type'] == 2) {
                            fix_link("/creator/");
                            echo 'Διαχείριση Ιδεών</a></li>';
                            fix_link("/creator/comments.php");
                            echo 'Διαχείριση Σχολίων</a></li>';
                            fix_link("/creator/questions.php");
                            echo 'Διαχείριση Ερωτήσεων</a></li>';
                            fix_link("/creator/answers.php");
                            echo 'Διαχείριση Απαντήσεων</a></li>';
                        } else if ($_SESSION['type'] == 3) {
                            fix_link("/admin/");
                            echo 'Διαχείριση Χρηστών</a></li>';
                            fix_link("/admin/ideas.php");
                            echo 'Διαχείριση Ιδεών</a></li>';
                            fix_link("/admin/comments.php");
                            echo 'Διαχείριση Σχολίων</a></li>';
                            fix_link("/admin/questions.php");
                            echo 'Διαχείριση Ερωτήσεων</a></li>';
                            fix_link("/admin/answers.php");
                            echo 'Διαχείριση Απαντήσεων</a></li>';
                        }
                    }
                    ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if (isset($_SESSION['id'])) {
                        if ($_SESSION['type'] == 2) {
                            fix_link("/newIdea.php");
                            echo 'Νέα Ιδέα</a></li>';
                        }
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= $user['first_name'] ?>     
                                <?php if ($_SESSION['img_temp'] == 0) { ?>
                                    <img style="height: 22px;width: 20px;" src="/img/no_image.jpg">
                                <?php } else { ?>  
                                    <img style="height: 22px;width: 20px;" src="../image.php">
                                <?php } ?><span class="caret"></span></a>    
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/profile.php">Προφίλ</a></li>
                                <li class="divider"></li>
                                <li><a href="/photo.php">Φωτογραφία Προφίλ</a></li>
                                <li class="divider"></li>
                                <?php
                                if (($_SESSION['type'] == 0) || (($_SESSION['type'] == 2))) {
                                    ?>
                                    <li><a href="/help">Βοήθεια</a></li>
                                    <li class="divider"></li>
                                <?php } ?>
                                <li><a href="/logout.php">Αποσύνδεση</a></li>
                            </ul>
                            <?php
                        } else {
                            fix_link("/login/");
                            echo 'Είσοδος</a></li>';
                            fix_link("/register/");
                            echo 'Εγγραφή</a></li>';
                            fix_link("/help/");
                            echo 'Βοήθεια</a></li>';
                        }
                        ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php
}

function sqldb() {
    $conn = new mysqli("localhost", "root", "", "ideas");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->query("SET NAMES 'utf8'");
    $conn->query("SET CHARACTER SET 'utf8'");
    return $conn;
}

function fix_link($l) {
    echo '<li';
    if (endsWith($_SERVER['REQUEST_URI'], $l)) {
        echo ' class="active"';
    }
    echo '><a href="' . $l . '">';
}

function endsWith($haystack, $needle) {
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function styles() {
    ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <?php
}

function show_footer() {
    ?>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>ICSD</p>
                </div>
            </div>
        </div>
    </footer>
<?php }
?>
