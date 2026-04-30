<?php 
session_start();
include("db/db.php");

if(isset($_POST['submit'])){

    $inputEmail = $_POST['email'];
    $inputPassword = $_POST['password'];

    $result = mysqli_query($conn, 
    "SELECT * FROM admin WHERE email='$inputEmail' AND password='$inputPassword'");

    if(mysqli_num_rows($result) == 1){

        $_SESSION['admin'] = $inputEmail;

        header("Location: dashboard.php");
        exit();

    } else{
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Admin Login | HEMOGLOBIN</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <link href="style.css" rel="stylesheet"/>
</head>
<body>

<main class="login-split">

  <!-- Left Side: Dark Red Brand Panel -->
  <div class="login-panel-left">
    <div class="login-panel-left-inner">

      <!-- Icon -->
      <div class="login-icon-wrap">
        <!-- <span class="material-symbols-outlined">water_drop</span> -->
         <img src="logo.png" alt="Logo" height="160">
      </div>

      <h1>Admin Login</h1>
      <p>Authorized personnel only. Accessing secure medical database for donor management and emergency supplies.</p>

    </div>

    <!-- Security Info -->
    <div class="login-security-info">
      <div class="login-security-row">
        <span class="login-status-dot"></span>
        <span class="login-security-label">Systems Secure</span>
      </div>
      <div class="login-security-row">
        <span class="material-symbols-outlined">shield</span>
        <span class="login-security-label">256-Bit Encryption</span>
      </div>
    </div>
  </div>

  <!-- Right Side: Login Form -->
  <div class="login-panel-right">
    <div class="login-form-wrap">

      <div class="login-heading">
        <h2>Welcome Back</h2>
        <p>Please enter your administrator credentials</p>
      </div>

      <form class="login-form" method="POST">

        <!-- Administrator Email -->
        <div class="login-field">
          <label class="login-label" for="email">Administrator Email</label>
          <div class="login-input-wrap">
            <span class="material-symbols-outlined input-icon">alternate_email</span>
            <input class="login-input" id="email" placeholder="name@institution.com" name="email" type="email" required autocomplete="email"/>
          </div>
        </div>
          <div class="login-input-wrap">
            <span class="material-symbols-outlined input-icon">lock</span>
            <input class="login-input" id="password" placeholder="••••••••••••" name="password" type="password" required autocomplete="new-password"/>
            <button class="login-toggle-vis" type="button">
              <span class="material-symbols-outlined">visibility</span>
            </button>
          </div>
        </div>

        <!-- Submit -->
        <button class="login-submit" type="submit" name="submit">
          <span>Sign In to Portal</span>
          <span class="material-symbols-outlined">arrow_forward</span>
        </button>

      </form>

      <div class="login-footer">
        <p>Need help? <a href="#">Contact System Admin</a></p>
        <span class="login-demo-hint">Demo: admin / admin123</span>
      </div>

    </div>
  </div>

</main>

</body>
</html>