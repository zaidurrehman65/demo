<?php
session_start();
require __DIR__ . '/../php/config.php';

if (empty($_SESSION['aazli_admin'])) {
    header('Location: login.php');
    exit;
}
