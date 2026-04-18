<?php 
include "includes/db.php"; 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$settings_query = $conn->query("SELECT * FROM settings WHERE id = 1");
$settings = $settings_query->fetch_assoc();

$sql = "SELECT * FROM products WHERE active = 1 ORDER BY id DESC";
$result = $conn->query($sql);

$today = date("Y-m-d");
$check = $conn->query("SELECT * FROM site_stats WHERE visit_date = '$today'");

if ($check->num_rows > 0) {
    $conn->query("UPDATE site_stats SET view_count = view_count + 1 WHERE visit_date = '$today'");
} else {
    $conn->query("INSERT INTO site_stats (visit_date, view_count) VALUES ('$today', 1)");
}

if(isset($_POST['send_message'])) {
    $fullname = htmlspecialchars($_POST['fullname']);
    $phone    = htmlspecialchars($_POST['phone']);
    $email    = htmlspecialchars($_POST['email']);
    $category = htmlspecialchars($_POST['category']);
    $message  = htmlspecialchars($_POST['message']);

    $my_whatsapp_no = "905XXXXXXXXX"; 
    $text = "*İletişim Formu Mesajı*%0A%0A"
          . "*İsim:* " . $fullname . "%0A"
          . "*Telefon:* " . $phone . "%0A"
          . "*E-posta:* " . $email . "%0A"
          . "*Kategori:* " . $category . "%0A"
          . "*Mesaj:* " . $message;

    $wa_url = "https://api.whatsapp.com/send?phone=" . $my_whatsapp_no . "&text=" . $text;

    header("Location: " . $wa_url);
    exit;
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vosera | Handmade Art</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <?php include "styles.php"; ?>

    <link rel="icon" type="image/png" href="favicon.png">    
</head> 
<body>


<nav class="main-nav">
    <div class="nav-container">
        <a href="#" class="logo">Vosera</a>
        <div class="nav-links">
            <a href="#hikaye">Our story</a>
            <a href="#calismalar">Our work</a>
            <a href="#urunler">Products</a>
            <a href="#yorumlar">Comments</a>
            <a href="#iletisim">Communication</a>
        </div>
    </div>
</nav>

<section class="hero">
  <div class="hero-left" data-aos="fade-right">
    <div class="hero-eyebrow">
      <div class="hero-eyebrow-dot">✦</div>
      <span>Handmade — Turkish Craftsmanship</span>
    </div>
    
    <h1 class="hero-h1">Furniture that adds value to your home.</h1>
    
    <p class="hero-sub">Unique designs shaped by the respect for natural materials and the passion of the craftsman.</p>
    
    <div class="hero-ctas">
      <a href="#urunler" class="btn-lg">Explore the Collection<span>→</span></a>
      
      <div class="hero-stats">
        <div class="stat-item">
          <span class="stat-num">50+</span>
          <span class="stat-label">Years of Experience</span>
        </div>
        <div class="stat-item">
          <span class="stat-num">1000+</span>
          <span class="stat-label">Happy Customer</span>
        </div>
        <div class="stat-item">
          <span class="stat-num">%100</span>
          <span class="stat-label">Handcrafted</span>
        </div>
      </div>
    </div>
  </div>

  <div class="hero-right" data-aos="fade-left" data-aos-delay="200">
    <img class="hero-right-img" src="<?php echo $settings['hero_img']; ?>" alt="Demircioğlu Mobilya">
    <div class="hero-right-overlay"></div>
  </div>
</section>

<section class="about-section" id="hikaye">
    <div class="about-container">
        <div class="about-visual" data-aos="zoom-in-right">
            <div class="about-image-main">
                <img src="<?php echo $settings['about_img']; ?>" alt="Demircioğlu Atölye">
                
                <div class="experience-tag" data-aos="fade-up" data-aos-delay="500">
                    <span class="exp-number">50+</span>
                    <span class="exp-text">YEAR</span>
                </div>
            </div>
        </div>

        <div class="about-content" data-aos="fade-left">
            <div class="about-header">
                <span class="sh-label">— OUR STORY</span>
                <h2 class="about-title">The roots <br> <em>are deep</em></h2>
            </div>
            
            <div class="about-description">
                <p>Vosera Furniture, operating in Türkiye since 2026, blends traditional craftsmanship with contemporary design.</p>
                <p>A workshop that goes beyond standard production, believing that every home is unique.</p>
            </div>

            <div class="about-features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <span class="feat-icon">🪵</span>
                    <h4>NATURAL WOOD</h4>
                    <p>Sustainable oak and walnut</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <span class="feat-icon">👋</span>
                    <h4>HANDCRAFTS</h4>
                    <p>Every detail meticulously crafted</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <span class="feat-icon">🎨</span>
                    <h4>CUSTOM DESIGN</h4>
                    <p>Imagine it, we'll make it happen.</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                    <span class="feat-icon">🛡️</span>
                    <h4>10-YEAR WARRANTY</h4>
                    <p>We trust our quality</p>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="gallery-section" id="calismalar">
    <span class="sh-label">— CATALOG</span>
    <h2 class="sh-title">Our work</h2>

    <div class="work-grid">
        <?php 
        $gallery_query = $conn->query("SELECT * FROM gallery ORDER BY id DESC LIMIT 5");
        $imgs = [];
        while($g = $gallery_query->fetch_assoc()) { $imgs[] = $g['image_path']; }
        ?>

        <div class="work-item gi-big reveal">
            <?php if(isset($imgs[0])): ?> <img src="uploads/gallery/<?= $imgs[0] ?>"> <?php endif; ?>
        </div>

        <div class="work-item reveal">
            <?php if(isset($imgs[1])): ?> <img src="uploads/gallery/<?= $imgs[1] ?>"> <?php endif; ?>
        </div>

        <div class="work-item gi-orange reveal">
            <h3>Every<br>Detail<br>Important</h3>
        </div>

        <div class="work-item reveal">
            <?php if(isset($imgs[2])): ?> <img src="uploads/gallery/<?= $imgs[2] ?>"> <?php endif; ?>
        </div>

        <div class="work-item reveal">
            <?php if(isset($imgs[3])): ?> <img src="uploads/gallery/<?= $imgs[3] ?>"> <?php endif; ?>
        </div>
    </div>
</section>

<section class="section product-section" id="urunler">
    <div class="section-head" data-aos="fade-up">
        <div class="sh-container">
            <span class="sh-label">— PRIVATE COLLECTION</span>
            <h2 class="sh-title">Products<em></em></h2>
        </div>
    </div>
    
    <div class="category-filters" data-aos="fade-up">
        <button class="filter-btn active" data-filter="all">Tümü</button>
        <?php 
        $filters = $conn->query("SELECT * FROM categories");
        while($f = $filters->fetch_assoc()): 
        ?>
            <button class="filter-btn" data-filter="cat-<?php echo $f['id']; ?>">
                <?php echo $f['category_name']; ?>
            </button>
        <?php endwhile; ?>
    </div>

    <div class="product-grid">
        <?php 
        if($result && $result->num_rows > 0):
            while($row = $result->fetch_assoc()): 
                $clean_name = htmlspecialchars(addslashes($row['name']));
                $clean_desc = htmlspecialchars(addslashes(preg_replace("/\r|\n/", " ", $row['description'])));
                $main_img = "uploads/" . $row['image'];
        ?>
        <div class="product-card cat-<?php echo $row['category_id']; ?>" data-aos="fade-up">
            <div class="product-image-wrapper">
                <img src="<?php echo $main_img; ?>" alt="<?php echo $row['name']; ?>" class="product-img">
                
                <?php if(!empty($row['discount_price']) && $row['discount_price'] > 0): 
                    $indirim_orani = round((($row['price'] - $row['discount_price']) / $row['price']) * 100);
                ?>
                    <span class="badge-discount">-%<?php echo $indirim_orani; ?></span>
                <?php endif; ?>

                <div class="product-overlay">
                    <button class="btn-incele" onclick="openProductModal(
                        '<?php echo $clean_name; ?>', 
                        '<?php echo $clean_desc; ?>', 
                        '<?php echo $row['price']; ?>', 
                        '<?php echo $row['discount_price']; ?>', 
                        '<?php echo $main_img; ?>'
                    )">Hızlı Bakış →</button>
                </div>
            </div>

            <div class="product-details">
                <span class="p-category">COLLECTION</span> 
                <h3 class="p-title"><?php echo $row['name']; ?></h3>
                
                <div class="p-price-row">
                    <?php if(!empty($row['discount_price']) && $row['discount_price'] > 0): ?>
                        <span class="p-price-current">₺<?php echo number_format($row['discount_price'], 0, ',', '.'); ?></span>
                        <span class="p-price-old">₺<?php echo number_format($row['price'], 0, ',', '.'); ?></span>
                    <?php else: ?>
                        <span class="p-price-current">₺<?php echo number_format($row['price'], 0, ',', '.'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php 
            endwhile; 
        endif;
        ?>
    </div> 

<section class="reviews-section" id="yorumlar">
    <div class="rev-header">
        <span class="rev-label">— OUR CUSTOMERS</span>
        <h2 class="rev-title">What Did They Say?</h2>
    </div>

    <div class="reviews-grid">
        <?php 
        $reviews = $conn->query("SELECT * FROM reviews ORDER BY id DESC LIMIT 3");
        while($r = $reviews->fetch_assoc()): 
        ?>
            <div class="review-card reveal">
                <div>
                    <div class="stars">
                        <?php for($i=0; $i<$r['rating']; $i++) echo "★ "; ?>
                    </div>
                    <p class="review-text">"<?= $r['review_text'] ?>"</p>
                </div>
                
                <div class="customer-info">
                    <div class="avatar-circle">..</div>
                    <div class="c-details">
                        <h4><?= $r['customer_name'] ?></h4>
                        <span><?= $r['customer_location'] ?></span>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>


<section class="dem-iletisim-v2-main" id="iletisim" data-aos="fade-up">
    <div class="dem-iletisim-v2-izole-grid">
        <div class="dem-iletisim-v2-sol-panel" data-aos="fade-right" data-aos-delay="200">
            <span class="dem-iletisim-v2-label">— COMMUNICATION</span>
            <h2 class="dem-iletisim-v2-title">Contact <em>Us</em></h2>
            <p class="dem-iletisim-v2-tanim">We are always here to answer your questions. We will get back to you as soon as possible.</p>
            
            <div class="dem-iletisim-v2-bilgi-listesi">
                <div class="dem-iletisim-v2-kart">
                    <div class="dem-iletisim-v2-icon-area"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="dem-iletisim-v2-text-area">
                        <label>ADDRESS</label>
                        <span>Türkiye</span>
                    </div>
                </div>
                <div class="dem-iletisim-v2-kart">
                    <div class="dem-iletisim-v2-icon-area"><i class="fa-solid fa-phone"></i></div>
                    <div class="dem-iletisim-v2-text-area">
                        <label>TELEPHONE</label>
                        <span> 0555 555 5555</span>
                    </div>
                </div>
                <div class="dem-iletisim-v2-kart">
                    <div class="dem-iletisim-v2-icon-area"><i class="fa-solid fa-envelope"></i></div>
                    <div class="dem-iletisim-v2-text-area">
                        <label>EMAIL</label>
                        <span>info@xxx.com.tr</span>
                    </div>
                </div>
                <div class="dem-iletisim-v2-kart">
                    <div class="dem-iletisim-v2-icon-area"><i class="fa-solid fa-clock"></i></div>
                    <div class="dem-iletisim-v2-text-area">
                        <label>WORKING HOURS</label>
                        <span>Mon-Sat: 09:00-19:00 </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dem-iletisim-v2-form-area" data-aos="fade-left" data-aos-delay="400">
            <form id="waFormModernV2" class="dem-iletisim-v2-form-yapisi">
                <div class="dem-iletisim-v2-form-row">
                    <div class="dem-iletisim-v2-form-group">
                        <label>NAME SURNAME</label>
                        <input type="text" class="dem-iletisim-v2-input-style" id="waNameV2" placeholder="Ali DEMİR" required>
                    </div>
                    <div class="dem-iletisim-v2-form-group">
                        <label>TELEPHONE</label>
                        <input type="text" class="dem-iletisim-v2-input-style" id="waPhoneV2" placeholder="0 5xx xxx xx xx" required>
                    </div>
                </div>
                
                <div class="dem-iletisim-v2-form-group">
                    <label>EMAIL</label>
                    <input type="email" class="dem-iletisim-v2-input-style" id="waEmailV2" placeholder="mail@email.com" required>
                </div>

                <div class="dem-iletisim-v2-form-group">
                    <label>KATEGORİ</label>
                    <select class="dem-iletisim-v2-input-style" id="waCatV2">
                    <option>Living Room</option>
                    <option>Bedroom</option>
                    <option>Dining Room</option>
                    <option>Custom Design</option>
                    </select>
                </div>

                <div class="dem-iletisim-v2-form-group">
                    <label>YOUR MESSAGE</label>
                    <textarea class="dem-iletisim-v2-input-style" id="waMsgV2" rows="4" placeholder="Hayalinizdeki mobilyayı anlatın..." required></textarea>
                </div>

                <button type="submit" class="dem-iletisim-v2-btn">
                    Send via WhatsApp <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</section>

</section> <div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-body-layout">
<div class="modal-image">
    <img id="modalImg" src="" alt="">
    <div id="modalGallery" class="modal-thumbnails"></div>
</div>>
            </div>
            <div class="modal-info">
                <span id="modalCat" class="p-category">COLLECTION</span>
                <h2 id="modalTitle">Product Name</h2>
                <div class="modal-price-row">
                    <span id="modalPrice" class="p-price-current"></span>
                    <span id="modalOldPrice" class="p-price-old"></span>
                </div>
                <hr>
                <p id="modalDesc">Product description will go here...</p>
                <a href="https://wa.me/905555555555" class="btn-lg" style="display:block; text-align:center; text-decoration:none;">
                    <i class="fa-brands fa-whatsapp"></i> Get Information & Place an Order
                </a>
            </div>
        </div>
    </div>
</div>    


<footer class="site-footer">
    <p>&copy; © 2026 Vosera Furniture. All rights reserved.</p>
</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    window.addEventListener('load', function() {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 150,
            easing: 'ease-in-out'
        });
    });

function openProductModal(name, desc, price, dPrice, img) {
    const modal = document.getElementById("productModal");
    
    document.getElementById("modalTitle").innerText = name;
    document.getElementById("modalDesc").innerText = desc;
    document.getElementById("modalImg").src = img;
    
    if(dPrice > 0 && dPrice != '') {
        document.getElementById("modalPrice").innerText = '₺' + dPrice;
        document.getElementById("modalOldPrice").innerText = '₺' + price;
        document.getElementById("modalOldPrice").style.display = 'inline';
    } else {
        document.getElementById("modalPrice").innerText = '₺' + price;
        document.getElementById("modalOldPrice").style.display = 'none';
    }

    modal.style.display = "block";
}

document.querySelector(".close-modal").onclick = function() {
    document.getElementById("productModal").style.display = "none";
}

window.onclick = function(event) {
    if (event.target == document.getElementById("productModal")) {
        document.getElementById("productModal").style.display = "none";
    }
}

document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        
        this.classList.add('active');
        
        const filterValue = this.getAttribute('data-filter');
        const products = document.querySelectorAll('.product-card');

        products.forEach(product => {
            if (filterValue === 'all' || product.classList.contains(filterValue)) {
                product.style.display = 'block';
                product.setAttribute('data-aos', 'fade-up');
            } else {
                product.style.display = 'none';
            }
        });
    });
});

const observerOptions = {
        threshold: 0.15
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('active');
                }, index * 150);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal').forEach((el) => observer.observe(el))


document.getElementById('waFormModernV2').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const name = document.getElementById('waNameV2').value;
    const phone = document.getElementById('waPhoneV2').value;
    const email = document.getElementById('waEmailV2').value;
    const category = document.getElementById('waCatV2').value;
    const message = document.getElementById('waMsgV2').value;

    const myNumber = "5389354153"; 

    const text = `Merhaba Demircioglu Mobilya,%0A%0A` +
                 `*Ad Soyad:* ${name}%0A` +
                 `*Telefon:* ${phone}%0A` +
                 `*E-posta:* ${email}%0A` +
                 `*Kategori:* ${category}%0A` +
                 `*Mesaj:* ${message}`;

    const waUrl = `https://wa.me/${myNumber}?text=${text}`;

    window.open(waUrl, '_blank');
});

    
</script>

<a href="https://wa.me/905555555555?text=Merhaba,%20bilgi%20almak%20istiyorum." 
   class="wa-fixed-button" 
   target="_blank">
    <i class="fa-brands fa-whatsapp"></i>

</a>

</body>
</html>