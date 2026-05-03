<?php 
  session_start();
  include("db/db.php");

if (!isset($_SESSION['admin'])){
  header("Location: index.php");
  exit();
}

$totalRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(units) AS total_units FROM stock;"));
$total_units = $totalRow['total_units'];
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <!-- Sidebar -->
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
        <a href="dashboard.php" class="active">
          <span class="material-symbols-outlined active-pill">dashboard</span>
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
        <a href="staff.php">
          <span class="material-symbols-outlined">badge</span>
          <span class="nav-label">Staff</span>
        </a>
      </nav>
      <div
        style="
          padding: 24px 32px 0;
          border-top: 1px solid #1e293b;
          margin-top: auto;
        "
      >
        <div class="sidebar-admin" style="margin-bottom: 24px">
          <div
            style="
              width: 40px;
              height: 40px;
              border-radius: 50%;
              background: #1e293b;
              border: 1px solid #334155;
              overflow: hidden;
              flex-shrink: 0;
            "
          >
            <img
              src="https://imgs.search.brave.com/wV3xjLUAA0KAxg04P9n_RbW0hWcPAA_gQKoQltugCew/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9jZG4u/dmVjdG9yc3RvY2su/Y29tL2kvNTAwcC81/Mi82OS9wcm9maWxl/LWNvbXBsZXRpb24t/cHJvZ3Jlc3MtdWkt/ZWxlbWVudC10ZW1w/bGF0ZS12ZWN0b3It/NDUxOTUyNjkuanBn"
              alt="Admin Profile"
              style="width: 100%; height: 100%; object-fit: cover"
            />
          </div>
          <div>
            <?php 
              $result = mysqli_query($conn, "SELECT * FROM admin");
              $row = mysqli_fetch_assoc($result);
            ?>
            <p class="sidebar-admin-name"><?php echo ucwords($row['name'])?></p>
            <p class="sidebar-admin-role"><?php echo strtolower($row['email'])?></p>
          </div>
        </div>
        <a
          href="logout.php"
          style="
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ef4444;
            text-decoration: none;
            font-size: 14px;
            font-family: &quot;Manrope&quot;, sans-serif;
            font-weight: 500;
            transition: color 0.2s;
          "
        >
          <span class="material-symbols-outlined">logout</span>
          <span>Logout</span>
        </a>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Header -->
      <header class="page-header">
        <div>
          <p class="font-label-caps text-primary page-header-label">
            SYSTEM OVERVIEW
          </p>
          <h2 class="font-h2 text-on-surface">Command Center</h2>
        </div>
        <div class="header-actions">
          <button class="btn-outline">
            <span class="material-symbols-outlined" style="font-size: 20px"
              >calendar_today</span
            >
            Last 30 Days
          </button>
          <button class="btn-primary">
            <span class="material-symbols-outlined" style="font-size: 20px"
              >add_circle</span
            >
            New Entry
          </button>
        </div>
      </header>

      <!-- Stats Bento Grid -->
      <div class="stats-grid">
        <!-- Total Donors -->
        <div class="glass-card stat-card">
          <div class="stat-card-top">
            <div class="stat-icon icon-primary">
              <span class="material-symbols-outlined">volunteer_activism</span>
            </div>
          </div>
          <div class="stat-bottom">
            <p class="font-label-caps text-on-surface-variant stat-label">
              Total Donors
            </p>
            <?php 
              $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM donors");
              $row = mysqli_fetch_assoc($result);
            ?>
            <p class="stat-value"><?php echo $row['total']?></p>
          </div>
        </div>

        <!-- Total Receivers -->
        <div class="glass-card stat-card">
          <div class="stat-card-top">
            <div class="stat-icon icon-tertiary">
              <span class="material-symbols-outlined">medical_services</span>
            </div>
          </div>
          <div class="stat-bottom">
            <p class="font-label-caps text-on-surface-variant stat-label">
              Total Receivers
            </p>
            <p class="stat-value">856</p>
          </div>
        </div>

        <!-- Available Units -->
        <div class="glass-card stat-card border-accent">
          <div class="stat-card-top">
            <div class="stat-icon icon-red">
              <span
                class="material-symbols-outlined"
                style="font-variation-settings: &quot;FILL&quot; 1"
                >bloodtype</span
              >
            </div>
          </div>
          <div class="stat-bottom">
            <p class="font-label-caps text-on-surface-variant stat-label">
              Available Units
            </p>
            <p class="stat-value"><?php echo $total_units ?></p>
          </div>
        </div>

        <!-- Pending Requests -->
        <!-- <div class="glass-card stat-card">
          <div class="stat-card-top">
            <div class="stat-icon icon-amber">
              <span class="material-symbols-outlined">priority_high</span>
            </div>
            <span class="stat-badge badge-amber">Urgent</span>
          </div>
          <div class="stat-bottom">
            <p class="font-label-caps text-on-surface-variant stat-label">
              Pending Requests
            </p>
            <p class="stat-value">24</p>
          </div>
        </div> -->

        <!-- Staff Members -->
        <div class="glass-card stat-card">
          <div class="stat-card-top">
            <div class="stat-icon icon-slate">
              <span class="material-symbols-outlined">badge</span>
            </div>
          </div>
          <div class="stat-bottom">
                        <?php 
              $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM staff");
              $row = mysqli_fetch_assoc($result);
            ?>
            <p class="font-label-caps text-on-surface-variant stat-label">
              Staff Members
            </p>
            <p class="stat-value"><?php echo $row['total'] ?></p>
          </div>
        </div>
      </div>

      <!-- Charts Grid -->
      <div class="charts-grid">
        <!-- Blood Stock Inventory -->
        <div
          class="glass-card stat-card col-span-2"
          style="padding: var(--spacing-md); border-radius: var(--radius-xl)"
        >
          <div class="chart-header">
            <h3 class="font-h3 text-lg">Blood Stock Inventory</h3>
            <div class="flex-row gap-xs">
              <div class="chart-legend">
                <div class="legend-dot dot-primary"></div>
                <span class="legend-text">Current Units</span>
              </div>
              <div class="chart-legend" style="margin-left: 12px">
                <div class="legend-dot dot-slate"></div>
                <span class="legend-text">Optimal Units</span>
              </div>
            </div>
          </div>

          <div class="bar-chart">
            <!-- A+ -->
            <div class="bar-group">
              <div class="bar-track">
                <div class="bar-fill" style="height: 75%"></div>
              </div>
              <p class="bar-label">A+</p>
            </div>
            <!-- A- -->
            <div class="bar-group">
              <div class="bar-track">
                <div class="bar-fill" style="height: 35%"></div>
              </div>
              <p class="bar-label">A-</p>
            </div>
            <!-- B+ -->
            <div class="bar-group">
              <div class="bar-track">
                <div class="bar-fill" style="height: 60%"></div>
              </div>
              <p class="bar-label">B+</p>
            </div>
            <!-- B- -->
            <div class="bar-group">
              <div class="bar-track">
                <div class="bar-fill" style="height: 20%"></div>
              </div>
              <p class="bar-label">B-</p>
            </div>
            <!-- AB+ -->
            <div class="bar-group">
              <div class="bar-track">
                <div class="bar-fill" style="height: 45%"></div>
              </div>
              <p class="bar-label">AB+</p>
            </div>
            <!-- AB- -->
            <div class="bar-group">
              <div class="bar-track">
                <div class="bar-fill" style="height: 15%"></div>
              </div>
              <p class="bar-label">AB-</p>
            </div>
            <!-- O+ -->
            <div class="bar-group">
              <div class="bar-track">
                <div class="bar-fill" style="height: 95%"></div>
              </div>
              <p class="bar-label">O+</p>
            </div>
            <!-- O- -->
            <div class="bar-group">
              <div class="bar-track">
                <div class="bar-fill" style="height: 30%"></div>
              </div>
              <p class="bar-label">O-</p>
            </div>
          </div>
        </div>

        <!-- Donation Growth -->
        <div
          class="glass-card stat-card growth-card"
          style="padding: var(--spacing-md); border-radius: var(--radius-xl)"
        >
          <div class="growth-card-content">
            <h3
              class="font-h3 text-lg"
              style="margin-bottom: var(--spacing-xs)"
            >
              Donation Growth
            </h3>
            <p
              class="text-body-sm text-on-surface-variant"
              style="margin-bottom: var(--spacing-md)"
            >
              Compared to previous quarter
            </p>
            <div class="growth-percent">+22.4%</div>
          </div>

          <!-- Background SVG chart -->
          <div class="growth-chart-bg">
            <svg viewBox="0 0 400 180" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0,150 Q50,130 100,140 T200,80 T300,100 T400,40 L400,180 L0,180 Z"
                fill="#b7131a"
              ></path>
              <path
                d="M0,150 Q50,130 100,140 T200,80 T300,100 T400,40"
                fill="none"
                stroke="#b7131a"
                stroke-width="3"
              ></path>
            </svg>
          </div>

          <div class="growth-stats">
            <div class="growth-stat-row">
              <span class="text-body-sm" style="font-weight: 500"
                >May 2024</span
              >
              <span class="text-body-sm" style="font-weight: 700"
                >142 Units</span
              >
            </div>
            <div class="growth-stat-row">
              <span class="text-body-sm" style="font-weight: 500"
                >June 2024</span
              >
              <span class="text-body-sm" style="font-weight: 700"
                >168 Units</span
              >
            </div>
            <div class="growth-stat-row">
              <span class="text-body-sm" style="font-weight: 500"
                >July 2024</span
              >
              <span class="text-body-sm" style="font-weight: 700"
                >189 Units</span
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity Table -->
      <div class="glass-card activity-table-wrapper">
        <div class="table-header-bar">
          <h3 class="font-h3 text-lg">Recent Activity</h3>
          <a href="#" class="table-link">View All Records</a>
        </div>

        <div class="table-scroll">
          <table>
            <thead>
              <tr>
                <th>Entity Name</th>
                <th>Type</th>
                <th>Blood Group</th>
                <th>Status</th>
                <th class="text-right">Timestamp</th>
              </tr>
            </thead>
            <tbody>
              <!-- Row 1 -->
              <tr>
                <td>
                  <div class="flex-row gap-xs">
                    <div class="avatar-initials">JD</div>
                    <div>
                      <p class="entity-name">Johnathan Doe</p>
                      <p class="entity-id">ID: DN-8821</p>
                    </div>
                  </div>
                </td>
                <td><span class="type-badge badge-donation">Donation</span></td>
                <td>
                  <div class="flex-row gap-2">
                    <span class="avatar-blood">O</span>
                    <span class="blood-sign">+</span>
                  </div>
                </td>
                <td>
                  <div class="flex-row gap-xs">
                    <span class="status-dot green"></span>
                    <span class="text-body-sm">Completed</span>
                  </div>
                </td>
                <td class="td-right text-body-sm text-on-surface-variant">
                  Today, 10:45 AM
                </td>
              </tr>
              <!-- Row 2 -->
              <tr>
                <td>
                  <div class="flex-row gap-xs">
                    <div class="avatar-initials">SH</div>
                    <div>
                      <p class="entity-name">St. Mary's Hospital</p>
                      <p class="entity-id">ID: RQ-1102</p>
                    </div>
                  </div>
                </td>
                <td><span class="type-badge badge-request">Request</span></td>
                <td>
                  <div class="flex-row gap-2">
                    <span class="avatar-blood">AB</span>
                    <span class="blood-sign">-</span>
                  </div>
                </td>
                <td>
                  <div class="flex-row gap-xs">
                    <span class="status-dot amber"></span>
                    <span class="text-body-sm">In Transit</span>
                  </div>
                </td>
                <td class="td-right text-body-sm text-on-surface-variant">
                  Today, 09:12 AM
                </td>
              </tr>
              <!-- Row 3 -->
              <tr>
                <td>
                  <div class="flex-row gap-xs">
                    <div class="avatar-initials">SM</div>
                    <div>
                      <p class="entity-name">Sarah Miller</p>
                      <p class="entity-id">ID: DN-8819</p>
                    </div>
                  </div>
                </td>
                <td><span class="type-badge badge-donation">Donation</span></td>
                <td>
                  <div class="flex-row gap-2">
                    <span class="avatar-blood">A</span>
                    <span class="blood-sign">+</span>
                  </div>
                </td>
                <td>
                  <div class="flex-row gap-xs">
                    <span class="status-dot red"></span>
                    <span class="text-body-sm">Testing</span>
                  </div>
                </td>
                <td class="td-right text-body-sm text-on-surface-variant">
                  Yesterday, 04:30 PM
                </td>
              </tr>
              <!-- Row 4 -->
              <tr>
                <td>
                  <div class="flex-row gap-xs">
                    <div class="avatar-initials">CC</div>
                    <div>
                      <p class="entity-name">City Clinic</p>
                      <p class="entity-id">ID: RQ-1099</p>
                    </div>
                  </div>
                </td>
                <td><span class="type-badge badge-request">Request</span></td>
                <td>
                  <div class="flex-row gap-2">
                    <span class="avatar-blood">B</span>
                    <span class="blood-sign">-</span>
                  </div>
                </td>
                <td>
                  <div class="flex-row gap-xs">
                    <span class="status-dot slate"></span>
                    <span class="text-body-sm">Scheduled</span>
                  </div>
                </td>
                <td class="td-right text-body-sm text-on-surface-variant">
                  Yesterday, 02:15 PM
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="table-footer">
          <span class="text-body-sm text-on-surface-variant"
            >Showing 4 of 1,284 activities</span
          >
          <div class="pagination">
            <button class="pagination-btn">
              <span class="material-symbols-outlined" style="font-size: 18px"
                >chevron_left</span
              >
            </button>
            <button class="pagination-btn">
              <span class="material-symbols-outlined" style="font-size: 18px"
                >chevron_right</span
              >
            </button>
          </div>
        </div>
      </div>

      <!-- Footer -->
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

    <!-- Floating Action Button -->
    <button class="fab">
      <span class="material-symbols-outlined">add</span>
    </button>
  </body>
</html>
