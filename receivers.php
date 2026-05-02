<?php
session_start();

include("db/db.php");

if (!isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Receiver Management | HEMOGLOBIN</title>
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
      <a href="donors.php">
        <span class="material-symbols-outlined">group</span>
        <span class="nav-label">Donors</span>
      </a>
      <a href="receivers.php" class="active">
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

  <!-- ═══════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════ -->
  <main class="main-content">

    <!-- Page Header -->
    <header class="recv-header">
      <div>
        <nav class="recv-breadcrumb">
          <!-- <span>ADMIN</span>
          <span>/</span> -->
          <span class="current">RECEIVERS</span>
        </nav>
        <h1>Receiver Management</h1>
        <p>Manage blood receivers and life-saving distributions.</p>
      </div>
      <button class="recv-header-btn">
        <span class="material-symbols-outlined" style="font-size:20px;">add</span>
        New Receiver
      </button>
    </header>

    <!-- Stats Cards -->
    <!-- <div class="recv-stats-grid" style="margin-bottom: 24px;">

      <div class="recv-stat-card">
        <div class="recv-stat-icon orange">
          <span class="material-symbols-outlined">pending_actions</span>
        </div>
        <div>
          <p class="recv-stat-label">Pending</p>
          <h3 class="recv-stat-value">14</h3>
        </div>
      </div>

      <div class="recv-stat-card">
        <div class="recv-stat-icon blue">
          <span class="material-symbols-outlined">check_circle</span>
        </div>
        <div>
          <p class="recv-stat-label">Approved</p>
          <h3 class="recv-stat-value">28</h3>
        </div>
      </div>

      <div class="recv-stat-card">
        <div class="recv-stat-icon green">
          <span class="material-symbols-outlined">vaccines</span>
        </div>
        <div>
          <p class="recv-stat-label">Issued</p>
          <h3 class="recv-stat-value">112</h3>
        </div>
      </div>

      <div class="recv-stat-card">
        <div class="recv-stat-icon red">
          <span class="material-symbols-outlined">emergency</span>
        </div>
        <div>
          <p class="recv-stat-label">Emergency</p>
          <h3 class="recv-stat-value">05</h3>
        </div>
      </div>

    </div> -->

    <table class=" donor-table">
      <thead>
        <tr>
          <th>Receiver Details</th>
          <th>Blood Type</th>
          <th>Aadhar Number</th>
          <th>Purpose</th>
          <th>Receiving Date</th>
          <th>Staff Assisted</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php

        // $result = mysqli_query($conn, "SELECT donors.*, staff.name AS staff_name FROM donors JOIN staff ON donors.staff_id = staff.id");
        // if ($filter) {
        //   $result = mysqli_query($conn, "
        //             SELECT donors.*, staff.name AS staff_name 
        //             FROM donors 
        //             JOIN staff ON donors.staff_id = staff.id
        //             WHERE donors.blood_group = '$filter'
        //         ");
        // } else {
          $result = mysqli_query($conn, "
                    SELECT receivers.*, staff.name AS staff_name 
                    FROM receivers 
                    JOIN staff ON receivers.staff_id = staff.id
                ");
        // }

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
            <td><span class="donor-status"><?php echo $row['aadhaar_no'] ?></span></td>
            <td><span class="donor-date"><?php echo $row['purpose'] ?></span></td>
            <td><span class="donor-date"><?php echo $row['receiving_date'] ?></span></td>
            <td>
              <p class="donor-name"><?php echo ucwords($row['staff_name']) ?></p>
            </td>
            <td>
              <div class="donor-actions">
                <a href="receiver-details.php?details=<?php echo $row['id'] ?>">
                  <button class="donor-action-btn view">
                    <span class="material-symbols-outlined" title="Full Details">visibility</span>
                  </button>
                </a>
                <a href="update-receiver.php?donor=<?php echo $row['id'] ?>">
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

    <!-- Two-column grid -->
    <!-- <div class="recv-grid">

      <section class="recv-form-card">
        <h3>
          <span class="material-symbols-outlined">person_add</span>
          Receiver Details
        </h3>

        <form class="recv-form" method="POST">

          <div class="recv-field">
            <label class="recv-label">Full Name</label>
            <input class="recv-input" type="text" placeholder="John Doe" />
          </div>

          <div class="recv-two-col">
            <div class="recv-field">
              <label class="recv-label">Aadhaar Number</label>
              <input class="recv-input" type="number" placeholder="ex. 258964322497" />
            </div>
            <div class="recv-field">
              <label class="recv-label">DOB</label>
              <input class="recv-input" type="date" />
            </div>
          </div>

          <div class="recv-field">
            <label class="recv-label">Phone</label>
            <input class="recv-input" type="tel" placeholder="+1 (555) 000-0000" />
          </div>

          <div class="recv-field">
            <label class="recv-label">Address</label>
            <textarea class="recv-textarea" rows="2" placeholder="St. Mary's Hospital, Ward 4B"></textarea>
          </div>

          <div class="recv-two-col">
            <div class="recv-field">
              <label class="recv-label">Required Blood Group</label>
              <select class="recv-select">
                <option>Select</option>
                <option>A+</option>
                <option>A-</option>
                <option>B+</option>
                <option>B-</option>
                <option>O+</option>
                <option>O-</option>
                <option>AB+</option>
                <option>AB-</option>
              </select>
            </div>
            <div class="recv-field">
              <label class="recv-label">Receive Date</label>
              <input class="recv-input" type="date" />
            </div>
          </div>

          <div class="recv-field">
            <label class="recv-label">Assistant Staff</label>
            <input class="recv-input" type="text" placeholder="Dr. Sarah Smith" />
          </div>

          <div class="recv-field">
            <label class="recv-label">Purpose</label>
            <input class="recv-input" type="text" placeholder="Surgery / Post-Trauma" />
          </div>

          <button class="recv-submit" type="submit">Create Request</button>

        </form>
      </section>

      <section class="recv-table-card">

        <div class="recv-table-toolbar">
          <div class="recv-search-wrap">
            <span class="material-symbols-outlined">search</span>
            <input class="recv-search-input" type="text" placeholder="Search receivers..." />
          </div>
          <div class="recv-toolbar-actions">
            <button class="recv-icon-btn">
              <span class="material-symbols-outlined">filter_list</span>
            </button>
            <button class="recv-icon-btn">
              <span class="material-symbols-outlined">download</span>
            </button>
          </div>
        </div>

        <div class="recv-table-scroll">
          <table class="recv-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Blood Group</th>
                <th>Purpose</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>

              <tr>
                <td>
                  <div class="recv-cell">
                    <div class="recv-avatar">EM</div>
                    <div>
                      <p class="recv-name">Elena Martinez</p>
                      <p class="recv-meta">REC-882 • 2h ago</p>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="recv-blood-badge">B-</div>
                </td>
                <td><span class="recv-purpose">Emergency C-Section</span></td>
                <td><span class="status-pill pending">Pending</span></td>
                <td class="text-right">
                  <button class="recv-edit-btn">
                    <span class="material-symbols-outlined">edit_note</span>
                  </button>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="recv-cell">
                    <div class="recv-avatar">JW</div>
                    <div>
                      <p class="recv-name">James Wilson</p>
                      <p class="recv-meta">REC-879 • 5h ago</p>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="recv-blood-badge">O+</div>
                </td>
                <td><span class="recv-purpose">Post-Trauma Surgery</span></td>
                <td><span class="status-pill approved">Approved</span></td>
                <td class="text-right">
                  <button class="recv-edit-btn">
                    <span class="material-symbols-outlined">edit_note</span>
                  </button>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="recv-cell">
                    <div class="recv-avatar">SH</div>
                    <div>
                      <p class="recv-name">Sarah Hughes</p>
                      <p class="recv-meta">REC-875 • 1d ago</p>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="recv-blood-badge">A+</div>
                </td>
                <td><span class="recv-purpose">Thalassemia Unit</span></td>
                <td><span class="status-pill issued">Issued</span></td>
                <td class="text-right">
                  <button class="recv-edit-btn">
                    <span class="material-symbols-outlined">edit_note</span>
                  </button>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="recv-cell">
                    <div class="recv-avatar">MK</div>
                    <div>
                      <p class="recv-name">Michael Knight</p>
                      <p class="recv-meta">REC-872 • 2d ago</p>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="recv-blood-badge">AB-</div>
                </td>
                <td><span class="recv-purpose">Cardio Treatment</span></td>
                <td><span class="status-pill issued">Issued</span></td>
                <td class="text-right">
                  <button class="recv-edit-btn">
                    <span class="material-symbols-outlined">edit_note</span>
                  </button>
                </td>
              </tr>

            </tbody>
          </table>
        </div>

        <div class="recv-table-footer">
          <p class="recv-table-count">Showing 4 of 142 results</p>
          <div class="recv-pagination">
            <button class="recv-page-btn">Previous</button>
            <button class="recv-page-btn">Next</button>
          </div>
        </div>

      </section>

    </div> -->



  </main>

</body>

</html>