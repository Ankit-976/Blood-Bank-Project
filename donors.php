<?php
session_start();
include("db/db.php");

if (!isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit();
}

$filter = $_GET["blood-group-select"] ?? "";

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Donor Management</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />
  <link href="style.css" rel="stylesheet" />
</head>

<body class="mesh-bg">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <h1><span id="life">Life </span><span id="drop">Drop</span></h1>
      <div class="sidebar-admin">
        <div class="sidebar-admin-avatar">
          <span class="material-symbols-outlined">shield_person</span>
        </div>
        <div>
          <p class="sidebar-admin-name">Admin Panel</p>
          <p class="sidebar-admin-role">Central Command</p>
        </div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <a href="dashboard.php">
        <span class="material-symbols-outlined active-pill">dashboard</span>
        <span class="nav-label">Dashboard</span>
      </a>
      <a href="donors.php" class="active">
        <span class="material-symbols-outlined">group</span>
        <span class="nav-label">Donors</span>
      </a>
      <a href="receivers.php">
        <span class="material-symbols-outlined">diversity_3</span>
        <span class="nav-label">Receivers</span>
      </a>
      <a href="stock.php">
        <span class="material-symbols-outlined">inventory_2</span>
        <span class="nav-label">Stock</span>
      </a>
      <a href="staff.php">
        <span class="material-symbols-outlined">badge</span>
        <span class="nav-label">Staff</span>
      </a>
    </nav>
    <div style="
          padding: 24px 32px 0;
          border-top: 1px solid #1e293b;
          margin-top: auto;
        ">
      <div class="sidebar-admin" style="margin-bottom: 24px">
        <div style="
              width: 40px;
              height: 40px;
              border-radius: 50%;
              background: #1e293b;
              border: 1px solid #334155;
              overflow: hidden;
              flex-shrink: 0;
            ">
          <img
            src="https://imgs.search.brave.com/wV3xjLUAA0KAxg04P9n_RbW0hWcPAA_gQKoQltugCew/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9jZG4u/dmVjdG9yc3RvY2su/Y29tL2kvNTAwcC81/Mi82OS9wcm9maWxl/LWNvbXBsZXRpb24t/cHJvZ3Jlc3MtdWkt/ZWxlbWVudC10ZW1w/bGF0ZS12ZWN0b3It/NDUxOTUyNjkuanBn"
            alt="Admin Profile" style="width: 100%; height: 100%; object-fit: cover" />
        </div>
        <div>
          <?php
          $result = mysqli_query($conn, "SELECT * FROM admin");
          $row = mysqli_fetch_assoc($result);
          ?>
          <p class="sidebar-admin-name"><?php echo ucwords($row['name']) ?></p>
          <p class="sidebar-admin-role"><?php echo strtolower($row['email']) ?></p>
        </div>
      </div>
      <a href="logout.php" style="
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ef4444;
            text-decoration: none;
            font-size: 14px;
            font-family: &quot;Manrope&quot;, sans-serif;
            font-weight: 500;
            transition: color 0.2s;
          ">
        <span class="material-symbols-outlined">logout</span>
        <span>Logout</span>
      </a>
    </div>
  </aside>
  <main class="main-content" style="padding: 0">
    <!-- Sticky Top Bar -->
    <header class="donor-topbar">
      <div class="donor-topbar-left">
        <h2>Donor Management</h2>
        <span class="donor-live-badge">Live Database</span>
      </div>
      <a href="add-donor.php">
        <button class="donor-topbar-btn">
          <span class="material-symbols-outlined" style="font-size: 20px">add</span>
          Add New Donor
        </button>
      </a>
    </header>

    <div class="donor-body">
      <div class="staff-table-card">
        <div class=" donor-table-card">
          <div class="donor-toolbar">
            <!-- <div class="donor-search-wrap">
              <span class="material-symbols-outlined">search</span>
              <input class="donor-search-input" type="text" placeholder="Search by name, ID or phone..." />
            </div> -->
            <form method="GET">
              <div class="donor-filter-row">
                <select class="donor-select" name="blood-group-select" onchange="this.form.submit()">
                  <option value="">All Blood Groups</option>
                  <option value="A+" <?php if ($filter == "A+") echo "selected"?>>A+</option>
                  <option value="A-"  <?php if ($filter == "A-") echo "selected"?>>A-</option>
                  <option value="B+"  <?php if ($filter == "B+") echo "selected"?>>B+</option>
                  <option value="B-"  <?php if ($filter == "B-") echo "selected"?>>B-</option>
                  <option value="AB+"  <?php if ($filter == "AB+") echo "selected"?>>AB+</option>
                  <option value="AB-"  <?php if ($filter == "AB-") echo "selected"?>>AB-</option>
                  <option value="O+"  <?php if ($filter == "O+") echo "selected"?>>O+</option>
                  <option value="O-"  <?php if ($filter == "O-") echo "selected"?>>O-</option>
                </select>
                <!-- <button class="donor-filter-btn">
                  <span class="material-symbols-outlined">filter_list</span>
                </button> -->
              </div>
            </form>
          </div>
          <table class=" donor-table">
            <thead>
              <tr>
                <th>Donor Details</th>
                <th>Blood Type</th>
                <th>Medical History</th>
                <th>Last Donation</th>
                <th>Donation Date</th>
                <th>Staff Assisted</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php

              // $result = mysqli_query($conn, "SELECT donors.*, staff.name AS staff_name FROM donors JOIN staff ON donors.staff_id = staff.id");
              if ($filter) {
                    $result = mysqli_query($conn, "
                    SELECT donors.*, staff.name AS staff_name 
                    FROM donors 
                    JOIN staff ON donors.staff_id = staff.id
                    WHERE donors.blood_group = '$filter'
                ");
              } else {
                    $result = mysqli_query($conn, "
                    SELECT donors.*, staff.name AS staff_name 
                    FROM donors 
                    JOIN staff ON donors.staff_id = staff.id
                ");
              }

              while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                  <td>
                    <div class="donor-cell">
                      <div class="donor-avatar"><?php echo ucfirst($row['name'][0]) ?></div>
                      <div>
                        <p class="donor-name"><?php echo ucwords($row['name']) ?></p>
                        <p class="donor-id"><?php echo $row['contact'] ?></p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="blood-type-wrap">
                      <?php
                      $bg = $row['blood_group'];

                      $sign = substr($bg, -1);
                      $group = substr($bg, 0, -1);
                      ?>
                      <div class="blood-letter"><?php echo strtoupper($group) ?></div>
                      <div class="blood-sign"><?php echo $sign ?></div>
                    </div>
                  </td>
                  <td><span class="donor-status eligible"><?php echo $row['medical_history'] ?></span></td>
                  <td><span class="donor-date"><?php echo $row['last_donation_date'] ?></span></td>
                  <td><span class="donor-date"><?php echo $row['donation_date'] ?></span></td>
                  <td>
                    <p class="donor-name"><?php echo ucwords($row['staff_name']) ?></p>
                  </td>
                  <td>
                    <div class="donor-actions">
                      <a href="donor-details.php?details=<?php echo $row['id'] ?>">
                        <button class="donor-action-btn view">
                          <span class="material-symbols-outlined" title="Full Details">visibility</span>
                        </button>
                      </a>
                      <a href="update-donor.php?donor=<?php echo $row['id'] ?>">
                        <button class="donor-action-btn edit">
                          <span class="material-symbols-outlined" title="Edit">edit</span>
                        </button>
                      </a>
                    </div>
                  </td>
                </tr>

              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</body>

</html>