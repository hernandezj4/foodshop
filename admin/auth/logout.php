<?php
require_once __DIR__ . '/../../config/auth.php';
session_destroy();
header('Location: ' . APP_URL . '/admin/auth/login.php');
exit;
