<?php 
include "auth_check.php"; 
include "../includes/db.php"; 

$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$discounted_products = $conn->query("SELECT COUNT(*) as count FROM products WHERE discount_price IS NOT NULL AND discount_price > 0")->fetch_assoc()['count'];
$today = date("Y-m-d");
$today_hits_query = $conn->query("SELECT view_count FROM site_stats WHERE visit_date = '$today'");
$today_hits = $today_hits_query->num_rows > 0 ? $today_hits_query->fetch_assoc()['view_count'] : 0;
$total_visits_query = $conn->query("SELECT SUM(view_count) as total FROM site_stats");
$total_visits = $total_visits_query->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root { 
            --primary: #c49b63; 
            --dark: #121212; 
            --sidebar-bg: #1a1a1a;
            --bg: #f8f9fa; 
            --white: #ffffff;
            --border: #eef0f2;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; color: #333; overflow-x: hidden; }
        
        .sidebar-toggle {
            position: fixed; top: 20px; left: 20px; z-index: 1100;
            background: var(--sidebar-bg); color: var(--primary);
            border: 1px solid var(--primary); padding: 10px 15px; border-radius: 8px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: 0.3s;
        }
        .sidebar-toggle:hover { transform: scale(1.05); }

        .sidebar { 
            width: 280px; height: 100vh; background: var(--sidebar-bg); color: #fff; 
            padding: 40px 20px; position: fixed; left: 0; top: 0; 
            box-shadow: 4px 0 15px rgba(0,0,0,0.1); z-index: 1000;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar.closed { transform: translateX(-280px); }

        .sidebar h2 { 
            font-family: 'Playfair Display', serif; color: var(--primary); 
            font-size: 24px; margin-bottom: 40px; text-align: center; letter-spacing: 2px; 
        }

        .nav-item { 
            padding: 14px 18px; color: #a0a0a0; text-decoration: none; 
            display: flex; align-items: center; border-radius: 12px; 
            transition: 0.3s; margin-bottom: 8px; font-weight: 500; font-size: 15px; 
        }
        .nav-item i { margin-right: 15px; width: 20px; font-size: 18px; }
        
        .nav-item.active { 
            background: var(--primary); color: #fff; 
            box-shadow: 0 10px 20px rgba(196, 155, 99, 0.3); 
        }
        .nav-item:hover:not(.active) { background: rgba(196, 155, 99, 0.1); color: var(--primary); }
        .logout { margin-top: 50px; color: #ff5e5e !important; }

        .main-content { 
            flex: 1; margin-left: 280px; padding: 60px; 
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .main-content.expanded { margin-left: 0; padding-top: 80px; }
        
        .header-welcome { margin-bottom: 40px; }
        .header-welcome h1 { font-size: 28px; font-weight: 700; color: var(--dark); }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px; margin-bottom: 50px; }
        .stat-card { 
            background: var(--white); padding: 30px; border-radius: 20px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid var(--border);
            position: relative; overflow: hidden;
        }
        .stat-card::after { content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 4px; background: var(--primary); }
        .stat-card .number { font-size: 32px; font-weight: 800; color: var(--dark); margin-top: 10px; }

        .btn-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .action-btn { 
            background: var(--white); padding: 30px 20px; border-radius: 20px; text-decoration: none; 
            color: var(--dark); font-weight: 600; text-align: center; border: 1px solid var(--border); 
            transition: 0.3s; display: flex; flex-direction: column; align-items: center; gap: 15px;
        }
        .action-btn i { font-size: 24px; color: var(--primary); }
        .action-btn:hover { border-color: var(--primary); transform: translateY(-5px); box-shadow: 0 15px 30px rgba(196, 155, 99, 0.1); }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-280px); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 80px 20px 40px 20px; }
            .sidebar-toggle { display: flex; }
        }

        @media (min-width: 993px) {
            .sidebar-toggle { display: none; }
            .sidebar.closed ~ .sidebar-toggle { display: flex; }
        }
    </style>
</head>
<body>

<button class="sidebar-toggle" id="toggleBtn">
    <i class="fa-solid fa-bars"></i>
</button>

<aside class="sidebar" id="sidebar">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>D. FURNITURE</h2>
        <i class="fa-solid fa-arrow-left" id="closeBtn" style="color: var(--primary); cursor: pointer; font-size: 20px;"></i>
    </div>
    <nav>
        <a href="index.php" class="nav-item active"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a href="urun_ekle.php" class="nav-item"><i class="fa-solid fa-circle-plus"></i> Add New Product</a>
        <a href="urun_liste.php" class="nav-item"><i class="fa-solid fa-list"></i> Product List</a>
        <a href="galeri_ekle.php" class="nav-item"><i class="fa-solid fa-images"></i> Gallery Management</a>
        <a href="yorum_ekle.php" class="nav-item"><i class="fa-solid fa-star"></i> Customer Reviews</a>
        <a href="ayarlar.php" class="nav-item"><i class="fa-solid fa-sliders"></i> Site Settings</a>
        <a href="logout.php" class="nav-item logout"><i class="fa-solid fa-power-off"></i> Secure Logout</a>
    </nav>
</aside>

<main class="main-content" id="mainContent">
    <header class="header-welcome">
        <h1>Welcome, <?php echo $_SESSION['admin_name'] ?? 'Admin'; ?></h1>
        <p>Welcome to the Furniture Management Center.</p>
    </header>

    <div class="stats-grid">
        <div class="stat-card">
            <h3 style="font-size: 12px; color: #888; text-transform: uppercase;">Total Products</h3>
            <div class="number"><?php echo $total_products; ?></div>
        </div>
        <div class="stat-card">
            <h3 style="font-size: 12px; color: #888; text-transform: uppercase;">Daily Visits</h3>
            <div class="number"><?php echo number_format($today_hits); ?></div>
        </div>
        <div class="stat-card">
            <h3 style="font-size: 12px; color: #888; text-transform: uppercase;">Total Visits</h3>
            <div class="number"><?php echo number_format($total_visits); ?></div>
        </div>
    </div>

    <div class="quick-actions">
        <h3 style="margin-bottom: 20px; font-weight: 700;"><i class="fa-solid fa-bolt" style="color:var(--primary)"></i> Quick Actions</h3>
        <div class="btn-grid">
            <a href="urun_ekle.php" class="action-btn"><i class="fa-solid fa-circle-plus"></i> Add Product</a>
            <a href="urun_liste.php" class="action-btn"><i class="fa-solid fa-pen-to-square"></i> Edit Products</a>
            <a href="../index.php" target="_blank" class="action-btn"><i class="fa-solid fa-eye"></i> View Site</a>
        </div>
    </div>
</main>

<script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleBtn');
    const closeBtn = document.getElementById('closeBtn');

    closeBtn.addEventListener('click', () => {
        sidebar.classList.add('closed');
        sidebar.classList.remove('active');
        mainContent.classList.add('expanded');
        toggleBtn.style.display = 'flex';
    });

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.remove('closed');
        sidebar.classList.add('active');
        mainContent.classList.remove('expanded');
        if(window.innerWidth > 992) toggleBtn.style.display = 'none';
    });

    window.addEventListener('resize', () => {
        if(window.innerWidth > 992 && !sidebar.classList.contains('closed')) {
            toggleBtn.style.display = 'none';
        } else {
            toggleBtn.style.display = 'flex';
        }
    });
</script>

</body>
</html>