<?php
session_start();
session_unset();
session_destroy();
// Get redirect safely and make sure relative path is valid
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/index.php';

if (strpos($redirect, '/') !== 0 && !filter_var($redirect, FILTER_VALIDATE_URL)) {
    $redirect = '/index.php'; // fallback if it's not a valid relative path
}
header("Location: " . $redirect);
exit;
