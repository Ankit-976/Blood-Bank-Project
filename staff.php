<?php
session_start();

include("db/db.php");

if (!isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit();
}

//Delete Staff 
if (isset($_GET["delete"])) {
  $id = $_GET["delete"];
  mysqli_query($conn, "DELETE FROM staff WHERE id=$id");
  header("Location: staff.php");
  exit();
}

?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Staff Directory | HEMOGLOBIN</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />
  <link href="style.css" rel="stylesheet" />
</head>

<body>
  <!-- ═══════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════ -->
  <aside class="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
      <h1>HEMOGLOBIN</h1>
      <p style="
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 4px;
          ">
        Central Command
      </p>
    </div>

    <!-- Nav Links -->
    <nav class="sidebar-nav">
      <a href="dashboard.php">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="nav-label">Dashboard</span>
      </a>
      <a href="donors.php">
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
      <a href="staff.php" class="active">
        <span class="material-symbols-outlined">badge</span>
        <span class="nav-label">Staff</span>
      </a>
      <!-- <a href="#">
        <span class="material-symbols-outlined">list_alt</span>
        <span class="nav-label">Requests</span>
      </a>
      <a href="#">
        <span class="material-symbols-outlined">analytics</span>
        <span class="nav-label">Reports</span> -->
      </a>
    </nav>

    <!-- Admin Profile + Logout -->
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
      <a href="index.php" style="
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

  <!-- ═══════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════ -->
  <main class="main-content">
    <!-- Page Header -->
    <header class="staff-header">
      <div>
        <h2>Staff Directory</h2>
        <p>Manage hospital staff records, roles, and attendance status.</p>
      </div>
      <a href="add-staff.php">
        <button class="staff-add-btn">
          <span class="material-symbols-outlined" style="font-size: 20px">person_add</span>
          + Add Staff
        </button>
      </a>
    </header>

    <!-- Stats Bento Grid -->
    <div class="staff-stats-grid">
      <div class="staff-stat-card accent">
        <?php
        $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM staff");
        $row = mysqli_fetch_assoc($result);
        ?>
        <p class="staff-stat-label">Total Staff</p>
        <div class="staff-stat-row">
          <span class="staff-stat-value"><?php echo $row['total']; ?></span>
        </div>
      </div>

      <div class="staff-stat-card">
        <p class="staff-stat-label">Doctors</p>
        <div class="staff-stat-row">
          <?php
          $result = mysqli_query($conn, "SELECT COUNT(*) AS doctors FROM STAFF WHERE role='Doctor'");
          $row = mysqli_fetch_assoc($result);
          ?>
          <span class="staff-stat-value"><?php echo $row['doctors'] ?></span>
        </div>
      </div>

      <div class="staff-stat-card">
        <p class="staff-stat-label">Compounders</p>
        <div class="staff-stat-row">
          <?php
          $result = mysqli_query($conn, "SELECT COUNT(*) AS compounders FROM STAFF WHERE role='Compounder'");
          $row = mysqli_fetch_assoc($result);
          ?>
          <span class="staff-stat-value"><?php echo $row['compounders'] ?></span>
        </div>
      </div>

      <div class="staff-stat-card">
        <p class="staff-stat-label">Nurses</p>
        <div class="staff-stat-row">
          <?php
          $result = mysqli_query($conn, "SELECT COUNT(*) AS nurses FROM staff WHERE role='Nurse'");
          $row = mysqli_fetch_assoc($result);
          ?>
          <span class="staff-stat-value"><?php echo $row['nurses'] ?></span>
        </div>
      </div>
    </div>

    <!-- Controls Bar
    <div class="staff-controls">
      <div class="staff-search-wrap">
        <span class="material-symbols-outlined">search</span>
        <input class="staff-search-input" type="text" placeholder="Search by Staff Name or ID..." />
      </div>
      <div class="staff-control-actions">
        <button class="staff-ctrl-btn">
          <span class="material-symbols-outlined" style="font-size: 20px">filter_list</span>
          Filter
        </button>
        <button class="staff-ctrl-btn">
          <span class="material-symbols-outlined" style="font-size: 20px">file_download</span>
          Export
        </button>
      </div>
    </div> -->

    <!-- Staff Table Card -->
    <div class="staff-table-card">
      <div class="staff-table-scroll">
        <table class="staff-table" cellpadding="10">
          <thead>
            <tr>
              <th>Staff Name</th>
              <th>Staff ID</th>
              <th>Post (Role)</th>
              <th>Joining Date</th>
              <th>Contact</th>
              <th>Aadhar Id</th>
              <!-- <th>Salary</th> -->
              <th class="text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Row 1 -->
            <?php

            $result = mysqli_query($conn, "SELECT * FROM staff");

            while ($row = mysqli_fetch_array($result)) {
              ?>
              <tr>
                <td>
                  <div class="staff-cell">
                    <!-- <div class="staff-avatar">
                      <img
                        src="https://imgs.search.brave.com/RIJ78YvOZhv5816VrwB5tzHiPRFlP8twQoBuoPsjRmk/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvMTQ1/MTU4NzgwNy92ZWN0/b3IvdXNlci1wcm9m/aWxlLWljb24tdmVj/dG9yLWF2YXRhci1v/ci1wZXJzb24taWNv/bi1wcm9maWxlLXBp/Y3R1cmUtcG9ydHJh/aXQtc3ltYm9sLXZl/Y3Rvci5qcGc_cz02/MTJ4NjEyJnc9MCZr/PTIwJmM9eURKNElU/WDFjSE1oMjVMdDF2/STF6Qm4yY0FLS0Fs/QnlIQnZQSjhnRWlJ/Zz0" />
                    </div> -->
                    <div class="donor-avatar"><?php echo ucfirst($row['name'][0])?></div>
                    <div>
                      <p class="staff-name"><?php echo ucwords($row['name']) ?></p>
                      <!-- <p class="staff-email"><?php echo $row['email'] ?></p> -->
                    </div>
                  </div>
                </td>
                <td><span class="staff-id">STF-00<?php echo $row['id'] ?></span></td>
                <td>
                  <span class="role-badge teal"><?php echo ucwords($row['role']) ?></span>
                </td>
                <td><span class="staff-date"><?php echo $row['joining_date'] ?></span></td>
                <td>
                  <div class="staff-status">
                    <span class="staff-status-text green"><?php echo $row['contact'] ?></span>
                  </div>
                </td>
                <td>
                  <div class="staff-status">
                    <span class="staff-status-text"><?php echo $row['aadhaar_number'] ?></span>
                  </div>
                </td>
                <!-- <td>
                  <div class="staff-status">
                    <span class="staff-status-text"><?php echo $row['salary'] ?></span>
                  </div>
                </td> -->
                <td class="text-right">
                  <div class="staff-actions">
                    <button class="staff-action-btn edit">
                      <span class="material-symbols-outlined">edit</span>
                    </button>
                    <a href="staff.php?delete=<?php echo $row['id'] ?>">
                      <button class="staff-action-btn del">
                        <span class="material-symbols-outlined">delete</span>
                      </button>
                    </a>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <!-- Footer -->
      <!-- <footer class="page-footer">
        <p class="footer-copy">
          © 2024 Hemoglobin Blood Management System. Life-saving efficiency.
        </p>
        <div class="footer-links">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
          <a href="#">Contact Support</a>
        </div>
      </footer> -->
  </main>
</body>

</html>