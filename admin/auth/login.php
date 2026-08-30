<?php
require_once __DIR__ . '/../../config/auth.php';
if (isLoggedIn()) { redirect(APP_URL.'/admin/dashboard/'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($email) || empty($password)) { $error = 'Fill all fields'; }
    else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email=?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            redirect(APP_URL.'/admin/dashboard/');
        } else { $error = 'Wrong email or password'; }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body>
<div class="auth-w">
    <div class="auth-c">
        <h2><?= APP_NAME ?> Admin</h2>
        <?php if ($error): ?><div style="background:var(--red-light);padding:10px;border-radius:10px;margin-bottom:14px;color:var(--red);text-align:center;font-size:12px"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= sanitize($_POST['email'] ?? '') ?>" placeholder="admin@foodshop.com"></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="Password"></div>
            <button type="submit" class="btn btn-primary btn-block btn-round" style="margin-top:8px;padding:12px">Sign In</button>
        </form>
    </div>
</div>
</body>
</html>
