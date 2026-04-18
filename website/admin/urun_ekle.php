<?php 
include "auth_check.php"; 
include "../includes/db.php"; 

$message = "";

if (isset($_POST['urun_ekle'])) {
    $urun_adi = mysqli_real_escape_string($conn, $_POST['urun_adi']);
    $aciklama = mysqli_real_escape_string($conn, $_POST['aciklama']);
    $fiyat = $_POST['fiyat'];
    $indirim_fiyat = $_POST['indirim_fiyat'] ?: 'NULL';
    $category_id = $_POST['category_id'];

    $target_dir = "../uploads/";
    $main_file_name = time() . "_" . basename($_FILES["gorsel"]["name"]);
    $target_file = $target_dir . $main_file_name;

    if (move_uploaded_file($_FILES["gorsel"]["tmp_name"], $target_file)) {
        
        $sql = "INSERT INTO products (name, description, price, discount_price, image, category_id) 
                VALUES ('$urun_adi', '$aciklama', '$fiyat', $indirim_fiyat, '$main_file_name', '$category_id')";
        
        if ($conn->query($sql)) {
            $product_last_id = $conn->insert_id;

            if(!empty($_FILES['galeri']['name'][0])){
                foreach($_FILES['galeri']['tmp_name'] as $key => $tmp_name){
                    if($_FILES['galeri']['name'][$key] != "") {
                        $gal_file_name = time() . "_gal_" . basename($_FILES['galeri']['name'][$key]);
                        $gal_target_file = $target_dir . $gal_file_name;

                        if(move_uploaded_file($tmp_name, $gal_target_file)){
                            $conn->query("INSERT INTO product_images (product_id, image_path) VALUES ('$product_last_id', '$gal_file_name')");
                        }
                    }
                }
            }
            $message = "<div class='alert alert-success'><i class='fa-solid fa-check-circle'></i> Product and gallery images added successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'><i class='fa-solid fa-circle-xmark'></i> Database error: " . $conn->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'><i class='fa-solid fa-triangle-exclamation'></i> An error occurred while uploading the main image.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product | Admin Panel</title>
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
            max-width: 900px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid var(--border); 
        }
        .form-group { margin-bottom: 25px; }
        label { display: block; font-weight: 600; margin-bottom: 10px; color: var(--dark); font-size: 14px; display: flex; align-items: center; gap: 8px; }
        label i { color: var(--primary); width: 20px; }
        
        .form-control { 
            width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #ddd; 
            background: #fafafa; transition: all 0.3s; font-family: inherit; outline: none;
        }
        .form-control:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(196, 155, 99, 0.1); }
        textarea.form-control { height: 120px; resize: vertical; }

        .flex-row { display: flex; gap: 20px; }
        .flex-row > .form-group { flex: 1; }

        .btn-submit { 
            background: var(--dark); color: #fff; padding: 16px; border: none; 
            border-radius: 12px; cursor: pointer; width: 100%; font-weight: 700; 
            font-size: 16px; transition: all 0.3s; margin-top: 10px;
        }
        .btn-submit:hover { background: var(--primary); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(196, 155, 99, 0.3); }

        .alert { padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #e7f6ed; color: #28a745; border: 1px solid #d1eddb; }
        .alert-danger { background: #fdeaea; color: #dc3545; border: 1px solid #f8d7da; }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-280px); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 80px 20px 40px 20px; }
            .sidebar-toggle { display: flex; }
            .flex-row { flex-direction: column; gap: 0; }
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
        <a href="urun_ekle.php" class="nav-item active"><i class="fa-solid fa-circle-plus"></i> Add New Product</a>
        <a href="urun_liste.php" class="nav-item"><i class="fa-solid fa-list"></i> Product List</a>
        <a href="galeri_ekle.php" class="nav-item"><i class="fa-solid fa-images"></i> Gallery Management</a>
        <a href="ayarlar.php" class="nav-item"><i class="fa-solid fa-sliders"></i> Site Settings</a>
        <a href="logout.php" class="nav-item logout"><i class="fa-solid fa-power-off"></i> Secure Logout</a>
    </nav>
</aside>

<main class="main-content" id="mainContent">
    <header class="header-title">
        <h1>Add New Furniture</h1>
        <p>You can add new product information and its gallery to the catalog from here.</p>
    </header>

    <section class="card">
        <?php echo $message; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label><i class="fa-solid fa-tag"></i> Product Name</label>
                <input type="text" name="urun_adi" class="form-control" placeholder="e.g. Chester Sofa Set" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-layer-group"></i> Product Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="" disabled selected>Select Category...</option>
                    <?php 
                    $cats = $conn->query("SELECT * FROM categories");
                    while($c = $cats->fetch_assoc()) {
                        echo "<option value='{$c['id']}'>{$c['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-align-left"></i> Description</label>
                <textarea name="aciklama" class="form-control" placeholder="Enter material info, dimensions, and other details..." required></textarea>
            </div>

            <div class="flex-row">
                <div class="form-group">
                    <label><i class="fa-solid fa-coins"></i> Regular Price ($)</label>
                    <input type="number" name="fiyat" step="0.01" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-percent"></i> Discounted Price ($)</label>
                    <input type="number" name="indirim_fiyat" step="0.01" class="form-control" placeholder="Optional">
                </div>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-image"></i> Main Cover Image</label>
                <input type="file" name="gorsel" accept="image/*" class="form-control" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-images"></i> Product Gallery</label>
                <input type="file" name="galeri[]" accept="image/*" class="form-control" multiple>
                <small style="color:#888; display:block; margin-top:8px;">* You can select multiple photos.</small>
            </div>

            <button type="submit" name="urun_ekle" class="btn-submit">
                <i class="fa-solid fa-plus-circle"></i> Save Product to System
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