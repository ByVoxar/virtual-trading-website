<?php 
include "auth_check.php"; 
include "../includes/db.php"; 

$sql = "SELECT p.*, c.category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List | Admin Panel</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --primary: #c49b63; 
            --primary-hover: #b38a52;
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

        .sidebar { 
            width: 280px; height: 100vh; background: var(--sidebar-bg); color: #fff; 
            padding: 40px 20px; position: fixed; left: 0; top: 0; 
            box-shadow: 4px 0 15px rgba(0,0,0,0.1); z-index: 1000;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar.closed { transform: translateX(-280px); }
        .sidebar h2 { font-family: 'Playfair Display', serif; color: var(--primary); font-size: 24px; margin-bottom: 40px; text-align: center; letter-spacing: 2px; }

        .nav-item { 
            padding: 14px 18px; color: #a0a0a0; text-decoration: none; 
            display: flex; align-items: center; border-radius: 12px; 
            transition: 0.3s; margin-bottom: 8px; font-weight: 500; font-size: 15px; 
        }
        .nav-item i { margin-right: 15px; width: 20px; font-size: 18px; }
        .nav-item.active { background: var(--primary); color: #fff; box-shadow: 0 10px 20px rgba(196, 155, 99, 0.3); }
        .nav-item:hover:not(.active) { background: rgba(196, 155, 99, 0.1); color: var(--primary); }
        .logout { margin-top: 50px; color: #ff5e5e !important; }

        .main-content { 
            flex: 1; margin-left: 280px; padding: 60px; 
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .main-content.expanded { margin-left: 0; padding-top: 80px; }
        
        .header-area { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .header-area h1 { font-size: 28px; font-weight: 700; color: var(--dark); }
        
        .btn-add-new { 
            background: var(--dark); color: white; padding: 14px 24px; border-radius: 12px; 
            text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; transition: 0.3s;
        }
        .btn-add-new:hover { background: var(--primary); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(196, 155, 99, 0.2); }

        .table-container { 
            background: var(--white); border-radius: 20px; overflow: hidden; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid var(--border); 
        }
        .admin-table { width: 100%; border-collapse: collapse; text-align: left; }
        .admin-table th { background: #fafafa; padding: 20px; color: #888; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border); }
        .admin-table td { padding: 20px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .admin-table tr:hover td { background: #fcfcfc; }
        
        .product-img { width: 70px; height: 70px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .product-info .name { font-weight: 700; color: var(--dark); font-size: 15px; margin-bottom: 4px; }
        .product-info .cat { font-size: 11px; color: var(--primary); font-weight: 600; background: #fef8f0; padding: 2px 8px; border-radius: 6px; }

        .price-tag { font-weight: 700; color: var(--dark); font-size: 16px; }
        .price-discount { color: #aaa; text-decoration: line-through; font-size: 12px; display: block; }

        .actions { display: flex; gap: 10px; justify-content: flex-end; }
        .btn-action { width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 10px; text-decoration: none; transition: 0.3s; }
        .btn-edit { background: #f0f7ff; color: #007bff; }
        .btn-edit:hover { background: #007bff; color: #fff; }
        .btn-delete { background: #fff5f5; color: #ff4d4d; }
        .btn-delete:hover { background: #ff4d4d; color: #fff; }

        .empty-state { padding: 80px; text-align: center; color: #aaa; }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-280px); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 80px 20px; }
            .sidebar-toggle { display: flex; }
        }
    </style>
</head>
<body>

<button class="sidebar-toggle" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>

<aside class="sidebar" id="sidebar">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>D. FURNITURE</h2>
        <i class="fa-solid fa-arrow-left" id="closeBtn" style="color: var(--primary); cursor: pointer; font-size: 20px;"></i>
    </div>
    <nav>
        <a href="index.php" class="nav-item"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a href="urun_ekle.php" class="nav-item"><i class="fa-solid fa-circle-plus"></i> Add New Product</a>
        <a href="urun_liste.php" class="nav-item active"><i class="fa-solid fa-list"></i> Product List</a>
        <a href="galeri_ekle.php" class="nav-item"><i class="fa-solid fa-images"></i> Gallery Management</a>
        <a href="ayarlar.php" class="nav-item"><i class="fa-solid fa-sliders"></i> Site Settings</a>
        <a href="logout.php" class="nav-item logout"><i class="fa-solid fa-power-off"></i> Secure Logout</a>
    </nav>
</aside>

<main class="main-content" id="mainContent">
    <div class="header-area">
        <div>
            <h1>Product Management</h1>
            <p style="color: #888; margin-top: 5px;">Total <strong><?php echo $result->num_rows; ?></strong> registered products listed.</p>
        </div>
        <a href="urun_ekle.php" class="btn-add-new">
            <i class="fa-solid fa-plus"></i> Add New Product
        </a>
    </div>

    <div class="table-container">
        <?php if($result->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Info</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="../uploads/<?php echo $row['image']; ?>" class="product-img" alt="Product">
                    </td>
                    
                    <td>
                        <div class="product-info">
                            <div class="name"><?php echo $row['name']; ?></div>
                            <div style="font-size: 11px; color: #bbb;">Barcode/ID: #<?php echo $row['id']; ?></div>
                        </div>
                    </td>
                    
                    <td>
                        <span class="cat"><?php echo $row['category_name'] ?? 'General'; ?></span>
                    </td>
                    
                    <td>
                        <?php if($row['discount_price']): ?>
                            <span class="price-discount">$<?php echo number_format($row['price'], 2); ?></span>
                            <div class="price-tag">$<?php echo number_format($row['discount_price'], 2); ?></div>
                        <?php else: ?>
                            <div class="price-tag">$<?php echo number_format($row['price'], 2); ?></div>
                        <?php endif; ?>
                    </td>
                    
                    <td>
                        <div class="actions">
                            <a href="urun_duzenle.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="urun_sil.php?id=<?php echo $row['id']; ?>" 
                               class="btn-action btn-delete" 
                               title="Delete"
                               onclick="return confirm('Are you sure you want to delete this product and its gallery completely?')">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-box-open" style="font-size: 48px; margin-bottom: 20px; display: block;"></i>
            <p>No registered products found in the system.</p>
            <a href="urun_ekle.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Add one now →</a>
        </div>
        <?php endif; ?>
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
</script>

</body>
</html>