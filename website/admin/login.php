<?php
session_start();
include "../includes/db.php";

if(isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM yoneticiler WHERE username = '$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($pass, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['fullname'];
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Management Panel</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f1ea; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: #fff; padding: 40px; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        .login-card h2 { font-family: 'Playfair Display', serif; margin-bottom: 20px; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 10px; box-sizing: border-box; outline: none; }
        button { width: 100%; padding: 12px; background: #1a1a1a; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; margin-top: 15px; }
        button:hover { background: #c49b63; }
        .error-msg { color: #d9534f; font-size: 0.9rem; margin-bottom: 15px; background: #fdf0f0; padding: 8px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Admin Login</h2>
        <?php if(isset($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>