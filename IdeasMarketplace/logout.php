<?php

session_start();
if (isset($_SESSION['type'])) {
    session_destroy();
    header('Location: ' . "http://$_SERVER[HTTP_HOST]");
    die();
} else {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]/404.html");
    die();
}