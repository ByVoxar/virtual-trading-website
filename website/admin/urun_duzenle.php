<?php 
include "auth_check.php"; 
include "../includes/db.php"; 

if(!isset($_GET['id'])) { header("Location: urun_liste.php"); exit(); }
$id = (int)$_GET['id'];
$query = $conn->query("SELECT * FROM products WHERE id = $id");
$p = $query->fetch_assoc();

if(!$p) { die("Product not found!"); }

$mesaj = "";
if(isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    
    if(!empty($_FILES['image']['name'])) {
        $img_name = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $img_name);
        $img_sql = ", image = '$img_name'";
    } else {
        $img_sql = "";
    }

    $sql = "UPDATE products SET name='$name', price='$price', description='$desc' $img_sql WHERE id=$id";
    if($conn->query($sql)) {
        $mesaj = "<div class='alert alert-success'><i class='fa-solid fa-check-circle'></i> Product updated successfully!</div>";
        $query = $conn->query("SELECT * FROM products WHERE id = $id");
        $p = $query->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Management Panel</title>
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
        
        .header-title { margin-bottom: 35px; display: flex; justify-content: space-between; align-items: flex-end; }
        .header-title h1 { font-size: 28px; font-weight: 700; color: var(--dark); }

        .card { 
            background: var(--white); padding: 40px; border-radius: 20px; 
            max-width: 850px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid var(--border); 
        }
        .form-group { margin-bottom: 25px; }
        label { display: block; font-weight: 600; margin-bottom: 10px; color: var(--dark); font-size: 14px; }
        
        .form-control { 
            width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #ddd; 
            background: #fafafa; transition: all 0.3s; font-family: inherit; outline: none;
        }
        .form-control:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(196, 155, 99, 0.1); }

        .image-preview-wrapper {
            display: flex; align-items: center; gap: 25px; background: #f9f9f9;
            padding: 20px; border-radius: 15px; border: 1px dashed #ddd;
        }
        .current-img {
            width: 120px; height: 120px; object-fit: cover;
            border-radius: 12px; border: 4px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .btn-group { display: flex; gap: 15px; margin-top: 20px; }
        .btn-save { 
            background: var(--dark); color: #fff; padding: 16px 30px; border: none; 
            border-radius: 12px; cursor: pointer; flex: 2; font-weight: 700; 
            font-size: 16px; transition: all 0.3s;
        }
        .btn-save:hover { background: var(--primary); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(196, 155, 99, 0.3); }
        .btn-back {
            background: #eee; color: #666; text-decoration: none; padding: 16px; 
            border-radius: 12px; flex: 1; text-align: center; font-weight: 600; transition: 0.3s;
        }

        .alert { padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #e7f6ed; color: #28a745; border: 1px solid #d1eddb; }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-280px); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 80px 20px 40px 20px; }
            .sidebar-toggle { display: flex; }
            .image-preview-wrapper { flex-direction: column; text-align: center; }
            .header-title { flex-direction: column; align-items: flex-start; gap: 10px; }
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
        <a href="urun_liste.php" class="nav-item active"><i class="fa-solid fa-list"></i> Product List</a>
        <a href="galeri_ekle.php" class="nav-item"><i class="fa-solid fa-images"></i> Gallery Management</a>
        <a href="ayarlar.php" class="nav-item"><i class="fa-solid fa-sliders"></i> Site Settings</a>
        <a href="logout.php" class="nav-item logout"><i class="fa-solid fa-power-off"></i> Secure Logout</a>
    </nav>
</aside>

<main class="main-content" id="mainContent">
    <header class="header-title">
        <div>
            <h1>Edit Product</h1>
            <p style="color: #888; margin-top: 5px;">You can quickly update product details here.</p>
        </div>
        <span style="background: var(--white); padding: 8px 15px; border-radius: 20px; font-weight: 600; font-size: 13px; color: var(--primary); border: 1px solid var(--border);">
            ID: #<?php echo $p['id']; ?>
        </span>
    </header>

    <section class="card">
        <?php echo $mesaj; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label><i class="fa-solid fa-tag"></i> Product Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $p['name']; ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-money-bill-wave"></i> Sale Price ($)</label>
                <input type="number" name="price" class="form-control" value="<?php echo $p['price']; ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-align-left"></i> Product Description</label>
                <textarea name="description" class="form-control" rows="5"><?php echo $p['description']; ?></textarea>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-image"></i> Product Image</label>
                <div class="image-preview-wrapper">
                    <img src="../uploads/<?php echo $p['image']; ?>" class="current-img" alt="Current">
                    <div style="flex:1;">
                        <input type="file" name="image" class="form-control" style="border:none; padding:0; background:none;">
                        <p style="margin-top:8px; font-size:12px; color:#888;">
                            Leave blank if you do not want to make any changes.
                        </p>
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" name="update" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Update
                </button>
                <a href="urun_liste.php" class="btn-back">Cancel</a>
            </div>
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