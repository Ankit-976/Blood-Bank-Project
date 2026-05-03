<?php

session_start();

include("db/db.php");

if (!isset($_SESSION["admin"])) {
header("Location: index.php");
exit();
}

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $aadhaar_no = $_POST["aadhaar_number"];
    $contact = $_POST["contact"];
    $blood_group = $_POST["blood_group"];
    $receiving_date = $_POST["receiving_date"];
    $purpose = $_POST["purpose"];
    $address = $_POST["address"];
    $staff_name = $_POST["staff"];

    $staffList = mysqli_query($conn, "SELECT id FROM staff WHERE name='$staff_name'");
    $staff = mysqli_fetch_assoc($staffList);

    if (!$staff) {
        echo "Staff not found. Please enter correct name.";
        exit();
    }

    $staff_id = $staff['id'];

    $query = "INSERT INTO receivers 
    (name, aadhaar_no, contact, blood_group, receiving_date, purpose, address, staff_id)
    VALUES
    ('$name', '$aadhaar_no', '$contact', '$blood_group', '$receiving_date', '$purpose', '$address', '$staff_id')
    ";

    mysqli_query($conn, $query);

    header("Location: receivers.php");
    exit();

}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Receiver</title>
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
                    <h1>Add New Receiver</h1>
                    <p>Enter receiver details below</p>
                </div>
                <a href="receivers.php" class="add-staff-back-btn">Back</a>
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
                    <label for="blood_group">Blood Group</label>
                    <input type="text" id="blood_group" name="blood_group" placeholder="A+" required>
                </div>


                <div class="add-staff-field">
                    <label for="doj">Receiving Date</label>
                    <input type="date" id="doj" name="receiving_date" required>
                </div>
                <div class="add-staff-field">
                    <label for="staff">Staff Assisting</label>
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
                    <label for="medical_history">Purpose</label>
                    <input type="text" name="purpose" id="medical_history" placeholder="ex. Accident"></input>
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