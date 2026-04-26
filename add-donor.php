<?php

session_start();
include("db/db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
;

if (isset($_POST['submit'])) {
    $aadhaar_no = $_POST['aadhaar_number'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $blood_group = $_POST['blood_group'];
    $last_donation_date = $_POST['last_donation_date'];
    $medical_history = $_POST['medical_history'];
    $staff_name = $_POST['staff'];

    if(empty($medical_history)){
        $medical_history = "Nothing";
    };

    $result = mysqli_query($conn, "SELECT id FROM staff WHERE name='$staff_name'");
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo "Staff not found. Please enter correct name.";
        exit();
    }

    $staff_id = $row['id'];

    $query = "INSERT INTO donors 
    (aadhaar_no, name, dob, contact, address, blood_group, last_donation_date, medical_history, staff_id, donation_date)
    VALUES
    ('$aadhaar_no', '$name', '$dob', '$contact', '$address', '$blood_group', '$last_donation_date', '$medical_history', '$staff_id', CURDATE())";

    mysqli_query($conn, $query);

    header("Location: donors.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Donor | HEMOGLOBIN</title>
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
                    <h1>Add New Donor</h1>
                    <p>Enter donor details below</p>
                </div>
                <a href="donors.php" class="add-staff-back-btn">Back</a>
            </div>

            <!-- Form -->
            <form class="add-staff-form" method="POST">

                <!-- Full Name -->
                <div class="add-staff-field">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="name" placeholder="Enter Full Name" required>
                </div>

                <!-- Email -->
                <div class="add-staff-field">
                    <label for="aadhaar_number">Aadhaar</label>
                    <input type='text' name="aadhaar_number" placeholder="ex. 268246876214" required>
                </div>

                <!-- Phone -->
                <div class="add-staff-field">
                    <label for="phone">Phone</label>
                    <input type="text" id="contact" name="contact" placeholder="9876543210" required>
                </div>

                <div class="add-staff-field">
                    <label for="salary">Blood Group</label>
                    <input type="text" id="salary" name="blood_group" placeholder="A+" required>
                </div>

                <!-- Date of Joining -->
                <div class="add-staff-field">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
                <div class="add-staff-field">
                    <label for="doj">Last Donation Date</label>
                    <input type="date" id="doj" name="last_donation_date" required>
                </div>
                <!-- <div class="add-staff-field">
                    <label for="doj">Donation Date</label>
                    <input type="date" id="doj" name="donation_date" required>
                </div> -->
                <div class="add-staff-field">
                    <label for="staff">Staff</label>
                    <select id="staff" name="staff" required>
                        <?php
                        $result = mysqli_query($conn, "SELECT name, role FROM staff");

                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <option><?php echo ucwords($row['name']) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="add-staff-field">
                    <label for="medical_history">Medical History</label>
                    <input type="text" name="medical_history" id="medical_history" placeholder="Nothing"></input>
                </div>
                <div class="add-staff-field">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" required></textarea>
                </div>
                <div class="add-staff-actions">
                    <button type="reset" class="btn-reset" name="reset">Reset</button>
                    <button type="submit" class="btn-save-staff" name="submit">Save Donor</button>
                </div>

            </form>

        </div>
    </div>

</body>

</html>