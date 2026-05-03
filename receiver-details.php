<?php

session_start();

include("db/db.php");

if (!isset($_SESSION["admin"])) {
  header("Location: index.php");
  exit();
}

if (isset($_GET["details"])) {

  $id = $_GET["details"];

  $result = mysqli_query($conn,"SELECT * FROM receivers WHERE id = '$id'");
  $row = mysqli_fetch_assoc($result);
  $staff_id = $row["staff_id"];

  $staffResult = mysqli_query($conn,"SELECT name FROM staff WHERE id = '$staff_id'");
  $staff = mysqli_fetch_assoc($staffResult);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receiver Details </title>
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
          <h1>Receiver Details</h1>
        </div>
        <a href="receivers.php" class="add-staff-back-btn">Back</a>
      </div>

      <!-- Form -->
      <form class="add-staff-form">

        <!-- Full Name -->
        <div class="add-staff-field">
          <label>Full Name</label>
          <div><?php echo  $row['name']?></div>
        </div>

        <!-- Email -->
        <div class="add-staff-field">
          <label>Aadhaar</label>
          <div><?php echo  $row['aadhaar_no']?></div>
        </div>

        <!-- Phone -->
        <div class="add-staff-field">
          <label>Phone</label>
          <div><?php echo  $row['contact']?></div>
        </div>

        <div class="add-staff-field">
          <label>Blood Group</label>
          <div><?php echo  ucwords($row['blood_group'])?></div>
        </div>

        <!-- Date of Joining -->
        <div class="add-staff-field">
          <label>Purpose</label>
          <div><?php echo  $row['purpose']?></div>
        </div>
        <div class="add-staff-field">
          <label>Receiving Date</label>
          <div><?php echo  $row['receiving_date']?></div>
        </div>

        <div class="add-staff-field">
          <label>Staff Assisted</label>
          <div><?php echo  ucwords($staff['name'])?></div>
        </div>
        <!-- <div class="add-staff-field">
          <label>Medical History</label>
          <div><?php echo  $row['medical_history']?></div>
        </div> -->
        <div class="add-staff-field">
          <label>Address</label>
          <div><?php echo  $row['address']?></div>
        </div>

      </form>

    </div>
  </div>

</body>

</html>