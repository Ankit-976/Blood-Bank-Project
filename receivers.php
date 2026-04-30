<?php 
  session_start();

include("db/db.php");

if (!isset($_SESSION['admin'])){
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Receiver Management | HEMOGLOBIN</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <link href="style.css" rel="stylesheet"/>
</head>
<body>

<!-- ═══════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════ -->
<aside class="sidebar">

  <!-- Brand -->
  <div class="sidebar-brand">
    <h1>HEMOGLOBIN</h1>
    <p style="font-size:11px; color:#64748b; font-weight:500; margin-top:4px;">Admin Panel</p>
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
    <!-- <a href="#">
      <span class="material-symbols-outlined">list_alt</span>
      <span class="nav-label">Requests</span>
    </a>
    <a href="#">
      <span class="material-symbols-outlined">analytics</span>
      <span class="nav-label">Reports</span>
    </a> -->
  </nav>

  <!-- Admin Profile + Logout -->
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
          href="index.php"
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

<!-- ═══════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════ -->
<main class="main-content">

  <!-- Page Header -->
  <header class="recv-header">
    <div>
      <nav class="recv-breadcrumb">
        <span>ADMIN</span>
        <span>/</span>
        <span class="current">RECEIVERS</span>
      </nav>
      <h1>Receiver Requests</h1>
      <p>Manage blood requests and life-saving distributions.</p>
    </div>
    <button class="recv-header-btn">
      <span class="material-symbols-outlined" style="font-size:20px;">add</span>
      New Request
    </button>
  </header>

  <!-- Stats Cards -->
  <div class="recv-stats-grid" style="margin-bottom: 24px;">

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

  </div>

  <!-- Two-column grid -->
  <div class="recv-grid">

    <!-- ── Left: Receiver Details Form ── -->
    <section class="recv-form-card">
      <h3>
        <span class="material-symbols-outlined">person_add</span>
        Receiver Details
      </h3>

      <form class="recv-form">

        <div class="recv-field">
          <label class="recv-label">Full Name</label>
          <input class="recv-input" type="text" placeholder="John Doe"/>
        </div>

        <div class="recv-two-col">
          <div class="recv-field">
            <label class="recv-label">Receiver ID</label>
            <input class="recv-input" type="text" placeholder="REC-901"/>
          </div>
          <div class="recv-field">
            <label class="recv-label">DOB</label>
            <input class="recv-input" type="date"/>
          </div>
        </div>

        <div class="recv-field">
          <label class="recv-label">Phone</label>
          <input class="recv-input" type="tel" placeholder="+1 (555) 000-0000"/>
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
            <input class="recv-input" type="date"/>
          </div>
        </div>

        <div class="recv-field">
          <label class="recv-label">Assistant Staff</label>
          <input class="recv-input" type="text" placeholder="Dr. Sarah Smith"/>
        </div>

        <div class="recv-field">
          <label class="recv-label">Purpose</label>
          <input class="recv-input" type="text" placeholder="Surgery / Post-Trauma"/>
        </div>

        <button class="recv-submit" type="submit">Create Request</button>

      </form>
    </section>

    <!-- ── Right: Receiver Table ── -->
    <section class="recv-table-card">

      <!-- Toolbar -->
      <div class="recv-table-toolbar">
        <div class="recv-search-wrap">
          <span class="material-symbols-outlined">search</span>
          <input class="recv-search-input" type="text" placeholder="Search receivers..."/>
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

      <!-- Table -->
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

            <!-- Row 1 -->
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

            <!-- Row 2 -->
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

            <!-- Row 3 -->
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

            <!-- Row 4 -->
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

      <!-- Table Footer -->
      <div class="recv-table-footer">
        <p class="recv-table-count">Showing 4 of 142 results</p>
        <div class="recv-pagination">
          <button class="recv-page-btn">Previous</button>
          <button class="recv-page-btn">Next</button>
        </div>
      </div>

    </section>

  </div><!-- /recv-grid -->

  <!-- Footer -->
  <footer class="page-footer">
    <p class="footer-copy">© 2024 Hemoglobin Blood Management System. Life-saving efficiency.</p>
    <div class="footer-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Contact Support</a>
    </div>
  </footer>

</main>

</body>
</html>
