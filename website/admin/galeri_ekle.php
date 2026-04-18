<?php
include "auth_check.php";
include "../includes/db.php";

$mesaj = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_gallery'])) {
    $target_dir = "../uploads/gallery/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (isset($_POST['slot_id']) && isset($_FILES['gallery_img'])) {
        $slot = (int)$_POST['slot_id'];
        $img_name = time() . "_" . basename($_FILES['gallery_img']['name']);
        $target_file = $target_dir . $img_name;
        
        if (move_uploaded_file($_FILES['gallery_img']['tmp_name'], $target_file)) {
            $sql = "INSERT INTO gallery (image_path, slot_id) VALUES ('$img_name', $slot) 
                    ON DUPLICATE KEY UPDATE image_path = '$img_name'";
            
            if ($conn->query($sql)) {
                $mesaj = "<div class='alert alert-success'><i class='fa-solid fa-circle-check'></i> Slot $slot successfully updated!</div>";
            }
        } else {
            $mesaj = "<div class='alert alert-danger'><i class='fa-solid fa-triangle-exclamation'></i> File could not be uploaded! Check directory permissions.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Management | Admin Panel</title>
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
        
        .header-title { margin-bottom: 35px; }
        .header-title h1 { font-size: 28px; font-weight: 700; color: var(--dark); }
        .header-title p { color: #888; margin-top: 5px; }

        .card { 
            background: var(--white); padding: 40px; border-radius: 20px; 
            max-width: 700px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid var(--border); 
        }
        .form-group { margin-bottom: 25px; }
        label { display: block; font-weight: 600; margin-bottom: 10px; color: var(--dark); font-size: 14px; display: flex; align-items: center; gap: 8px; }
        label i { color: var(--primary); }
        
        select, input[type="file"] { 
            width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #ddd; 
            background: #fafafa; transition: all 0.3s; font-family: inherit; outline: none;
        }
        select:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(196, 155, 99, 0.1); }

        .btn-submit { 
            background: var(--dark); color: #fff; padding: 16px; border: none; 
            border-radius: 12px; cursor: pointer; width: 100%; font-weight: 700; 
            font-size: 16px; transition: all 0.3s; margin-top: 10px;
        }
        .btn-submit:hover { background: var(--primary); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(196, 155, 99, 0.3); }

        .alert { padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; font-size: 14px; }
        .alert-success { background: #e7f6ed; color: #28a745; border: 1px solid #d1eddb; }
        .alert-danger { background: #fdeaea; color: #dc3545; border: 1px solid #f8d7da; }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-280px); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 80px 20px 40px 20px; }
            .sidebar-toggle { display: flex; }
            .card { width: 100%; padding: 25px; }
        }

        @media (min-width: 993px) {
            .sidebar-toggle { display: none; }
            .sidebar.closed ~ .sidebar-toggle { display: flex; }
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
        <a href="urun_liste.php" class="nav-item"><i class="fa-solid fa-list"></i> Product List</a>
        <a href="galeri_ekle.php" class="nav-item active"><i class="fa-solid fa-images"></i> Gallery Management</a>
        <a href="yorum_ekle.php" class="nav-item"><i class="fa-solid fa-star"></i> Customer Reviews</a>
        <a href="ayarlar.php" class="nav-item"><i class="fa-solid fa-sliders"></i> Site Settings</a>
        <a href="logout.php" class="nav-item logout"><i class="fa-solid fa-power-off"></i> Secure Logout</a>
    </nav>
</aside>

<main class="main-content" id="mainContent">
    <header class="header-title">
        <h1>Gallery & Slot Management</h1>
        <p>Manage the images in the "Our Works" section on the homepage.</p>
    </header>

    <section class="card">
        <?php echo $mesaj; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label><i class="fa-solid fa-border-all"></i> Target Square (Slot) Selection</label>
                <select name="slot_id" required>
                    <option value="" disabled selected>Select the slot you want to change...</option>
                    <option value="1">Slot 1 (Large Left Vertical)</option>
                    <option value="2">Slot 2 (Top Middle)</option>
                    <option value="3">Slot 3 (Bottom Middle)</option>
                    <option value="4">Slot 4 (Bottom Right)</option>
                    <option value="5">Slot 5 (Extra)</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-image"></i> Upload New Image</label>
                <input type="file" name="gallery_img" required>
                <small style="color:#888; display:block; margin-top:8px;">* Recommended formats: JPG, PNG. Max: 2MB</small>
            </div>
            
            <button type="submit" name="upload_gallery" class="btn-submit">
                <i class="fa-solid fa-cloud-arrow-up"></i> Save Image to System
            </button>
        </form>
    </section>
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