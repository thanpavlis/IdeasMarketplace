<?php
include 'functions.php';
if (isset($_SESSION['type'])) {
    $ok = false;
    $conn = sqldb();
    if (isset($_POST["check"])) {
        if (isset($_FILES['my_file'])) {
            $myFile = $_FILES['my_file'];
            $fileCount = count($myFile["name"]);
            if ($myFile["name"][0] == "") {
                $ok = true;
            } else {
                for ($i = 0; $i < $fileCount; $i++) {
                    $formOk = true;
//Assign Variables
                    $path = $myFile['tmp_name'][$i];
                    $name = $myFile['name'][$i];
                    $size = $myFile['size'][$i];
                    $type = $myFile['type'][$i]; 

                    if ($myFile['error'][$i] || !is_uploaded_file($path)) {
                        $formOk = false;
                        echo "Error: Error in uploading file. Please try again.";
                    }
//check file extension
                    if ($formOk && !in_array($type, array('image/png', 'image/x-png', 'image/jpeg', 'image/pjpeg', 'image/gif'))) {
                        $formOk = false;
                        echo "Error: Unsupported file extension. Supported extensions are JPG / PNG.";
                    }
// check for file size.
                    if ($formOk && filesize($path) > 500000) {
                        $formOk = false;
                        echo "Error: File size must be less than 500 KB.";
                    }
                    if ($formOk) {
// read file contents 
                        $content = file_get_contents($path);
//connect to mysql database
                        $content = mysqli_real_escape_string($conn, $content);
                        $sql = "UPDATE users SET img_temp=1, image='{$content}' WHERE id=" . $_SESSION['id'];
                        mysqli_query($conn, $sql);
                        $_SESSION['img_temp'] = 1;
                    }
                }
                $conn->close();
                header('Location: ' . "http://$_SERVER[HTTP_HOST]"); 
                die();
            }
        }
    }
    $conn->close();
    ?>   
    <html>
        <head>
            <title>Φωτογραφία Προφίλ</title>
            <?php styles(); ?>
            <style>
                td.elipsis {
                    max-width: 100px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
            </style>
            <script>
                function deletePhoto() {
                    $.post("photo_delete.php", {})
                            .done(function (data) {
                                alert('ΕΠΙΤΥΧΗΣ ΔΙΑΓΡΑΦΗ ΕΙΚΟΝΑΣ!');
                                window.location.reload();
                            });
                }
            </script>
        </head>
        <body>
            <?php show_header(); ?>
            <div class="container" style="width: 1300px;">
                <div class="row">
                    <div class="box">
                        <center><h1>Εδώ μπορείτε να ανεβάσετε, να αλλάξατε ή να διαγράψετε την φωτογραφία του προφίλ σας !</h1></center>
                        <br><br>
                        <center> <a href="../creator/index.php">Επιστροφή</a></center>       
                        <br><br>
                        <center><h2>Παρακάτω φαίνεται η τρέχων φωτογραφία του προφίλ σας.</h2></center><br><br>
                        <?php if ($ok) { ?>
                            <center><div class="alert alert-danger" style="width: 300px;" role="alert">Δεν έχετε επιλέξει αρχείο για ανέβασμα !</div></center><br><br>
                            <?php
                        }
                        if ($_SESSION['img_temp'] == 0) {
                            echo '<center><img style="height: 250px;width: 230px;" src="/img/no_image.jpg"></center>';
                        } else {
                            echo '<center><img style="height: 250px;width: 230px;" src="/image.php"></center>';
                        }
                        ?>
                        <center>                        
                            <h3>Επιλέξτε την φωτογραφία προφίλ σας</h3><br><br>
                            <form action="photo.php" method="post" enctype="multipart/form-data">
                                <input type="file" name="my_file[]" multiple>
                                <input type="hidden" name="check" value="1"><br><br><br>
                                <input class="btn btn-default" type="submit" value="Αποθήκευση">
                                <button onclick="deletePhoto();
                                            return false;" class="btn btn-danger">Διαγραφή εικόνας</button>
                            </form>
                        </center>
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