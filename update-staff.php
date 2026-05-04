<?php 

    session_start();

    include("db/db.php");
    if (!isset($_SESSION["admin"])) {
        header("Location: index.php");
        exit();
    }

    if(isset($_GET["staff"])){
        $id = $_GET["staff"];

        $staffResult = mysqli_query($conn,"SELECT * FROM staff WHERE id = '$id'");
        $staff = mysqli_fetch_assoc($staffResult);
    }

    if(isset($_POST["submit"])){
        $name = $_POST["name"];
        $role = $_POST["role"];
        $joining_date = $_POST["joining_date"];
        $contact = $_POST["contact"];
        $aadhaar_number = $_POST["aadhaar_number"];
        $salary = $_POST["salary"];

        $query = "UPDATE staff 
        SET
        name='$name', role='$role', joining_date='$joining_date', contact='$contact', aadhaar_number='$aadhaar_number', salary='$salary' WHERE id = '$id'";

        mysqli_query($conn, $query);

        header("Location: staff.php");
        exit();
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Staff</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="add-staff-wrapper">
  <div class="add-staff-card">

    <!-- Header -->
    <div class="add-staff-header">
      <div>
        <h1>Add New Staff</h1>
        <p>Enter staff details below</p>
      </div>
      <a href="staff.php" class="add-staff-back-btn">Back</a>
    </div>

    <!-- Form -->
    <form class="add-staff-form" method="POST">

      <!-- Full Name -->
      <div class="add-staff-field">
        <label for="fullname">Full Name</label>
        <input type="text" id="fullname" name="name" value="<?php echo $staff['name']?>" required>
      </div>

      <!-- Email -->
      <div class="add-staff-field">
        <label for="aadhaar_number">Aadhaar</label>
        <input type='text' name="aadhaar_number" value="<?php echo $staff['aadhaar_number']?>" required>
      </div>

      <!-- Phone -->
      <div class="add-staff-field">
        <label for="phone">Phone</label>
        <input type="text" id="contact" name="contact" value="<?php echo $staff['contact']?>" required>
      </div>

      <!-- Role -->
      <div class="add-staff-field">
        <label for="role">Post / Role</label>
        <select id="role" name="role" required value="<?php echo $staff['role']?>">
          <option>Doctor</option>
          <option>Nurse</option>
          <option>Compounder</option>
        </select>
      </div>

      <!-- Date of Joining -->
      <div class="add-staff-field">
        <label for="doj">Date of Joining</label>
        <input type="date" id="doj" name="joining_date" value="<?php echo $staff['joining_date']?>" required>
      </div>

      <!-- Salary -->
      <div class="add-staff-field">
        <label for="salary">Salary</label>
        <input type="text" id="salary" name="salary" value="<?php echo $staff['salary']?>" required>
      </div>

      <!-- Buttons -->
      <div class="add-staff-actions">
        <button type="reset" class="btn-reset" name="reset">Reset</button>
        <button type="submit" class="btn-save-staff" name="submit">Save Staff</button>
      </div>

    </form>

  </div>
</div>

</body>
</html>
