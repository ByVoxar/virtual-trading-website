<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap');

:root {
    --bg: #f5e9d6;
    --text: #1c1917;
    --accent: #8b5e3c;
    --glass: rgba(255, 255, 255, 0.8);
    --border: rgba(28, 25, 23, 0.1);
}

* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; scroll-behavior: smooth; }

.main-nav { position: fixed; top: 0; width: 100%; z-index: 1000; padding: 20px 5%; background: var(--glass); backdrop-filter: blur(10px); border-bottom: 1px solid var(--border); }
.nav-container { display: flex; justify-content: space-between; align-items: center; max-width: 1400px; margin: 0 auto; }
.logo { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: var(--text); text-decoration: none; letter-spacing: 2px; }
.nav-links a { margin-left: 30px; text-decoration: none; color: var(--text); font-weight: 600; font-size: 0.9rem; transition: 0.3s; }
.nav-links a:hover { color: var(--accent); }

.hero { 
    display: flex; 
    min-height: 100vh; 
    align-items: center; 
    padding: 0; 
    gap: 0; 
    background: #f5e9d6;
    overflow: hidden;
}

.hero-left { 
    flex: 1; 
    padding: 0 5% 0 10%; 
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.hero-eyebrow {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 1px;
    color: var(--accent);
}

.hero-h1 { 
    font-family: 'Playfair Display', serif; 
    font-size: 5rem; 
    line-height: 1.1; 
    margin-bottom: 30px; 
    color: var(--text);
}

.hero-h1 em { 
    font-style: italic; 
    font-weight: 400; 
    color: var(--accent); 
}

.hero-sub {
    font-size: 1.1rem;
    max-width: 500px;
    margin-bottom: 50px; 
    opacity: 0.8;
}

.hero-right { 
    flex: 1.2; 
    height: 100vh; 
}

.hero-right-img { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    border-radius: 0; 
    box-shadow: none;
}


.hero-ctas .btn-lg {
    padding: 20px 50px;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-size: 0.85rem;
}


@media (max-width: 992px) {
    .hero { flex-direction: column; text-align: center; }
    .hero-left { padding: 100px 5% 50px; order: 2; }
    .hero-right { width: 100%; height: 50vh; order: 1; }
    .hero-h1 { font-size: 3rem; }
}

.hero-stats {
    display: flex;
    gap: 50px; 
    margin-top: 50px; 
    padding-top: 30px;
    border-top: 1px solid rgba(28, 25, 23, 0.1); 
}

.stat-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.stat-num {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--text);
    line-height: 1;
}

.stat-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 600;
    color: var(--accent);
    opacity: 0.8;
}

@media (max-width: 768px) {
    .hero-stats {
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }
}

.about-section {
    padding: 100px 5%;
    background-color: var(--bg);
}

.about-container {
    display: flex;
    gap: 80px;
    max-width: 1600px;
    margin: 0 auto;
    align-items: center;
}


.about-visual {
    flex: 1;
    position: relative;
}

.about-image-main {
    width: 100%;
    height: 700px; 
    background: #e5e0d8;
    border-radius: 40px; 
    overflow: hidden;
    position: relative;
}

.about-image-main img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.experience-tag {
    position: absolute;
    bottom: 40px;
    right: 40px;
    background: #fff;
    padding: 25px;
    border-radius: 25px;
    text-align: center;
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
}

.exp-number { font-size: 2.5rem; font-weight: 800; display: block; color: var(--text); }
.exp-text { font-size: 0.8rem; font-weight: 600; letter-spacing: 2px; }

.about-content { flex: 1; }

.about-title {
    font-family: 'Playfair Display', serif;
    font-size: 4.5rem; 
    line-height: 1;
    margin: 20px 0 30px;
}

.about-description p {
    font-size: 1.1rem;
    opacity: 0.8;
    margin-bottom: 20px;
    max-width: 600px;
}

.about-features-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 40px;
}

.feature-card {
    background: rgba(255, 255, 255, 0.5); 
    padding: 30px;
    border-radius: 20px;
    border: 1px solid rgba(0,0,0,0.03);
    transition: 0.3s;
}

.feature-card:hover {
    background: #fff;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.feat-icon { font-size: 1.5rem; display: block; margin-bottom: 15px; }

.feature-card h4 {
    font-size: 0.9rem;
    letter-spacing: 1px;
    margin-bottom: 8px;
    font-weight: 700;
}

.feature-card p {
    font-size: 0.85rem;
    opacity: 0.6;
}

@media (max-width: 1100px) {
    .about-container { flex-direction: column; }
    .about-title { font-size: 3rem; }
}


.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    padding: 0 5% 80px 5% !important;
    margin: 0 !important;
    justify-content: center; 
}

.product-card {
    background: #fff;
    border-radius: 20px; 
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
    max-width: 320px; 
    width: 100%;
}

.product-image-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 5; 
    background: #f5f5f5;
    overflow: hidden;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}


.product-details {
    padding: 12px; 
    text-align: left; 
}

.badge-discount {
    position: absolute;
    top: 20px;
    left: 20px;
    background: #e74c3c;
    color: #fff;
    padding: 5px 12px;
    border-radius: 10px;
    font-weight: bold;
    font-size: 0.8rem;
}

.product-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: 0.3s;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.btn-incele {
    background: #fff;
    color: #000;
    padding: 12px 25px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transform: translateY(20px);
    transition: 0.4s;
}

.product-card:hover .btn-incele {
    transform: translateY(0);
}

.product-details {
    padding: 20px; 
    text-align: left;
}

.p-category {
    color: #c49b63;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.p-title {
    font-size: 1.25rem; 
    font-weight: 600;
    margin-bottom: 8px;
}


.p-price-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.p-price-current {
    font-size: 1.2rem;
    font-weight: 800;
    color: #000;
}

.p-price-old {
    color: #aaa;
    text-decoration: line-through;
    font-size: 0.9rem;
}

.p-discount-text {
    background: #ff4757;
    color: #fff;
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 5px;
    font-weight: 700;
}

.modal {
    display: none; position: fixed; z-index: 9999;
    left: 0; top: 0; width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.8); backdrop-filter: blur(5px);
}

.modal-content {
    background-color: #fff;
    margin: 2% auto; 
    width: 90%;
    max-width: 850px; 
    max-height: 90vh; 
    border-radius: 20px;
    position: relative;
    overflow-y: auto; 
}

@keyframes modalAnim {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.close-modal {
    position: absolute; right: 25px; top: 15px;
    font-size: 35px; cursor: pointer; z-index: 10;
}


.modal-body-layout {
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
}
.modal-body-layout {
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
}

.modal-image {
    flex: 1;
    min-width: 300px;
    background: #f9f9f9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-image img {
    width: 100%;
    height: 450px;
    object-fit: contain; 
    padding: 20px; 
}

.modal-info {
    flex: 1.2;
    padding: 50px;
    min-width: 300px;
    display: flex;
    flex-direction: column;
    overflow: hidden; 
}

.modal-info h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; 
    margin: 10px 0;
}

#modalDesc {
    color: #666;
    line-height: 1.8;
    margin-bottom: 30px;
    
    white-space: normal;       
    word-wrap: break-word;     
    overflow-wrap: break-word; 
    max-width: 100%;           
    display: block;            
}

.close-modal {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 30px;
    color: #333;
    z-index: 100;
}


@media (max-width: 768px) {
    .modal-content { margin: 10% auto; width: 95%; }
    .modal-image img { height: 300px; } 
    .modal-info { padding: 25px; }
}

.section { padding: 100px 5%; background: var(--bg); }
.bg-white { background: #fff !important; } 



.dem-iletisim-v2-main {

    padding: 120px 8%;
    position: relative;
    width: 100%;
    box-sizing: border-box;
}


.dem-iletisim-v2-izole-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr; 
    gap: 80px;
    align-items: start;
    max-width: 1400px;
    margin: 0 auto;
}

.dem-iletisim-v2-label {
    color: #C49B63; 
    font-weight: 800;
    letter-spacing: 3px;
    font-size: 0.8rem;
    text-transform: uppercase;
    display: block;
    margin-bottom: 15px;
}

.dem-iletisim-v2-title {
    font-family: 'Playfair Display', serif; 
    font-size: 3.8rem;
    color: #1c1917;
    margin-bottom: 25px;
    line-height: 1.1;
}

.dem-iletisim-v2-title em {
    color: #C49B63;
    font-style: italic;
    font-weight: normal;
}

.dem-iletisim-v2-tanim {
    color: #666;
    margin-bottom: 50px;
    font-size: 1.05rem;
    line-height: 1.8;
}

.dem-iletisim-v2-kart {
    background: #fff;
    padding: 22px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.02);
    border: 1px solid rgba(0,0,0,0.02);
    transition: 0.3s;
}

.dem-iletisim-v2-kart:hover {
    transform: translateX(10px);
    border-color: #C49B63;
}

.dem-iletisim-v2-icon-area {
    width: 50px;
    height: 50px;
    background: #fff5e9; 
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #C49B63;
    font-size: 1.2rem;
}

.dem-iletisim-v2-text-area label {
    display: block;
    font-size: 0.7rem;
    font-weight: 900;
    color: #C49B63;
    margin-bottom: 4px;
    letter-spacing: 1px;
}

.dem-iletisim-v2-text-area span {
    font-size: 0.95rem;
    font-weight: 600;
    color: #262626;
}


.dem-iletisim-v2-form-area {
    background: #fff;
    padding: 60px;
    border-radius: 45px; 
    box-shadow: 0 30px 70px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.02);
}

.dem-iletisim-v2-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr; 
    gap: 25px;
}

.dem-iletisim-v2-form-group {
    margin-bottom: 25px;
}

.dem-iletisim-v2-form-group label {
    display: block;
    font-size: 0.75rem;
    font-weight: 800;
    color: #aaa;
    margin-bottom: 12px;
    letter-spacing: 1px;
    text-transform: uppercase;
}


.dem-iletisim-v2-input-style {
    width: 100%;
    padding: 16px 20px;
    border-radius: 15px;
    border: 1px solid #f0f0f0;
    background: #f9f9f9;
    color: #333;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.95rem;
    outline: none;
    transition: 0.3s;
    box-sizing: border-box;
}

.dem-iletisim-v2-input-style:focus {
    background: #fff;
    border-color: #C49B63;
    box-shadow: 0 5px 15px rgba(196, 155, 99, 0.1);
}

.dem-iletisim-v2-btn {
    width: 100%;
    padding: 20px;
    background-color: #e09262; 
    color: #fff;
    border: none;
    border-radius: 20px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: 0.4s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-transform: uppercase;
}

.dem-iletisim-v2-btn:hover {
    background-color: #c47b4d;
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(224, 146, 98, 0.3);
}

@media (max-width: 992px) {
    .dem-iletisim-v2-izole-grid {
        grid-template-columns: 1fr;
        gap: 50px;
    }
    .dem-iletisim-v2-form-row {
        grid-template-columns: 1fr;
    }
    .dem-iletisim-v2-title {
        font-size: 3rem;
    }
    .dem-iletisim-v2-form-area {
        padding: 40px;
    }
}

.category-filters {
    display: flex;
    justify-content: flex-start;
    gap: 10px;
    padding: 0 5% 20px 5% !important;
    margin: 0 !important;
}


.filter-btn {
    padding: 8px 18px; 
    font-size: 0.9rem;
    border-radius: 50px;
    background: #fff;
    border: 1px solid #e0e0e0;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-bottom: 5px; 
}

.filter-btn:hover {
    border-color: #c49b63; 
    color: #c49b63;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(196, 155, 99, 0.15);
}

.section-head, 
.category-filters, 
.product-grid {

    padding-left: 5% !important; 
    padding-right: 5% !important;
    max-width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

.filter-btn.active {
    background-color: #1a1a1a; 
    color: #ffffff;
    border-color: #1a1a1a;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.category-filters {
    padding: 0 5% 30px 5% !important; 
    display: flex;
    justify-content: flex-start;
    gap: 10px;
    flex-wrap: wrap;
}

.info-list { margin-top: 40px; }
.info-item { display: flex; align-items: center; margin-bottom: 20px; gap: 15px; }
.info-item i { color: var(--accent); font-size: 1.2rem; }

.contact-form { 
    display: flex; 
    flex-direction: column; 
    gap: 20px; 
    background: #F2EDE4; 
    padding: 40px; 
    border-radius: 30px; 
    border: 1px solid rgba(0,0,0,0.05); 
}

.contact-form input, .contact-form textarea { 
    padding: 15px; 
    border-radius: 12px; 
    border: 1px solid rgba(0,0,0,0.1); 
    background: #ffffff; 
    font-family: inherit; 
}


.btn-lg { 
    display: inline-block; 
    padding: 18px 45px; 
    background: var(--text); 
    color: white; 
    border-radius: 100px; 
    font-weight: 600; 
    border: none; 
    transition: 0.3s; 
    cursor: pointer; 
    text-decoration: none; 
    text-align: center; 
}
.btn-lg:hover { 
    background: var(--accent); 
    transform: translateY(-3px); 
}


.site-footer { 
    background: #ffffff; 
    text-align: center; 
    padding: 60px 40px; 
    border-top: 1px solid var(--border); 
    font-size: 0.9rem; 
    opacity: 0.7; 
}

@media (max-width: 992px) {
    .hero, .contact-container { grid-template-columns: 1fr; flex-direction: column; text-align: center; }
    .hero-h1 { font-size: 3rem; }
    .info-item { justify-content: center; }
}

.gallery-section {
        background-color: #f5e9d6; 
        padding: 100px 7%;
        overflow: hidden;
    }

    .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.9s cubic-bezier(0.17, 0.55, 0.55, 1);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    .work-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-auto-rows: 280px;
        gap: 30px;
    }

    .work-item {
        background: #ebe0cf;
        border-radius: 30px;
        overflow: hidden;
        position: relative;
    }

    .work-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }

    .work-item:hover img {
        transform: scale(1.1);
    }

    .gi-big { grid-column: 1 / 2; grid-row: 1 / 3; }
    
    .gi-orange {
        background-color: #D68E5E;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .gi-orange h3 {
        font-family: 'Playfair Display', serif;
        font-style: italic;
        font-size: 2.2rem;
        line-height: 1.2;
    }

.reviews-section {
        background-color: #f5e9d6; 
        padding: 100px 7%;
    }

    .rev-header { margin-bottom: 60px; }
    .rev-label { font-size: 0.8rem; letter-spacing: 3px; color: #C49B63; font-weight: 600; text-transform: uppercase; }
    .rev-title { font-family: 'Playfair Display', serif; font-size: 3.5rem; color: #1C1917; margin-top: 10px; }

    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .review-card {
        background: #fff;
        padding: 40px;
        border-radius: 35px; 
        border: 1px solid rgba(196, 155, 99, 0.2);
        transition: 0.4s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .review-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }

    .stars { color: #C49B63; font-size: 0.9rem; margin-bottom: 20px; }
    
    .review-text {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-style: italic;
        line-height: 1.8;
        color: #444;
        margin-bottom: 30px;
        font-size: 1.05rem;
    }

    .customer-info { display: flex; align-items: center; gap: 15px; }
    .avatar-circle {
        width: 50px; height: 50px; background: #eee; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; color: #aaa; font-size: 0.8rem;
    }

    .c-details h4 { margin: 0; font-size: 1rem; color: #1C1917; }
    .c-details span { font-size: 0.85rem; color: #aaa; }

    @media (max-width: 992px) { .reviews-grid { grid-template-columns: 1fr; } }


.wa-fixed-button {
    position: fixed;
    bottom: 30px; 
    right: 30px;  
    background-color: #25d366; 
    color: white;
    padding: 12px 20px;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Arial', sans-serif;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    z-index: 9999; 
    transition: all 0.3s ease;
}

.wa-fixed-button i {
    font-size: 24px; 
}

.wa-fixed-button:hover {
    transform: translateY(-5px);
    background-color: #128c7e;
    color: white;
}

@media (max-width: 768px) {
    .wa-fixed-button span {
        display: none;
    }
    .wa-fixed-button {
        padding: 15px;
        bottom: 20px;
        right: 20px;
    }
}


</style>