<?php
session_start();

include("db/db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET["update"])) {
    $id = $_GET["update"];
    $result = mysqli_query($conn, "SELECT * FROM stock WHERE id= '$id'");

    $row = mysqli_fetch_array($result);

    $name = $row["blood_group"];
    $units = $row["units"];
}

if (isset($_POST["submit"])) {

    $units = $_POST["units"];

    $query = "UPDATE stock SET units = $units WHERE id = '$id'";

    mysqli_query($conn, $query);

    header("Location: stock.php");
    exit();

}

;

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock | HEMOGLOBIN</title>
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
                    <h1>Update Stock</h1>
                    <p>Enter Stock details below</p>
                </div>
                <a href="stock.php" class="add-staff-back-btn">Back</a>
            </div>

            <!-- Form -->
            <form class="add-staff-form" method="POST">

                <!-- Full Name -->
                <!-- <div class="add-staff-field">
          <label for="type">Type</label>
          <select name="type" id="type" required>
            <option value="Blood Stock">Blood Stock</option>
            <option value="Testing Kit">Testing Kit</option>
            <option value="Energy Kit">Energy Kit</option>
          </select>
        </div> -->

                <div class="add-staff-field">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Blood Group" value="<?php echo $name ?>"
                        disabled>
                </div>

                <!-- Email -->
                <div class="add-staff-field">
                    <label for="units">Units</label>
                    <input type="number" name="units" placeholder="Present units: <?php echo $units ?>" required>
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