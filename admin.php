<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "restaurant_reservations";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle status updates
if (isset($_POST['update_status'])) {
    $reservation_id = mysqli_real_escape_string($conn, $_POST['reservation_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
    
    $update_sql = "UPDATE reservations SET status = '$new_status' WHERE reservation_id = '$reservation_id'";
    mysqli_query($conn, $update_sql);
}

// Handle deletion
if (isset($_POST['delete_reservation'])) {
    $reservation_id = mysqli_real_escape_string($conn, $_POST['reservation_id']);
    
    $delete_sql = "DELETE FROM reservations WHERE reservation_id = '$reservation_id'";
    mysqli_query($conn, $delete_sql);
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Build query with filters
$sql = "SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time ASC";
$where_conditions = [];

if ($status_filter) {
    $where_conditions[] = "status = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
}

if ($date_filter) {
    $where_conditions[] = "reservation_date = '" . mysqli_real_escape_string($conn, $date_filter) . "'";
}

if (!empty($where_conditions)) {
    $sql = "SELECT * FROM reservations WHERE " . implode(' AND ', $where_conditions) . " ORDER BY reservation_date DESC, reservation_time ASC";
}

$result = mysqli_query($conn, $sql);

// Get unique statuses for filter
$statuses_sql = "SELECT DISTINCT status FROM reservations ORDER BY status";
$statuses_result = mysqli_query($conn, $statuses_sql);
$statuses = [];
while ($row = mysqli_fetch_assoc($statuses_result)) {
    $statuses[] = $row['status'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Restaurant Reservations</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-dashboard {
            padding-top: 90px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .dashboard-header {
            background: white;
            padding: 30px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .dashboard-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .dashboard-subtitle {
            text-align: center;
            color: #666;
            font-size: 1.1rem;
        }
        
        .filters {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-group label {
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .filter-group select,
        .filter-group input {
            padding: 10px;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            align-items: end;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: #e74c3c;
            color: white;
        }
        
        .btn-primary:hover {
            background: #c0392b;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .btn-success:hover {
            background: #229954;
        }
        
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        
        .btn-warning:hover {
            background: #e67e22;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .reservations-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table-header {
            background: #2c3e50;
            color: white;
            padding: 20px;
        }
        
        .table-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e1e8ed;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-completed {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #e74c3c;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: #c0392b;
        }
        
        .no-reservations {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #666;
            font-size: 1.1rem;
        }
        
        .guest-count {
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .guest-number {
            font-size: 1.2em;
            font-weight: bold;
            color: #e74c3c;
        }

        .guest-label {
            font-size: 0.8em;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .filter-buttons {
                justify-content: center;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .btn-sm {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <i class="fas fa-utensils"></i>
                <span>Fine Dining</span>
            </div>
            <div class="nav-menu">
                <a href="index.html" class="nav-link">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="index.html#reservation" class="nav-link">
                    <i class="fas fa-calendar-check"></i> Reservations
                </a>
                <a href="admin.php" class="nav-link admin-link">
                    <i class="fas fa-user-shield"></i> Admin
                </a>
            </div>
        </div>
    </nav>

    <div class="admin-dashboard">
        <div class="container">
            <a href="index.html" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
            
            <div class="dashboard-header">
                <h1 class="dashboard-title">
                    <i class="fas fa-chart-line"></i> Admin Dashboard
                </h1>
                <p class="dashboard-subtitle">Manage restaurant reservations and bookings</p>
            </div>

            <!-- Statistics -->
            <?php
            $total_sql = "SELECT COUNT(*) as total FROM reservations";
            $total_result = mysqli_query($conn, $total_sql);
            $total = mysqli_fetch_assoc($total_result)['total'];
            
            $today_sql = "SELECT COUNT(*) as today FROM reservations WHERE reservation_date = CURDATE()";
            $today_result = mysqli_query($conn, $today_sql);
            $today = mysqli_fetch_assoc($today_result)['today'];
            
            $pending_sql = "SELECT COUNT(*) as pending FROM reservations WHERE status = 'pending'";
            $pending_result = mysqli_query($conn, $pending_sql);
            $pending = mysqli_fetch_assoc($pending_result)['pending'];
            ?>
            
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total; ?></div>
                    <div class="stat-label">Total Reservations</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $today; ?></div>
                    <div class="stat-label">Today's Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $pending; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters">
                <form method="GET" class="filter-row">
                    <div class="filter-group">
                        <label for="status">Filter by Status</label>
                        <select name="status" id="status">
                            <option value="">All Statuses</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?php echo $status; ?>" <?php echo $status_filter === $status ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($status); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="date">Filter by Date</label>
                        <input type="date" name="date" id="date" value="<?php echo $date_filter; ?>">
                    </div>
                    
                    <div class="filter-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                        <a href="admin.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Reservations Table -->
            <div class="reservations-table">
                <div class="table-header">
                    <h3>
                        <i class="fas fa-list"></i> 
                        Reservations 
                        <?php if ($status_filter || $date_filter): ?>
                            <span style="font-size: 0.8em; opacity: 0.8;">
                                (Filtered)
                            </span>
                        <?php endif; ?>
                    </h3>
                </div>
                
                <div class="table-container">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Guest Name</th>
                                    <th>Contact</th>
                                    <th>Guests</th>
                                    <th>Date & Time</th>
                                    <th>Special Requests</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $row['reservation_id']; ?></strong>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['guest_name']); ?></strong>
                                        </td>
                                        <td>
                                            <div><?php echo htmlspecialchars($row['email']); ?></div>
                                            <div style="color: #666;"><?php echo htmlspecialchars($row['phone']); ?></div>
                                        </td>
                                        <td>
                                            <div class="guest-count">
                                                <span class="guest-number"><?php echo $row['guest_count']; ?></span>
                                                <span class="guest-label"><?php echo $row['guest_count'] == 1 ? 'Person' : 'People'; ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div><strong><?php echo date('M j, Y', strtotime($row['reservation_date'])); ?></strong></div>
                                            <div style="color: #666;"><?php echo date('g:i A', strtotime($row['reservation_time'])); ?></div>
                                        </td>
                                        <td>
                                            <?php if ($row['special_requests']): ?>
                                                <div style="max-width: 200px; word-wrap: break-word;">
                                                    <?php echo htmlspecialchars($row['special_requests']); ?>
                                                </div>
                                            <?php else: ?>
                                                <span style="color: #999;">None</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo $row['status']; ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="actions">
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
                                                    <select name="new_status" onchange="this.form.submit()" class="btn btn-sm btn-secondary">
                                                        <option value="">Change Status</option>
                                                        <option value="confirmed">Confirmed</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="cancelled">Cancelled</option>
                                                        <option value="completed">Completed</option>
                                                    </select>
                                                </form>
                                                
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this reservation?')">
                                                    <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
                                                    <button type="submit" name="delete_reservation" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="no-reservations">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 20px;"></i>
                            <h3>No reservations found</h3>
                            <p>There are no reservations matching your current filters.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit status change form
        document.querySelectorAll('select[name="new_status"]').forEach(select => {
            select.addEventListener('change', function() {
                if (this.value) {
                    this.form.submit();
                }
            });
        });
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>
