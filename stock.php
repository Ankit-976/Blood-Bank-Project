<?php
session_start();

include("db/db.php");

if (!isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit();
}

$totalRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(units) AS total_units FROM stock;"));
$total_units = $totalRow['total_units'];
$result = mysqli_query($conn, "SELECT blood_group, units FROM stock");

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
  $data[$row['blood_group']] = $row['units'];
}

$percentage = [];

foreach ($data as $group => $units) {
  $percentage[$group] = $total_units > 0 ? ($units / $total_units) * 100 : 0;
}

$result = mysqli_query($conn, "
SELECT 
  SUM(CASE WHEN blood_group IN ('A+', 'A-') THEN units ELSE 0 END) AS A_total,
  SUM(CASE WHEN blood_group IN ('B+', 'B-') THEN units ELSE 0 END) AS B_total,
  SUM(CASE WHEN blood_group IN ('AB+', 'AB-', 'O+', 'O-') THEN units ELSE 0 END) AS ABO_total
FROM stock
");

$data = mysqli_fetch_assoc($result);

$A = $data['A_total'] ?? 0;
$B = $data['B_total'] ?? 0;
$ABO = $data['ABO_total'] ?? 0;

$result = mysqli_query($conn, "SELECT blood_group FROM stock WHERE units < 10");
$criticalGroups = [];

while ($row = mysqli_fetch_assoc($result)) {
  $criticalGroups[] = $row['blood_group'];
}
$criticalGroupsList = ucwords(implode(', ', $criticalGroups));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Stock Management | HEMOGLOBIN</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>

<body>

  <div class="layout-shell">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <h2>HEMOGLOBIN</h2>
        <p class="sidebar-subtitle">Central Command</p>
      </div>
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
        <a href="stock.php" class="active">
          <span class="material-symbols-outlined">inventory_2</span>
          <span class="nav-label">Stock</span>
        </a>
        <a href="staff.php">
          <span class="material-symbols-outlined">badge</span>
          <span class="nav-label">Staff</span>
        </a>
        <!-- <a href="#">
          <span class="material-symbols-outlined">list_alt</span>
          <span class="nav-label">Requests</span>
        </a>
        <a href="#">
          <span class="material-symbols-outlined">analytics</span>
          <span class="nav-label">Reports</span>
        </a> -->
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

    <!-- Main Content -->
    <main class="main-content">

      <!-- Page Header -->
      <div class="page-header">
        <div>
          <h1>Inventory Management</h1>
          <p class="page-subtitle">Real-time status of blood units and supplies.</p>
        </div>
        <div class="header-actions">
          <!-- <a href="update-stock.php">
            <button class="btn-outline">
              <span class="material-symbols-outlined">edit</span>
              Update Stock
            </button>
          </a> -->
          <a href="add-stock.php">
            <button class="btn-primary">
              <span class="material-symbols-outlined">add</span>
              Add New Unit
            </button>
          </a>
        </div>
      </div>

      <!-- Alert Banner -->
      <div class="alert-banner <?php echo count($criticalGroups) > 0 ? '' : 'critical-hidden' ?>">
        <span class="material-symbols-outlined alert-icon">warning</span>
        <div class="alert-body">
          <h4 class="alert-title">Critical Low Stock Warning</h4>
          <p class="alert-text">Blood groups <strong><u><?php echo ucwords($criticalGroupsList); ?></u></strong> have
            fallen
            below the mandatory reserve threshold of 10 units.</p>
        </div>
        <!-- <button class="btn-danger">Immediate Action</button> -->
      </div>

      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="glass-card stat-card">
          <div class="stat-card-top">
            <span class="material-symbols-outlined stat-icon-primary">water_drop</span>
            <span class="stat-label">Live units</span>
          </div>
          <div>
            <?php
            $result = mysqli_query($conn, "SELECT SUM(units) AS total_units FROM stock;");
            $row = mysqli_fetch_assoc($result);
            ?>
            <p class="stat-value"><?php echo $row['total_units'] ?></p>
            <p class="stat-desc">Total Blood Units Available</p>
          </div>
        </div>
        <div class="glass-card stat-card">
          <div class="stat-card-top">
            <span class="material-symbols-outlined stat-icon-tertiary">medication</span>
            <span class="stat-label">Supply</span>
          </div>
          <div>
            <?php
            $result = mysqli_query($conn, "SELECT units FROM testingkit");
            $row = mysqli_fetch_assoc($result);
            ?>
            <p class="stat-value"><?php echo $row['units'] ?></p>
            <p class="stat-desc">Testing Kits in Stock</p>
          </div>
        </div>
        <div class="glass-card stat-card">
          <div class="stat-card-top">
            <span class="material-symbols-outlined stat-icon-secondary">nutrition</span>
            <span class="stat-label">Refreshments</span>
          </div>
          <div>
            <?php
            $result = mysqli_query($conn, "SELECT units FROM energykit");
            $row = mysqli_fetch_assoc($result);
            ?>
            <p class="stat-value"><?php echo $row['units'] ?></p>
            <p class="stat-desc">Energy Supplement Kits</p>
          </div>
        </div>
        <div class="glass-card stat-card">
          <div class="stat-card-top">
            <span class="material-symbols-outlined stat-icon-primary">emergency</span>
            <span class="stat-label">Urgent</span>
          </div>
          <div>
            <p class="stat-value stat-value-error"><?php echo count($criticalGroups) ?></p>
            <p class="stat-desc">Critical Type Shortages</p>
          </div>
        </div>
      </div>

      <!-- Bento Grid: Table + Analytics -->
      <div class="bento-grid">

        <!-- Stock Table -->
        <div class="glass-card stock-table-card">
          <div class="stock-table-header">
            <h3>Blood Inventory Breakdown</h3>
            <div class="stock-filter-btn">
              <span class="material-symbols-outlined">filter_list</span>
              <span>Filter by RH</span>
            </div>
          </div>
          <div class="table-scroll">
            <table class="inv-table">
              <thead>
                <tr class="inv-thead-row">
                  <th>Blood Group</th>
                  <th>Stock Level</th>
                  <th>Volume (Units)</th>
                  <th>Status</th>
                  <th class="text-right">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM stock");

                while ($row = mysqli_fetch_assoc($result)) {
                  ?>

                  <tr
                    class="inv-row <?php echo $row['units'] > 50 ? '' : ($row['units'] > 10 ? 'inv-row-low' : 'inv-row-critical') ?>">
                    <td class="inv-cell">
                      <div class="inv-row-info">
                        <div class="blood-badge badge-primary"><?php echo $row['blood_group'] ?></div>
                        <span class="inv-group-name"><?php echo $row['name'] ?></span>
                      </div>
                    </td>
                    <td class="inv-cell">
                      <div class="progress-track">
                        <div
                          class="progress-fill <?php echo $row['units'] > 50 ? 'fill-primary' : ($row['units'] > 10 ? 'fill-yellow' : 'fill-yellow') ?>"
                          style="width:<?php echo $percentage[$row["blood_group"]]; ?>%;"></div>
                      </div>
                    </td>
                    <td class="inv-cell inv-units"><?php echo $row['units'] ?> Units</td>
                    <td class="inv-cell"><span
                        class="status-pill <?php echo $row['units'] > 50 ? 'status-adequate' : ($row['units'] > 10 ? 'status-low' : 'status-critical') ?>">
                        <?php echo $row['units'] > 50 ? 'Adequate' : ($row['units'] > 10 ? 'Low' : 'Critical') ?>
                      </span></td>
                    <td class="inv-cell text-right">
                      <a href="update-stock.php?update=<?php echo $row['id']?>">
                        <button class="staff-action-btn edit">
                          <span class="material-symbols-outlined">edit</span>
                        </button>
                      </a>
                    </td>
                  </tr>

                <?php } ?>





                <!-- <?php
                $blood_units = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units FROM stock WHERE blood_group = 'A+'"));
                $Aunits = (int) $blood_units['units'];
                ?>
                <tr  class="inv-row <?php echo $Aunits > 50 ? '' : ($Aunits > 10 ? 'inv-row-low' : 'inv-row-critical') ?>">
                  <td class="inv-cell">
                    <div class="inv-row-info">
                      <div class="blood-badge badge-primary">A+</div>
                      <span class="inv-group-name">Alpha Positive</span>
                    </div>
                  </td>
                  <td class="inv-cell">
                    <div class="progress-track">
                      <div
                        class="progress-fill <?php echo $Aunits > 50 ? 'fill-primary' : ($Aunits > 10 ? 'fill-yellow' : 'fill-yellow') ?>"
                        style="width:<?php echo $percentage['A+']; ?>%;"></div>
                    </div>
                  </td>
                  <td class="inv-cell inv-units"><?php echo $Aunits ?> Units</td>
                  <td class="inv-cell"><span
                      class="status-pill <?php echo $Aunits > 50 ? 'status-adequate' : ($Aunits > 10 ? 'status-low' : 'status-critical') ?>">
                      <?php echo $Aunits > 50 ? 'Adequate' : ($Aunits > 10 ? 'Low Stock' : 'Critical') ?>
                    </span></td>
                  <td class="inv-cell text-right">
                    <button class="staff-action-btn edit">
                      <span class="material-symbols-outlined">edit</span>
                    </button>
                  </td>
                </tr> -->
                <!-- <?php
                $blood_units = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units FROM stock WHERE blood_group = 'O-'"));
                $Ounits = (int) $blood_units['units'];
                ?>
                <tr
                  class="inv-row <?php echo $Ounits > 50 ? '' : ($Ounits > 10 ? 'inv-row-low' : 'inv-row-critical') ?>">
                  <td class="inv-cell">
                    <div class="inv-row-info">
                      <div class="blood-badge badge-secondary">O-</div>
                      <span class="inv-group-name">Universal Donor</span>
                    </div>
                  </td>
                  <td class="inv-cell">
                    <div class="progress-track">
                      <div class="progress-fill   <?php echo $Ounits > 50 ? 'fill-primary' : ($Ounits
                        > 10 ? 'fill-yellow' : 'fill-error') ?>" style="width:<?php echo $percentage['O-']; ?>%;">
                      </div>
                    </div>
                  </td>
                  <td class="inv-cell inv-units"><?php echo $blood_units['units'] ?> Units</td>
                  <td class="inv-cell"><span
                      class="status-pill <?php echo $Ounits > 50 ? 'status-adequate' : ($Ounits > 10 ? 'status-low' : 'status-critical') ?>">
                      <?php echo $Ounits > 50 ? 'Adequate' : ($Ounits > 10 ? 'Low Stock' : 'Critical') ?>
                    </span></td>
                  <td class="inv-cell text-right">
                    <button class="staff-action-btn edit">
                      <span class="material-symbols-outlined">edit</span>
                    </button>
                  </td>
                </tr> -->
                <!-- <?php
                $blood_units = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units FROM stock WHERE blood_group = 'B+'"));
                $Bunits = (int) $blood_units['units'];
                ?>
                <tr
                  class="inv-row <?php echo $Bunits > 50 ? '' : ($Bunits > 10 ? 'inv-row-low' : 'inv-row-critical') ?>">
                  <td class="inv-cell">
                    <div class="inv-row-info">
                      <div class="blood-badge badge-primary">B+</div>
                      <span class="inv-group-name">Beta Positive</span>
                    </div>
                  </td>
                  <td class="inv-cell">
                    <div class="progress-track">
                      <div class="progress-fill   <?php echo $Bunits > 50 ? 'fill-primary' : ($Bunits
                        > 10 ? 'fill-yellow' : 'fill-error') ?>" style="width:<?php echo $percentage['B+']; ?>%;">
                      </div>
                    </div>
                  </td>
                  <td class="inv-cell inv-units"><?php echo $Bunits ?> Units</td>
                  <td class="inv-cell"><span
                      class="status-pill <?php echo $Bunits > 50 ? 'status-adequate' : ($Bunits > 10 ? 'status-low' : 'status-critical') ?>">
                      <?php echo $Bunits > 50 ? 'Adequate' : ($Bunits > 10 ? 'Low Stock' : 'Critical') ?>
                    </span></td>
                  <td class="inv-cell text-right">
                    <button class="staff-action-btn edit">
                      <span class="material-symbols-outlined">edit</span>
                    </button>
                  </td>
                </tr> -->
                <!-- <?php
                $blood_units = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units FROM stock WHERE blood_group = 'A-'"));
                $A_units = (int) $blood_units['units'];
                ?>
                <tr
                  class="inv-row <?php echo $A_units > 50 ? '' : ($A_units > 10 ? 'inv-row-low' : 'inv-row-critical') ?>">
                  <td class="inv-cell">
                    <div class="inv-row-info">
                      <div class="blood-badge badge-primary">A-</div>
                      <span class="inv-group-name">Alpha Negative</span>
                    </div>
                  </td>
                  <td class="inv-cell">
                    <div class="progress-track">
                      <div class="progress-fill  <?php echo $A_units > 50 ? 'fill-primary' : ($A_units
                        > 10 ? 'fill-yellow' : 'fill-error') ?>" style="width:<?php echo $percentage['A-']; ?>%;">
                      </div>
                    </div>
                  </td>
                  <td class="inv-cell inv-units"><?php echo $A_units ?> Units</td>
                  <td class="inv-cell"><span
                      class="status-pill <?php echo $A_units > 50 ? 'status-adequate' : ($A_units > 10 ? 'status-low' : 'status-critical') ?>">
                      <?php echo $A_units > 50 ? 'Adequate' : ($A_units > 10 ? 'Low Stock' : 'Critical') ?>
                    </span></td>
                  <td class="inv-cell text-right">
                    <button class="staff-action-btn edit">
                      <span class="material-symbols-outlined">edit</span>
                    </button>
                  </td>
                </tr> -->
                <!-- <?php
                $blood_units = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units FROM stock WHERE blood_group = 'AB+'"));
                $ABunits = (int) $blood_units['units'];
                ?>
                <tr
                  class="inv-row <?php echo $ABunits > 50 ? '' : ($ABunits > 10 ? 'inv-row-low' : 'inv-row-critical') ?>">
                  <td class="inv-cell">
                    <div class="inv-row-info">
                      <div class="blood-badge badge-primary">AB+</div>
                      <span class="inv-group-name">AB Positive</span>
                    </div>
                  </td>
                  <td class="inv-cell">
                    <div class="progress-track">
                      <div class="progress-fill  <?php echo $ABunits > 50 ? 'fill-primary' : ($ABunits
                        > 10 ? 'fill-yellow' : 'fill-error') ?>" style="width:<?php echo $percentage['AB+']; ?>%;">
                      </div>
                    </div>
                  </td>
                  <td class="inv-cell inv-units"><?php echo $ABunits ?> Units</td>
                  <td class="inv-cell"><span
                      class="status-pill <?php echo $ABunits > 50 ? 'status-adequate' : ($ABunits > 10 ? 'status-low' : 'status-critical') ?>">
                      <?php echo $ABunits > 50 ? 'Adequate' : ($ABunits > 10 ? 'Low Stock' : 'Critical') ?>
                    </span></td>
                  <td class="inv-cell text-right">
                    <button class="staff-action-btn edit">
                      <span class="material-symbols-outlined">edit</span>
                    </button>
                  </td>
                </tr> -->
                <!-- <?php
                $blood_units = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units FROM stock WHERE blood_group = 'B-'"));
                $B_units = (int) $blood_units['units'];
                ?>
                <tr
                  class="inv-row <?php echo $B_units > 50 ? '' : ($B_units > 10 ? 'inv-row-low' : 'inv-row-critical') ?>">
                  <td class="inv-cell">
                    <div class="inv-row-info">
                      <div class="blood-badge badge-primary">B-</div>
                      <span class="inv-group-name">Beta Negative</span>
                    </div>
                  </td>
                  <td class="inv-cell">
                    <div class="progress-track">
                      <div class="progress-fill  <?php echo $B_units > 50 ? 'fill-primary' : ($B_units
                        > 10 ? 'fill-yellow' : 'fill-error') ?>" style="width:<?php echo $percentage['B-']; ?>%;">
                      </div>
                    </div>
                  </td>
                  <td class="inv-cell inv-units"><?php echo $B_units ?> Units</td>
                  <td class="inv-cell"><span
                      class="status-pill <?php echo $B_units > 50 ? 'status-adequate' : ($B_units > 10 ? 'status-low' : 'status-critical') ?>">
                      <?php echo $B_units > 50 ? 'Adequate' : ($B_units > 10 ? 'Low Stock' : 'Critical') ?>
                    </span></td>
                  <td class="inv-cell text-right">
                    <button class="staff-action-btn edit">
                      <span class="material-symbols-outlined">edit</span>
                    </button>
                  </td>
                </tr> -->
                <!-- <?php
                $blood_units = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units FROM stock WHERE blood_group = 'AB-'"));
                $AB_units = (int) $blood_units['units'];
                ?>
                <tr
                  class="inv-row <?php echo $AB_units > 50 ? '' : ($AB_units > 10 ? 'inv-row-low' : 'inv-row-critical') ?>">
                  <td class="inv-cell">
                    <div class="inv-row-info">
                      <div class="blood-badge badge-primary">AB-</div>
                      <span class="inv-group-name">AB Negative</span>
                    </div>
                  </td>
                  <td class="inv-cell">
                    <div class="progress-track">
                      <div class="progress-fill  <?php echo $AB_units > 50 ? 'fill-primary' : ($AB_units
                        > 10 ? 'fill-yellow' : 'fill-error') ?>" style="width:<?php echo $percentage['AB-']; ?>%;">
                      </div>
                    </div>
                  </td>
                  <td class="inv-cell inv-units"><?php echo $AB_units ?> Units</td>
                  <td class="inv-cell"><span
                      class="status-pill <?php echo $AB_units > 50 ? 'status-adequate' : ($AB_units > 10 ? 'status-low' : 'status-critical') ?>">
                      <?php echo $AB_units > 50 ? 'Adequate' : ($AB_units > 10 ? 'Low Stock' : 'Critical') ?>
                    </span></td>
                  <td class="inv-cell text-right">
                    <button class="staff-action-btn edit">
                      <span class="material-symbols-outlined">edit</span>
                    </button>
                  </td>
                </tr> -->
                <!-- <?php
                $blood_units = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units FROM stock WHERE blood_group = 'O+'"));
                $O_units = (int) $blood_units['units'];
                ?>
                <tr
                  class="inv-row <?php echo $O_units > 50 ? '' : ($O_units > 10 ? 'inv-row-low' : 'inv-row-critical') ?>">
                  <td class="inv-cell">
                    <div class="inv-row-info">
                      <div class="blood-badge badge-primary">O+</div>
                      <span class="inv-group-name">Universal Positive</span>
                    </div>
                  </td>
                  <td class="inv-cell">
                    <div class="progress-track">
                      <div class="progress-fill  <?php echo $O_units > 50 ? 'fill-primary' : ($O_units
                        > 10 ? 'fill-yellow' : 'fill-error') ?>" style="width:<?php echo $percentage['O+']; ?>%;">
                      </div>
                    </div>
                  </td>
                  <td class="inv-cell inv-units"><?php echo $O_units ?> Units</td>
                  <td class="inv-cell"><span
                      class="status-pill <?php echo $O_units > 50 ? 'status-adequate' : ($O_units > 10 ? 'status-low' : 'status-critical') ?>">
                      <?php echo $O_units > 50 ? 'Adequate' : ($O_units > 10 ? 'Low Stock' : 'Critical') ?>
                    </span></td>
                  <td class="inv-cell text-right">
                    <button class="staff-action-btn edit">
                      <span class="material-symbols-outlined">edit</span>
                    </button>
                  </td>
                </tr> -->
              </tbody>
            </table>
          </div>
          <!-- <div class="stock-table-footer">
            <button class="view-all-btn">View All Blood Groups</button>
          </div> -->
        </div>

        <!-- Analytics Sidebar -->
        <div class="analytics-col">

          <!-- Donut Chart -->
          <div class="glass-card analytics-card">
            <?php
            $A_per = $total_units > 0 ? round(($A / $total_units) * 100) : 0;
            $B_per = $total_units > 0 ? round(($B / $total_units) * 100) : 0;
            $ABO_per = $total_units > 0 ? round(($ABO / $total_units) * 100) : 0;

            ?>
            <h3 class="analytics-title">Stock Distribution</h3>
            <div class="donut-wrap">
              <svg class="donut-svg" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                  stroke="#e5e2e1" stroke-dasharray="100, 100" stroke-width="4" />
                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                  stroke="#b7131a" stroke-dasharray="<?php echo $A_per; ?>, 100" stroke-width="4" />
                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                  stroke="#d93630" stroke-dasharray="<?php echo $B_per; ?>, 100"
                  stroke-dashoffset="-<?php echo $A_per; ?>" stroke-dashoffset="-45" stroke-width="4" />
                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none"
                  stroke="#006578" stroke-dasharray="<?php echo $ABO_per; ?>, 100"
                  stroke-dashoffset="-<?php echo $A_per + $B_per; ?>" stroke-dashoffset="-70" stroke-width="4" />
              </svg>
              <div class="donut-center">
                <span class="donut-total"><?php echo $total_units; ?></span>
                <span class="donut-label">Units</span>
              </div>
            </div>
            <div class="donut-legend">
              <div class="legend-row">
                <div class="legend-left">
                  <div class="legend-dot legend-dot-primary"></div>
                  <span>Type A Group</span>
                </div>
                <span class="legend-pct"><?php echo $A_per; ?>%</span>
              </div>
              <div class="legend-row">
                <div class="legend-left">
                  <div class="legend-dot legend-dot-secondary"></div>
                  <span>Type B Group</span>
                </div>
                <span class="legend-pct"><?php echo $B_per; ?>%</span>
              </div>
              <div class="legend-row">
                <div class="legend-left">
                  <div class="legend-dot legend-dot-tertiary"></div>
                  <span>Type AB &amp; O</span>
                </div>
                <span class="legend-pct"><?php echo $ABO_per; ?>%</span>
              </div>
            </div>
          </div>

          <!-- Report Card -->
          <!-- <div class="glass-card report-card">
            <div class="report-card-bg-icon">
              <span class="material-symbols-outlined">clinical_notes</span>
            </div>
            <h4 class="report-title">Generate Inventory Report</h4>
            <p class="report-desc">Export detailed CSV/PDF of current stock, expiry dates, and usage trends.</p>
            <button class="report-download-btn">
              <span class="material-symbols-outlined">download</span>
              Download Report
            </button>
          </div> -->

        </div>
      </div>
      <footer class="page-footer">
        <p class="footer-copy">
          © 2024 Hemoglobin Blood Management System. Life-saving efficiency.
        </p>
        <div class="footer-links">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
          <a href="#">Contact Support</a>
        </div>
      </footer>

    </main>
  </div>



</body>

</html>