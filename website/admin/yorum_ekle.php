<?php
ob_start();
include "auth_check.php";
include "../includes/db.php";

$message = "";

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM reviews WHERE id = $id";
    if ($conn->query($delete_sql)) {
        header("Location: yorum_ekle.php?status=deleted");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_review'])) {
    $name = $conn->real_escape_string(trim($_POST['customer_name']));
    $location = $conn->real_escape_string(trim($_POST['customer_location']));
    $text = $conn->real_escape_string(trim($_POST['review_text']));
    $rating = (int)$_POST['rating'];

    $sql = "INSERT INTO reviews (customer_name, customer_location, review_text, rating) 
            VALUES ('$name', '$location', '$text', $rating)";
    
    if ($conn->query($sql)) {
        header("Location: yorum_ekle.php?status=success");
        exit;
    } else {
        $message = "<div class='alert alert-danger'><i class='fa-solid fa-circle-xmark'></i> Error: " . $conn->error . "</div>";
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $message = "<div class='alert alert-success'><i class='fa-solid fa-circle-check'></i> New review added successfully!</div>";
    } elseif ($_GET['status'] == 'deleted') {
        $message = "<div class='alert alert-success'><i class='fa-solid fa-trash-can'></i> Review deleted successfully.</div>";
    }
}

$reviews = $conn->query("SELECT * FROM reviews ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews Management | Admin Panel</title>
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
        body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; color: #333; min-height: 100vh; overflow-x: hidden; }

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
        .nav-item.active { background: var(--primary); color: #fff; box-shadow: 0 10px 20px rgba(196, 155, 99, 0.3); }
        .nav-item:hover:not(.active) { background: rgba(196, 155, 99, 0.1); color: var(--primary); }
        .logout { margin-top: 50px; color: #ff5e5e !important; }

        .main-content { 
            flex: 1; margin-left: 280px; padding: 60px; 
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .main-content.expanded { margin-left: 0; }

        .header-welcome { margin-bottom: 40px; }
        .header-welcome h1 { font-size: 28px; font-weight: 700; color: var(--dark); }

        .management-grid { display: grid; grid-template-columns: 380px 1fr; gap: 30px; align-items: start; }
        .grid-full { grid-template-columns: 1fr; }

        .card { 
            background: var(--white); padding: 35px; border-radius: 20px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid var(--border);
            position: relative; overflow: hidden;
        }
        .card::after { content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 4px; background: var(--primary); }

        label { display: block; font-weight: 600; font-size: 13px; margin-bottom: 8px; color: var(--dark); }
        .form-control { 
            width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border); 
            background: #fafafa; margin-bottom: 20px; outline: none; transition: 0.3s; font-family: inherit;
        }
        .form-control:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(196, 155, 99, 0.1); }
        
        .btn-submit { 
            background: var(--dark); color: #fff; padding: 16px; border: none; border-radius: 12px; 
            width: 100%; cursor: pointer; font-weight: 700; transition: 0.3s;
        }
        .btn-submit:hover { background: var(--primary); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(196, 155, 99, 0.2); }

        .list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .toggle-view-btn { 
            background: none; border: 1px solid var(--primary); color: var(--primary); 
            padding: 8px 15px; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 13px; transition: 0.3s;
        }
        .toggle-view-btn:hover { background: var(--primary); color: #fff; }

        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table th { text-align: left; padding: 15px; font-size: 12px; color: #888; text-transform: uppercase; border-bottom: 2px solid var(--bg); }
        .custom-table td { padding: 20px 15px; border-bottom: 1px solid var(--border); font-size: 14px; }
        
        .rating-stars { color: #f1c40f; letter-spacing: 2px; }
        .btn-delete { color: #ff5e5e; background: #fff5f5; width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; text-decoration: none; transition: 0.3s; }
        .btn-delete:hover { background: #ff5e5e; color: #fff; transform: rotate(10deg); }

        .alert { padding: 15px 25px; border-radius: 15px; margin-bottom: 30px; border-left: 5px solid; font-weight: 500; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #e7f6ed; color: #28a745; border-left-color: #28a745; }
        .alert-danger { background: #fdeaea; color: #dc3545; border-left-color: #dc3545; }

        .sidebar-toggle {
            position: fixed; top: 20px; left: 20px; z-index: 1100;
            background: var(--sidebar-bg); color: var(--primary);
            border: 1px solid var(--primary); padding: 10px 15px; border-radius: 8px;
            cursor: pointer; display: none; align-items: center; justify-content: center;
        }

        @media (max-width: 1200px) {
            .management-grid { grid-template-columns: 1fr; }
            .sidebar { transform: translateX(-280px); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 80px 20px; }
            .sidebar-toggle { display: flex; }
        }
    </style>
</head>
<body>

<button class="sidebar-toggle" id="toggleBtnMain">
    <i class="fa-solid fa-bars"></i>
</button>

<aside class="sidebar" id="sidebar">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>D. FURNITURE</h2>
        <i class="fa-solid fa-arrow-left" id="closeBtn" style="color: var(--primary); cursor: pointer; font-size: 20px;"></i>
    </div>
    <nav>
        <a href="index.php" class="nav-item"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a href="urun_ekle.php" class="nav-item"><i class="fa-solid fa-circle-plus"></i> Add New Product</a>
        <a href="urun_liste.php" class="nav-item"><i class="fa-solid fa-list"></i> Product List</a>
        <a href="galeri_ekle.php" class="nav-item"><i class="fa-solid fa-images"></i> Gallery Management</a>
        <a href="yorum_ekle.php" class="nav-item active"><i class="fa-solid fa-star"></i> Customer Reviews</a>
        <a href="ayarlar.php" class="nav-item"><i class="fa-solid fa-sliders"></i> Site Settings</a>
        <a href="logout.php" class="nav-item logout"><i class="fa-solid fa-power-off"></i> Logout</a>
    </nav>
</aside>

<main class="main-content" id="mainContent">
    <header class="header-welcome">
        <h1>Customer Reviews</h1>
        <p>Manage customer feedback to be displayed in the testimonials section.</p>
    </header>

    <?php echo $message; ?>

    <div class="management-grid" id="mainGrid">
        <section class="card">
            <h3 style="margin-bottom: 25px; font-weight: 700;"><i class="fa-solid fa-plus-circle" style="color:var(--primary)"></i> Add New</h3>
            <form method="POST">
                <label>Customer Name</label>
                <input type="text" name="customer_name" class="form-control" placeholder="e.g. John Doe" required>
                
                <label>Customer Location</label>
                <input type="text" name="customer_location" class="form-control" placeholder="e.g. New York, NY">
                
                <label>Review Message</label>
                <textarea name="review_text" class="form-control" rows="5" placeholder="Write customer experience here..." required></textarea>
                
                <label>Rating</label>
                <select name="rating" class="form-control">
                    <option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option>
                    <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                    <option value="3">⭐⭐⭐ (3 Stars)</option>
                </select>
                
                <button type="submit" name="add_review" class="btn-submit">Publish Review</button>
            </form>
        </section>

        <section class="card">
            <div class="list-header">
                <h3 style="font-weight: 700;">Existing Reviews</h3>
                <button class="toggle-view-btn" onclick="toggleView()" id="toggleBtnView">
                    <i class="fa-solid fa-eye-slash"></i> Hide List
                </button>
            </div>

            <div id="tableWrapper" style="overflow-x: auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Summary</th>
                            <th>Rating</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($reviews->num_rows > 0): ?>
                            <?php while($row = $reviews->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $row['customer_name']; ?></strong><br>
                                    <small style="color:#aaa;"><?php echo $row['customer_location']; ?></small>
                                </td>
                                <td><span style="color:#666;">"<?php echo mb_strimwidth($row['review_text'], 0, 60, "..."); ?>"</span></td>
                                <td class="rating-stars"><?php echo str_repeat("★", $row['rating']); ?></td>
                                <td style="text-align: right;">
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align:center; padding:30px; color:#ccc;">No reviews found yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtnMain = document.getElementById('toggleBtnMain');
    const closeBtn = document.getElementById('closeBtn');

    closeBtn.addEventListener('click', () => {
        sidebar.classList.add('closed');
        sidebar.classList.remove('active');
        mainContent.classList.add('expanded');
        toggleBtnMain.style.display = 'flex';
    });

    toggleBtnMain.addEventListener('click', () => {
        sidebar.classList.remove('closed');
        sidebar.classList.add('active');
        mainContent.classList.remove('expanded');
        if(window.innerWidth > 1200) toggleBtnMain.style.display = 'none';
    });

    function toggleView() {
        const grid = document.getElementById('mainGrid');
        const wrapper = document.getElementById('tableWrapper');
        const btn = document.getElementById('toggleBtnView');
        
        if(wrapper.style.display === "none") {
            wrapper.style.display = "block";
            grid.classList.remove('grid-full');
            btn.innerHTML = '<i class="fa-solid fa-eye-slash"></i> Hide List';
        } else {
            wrapper.style.display = "none";
            grid.classList.add('grid-full');
            btn.innerHTML = '<i class="fa-solid fa-eye"></i> Show List';
        }
    }
</script>

</body>
</html>
<?php ob_end_flush(); ?>