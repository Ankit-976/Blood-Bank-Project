<?php
session_start();
include("db/db.php");

if (!isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit();
}
;

if (isset($_POST['submit'])) {

  $type = $_POST['type'];
  $name = $_POST['name'];
  $units = (int) $_POST['units'];

  if ($type == "Testing Kit") {
    $query = "INSERT INTO testingkit 
            (name, units, lastUpdateAt)
            VALUES
            ('$name', '$units', NOW())";

    mysqli_query($conn, $query);

  } elseif ($type == "Energy Kit") {
    $query = "INSERT INTO energyKit 
            (name, units, lastUpdateAt)
            VALUES
            ('$name', '$units', NOW())";

    mysqli_query($conn, $query);

  } elseif ($type == "Blood Stock") {
    $bg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT blood_group FROM stock WHERE blood_group = '$name'"));

    $expiry_date = date("Y-m-d", strtotime("+42 days"));
    if (!$bg) {
      $query = "INSERT INTO stock 
                (blood_group, units, expiry_date)
                VALUES
                ('$name', '$units', '$expiry_date')";

      mysqli_query($conn, $query);

    } else {
      mysqli_query($conn, "UPDATE stock 
      SET 
      units = units + $units
      -- expiry_date = $expiry_date
      WHERE blood_group = '$name'
      ");
    }

  }
  header("Location: stock.php");
  exit();

}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Stock</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <div class="add-staff-wrapper">
    <div class="add-staff-card">

      <!-- Header -->
      <div class="add-staff-header">
        <div>
          <h1>Add New Stock</h1>
          <p>Enter Stock details below</p>
        </div>
        <a href="stock.php" class="add-staff-back-btn">Back</a>
      </div>

      <!-- Form -->
      <form class="add-staff-form" method="POST">

        <!-- Full Name -->
        <div class="add-staff-field">
          <label for="type">Type</label>
          <select name="type" id="type" required>
            <option value="Blood Stock">Blood Stock</option>
            <option value="Testing Kit">Testing Kit</option>
            <option value="Energy Kit">Energy Kit</option>
          </select>
        </div>

        <div class="add-staff-field">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" placeholder="Enter Name" required>
        </div>

        <!-- Email -->
        <div class="add-staff-field">
          <label for="units">Units</label>
          <input type="number" name="units" placeholder="ex. 268" required>
        </div>



        <!-- Buttons -->
        <div class="add-staff-actions">
          <button type="reset" class="btn-reset" name="reset">Reset</button>
          <button type="submit" class="btn-save-staff" name="submit">Save Stock</button>
        </div>

      </form>

    </div>
  </div>

</body>

</html>