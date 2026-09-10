
<!-- ========================= -->
<!-- POPUP NEWSLETTER -->
<!-- ========================= -->

<?php 
// Récupérer l'image popup depuis la table settings
$popup_image = $this->db->where('KeyValue', 'popup_image')->get('settings')->row();
$popup_image_url = ($popup_image && !empty($popup_image->Value)) 
                   ? base_url($popup_image->Value) 
                   : base_url('assets/images/promo.jpg');
?>

<div id="welcomePopup" class="popup-overlay">

    <div class="popup-box">

        <!-- FERMER -->
        <span class="popup-close" id="closePopup">&times;</span>

        <!-- IMAGE DYNAMIQUE -->
        <div class="popup-left">
            <img src="<?= $popup_image_url; ?>" alt="Promo">
        </div>

        <!-- FORMULAIRE -->
        <form action="<?= base_url('home/sabonner'); ?>" method="POST" class="popup-right">

            <h1>Bienvenue sur notre boutique</h1>

            <h3>Profitez de nos meilleures offres</h3>

            <p>
                Abonnez-vous maintenant et recevez
                les meilleures promotions.
            </p>

            <!-- EMAIL -->
            <input 
                type="email"
                name="email"
                placeholder="Votre adresse email"
                required
            >

            <!-- BOUTON -->
            <button type="submit">
                S'abonner
            </button>

        </form>

    </div>

</div>

<style>

/* ========================= */
/* OVERLAY */
/* ========================= */

.popup-overlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999999;
}

/* ========================= */
/* BOX */
/* ========================= */

.popup-box{
    width:900px;
    max-width:95%;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    display:flex;
    position:relative;
    animation:popupAnimation 0.4s ease;
}

/* ========================= */
/* IMAGE */
/* ========================= */

.popup-left{
    width:45%;
}

.popup-left img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

/* ========================= */
/* CONTENU */
/* ========================= */

.popup-right{
    width:55%;
    padding:40px;
    text-align:center;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.popup-right h1{
    font-size:42px;
    color:#333;
    margin-bottom:20px;
}

.popup-right h3{
    font-size:28px;
    margin-bottom:15px;
    color:#111;
}

.popup-right p{
    font-size:18px;
    color:#666;
    margin-bottom:25px;
    line-height:1.6;
}

/* ========================= */
/* INPUT */
/* ========================= */

.popup-right input{
    width:100%;
    height:55px;
    border:1px solid #ddd;
    border-radius:5px;
    padding:0 15px;
    font-size:16px;
    margin-bottom:20px;
    outline:none;
}

/* ========================= */
/* BUTTON */
/* ========================= */

.popup-right button{
    width:100%;
    height:55px;
    border:none;
    background:#ff6600;
    color:#fff;
    font-size:20px;
    border-radius:5px;
    cursor:pointer;
    transition:0.3s;
}

.popup-right button:hover{
    background:#e65c00;
}

/* ========================= */
/* CLOSE */
/* ========================= */

.popup-close{
    position:absolute;
    top:10px;
    right:20px;
    font-size:40px;
    cursor:pointer;
    color:#333;
    z-index:10;
}

/* ========================= */
/* ANIMATION */
/* ========================= */

@keyframes popupAnimation{

    from{
        opacity:0;
        transform:scale(0.7);
    }

    to{
        opacity:1;
        transform:scale(1);
    }

}

/* ========================= */
/* RESPONSIVE */
/* ========================= */

@media(max-width:768px){

    .popup-box{
        flex-direction:column;
    }

    .popup-left,
    .popup-right{
        width:100%;
    }

    .popup-right{
        padding:25px;
    }

    .popup-right h1{
        font-size:30px;
    }

}



.search-result-info {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 20px;
}

.search-suggestion-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    list-style: none;
    padding: 0;
    margin-top: 15px;
}

.search-suggestion-list li {
    font-size: 13px;
}

.search-suggestion-list li a {
    color: #f68b1e;
    text-decoration: none;
}

.search-suggestion-list li a:hover {
    text-decoration: underline;
}
</style>


<script>

document.addEventListener("DOMContentLoaded", function () {

    const popup = document.getElementById("welcomePopup");

    // Vérifier si popup déjà affiché
    let popupAlreadyShown = localStorage.getItem("popupAlreadyShown");

    // Si jamais affiché
    if (!popupAlreadyShown) {

        // Afficher popup
        popup.style.display = "flex";

        // Sauvegarder
        localStorage.setItem("popupAlreadyShown", "yes");
    }

    // Bouton fermer
    document.getElementById("closePopup").addEventListener("click", function () {

        popup.style.display = "none";

    });

});

</script>




















<style>
    /* ========================================
       VARIABLES DESIGN SYSTEM
       ======================================== */
    :root {
        --abm-primary: #f97316;
        --abm-primary-dark: #e0670e;
        --abm-primary-light: #fef3e8;
        --abm-secondary: #2c3e50;
        --abm-success: #25D366;
        --abm-text: #1a1a2e;
        --abm-text-light: #6c757d;
        --abm-border: #e9ecef;
        --abm-shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
        --abm-shadow-md: 0 8px 24px rgba(0,0,0,0.08);
        --abm-shadow-lg: 0 12px 32px rgba(0,0,0,0.12);
        --abm-radius-sm: 8px;
        --abm-radius-md: 12px;
        --abm-radius-lg: 16px;
        --abm-radius-xl: 24px;
        --abm-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* ========================================
       MEGA MENU PRINCIPAL
       ======================================== */
    .abm-mega-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .abm-mega-container {
        background: white;
        border-radius: var(--abm-radius-lg);
        box-shadow: var(--abm-shadow-md);
        overflow: hidden;
        transition: var(--abm-transition);
    }
    
    .abm-mega-container:hover {
        box-shadow: var(--abm-shadow-lg);
    }
    
    .abm-mega-grid {
        display: grid;
        grid-template-columns: 280px 1fr 320px;
        min-height: 520px;
        background: white;
    }
    
    /* ========== COLONNE 1 - CATÉGORIES ========== */
    .abm-categories-panel {
        background: #fff;
        border-right: 1px solid var(--abm-border);
        padding: 20px 0;
    }
    
    .abm-cat-item {
        padding: 12px 20px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        color: var(--abm-text);
        transition: var(--abm-transition);
        border-left: 3px solid transparent;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .abm-cat-item i {
        font-size: 18px;
        color: #adb5bd;
        transition: var(--abm-transition);
    }
    
    .abm-cat-item:hover {
        background: var(--abm-primary-light);
        color: var(--abm-primary);
        transform: translateX(4px);
    }
    
    .abm-cat-item:hover i {
        color: var(--abm-primary);
    }
    
    .abm-cat-item.active {
        background: var(--abm-primary-light);
        color: var(--abm-primary);
        border-left-color: var(--abm-primary);
    }
    
    .abm-cat-item.active i {
        color: var(--abm-primary);
    }
    
    /* ========== COLONNE 2 - SOUS-CATÉGORIES ========== */
    .abm-subs-panel {
        padding: 24px 28px;
        background: #fff;
        overflow-y: auto;
        max-height: 560px;
    }
    
    .abm-subs-panel::-webkit-scrollbar {
        width: 4px;
    }
    
    .abm-subs-panel::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .abm-subs-panel::-webkit-scrollbar-thumb {
        background: var(--abm-primary);
        border-radius: 10px;
    }
    
    .abm-default-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
        min-height: 450px;
    }
    
    .abm-default-content.hide {
        display: none;
    }
    
    .abm-dynamic-content {
        display: none;
        animation: abmFadeSlide 0.3s ease;
    }
    
    .abm-dynamic-content.active {
        display: block;
    }
    
    @keyframes abmFadeSlide {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Grille sous-catégories */
    .abm-subs-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }
    
    .abm-subs-card {
        background: #fff;
        border-radius: var(--abm-radius-md);
        transition: var(--abm-transition);
    }
    
    .abm-subs-card h4 {
        color: var(--abm-primary);
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--abm-primary);
        display: inline-block;
    }
    
    .abm-subs-list {
        list-style: none;
        margin-top: 12px;
    }
    
    .abm-subs-list li {
        padding: 8px 0;
        font-size: 13px;
        color: var(--abm-text-light);
        cursor: pointer;
        transition: var(--abm-transition);
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .abm-subs-list li:last-child {
        border-bottom: none;
    }
    
    .abm-subs-list li i {
        font-size: 12px;
        color: #ced4da;
        transition: var(--abm-transition);
    }
    
    .abm-subs-list li:hover {
        color: var(--abm-primary);
        transform: translateX(5px);
    }
    
    .abm-subs-list li:hover i {
        color: var(--abm-primary);
    }
    
    /* Niveau 3 */
    .abm-level3-list {
        margin-left: 20px;
        margin-top: 8px;
        padding-left: 12px;
        border-left: 2px dashed #e9ecef;
    }
    
    .abm-level3-list li {
        padding: 5px 0;
        font-size: 12px;
        color: #868e96;
        border-bottom: none;
    }
    
    .abm-subs-card h4 a {
        color: inherit;
        text-decoration: none;
    }
    
    .abm-subs-list li a {
        color: inherit;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
    }
    
    .abm-level3-list li a {
        color: inherit;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
        width: 100%;
    }
    
    /* ========== COLONNE 3 - PROMO ========== */
    .abm-promo-panel {
        background: linear-gradient(135deg, #fff 0%, #fefaf5 100%);
        border-left: 1px solid var(--abm-border);
        padding: 24px 20px;
    }
    
    .abm-support-card {
        background: var(--abm-primary-light);
        border-radius: var(--abm-radius-md);
        padding: 18px;
        margin-bottom: 20px;
        text-align: center;
        transition: var(--abm-transition);
        cursor: pointer;
    }
    
    .abm-support-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--abm-shadow-sm);
    }
    
    .abm-support-card i {
        font-size: 28px;
        color: var(--abm-primary);
        margin-bottom: 8px;
    }
    
    .abm-support-card h4 {
        color: var(--abm-primary);
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .abm-whatsapp-btn {
        background: var(--abm-success);
        border-radius: var(--abm-radius-md);
        padding: 14px;
        text-align: center;
        color: white;
        margin-bottom: 16px;
        cursor: pointer;
        transition: var(--abm-transition);
        font-weight: 500;
    }
    
    .abm-whatsapp-btn:hover {
        background: #128C7E;
        transform: scale(1.02);
    }
    
    .abm-seller-btn {
        background: var(--abm-primary);
        border-radius: var(--abm-radius-md);
        padding: 14px;
        text-align: center;
        color: white;
        margin-bottom: 24px;
        cursor: pointer;
        transition: var(--abm-transition);
        font-weight: 500;
    }
    
    .abm-seller-btn:hover {
        background: var(--abm-primary-dark);
        transform: scale(1.02);
    }
    
    .abm-brand-footer {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid var(--abm-border);
    }
    
    .abm-brand-logo {
        font-size: 26px;
        font-weight: 800;
        color: var(--abm-primary);
        letter-spacing: -0.5px;
    }
    
    /* ========== CARROUSEL DESKTOP - CORRIGÉ ========== */
    .abm-carousel-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: var(--abm-radius-lg);
        background: #000;
        min-height: 400px;
    }
    
    .abm-carousel-slides {
        position: relative;
        width: 100%;
        height: 400px;
    }
    
    .abm-carousel-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.6s ease-in-out;
    }
    
    .abm-carousel-slide.active {
        opacity: 1;
        z-index: 1;
    }
    
    .abm-carousel-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    
    /* Bouton Découvrir en bas à droite */
    .abm-carousel-discover-btn {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: var(--abm-primary);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        z-index: 20;
        transition: var(--abm-transition);
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .abm-carousel-discover-btn:hover {
        background: var(--abm-primary-dark);
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    
    .abm-carousel-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 20;
        transition: var(--abm-transition);
        backdrop-filter: blur(4px);
        font-size: 18px;
        font-weight: bold;
    }
    
    .abm-carousel-arrow:hover {
        background: var(--abm-primary);
        transform: translateY(-50%) scale(1.1);
    }
    
    .abm-carousel-arrow.prev { left: 16px; }
    .abm-carousel-arrow.next { right: 16px; }
    
    .abm-carousel-dots {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        position: relative;
        z-index: 5;
    }
    
    .abm-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ddd;
        cursor: pointer;
        transition: var(--abm-transition);
    }
    
    .abm-dot.active {
        background: var(--abm-primary);
        width: 28px;
        border-radius: 10px;
    }
    
    /* ========== VERSION MOBILE ========== */
    @media (max-width: 768px) {
        .abm-desktop-only { display: none; }
        .abm-mobile-only { display: block; }
        .abm-mega-wrapper { padding: 12px; }
    }
    
    @media (min-width: 769px) {
        .abm-mobile-only { display: none; }
        .abm-desktop-only { display: block; }
    }
    
    /* Styles Mobile */
    .abm-mobile-header {
        background: linear-gradient(135deg, var(--abm-primary), var(--abm-primary-dark));
        padding: 14px 16px;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .abm-burger-btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 8px;
        border-radius: 12px;
        transition: var(--abm-transition);
    }
    
    .abm-mobile-logo {
        color: white;
        font-size: 18px;
        font-weight: 800;
    }
    
    .abm-mobile-logo small {
        font-size: 10px;
        font-weight: 400;
    }
    
    /* Offcanvas */
    .abm-offcanvas {
        width: 85% !important;
        max-width: 340px !important;
    }
    
    .abm-offcanvas-header {
        background: linear-gradient(135deg, var(--abm-primary), var(--abm-primary-dark));
        color: white;
        padding: 18px 20px;
    }
    
    /* Carrousel Mobile - CORRIGÉ */
    .abm-mobile-carousel {
        background: #000;
        border-radius: var(--abm-radius-lg);
        overflow: hidden;
        margin-bottom: 20px;
        position: relative;
    }
    
    .abm-mobile-carousel-slides {
        position: relative;
        width: 100%;
        height: 260px;
    }
    
    .abm-mobile-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    
    .abm-mobile-slide.active {
        opacity: 1;
        z-index: 1;
    }
    
    .abm-mobile-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    
    /* Bouton Découvrir mobile */
    .abm-mobile-discover-btn {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: var(--abm-primary);
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        z-index: 20;
        transition: var(--abm-transition);
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .abm-mobile-discover-btn:hover {
        background: var(--abm-primary-dark);
        transform: scale(1.02);
    }
    
    .abm-mobile-carousel-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 20;
        transition: var(--abm-transition);
        font-size: 16px;
        font-weight: bold;
    }
    
    .abm-mobile-carousel-arrow:hover {
        background: var(--abm-primary);
        transform: translateY(-50%) scale(1.05);
    }
    
    .abm-mobile-carousel-arrow.prev { left: 10px; }
    .abm-mobile-carousel-arrow.next { right: 10px; }
    
    .abm-mobile-carousel-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        padding: 12px;
        position: relative;
        z-index: 5;
    }
    
    .abm-mobile-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #ccc;
        cursor: pointer;
        transition: var(--abm-transition);
    }
    
    .abm-mobile-dot.active {
        background: var(--abm-primary);
        width: 20px;
        border-radius: 10px;
    }
    
    .abm-mobile-card {
        background: white;
        border-radius: var(--abm-radius-lg);
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: var(--abm-shadow-sm);
    }
    
    .abm-mobile-cat-item {
        border-bottom: 1px solid #f0f0f0;
    }
    
    .abm-mobile-cat-title {
        padding: 16px 20px;
        font-weight: 600;
        font-size: 15px;
        background: white;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .abm-mobile-cat-title i {
        transition: transform 0.3s;
        color: var(--abm-primary);
    }
    
    .abm-mobile-cat-title.active i {
        transform: rotate(90deg);
    }
    
    .abm-mobile-subs {
        display: none;
        background: #fafbfc;
        padding: 12px 20px 20px 35px;
    }
    
    .abm-mobile-subs.show {
        display: block;
    }
    
    .abm-mobile-subgroup {
        margin-bottom: 18px;
    }
    
    .abm-mobile-subgroup h6 {
        color: var(--abm-primary);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .abm-mobile-subgroup ul {
        list-style: none;
        padding-left: 0;
    }
    
    .abm-mobile-subgroup ul li {
        padding: 8px 0;
        font-size: 13px;
        color: #555;
        border-bottom: 1px solid #eee;
    }
    
    .abm-mobile-subgroup h6 a {
        color: inherit;
        text-decoration: none;
    }
    
    .abm-mobile-subgroup ul li a {
        color: inherit;
        text-decoration: none;
        display: block;
        width: 100%;
    }
</style>
<!-- ========== VERSION DESKTOP ========== -->
<div class="abm-desktop-only">
    <div class="abm-mega-wrapper">
        <div class="abm-mega-container">
            <div class="abm-mega-grid">
                <div class="abm-categories-panel" id="abmDesktopCategories"></div>
                <div class="abm-subs-panel" id="abmDesktopSubsContainer">
                    <div class="abm-default-content" id="abmDefaultContent">
                        <div class="abm-carousel-wrapper">
                            <div class="abm-carousel-arrow prev" id="abmCarouselPrev">❮</div>
                            <div class="abm-carousel-arrow next" id="abmCarouselNext">❯</div>
                            <div class="abm-carousel-slides" id="abmCarouselSlides"></div>
                            <button class="abm-carousel-discover-btn" id="discoverBtn">
                                Découvrir <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        <div class="abm-carousel-dots" id="abmCarouselDots"></div>
                    </div>
                    <div class="abm-dynamic-content" id="abmDynamicContent"></div>
                </div>
                <div class="abm-promo-panel">
                    <div class="abm-support-card">
                        <i class="bi bi-headset"></i>
                        <h4>Centre d'assistance</h4>
                        <p class="small text-muted mb-0">Guide du service client</p>
                    </div>
                    <div class="abm-whatsapp-btn">
                        <i class="bi bi-whatsapp me-2"></i> WhatsApp - Discuter pour commander
                    </div>
                    <div class="abm-seller-btn">
                        <i class="bi bi-shop me-2"></i> Vendez sur AbeMarket
                    </div>
                    <div class="abm-brand-footer">
                        <div class="abm-brand-logo">ABEMARKET®</div>
                        <p class="small text-muted mt-2 mb-0">trendyol</p>
                        <p class="small fw-bold mt-2" style="color: var(--abm-primary);">DÉCOUVRIER →</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== VERSION MOBILE ========== -->
<div class="abm-mobile-only">
    <div class="abm-mobile-header d-flex justify-content-between align-items-center">
        <button class="abm-burger-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#abmOffcanvas">
    </div>
    
    <div class="offcanvas offcanvas-start abm-offcanvas" tabindex="-1" id="abmOffcanvas">
        <div class="abm-offcanvas-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Catégories</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0" id="abmMobileCategories"></div>
    </div>
    
    <div class="p-3 pb-5">
        <div class="abm-mobile-carousel">
            <div class="abm-mobile-carousel-arrow prev" id="abmMobilePrev">❮</div>
            <div class="abm-mobile-carousel-arrow next" id="abmMobileNext">❯</div>
            <div class="abm-mobile-carousel-slides" id="abmMobileSlides"></div>
            <button class="abm-mobile-discover-btn" id="mobileDiscoverBtn">
                Découvrir <i class="bi bi-arrow-right"></i>
            </button>
            <div class="abm-mobile-carousel-dots" id="abmMobileDots"></div>
        </div>
        
        <div class="abm-mobile-card text-center">
            <i class="bi bi-headset" style="font-size: 32px; color: var(--abm-primary);"></i>
            <h5 class="mt-2" style="color: var(--abm-primary);">Centre d'assistance</h5>
            <p class="text-muted small">Guide du service client</p>
            <div class="mt-3">
                <div class="btn btn-success w-100 mb-2 rounded-pill"><i class="bi bi-whatsapp me-2"></i> WhatsApp - Commander</div>
                <div class="btn w-100 rounded-pill" style="background: var(--abm-primary); color: white;"><i class="bi bi-shop me-2"></i> Vendez sur AbeMarket</div>
            </div>
        </div>
        
        <div class="abm-mobile-card text-center">
            <div style="font-size: 24px; font-weight: 800; color: var(--abm-primary);">ABEMARKET®</div>
            <p class="text-muted small mb-1">trendyol</p>
            <p class="small fw-bold mb-0" style="color: var(--abm-primary);">DÉCOUVRIER →</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ============================================
// DONNÉES DEPUIS PHP
// ============================================
const categoriesHierarchy = <?= json_encode($categories_hierarchy) ?>;
const bannersData = <?= json_encode($slider_banners) ?>;
const BASE_URL = '<?= base_url() ?>';

// ============================================
// UTILITAIRES
// ============================================
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;')
              .replace(/'/g, '&#39;');
}

function getImageUrl(imagePath) {
    if (!imagePath) return 'https://img.icons8.com/color/96/000000/shopping-bag.png';
    if (imagePath.startsWith('http')) return imagePath;
    let cleanPath = imagePath.replace(/^\/+/, '');
    return BASE_URL + cleanPath;
}

// ============================================
// SOUS-CATÉGORIES DESKTOP
// ============================================
function renderDesktopSubs(categoryId) {
    const container = document.getElementById('abmDynamicContent');
    const defaultContent = document.getElementById('abmDefaultContent');
    
    let selectedCat = null;
    for (let cat of categoriesHierarchy) {
        if (parseInt(cat.id_categorie) === parseInt(categoryId)) {
            selectedCat = cat;
            break;
        }
    }
    
    if (!selectedCat || !selectedCat.children || selectedCat.children.length === 0) {
        container.innerHTML = '<div class="text-center py-5 text-muted">Aucune sous-catégorie</div>';
        defaultContent.classList.add('hide');
        container.classList.add('active');
        return;
    }
    
    let html = '<div class="abm-subs-grid">';
    selectedCat.children.forEach(lvl1 => {
        const lvl1Url = lvl1.slug_categorie ? 'category/' + lvl1.slug_categorie : '#';
        html += `<div class="abm-subs-card">
                    <h4><a href="${lvl1Url}"><i class="bi bi-folder2-open me-2"></i>${escapeHtml(lvl1.nom_categorie)}</a></h4>
                    <ul class="abm-subs-list">`;
        if (lvl1.children && lvl1.children.length > 0) {
            lvl1.children.forEach(lvl2 => {
                const lvl2Url = lvl2.slug_categorie ? 'category/' + lvl2.slug_categorie : '#';
                html += `<li><a href="${lvl2Url}"><i class="bi bi-chevron-right"></i><strong>${escapeHtml(lvl2.nom_categorie)}</strong></a>`;
                if (lvl2.children && lvl2.children.length > 0) {
                    html += `<ul class="abm-level3-list">`;
                    lvl2.children.forEach(lvl3 => {
                        const lvl3Url = lvl3.slug_categorie ? 'category/' + lvl3.slug_categorie : '#';
                        html += `<li><a href="${lvl3Url}"><i class="bi bi-dot"></i>${escapeHtml(lvl3.nom_categorie)}</a></li>`;
                    });
                    html += `</ul>`;
                }
                html += `</li>`;
            });
        } else {
            html += `<li><a href="${lvl1Url}"><i class="bi bi-chevron-right"></i>${escapeHtml(lvl1.nom_categorie)}</a></li>`;
        }
        html += `</ul></div>`;
    });
    html += '</div>';
    
    container.innerHTML = html;
    defaultContent.classList.add('hide');
    container.classList.add('active');
}

function generateDesktopCategories() {
    const container = document.getElementById('abmDesktopCategories');
    if (!container) return;
    
    let html = '';
    categoriesHierarchy.forEach(cat => {
        html += `<div class="abm-cat-item" data-cat-id="${cat.id_categorie}">
                    <i class="bi bi-folder"></i>
                    <span>${escapeHtml(cat.nom_categorie)}</span>
                </div>`;
    });
    container.innerHTML = html;
    
    const first = container.querySelector('.abm-cat-item');
    if (first) {
        first.classList.add('active');
        renderDesktopSubs(first.dataset.catId);
    }
}

let desktopHoverTimeout;
function initDesktopEvents() {
    const container = document.getElementById('abmDesktopSubsContainer');
    const defaultContent = document.getElementById('abmDefaultContent');
    const dynamicContent = document.getElementById('abmDynamicContent');
    
    document.querySelectorAll('.abm-cat-item').forEach(item => {
        item.addEventListener('mouseenter', () => {
            if (desktopHoverTimeout) clearTimeout(desktopHoverTimeout);
            document.querySelectorAll('.abm-cat-item').forEach(i => i.classList.remove('active'));
            item.classList.add('active');
            renderDesktopSubs(item.dataset.catId);
        });
    });
    
    if (container) {
        container.addEventListener('mouseleave', () => {
            desktopHoverTimeout = setTimeout(() => {
                defaultContent.classList.remove('hide');
                dynamicContent.classList.remove('active');
            }, 150);
        });
        container.addEventListener('mouseenter', () => {
            if (desktopHoverTimeout) clearTimeout(desktopHoverTimeout);
        });
    }
}

// ============================================
// CARROUSEL DESKTOP
// ============================================
function generateDesktopCarousel() {
    const container = document.getElementById('abmCarouselSlides');
    const dotsContainer = document.getElementById('abmCarouselDots');
    if (!container) return;
    
    if (!bannersData || bannersData.length === 0) {
        container.innerHTML = `<div class="abm-carousel-slide active">
            <img src="https://img.icons8.com/color/96/000000/shopping-bag.png">
        </div>`;
        if (dotsContainer) dotsContainer.innerHTML = '<div class="abm-dot active"></div>';
        return;
    }
    
    let slides = '', dots = '';
    bannersData.forEach((b, i) => {
        const imgUrl = getImageUrl(b.image);
        slides += `<div class="abm-carousel-slide ${i === 0 ? 'active' : ''}" data-index="${i}">
                    <img src="${imgUrl}" onerror="this.src='https://img.icons8.com/color/96/000000/shopping-bag.png'">
                </div>`;
        dots += `<div class="abm-dot ${i === 0 ? 'active' : ''}" data-index="${i}"></div>`;
    });
    container.innerHTML = slides;
    if (dotsContainer) dotsContainer.innerHTML = dots;
}

let carouselInterval, currentSlide = 0;
function initDesktopCarousel() {
    const slides = document.querySelectorAll('#abmCarouselSlides .abm-carousel-slide');
    const dots = document.querySelectorAll('#abmCarouselDots .abm-dot');
    const defaultContent = document.getElementById('abmDefaultContent');
    if (slides.length === 0) return;
    
    function goTo(i) {
        slides.forEach((s, idx) => s.classList.toggle('active', idx === i));
        dots.forEach((d, idx) => d.classList.toggle('active', idx === i));
        currentSlide = i;
    }
    function next() { goTo((currentSlide + 1) % slides.length); }
    function start() { if (carouselInterval) clearInterval(carouselInterval); carouselInterval = setInterval(() => { if (!defaultContent?.classList.contains('hide')) next(); }, 5000); }
    
    document.getElementById('abmCarouselPrev')?.addEventListener('click', () => { goTo((currentSlide - 1 + slides.length) % slides.length); start(); });
    document.getElementById('abmCarouselNext')?.addEventListener('click', () => { next(); start(); });
    dots.forEach(d => d.addEventListener('click', () => { goTo(parseInt(d.dataset.index)); start(); }));
    start();
}

// ============================================
// BOUTON DÉCOUVRIR
// ============================================
function initDiscoverButtons() {
    const discoverBtn = document.getElementById('discoverBtn');
    const mobileDiscoverBtn = document.getElementById('mobileDiscoverBtn');
    const currentLink = bannersData && bannersData[currentSlide] ? bannersData[currentSlide].link : null;
    
    function handleDiscover() {
        if (currentLink && currentLink !== '#') {
            window.location.href = currentLink;
        } else {
            window.location.href = BASE_URL;
        }
    }
    
    if (discoverBtn) discoverBtn.addEventListener('click', handleDiscover);
    if (mobileDiscoverBtn) mobileDiscoverBtn.addEventListener('click', handleDiscover);
}

// ============================================
// MENU MOBILE
// ============================================
function generateMobileMenu() {
    const container = document.getElementById('abmMobileCategories');
    if (!container) return;
    
    let html = '';
    categoriesHierarchy.forEach(cat => {
        html += `<div class="abm-mobile-cat-item">
                    <div class="abm-mobile-cat-title" data-cat-id="${cat.id_categorie}">
                        <span><i class="bi bi-folder me-2"></i>${escapeHtml(cat.nom_categorie)}</span>
                        <i class="bi bi-chevron-right"></i>
                    </div>
                    <div class="abm-mobile-subs">`;
        if (cat.children?.length) {
            cat.children.forEach(lvl1 => {
                const lvl1Url = lvl1.slug_categorie ? 'category/' + lvl1.slug_categorie : '#';
                html += `<div class="abm-mobile-subgroup">
                            <h6><a href="${lvl1Url}">${escapeHtml(lvl1.nom_categorie)}</a></h6>
                            <ul>`;
                if (lvl1.children?.length) {
                    lvl1.children.forEach(lvl2 => {
                        const lvl2Url = lvl2.slug_categorie ? 'category/' + lvl2.slug_categorie : '#';
                        html += `<li><a href="${lvl2Url}"><strong>${escapeHtml(lvl2.nom_categorie)}</strong></a>`;
                        if (lvl2.children?.length) {
                            html += `<ul style="margin-left:15px; margin-top:5px;">`;
                            lvl2.children.forEach(lvl3 => {
                                const lvl3Url = lvl3.slug_categorie ? 'category/' + lvl3.slug_categorie : '#';
                                html += `<li><a href="${lvl3Url}">• ${escapeHtml(lvl3.nom_categorie)}</a></li>`;
                            });
                            html += `</ul>`;
                        }
                        html += `</li>`;
                    });
                } else {
                    html += `<li><a href="${lvl1Url}">${escapeHtml(lvl1.nom_categorie)}</a></li>`;
                }
                html += `</ul></div>`;
            });
        } else {
            html += `<div class="text-muted small p-3">Aucune sous-catégorie</div>`;
        }
        html += `</div></div>`;
    });
    container.innerHTML = html;
    
    document.querySelectorAll('.abm-mobile-cat-title').forEach(title => {
        title.addEventListener('click', function(e) {
            e.stopPropagation();
            const subs = this.nextElementSibling;
            const isOpen = subs.classList.contains('show');
            document.querySelectorAll('.abm-mobile-subs').forEach(s => s.classList.remove('show'));
            document.querySelectorAll('.abm-mobile-cat-title').forEach(t => t.classList.remove('active'));
            if (!isOpen) {
                subs.classList.add('show');
                this.classList.add('active');
            }
        });
    });
}

// ============================================
// CARROUSEL MOBILE
// ============================================
function generateMobileCarousel() {
    const slidesContainer = document.getElementById('abmMobileSlides');
    const dotsContainer = document.getElementById('abmMobileDots');
    if (!slidesContainer) return;
    
    if (!bannersData || bannersData.length === 0) {
        slidesContainer.innerHTML = `<div class="abm-mobile-slide active">
            <img src="https://img.icons8.com/color/96/000000/shopping-bag.png">
        </div>`;
        if (dotsContainer) dotsContainer.innerHTML = '<div class="abm-mobile-dot active"></div>';
        return;
    }
    
    let slides = '', dots = '';
    bannersData.forEach((b, i) => {
        const imgUrl = getImageUrl(b.image);
        slides += `<div class="abm-mobile-slide ${i === 0 ? 'active' : ''}" data-index="${i}">
                    <img src="${imgUrl}" onerror="this.src='https://img.icons8.com/color/96/000000/shopping-bag.png'">
                </div>`;
        dots += `<div class="abm-mobile-dot ${i === 0 ? 'active' : ''}" data-index="${i}"></div>`;
    });
    slidesContainer.innerHTML = slides;
    if (dotsContainer) dotsContainer.innerHTML = dots;
}

let mobileCarouselInterval, mobileCurrentSlide = 0;
function initMobileCarousel() {
    const slides = document.querySelectorAll('#abmMobileSlides .abm-mobile-slide');
    const dots = document.querySelectorAll('#abmMobileDots .abm-mobile-dot');
    if (slides.length === 0) return;
    
    function goTo(i) {
        slides.forEach((s, idx) => s.classList.toggle('active', idx === i));
        dots.forEach((d, idx) => d.classList.toggle('active', idx === i));
        mobileCurrentSlide = i;
    }
    function next() { goTo((mobileCurrentSlide + 1) % slides.length); }
    function start() { if (mobileCarouselInterval) clearInterval(mobileCarouselInterval); mobileCarouselInterval = setInterval(next, 5000); }
    
    document.getElementById('abmMobilePrev')?.addEventListener('click', () => { goTo((mobileCurrentSlide - 1 + slides.length) % slides.length); start(); });
    document.getElementById('abmMobileNext')?.addEventListener('click', () => { next(); start(); });
    dots.forEach(d => d.addEventListener('click', () => { goTo(parseInt(d.dataset.index)); start(); }));
    start();
}

// ============================================
// INITIALISATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    generateDesktopCategories();
    generateDesktopCarousel();
    initDesktopEvents();
    initDesktopCarousel();
    generateMobileMenu();
    generateMobileCarousel();
    initMobileCarousel();
    initDiscoverButtons();
});
</script>




















<!-- Flash Sale Section Start -->
<section class="mt-20">
    <div class="custom-container">
        <div class="flash-sale-section light-bg-color">
            <div class="title title-timer justify-content-between">
                <div class="title-flex">
                    <h3><i class="ri-flashlight-line"></i> Vente Flash</h3>
                    <div class="title-timer clockdiv" id="flashSaleTimer">
                        <span class="timer">Se termine dans :</span>
                        <div class="counter">
                            <span class="hours" id="flashHours">00</span>
                            <span class="smalltext">Heures</span>
                        </div>
                        <div class="counter">
                            <span class="minutes" id="flashMinutes">00</span>
                            <span class="smalltext">Minutes</span>
                        </div>
                        <div class="counter">
                            <span class="seconds" id="flashSeconds">00</span>
                            <span class="smalltext">Secondes</span>
                        </div>
                    </div>
                    <p>Articles chauds, prix abordables, mises à jour quotidiennes.</p>
                </div>
                <a href="<?= base_url('offres'); ?>">Voir toutes les offres <i class="ri-arrow-right-s-line"></i></a>
            </div>

            <div class="swiper product-slider-7 product-box-slider">
                <div class="swiper-wrapper">
                    <?php if(!empty($flashSaleProducts)): ?>
                        <?php foreach($flashSaleProducts as $product): ?>
                        <div class="swiper-slide">
                            <div class="product-box productMain">
                                <a href="<?= base_url('product/' . $product['slug_produit']); ?>" class="product-image">
                                    <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid productImage" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                </a>
                                <div class="product-content">
                                    <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                        <h4 class="productName"><?= htmlspecialchars(substr($product['nom_produit'], 0, 40), ENT_QUOTES, 'UTF-8'); ?>...</h4>
                                    </a>
                                    <ul class="rating">
                                        <?php 
                                        $rating = round($product['note_moyenne'] ?? 0);
                                        for($i = 1; $i <= 5; $i++): 
                                        ?>
                                            <li><i class="ri-star-fill <?= $i <= $rating ? 'fill' : ''; ?>"></i></li>
                                        <?php endfor; ?>
                                        <li><span>(<?= $product['nombre_avis'] ?? 0; ?> avis)</span></li>
                                    </ul>
                                    <h5 class="price">
                                        <?= number_format($product['prix_promo'], 0, ',', ' '); ?> BIF
                                        <del><?= number_format($product['prix_base'], 0, ',', ' '); ?> BIF</del>
                                    </h5>
                                    <div class="progress">
                                        <?php 
                                        $total_stock = $product['quantite_actuelle'] + ($product['nombre_ventes'] ?? 0);
                                        $sold_percent = ($total_stock > 0) ? round((($product['nombre_ventes'] ?? 0) / $total_stock) * 100) : 0;
                                        ?>
                                        <div class="progress-bar" style="width: <?= min($sold_percent, 100); ?>%"></div>
                                    </div>
                                    <h5 class="sold">Vendus : <?= $product['nombre_ventes'] ?? 0; ?>/<?= $total_stock; ?></h5>
                                </div>
                                <div class="compare-box">
                                    <button class="btn cart-button add-to-cart-btn" data-product-id="<?= $product['id_produit']; ?>" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                                        Ajouter au panier
                                    </button>
                                    <ul class="compare-list">
                                        <li>
                                            <a href="#" class="wishlistProduct add-to-wishlist" data-product-id="<?= $product['id_produit']; ?>">
                                                <i class="ri-heart-3-line"></i>
                                                <span>Wishlist</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="quick-view-btn" data-product-id="<?= $product['id_produit']; ?>" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                                                <i class="ri-eye-line"></i>
                                                <span>Vue rapide</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Produits par défaut si aucun en flash sale -->
                        <div class="swiper-slide">
                            <div class="product-box productMain">
                                <a href="<?= base_url('product/iphone-14-pro'); ?>" class="product-image">
                                    <img src="<?= base_url('assets/frontend/images/product/1.png'); ?>" class="img-fluid productImage" alt="iPhone 14 Pro">
                                </a>
                                <div class="product-content">
                                    <a href="<?= base_url('product/iphone-14-pro'); ?>">
                                        <h4 class="productName">iPhone 14 Pro</h4>
                                    </a>
                                    <ul class="rating">
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                    </ul>
                                    <h5 class="price">1 100 000 BIF <del>1 200 000 BIF</del></h5>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 35%"></div>
                                    </div>
                                    <h5 class="sold">Vendus : 8/25</h5>
                                </div>
                                <div class="compare-box">
                                    <button class="btn cart-button add-to-cart-btn" data-product-id="1">Ajouter au panier</button>
                                    <ul class="compare-list">
                                        <li><a href="#" class="wishlistProduct add-to-wishlist" data-product-id="1"><i class="ri-heart-3-line"></i><span>Wishlist</span></a></li>
                                        <li><a href="#" class="quick-view-btn" data-product-id="1"><i class="ri-eye-line"></i><span>Vue rapide</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="product-box productMain">
                                <a href="<?= base_url('product/ordinateur-portable-dell'); ?>" class="product-image">
                                    <img src="<?= base_url('assets/frontend/images/product/2.png'); ?>" class="img-fluid productImage" alt="Dell Inspiron">
                                </a>
                                <div class="product-content">
                                    <a href="<?= base_url('product/ordinateur-portable-dell'); ?>">
                                        <h4 class="productName">Dell Inspiron 15</h4>
                                    </a>
                                    <ul class="rating">
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill"></i></li>
                                    </ul>
                                    <h5 class="price">799 000 BIF <del>850 000 BIF</del></h5>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 45%"></div>
                                    </div>
                                    <h5 class="sold">Vendus : 5/10</h5>
                                </div>
                                <div class="compare-box">
                                    <button class="btn cart-button add-to-cart-btn" data-product-id="2">Ajouter au panier</button>
                                    <ul class="compare-list">
                                        <li><a href="#" class="wishlistProduct add-to-wishlist" data-product-id="2"><i class="ri-heart-3-line"></i><span>Wishlist</span></a></li>
                                        <li><a href="#" class="quick-view-btn" data-product-id="2"><i class="ri-eye-line"></i><span>Vue rapide</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="product-box productMain">
                                <a href="<?= base_url('product/t-shirt-homme'); ?>" class="product-image">
                                    <img src="<?= base_url('assets/frontend/images/product/3.png'); ?>" class="img-fluid productImage" alt="T-Shirt">
                                </a>
                                <div class="product-content">
                                    <a href="<?= base_url('product/t-shirt-homme'); ?>">
                                        <h4 class="productName">T-Shirt Nike</h4>
                                    </a>
                                    <ul class="rating">
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill"></i></li>
                                        <li><i class="ri-star-fill"></i></li>
                                    </ul>
                                    <h5 class="price">19 990 BIF <del>25 000 BIF</del></h5>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 70%"></div>
                                    </div>
                                    <h5 class="sold">Vendus : 30/130</h5>
                                </div>
                                <div class="compare-box">
                                    <button class="btn cart-button add-to-cart-btn" data-product-id="3">Ajouter au panier</button>
                                    <ul class="compare-list">
                                        <li><a href="#" class="wishlistProduct add-to-wishlist" data-product-id="3"><i class="ri-heart-3-line"></i><span>Wishlist</span></a></li>
                                        <li><a href="#" class="quick-view-btn" data-product-id="3"><i class="ri-eye-line"></i><span>Vue rapide</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="product-box productMain">
                                <a href="<?= base_url('product/chaussures-sport'); ?>" class="product-image">
                                    <img src="<?= base_url('assets/frontend/images/product/4.png'); ?>" class="img-fluid productImage" alt="Chaussures">
                                </a>
                                <div class="product-content">
                                    <a href="<?= base_url('product/chaussures-sport'); ?>">
                                        <h4 class="productName">Chaussures Adidas</h4>
                                    </a>
                                    <ul class="rating">
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill fill"></i></li>
                                        <li><i class="ri-star-fill"></i></li>
                                    </ul>
                                    <h5 class="price">39 990 BIF <del>45 000 BIF</del></h5>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 25%"></div>
                                    </div>
                                    <h5 class="sold">Vendus : 22/115</h5>
                                </div>
                                <div class="compare-box">
                                    <button class="btn cart-button add-to-cart-btn" data-product-id="4">Ajouter au panier</button>
                                    <ul class="compare-list">
                                        <li><a href="#" class="wishlistProduct add-to-wishlist" data-product-id="4"><i class="ri-heart-3-line"></i><span>Wishlist</span></a></li>
                                        <li><a href="#" class="quick-view-btn" data-product-id="4"><i class="ri-eye-line"></i><span>Vue rapide</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Flash Sale Section End -->
























<!-- ============================================ -->
<!-- SECTION ARTICLES DE CUISINE / HIGH-TECH / ÉLECTROMÉNAGER -->
<!-- ============================================ -->
<section class="mt-20">
    <div class="custom-container">
        <div class="row g-sm-4 g-3">
            <!-- Top Kitchen Items (Catégorie ID 3 - Maison & Cuisine) -->
            <div class="col-xl-4 col-md-6">
                <div class="light-bg-color">
                    <div class="title d-block">
                        <h3>Articles de cuisine</h3>
                    </div>
                    <div class="row g-3">
                        <?php if(isset($categoryProducts[3]['products']) && !empty($categoryProducts[3]['products'])): ?>
                            <?php foreach(array_slice($categoryProducts[3]['products'], 0, 5) as $product): ?>
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                        <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </a>
                                    <h4><?= htmlspecialchars(substr($product['nom_produit'], 0, 25), ENT_QUOTES, 'UTF-8'); ?>...</h4>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Produits par défaut (fallback) -->
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=3'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/8.png'); ?>" class="img-fluid" alt="Ustensiles cuisine">
                                    </a>
                                    <h4>Ustensiles cuisine</h4>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=3'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/9.png'); ?>" class="img-fluid" alt="Mug isotherme">
                                    </a>
                                    <h4>Mug isotherme</h4>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=3'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/10.png'); ?>" class="img-fluid" alt="Set stainless">
                                    </a>
                                    <h4>Set inox 6pcs</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=3'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/11.png'); ?>" class="img-fluid" alt="Vaisselle">
                                    </a>
                                    <h4>Vaisselle inox</h4>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-lg-12 col-sm-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=3'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/12.png'); ?>" class="img-fluid" alt="Set de table">
                                    </a>
                                    <h4>Set de table carré</h4>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Top Tech Items (Catégorie ID 1 - Électronique) -->
            <div class="col-xl-4 col-md-6">
                <div class="light-bg-color">
                    <div class="title d-block">
                        <h3>High-Tech</h3>
                    </div>
                    <div class="row g-3">
                        <?php if(isset($categoryProducts[1]['products']) && !empty($categoryProducts[1]['products'])): ?>
                            <?php foreach(array_slice($categoryProducts[1]['products'], 0, 5) as $product): ?>
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                        <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </a>
                                    <h4><?= htmlspecialchars(substr($product['nom_produit'], 0, 25), ENT_QUOTES, 'UTF-8'); ?>...</h4>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Produits par défaut -->
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=8'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/13.png'); ?>" class="img-fluid" alt="Téléphone">
                                    </a>
                                    <h4>One Plus Nord CE</h4>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=8'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/14.png'); ?>" class="img-fluid" alt="Samsung Galaxy">
                                    </a>
                                    <h4>Samsung Galaxy M14</h4>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('product/iphone-14-pro'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/15.png'); ?>" class="img-fluid" alt="iPhone 14 Pro">
                                    </a>
                                    <h4>iPhone 14 Pro</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=8'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/16.png'); ?>" class="img-fluid" alt="Realme">
                                    </a>
                                    <h4>Realme 10 PRO</h4>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-lg-12 col-sm-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=9'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/17.png'); ?>" class="img-fluid" alt="MacBook Pro">
                                    </a>
                                    <h4>MacBook Pro 14"</h4>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Top Kitchen Appliances (Catégorie ID 8 - Téléphones/Accessoires) -->
            <div class="col-xl-4 col-md-6">
                <div class="light-bg-color">
                    <div class="title d-block">
                        <h3>Accessoires & Électroménager</h3>
                    </div>
                    <div class="row g-3">
                        <?php if(isset($categoryProducts[8]['products']) && !empty($categoryProducts[8]['products'])): ?>
                            <?php foreach(array_slice($categoryProducts[8]['products'], 0, 5) as $product): ?>
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                        <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </a>
                                    <h4><?= htmlspecialchars(substr($product['nom_produit'], 0, 25), ENT_QUOTES, 'UTF-8'); ?>...</h4>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=18'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/18.png'); ?>" class="img-fluid" alt="Coques">
                                    </a>
                                    <h4>Coques téléphone</h4>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=18'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/21.png'); ?>" class="img-fluid" alt="Chargeurs">
                                    </a>
                                    <h4>Chargeurs rapides</h4>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=18'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/20.png'); ?>" class="img-fluid" alt="Écouteurs">
                                    </a>
                                    <h4>Écouteurs Bluetooth</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=19'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/19.png'); ?>" class="img-fluid" alt="Montres">
                                    </a>
                                    <h4>Montres connectées</h4>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-lg-12 col-sm-6">
                                <div class="latest-product-box">
                                    <a href="<?= base_url('shop?category=9'); ?>">
                                        <img src="<?= base_url('assets/frontend/images/product/18.png'); ?>" class="img-fluid" alt="PC Gaming">
                                    </a>
                                    <h4>PC Gaming</h4>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>








<!-- Banner Section Start -->
<section class="mt-20">
    <div class="custom-container">
        <div class="row g-sm-4 g-3">
            <!-- Grande bannière gauche -->
            <div class="col-xl-8">
                <a href="<?= base_url('shop'); ?>" class="banner-box">
                    <?php 
                    $mainBanner = !empty($slider_banners) ? $slider_banners[0] : null;
                    if($mainBanner && !empty($mainBanner['image'])): 
                    ?>
                    <img src="<?= base_url($mainBanner['image']); ?>" 
                         class="img-fluid" 
                         alt="<?= htmlspecialchars($mainBanner['title'] ?? 'Bannière promotion'); ?>"
                         loading="lazy">
                    <?php else: ?>
                    <img src="<?= base_url('assets/frontend/images/banner/8.jpg'); ?>" 
                         class="img-fluid" 
                         alt="Promotion"
                         loading="lazy">
                    <?php endif; ?>
                </a>
            </div>
            
            <!-- Petite bannière droite -->
            <div class="col-xl-4">
                <a href="<?= base_url('offres'); ?>" class="banner-box h-100">
                    <?php 
                    $secondaryBanner = !empty($slider_banners) && isset($slider_banners[1]) ? $slider_banners[1] : null;
                    if($secondaryBanner && !empty($secondaryBanner['image'])): 
                    ?>
                    <img src="<?= base_url($secondaryBanner['image']); ?>" 
                         class="img-fluid" 
                         alt="<?= htmlspecialchars($secondaryBanner['title'] ?? 'Offres flash'); ?>"
                         loading="lazy">
                    <?php else: ?>
                    <img src="<?= base_url('assets/frontend/images/banner/9.jpg'); ?>" 
                         class="img-fluid" 
                         alt="Offres flash"
                         loading="lazy">
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- Banner Section End -->







<!-- Category Section Start -->
<section class="mt-20">
    <div class="custom-container">
        <div class="light-bg-color overflow-hidden">
            <div class="title title-timer justify-content-between">
                <h3>Toutes nos catégories</h3>
                <a href="<?= base_url('shop'); ?>">Voir tout <i class="ri-arrow-right-s-line"></i></a>
            </div>

            <div class="swiper category-box-slide">
                <div class="swiper-wrapper">
                    <?php if(!empty($main_categories)): ?>
                        <?php foreach($main_categories as $cat): ?>
                        <div class="swiper-slide">
                            <a href="<?= base_url('category/' . $cat['slug_categorie']); ?>" class="category-box">
                                <?php if(!empty($cat['url_image'])): ?>
                                <img src="<?= base_url($cat['url_image']); ?>" class="img-fluid" alt="<?= htmlspecialchars($cat['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php else: ?>
                                <img src="<?= base_url('assets/frontend/images/category/' . $cat['id_categorie'] . '.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($cat['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php endif; ?>
                                <h4><?= htmlspecialchars($cat['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?></h4>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/1.png'); ?>" class="img-fluid" alt="Électronique">
                                <h4>Électronique</h4>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/2.png'); ?>" class="img-fluid" alt="Maison">
                                <h4>Maison</h4>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/3.png'); ?>" class="img-fluid" alt="Sport">
                                <h4>Sport & Outdoor</h4>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/4.png'); ?>" class="img-fluid" alt="Jouets">
                                <h4>Jouets</h4>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/5.png'); ?>" class="img-fluid" alt="Épicerie">
                                <h4>Épicerie</h4>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/6.png'); ?>" class="img-fluid" alt="Mode Homme">
                                <h4>Mode Homme</h4>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/7.png'); ?>" class="img-fluid" alt="Mode Femme">
                                <h4>Mode Femme</h4>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/8.png'); ?>" class="img-fluid" alt="Bébé">
                                <h4>Bébé</h4>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/9.png'); ?>" class="img-fluid" alt="Santé">
                                <h4>Santé & Bien-être</h4>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/10.png'); ?>" class="img-fluid" alt="Auto">
                                <h4>Auto & Pneus</h4>
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="<?= base_url('shop'); ?>" class="category-box">
                                <img src="<?= base_url('assets/frontend/images/category/11.png'); ?>" class="img-fluid" alt="Essentiels">
                                <h4>Essentiels maison</h4>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Category Section End -->








<!-- Recommendations Product & Banner Section Start -->
<section class="mt-20">
    <div class="custom-container">
        <div class="row g-4">
            <!-- Left Side Banner -->
            <div class="col-xxl-3-1 col-xl-4 d-xl-block d-none">
                <a href="<?= base_url('offres'); ?>" class="banner-box b-left h-100">
                    <?php if(!empty($banners) && isset($banners[3])): ?>
                    <img src="<?= base_url('uploads/banners/' . $banners[3]['image']); ?>" class="img-fluid" alt="Recommandations">
                    <?php else: ?>
                    <img src="<?= base_url('assets/frontend/images/banner/10.jpg'); ?>" class="img-fluid" alt="Recommandations">
                    <?php endif; ?>
                </a>
            </div>

            <!-- Recommendations Products -->
            <div class="col-xxl-8-1 col-xl-8">
                <div class="light-bg-color">
                    <div class="title justify-content-between d-sm-flex d-block">
                        <h3>Recommandations</h3>
                        <ul class="nav nav-pills title-nav-pills mt-sm-0 mt-3" id="pills-tab">
                            <li class="nav-item">
                                <button class="nav-link active" id="pills-top-tabe" data-bs-toggle="pill" data-bs-target="#pills-top" type="button">Top 20</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="pills-rate-tabe" data-bs-toggle="pill" data-bs-target="#pills-rate" type="button">Les mieux notés</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="pills-choice-tabe" data-bs-toggle="pill" data-bs-target="#pills-choice" type="button">Choix de la rédaction</button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- Top 20 Tab -->
                        <div class="tab-pane fade show active" id="pills-top">
                            <div class="swiper recommendations-slider">
                                <div class="swiper-wrapper">
                                    <?php if(!empty($topSellingProducts)): ?>
                                        <?php 
                                        $chunks = array_chunk($topSellingProducts, 3);
                                        foreach($chunks as $chunk): 
                                        ?>
                                        <div class="swiper-slide">
                                            <ul class="recommendations-product-list">
                                                <?php foreach($chunk as $product): ?>
                                                <li>
                                                    <div class="vertical-product-box">
                                                        <a href="<?= base_url('product/' . $product['slug_produit']); ?>" class="product-image">
                                                            <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        </a>
                                                        <div class="product-content">
                                                            <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                                                <h4 class="name title-color"><?= htmlspecialchars(substr($product['nom_produit'], 0, 45), ENT_QUOTES, 'UTF-8'); ?>...</h4>
                                                            </a>
                                                            <ul class="rating">
                                                                <?php 
                                                                $rating = round($product['note_moyenne'] ?? 0);
                                                                for($i = 1; $i <= 5; $i++): 
                                                                ?>
                                                                    <li><i class="ri-star-fill <?= $i <= $rating ? 'fill' : ''; ?>"></i></li>
                                                                <?php endfor; ?>
                                                            </ul>
                                                            <h5 class="price">
                                                                <?= number_format($product['prix_promo'] ?? $product['prix_base'], 0, ',', ' '); ?> BIF
                                                                <?php if(!empty($product['prix_promo']) && $product['prix_promo'] < $product['prix_base']): ?>
                                                                <del><?= number_format($product['prix_base'], 0, ',', ' '); ?> BIF</del>
                                                                <?php endif; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="swiper-slide">
                                            <ul class="recommendations-product-list">
                                                <li class="text-center p-4">Aucun produit recommandé pour le moment</li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Best Rated Tab -->
                        <div class="tab-pane fade" id="pills-rate">
                            <div class="swiper recommendations-slider">
                                <div class="swiper-wrapper">
                                    <?php if(!empty($recommendedProducts)): ?>
                                        <?php 
                                        $chunks = array_chunk($recommendedProducts, 3);
                                        foreach($chunks as $chunk): 
                                        ?>
                                        <div class="swiper-slide">
                                            <ul class="recommendations-product-list">
                                                <?php foreach($chunk as $product): ?>
                                                <li>
                                                    <div class="vertical-product-box">
                                                        <a href="<?= base_url('product/' . $product['slug_produit']); ?>" class="product-image">
                                                            <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        </a>
                                                        <div class="product-content">
                                                            <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                                                <h5 class="name title-color"><?= htmlspecialchars(substr($product['nom_produit'], 0, 45), ENT_QUOTES, 'UTF-8'); ?>...</h5>
                                                            </a>
                                                            <ul class="rating">
                                                                <?php 
                                                                $rating = round($product['note_moyenne'] ?? 0);
                                                                for($i = 1; $i <= 5; $i++): 
                                                                ?>
                                                                    <li><i class="ri-star-fill <?= $i <= $rating ? 'fill' : ''; ?>"></i></li>
                                                                <?php endfor; ?>
                                                            </ul>
                                                            <h5 class="price">
                                                                <?= number_format($product['prix_promo'] ?? $product['prix_base'], 0, ',', ' '); ?> BIF
                                                                <?php if(!empty($product['prix_promo']) && $product['prix_promo'] < $product['prix_base']): ?>
                                                                <del><?= number_format($product['prix_base'], 0, ',', ' '); ?> BIF</del>
                                                                <?php endif; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="swiper-slide">
                                            <ul class="recommendations-product-list">
                                                <li class="text-center p-4">Aucun produit recommandé pour le moment</li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Editor's Choice Tab -->
                        <div class="tab-pane fade" id="pills-choice">
                            <div class="swiper recommendations-slider">
                                <div class="swiper-wrapper">
                                    <?php if(!empty($newProducts)): ?>
                                        <?php 
                                        $chunks = array_chunk($newProducts, 3);
                                        foreach($chunks as $chunk): 
                                        ?>
                                        <div class="swiper-slide">
                                            <ul class="recommendations-product-list">
                                                <?php foreach($chunk as $product): ?>
                                                <li>
                                                    <div class="vertical-product-box">
                                                        <a href="<?= base_url('product/' . $product['slug_produit']); ?>" class="product-image">
                                                            <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        </a>
                                                        <div class="product-content">
                                                            <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                                                <h5 class="name title-color"><?= htmlspecialchars(substr($product['nom_produit'], 0, 45), ENT_QUOTES, 'UTF-8'); ?>...</h5>
                                                            </a>
                                                            <ul class="rating">
                                                                <?php 
                                                                $rating = round($product['note_moyenne'] ?? 0);
                                                                for($i = 1; $i <= 5; $i++): 
                                                                ?>
                                                                    <li><i class="ri-star-fill <?= $i <= $rating ? 'fill' : ''; ?>"></i></li>
                                                                <?php endfor; ?>
                                                            </ul>
                                                            <h5 class="price">
                                                                <?= number_format($product['prix_promo'] ?? $product['prix_base'], 0, ',', ' '); ?> BIF
                                                                <?php if(!empty($product['prix_promo']) && $product['prix_promo'] < $product['prix_base']): ?>
                                                                <del><?= number_format($product['prix_base'], 0, ',', ' '); ?> BIF</del>
                                                                <?php endif; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="swiper-slide">
                                            <ul class="recommendations-product-list">
                                                <li class="text-center p-4">Aucun nouveau produit pour le moment</li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Recommendations Product & Banner Section End -->









<!-- Top Trending & Hot Deal Product Section Start -->
<section class="mt-20">
    <div class="custom-container">
        <div class="row g-3">
            <!-- Left Sidebar Banner avec catégories -->
            <div class="col-xxl-3-1 col-xl-3 d-xl-block d-none">
                <div class="bg-white h-100 ratio_50">
                    <?php if (!empty($banners) && isset($banners[0])): ?>
                    <a href="<?= base_url('shop'); ?>" class="banner-box b-left">
                        <img src="<?= base_url('uploads/banners/' . ($banners[0]['image'] ?? 'banner-11.jpg')); ?>" class="bg-img" alt="Banner">
                    </a>
                    <?php else: ?>
                    <a href="<?= base_url('shop'); ?>" class="banner-box b-left">
                        <img src="<?= base_url('assets/frontend/images/banner/11.jpg'); ?>" class="bg-img" alt="Banner">
                    </a>
                    <?php endif; ?>
                    
                    <div class="left-banner-content">
                        <!-- Accessoires -->
                        <div class="accessories-computer-box">
                            <h4 class="deal-title">Accessoires</h4>
                            <ul class="deal-list">
                                <li><a href="<?= base_url('shop?category=18'); ?>">Coques téléphone</a></li>
                                <li><a href="<?= base_url('shop?category=18'); ?>">Chargeurs</a></li>
                                <li><a href="<?= base_url('shop?category=18'); ?>">Écouteurs</a></li>
                                <li><a href="<?= base_url('shop?category=9'); ?>">Souris</a></li>
                                <li><a href="<?= base_url('shop?category=19'); ?>">Montres</a></li>
                                <li><a href="<?= base_url('shop'); ?>">Voir plus</a></li>
                            </ul>
                        </div>

                        <!-- Informatique -->
                        <div class="accessories-computer-box">
                            <h4 class="deal-title">Informatique</h4>
                            <ul class="deal-list">
                                <li><a href="<?= base_url('shop?category=9'); ?>">Ordinateurs portables</a></li>
                                <li><a href="<?= base_url('shop?category=9'); ?>">Ordinateurs de bureau</a></li>
                                <li><a href="<?= base_url('shop?category=9'); ?>">PC Gaming</a></li>
                                <li><a href="<?= base_url('shop?category=9'); ?>">Composants</a></li>
                                <li><a href="<?= base_url('shop?category=8'); ?>">Téléphones</a></li>
                                <li><a href="<?= base_url('shop'); ?>">Voir plus</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Trending & Hot Deal Products -->
            <div class="col-xxl-8-1 col-xl-8">
                <div class="row g-3">
                    <!-- Top Trending Products -->
                    <div class="col-xxl-7 col-xl-6">
                        <div class="light-bg-color">
                            <div class="title title-timer justify-content-between">
                                <h3>Produits tendance</h3>
                                <a href="<?= base_url('shop?sort=trending'); ?>">Voir tout <i class="ri-arrow-right-s-line"></i></a>
                            </div>

                            <div class="swiper slider-2 slider-pagination">
                                <div class="swiper-wrapper">
                                    <?php if (!empty($trendingProducts)): ?>
                                        <?php 
                                        $chunks = array_chunk($trendingProducts, 2);
                                        foreach($chunks as $chunk): 
                                        ?>
                                        <div class="swiper-slide">
                                            <ul class="top-trending-product-list">
                                                <?php foreach($chunk as $product): ?>
                                                <li>
                                                    <div class="vertical-product-box">
                                                        <a href="<?= base_url('product/' . $product['slug_produit']); ?>" class="product-image">
                                                            <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        </a>
                                                        <div class="product-content">
                                                            <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                                                <h4 class="name title-color"><?= htmlspecialchars(substr($product['nom_produit'], 0, 60), ENT_QUOTES, 'UTF-8'); ?>...</h4>
                                                            </a>
                                                            <ul class="rating">
                                                                <?php 
                                                                $rating = round($product['note_moyenne'] ?? 0);
                                                                for($i = 1; $i <= 5; $i++): 
                                                                ?>
                                                                    <li><i class="ri-star-fill <?= $i <= $rating ? 'fill' : ''; ?>"></i></li>
                                                                <?php endfor; ?>
                                                                <li><span>(<?= $product['nombre_avis'] ?? 0; ?>)</span></li>
                                                            </ul>
                                                            <h5 class="price">
                                                                <?= number_format($product['prix_promo'] ?? $product['prix_base'], 0, ',', ' '); ?> BIF
                                                                <?php if(!empty($product['prix_promo']) && $product['prix_promo'] < $product['prix_base']): ?>
                                                                <del><?= number_format($product['prix_base'], 0, ',', ' '); ?> BIF</del>
                                                                <?php endif; ?>
                                                            </h5>
                                                            <a class="btn btn-sm mt-sn-3 mt-2 btn-bg-theme" href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                                                Acheter
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="swiper-slide">
                                            <ul class="top-trending-product-list">
                                                <li class="text-center p-4">Aucun produit tendance pour le moment</li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Hot Deal Products -->
                    <div class="col-xxl-5 col-xl-6">
                        <div class="light-bg-color">
                            <div class="title slider-button">
                                <h3>Offres du jour</h3>
                                <div class="title-slider-button">
                                    <div class="swiper-button-prev swiper-btn-prev">
                                        <i class="ri-arrow-left-s-line"></i>
                                    </div>
                                    <div class="swiper-button-next swiper-btn-next">
                                        <i class="ri-arrow-right-s-line"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper hot-deal-slider">
                                <div class="swiper-wrapper">
                                    <?php if (!empty($flashSaleProducts)): ?>
                                        <?php foreach($flashSaleProducts as $index => $product): ?>
                                        <div class="swiper-slide">
                                            <div class="hot-deal-product-box">
                                                <div class="product-slider-box">
                                                    <div class="swiper swiper-main-<?= $index; ?>">
                                                        <div class="swiper-wrapper">
                                                            <div class="swiper-slide">
                                                                <a href="<?= base_url('product/' . $product['slug_produit']); ?>" class="main-image-box">
                                                                    <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                                                </a>
                                                            </div>
                                                            <?php 
                                                            $additional_images = $this->Home_model->getProductImages($product['id_produit']);
                                                            $additional_images = array_slice($additional_images, 1, 2);
                                                            foreach($additional_images as $img): 
                                                            ?>
                                                            <div class="swiper-slide">
                                                                <a href="<?= base_url('product/' . $product['slug_produit']); ?>" class="main-image-box">
                                                                    <img src="<?= base_url($img['url_image']); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                                                </a>
                                                            </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <ul class="clockdiv-2 product-timer" data-end-time="<?= strtotime($product['date_fin_promo'] ?? '+24 hours') * 1000; ?>">
                                                            <li><div><div class="counter"><div class="days"></div></div><span class="smalltext">Jours</span></div></li>
                                                            <li><div><div class="counter"><div class="hours"></div></div><span class="smalltext">Heures</span></div></li>
                                                            <li><div><div class="counter"><div class="minutes"></div></div><span class="smalltext">Minutes</span></div></li>
                                                            <li><div><div class="counter"><div class="seconds"></div></div><span class="smalltext">Secondes</span></div></li>
                                                        </ul>
                                                    </div>
                                                    <?php if(count($additional_images) > 0): ?>
                                                    <div class="swiper swiper-thumbnail-<?= $index; ?>">
                                                        <div class="swiper-wrapper">
                                                            <div class="swiper-slide">
                                                                <div class="thumbnail-image">
                                                                    <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="">
                                                                </div>
                                                            </div>
                                                            <?php foreach($additional_images as $img): ?>
                                                            <div class="swiper-slide">
                                                                <div class="thumbnail-image">
                                                                    <img src="<?= base_url($img['url_image']); ?>" class="img-fluid" alt="">
                                                                </div>
                                                            </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="product-content">
                                                    <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                                        <h3><?= htmlspecialchars(substr($product['nom_produit'], 0, 50), ENT_QUOTES, 'UTF-8'); ?>...</h3>
                                                    </a>
                                                    <div class="price-stock">
                                                        <h4 class="price">
                                                            <?= number_format($product['prix_promo'] ?? $product['prix_base'], 0, ',', ' '); ?> BIF
                                                            <?php if(!empty($product['prix_promo']) && $product['prix_promo'] < $product['prix_base']): ?>
                                                            <del><?= number_format($product['prix_base'], 0, ',', ' '); ?> BIF</del>
                                                            <?php endif; ?>
                                                        </h4>
                                                        <div class="product-rating">
                                                            <ul class="rating">
                                                                <?php 
                                                                $rating = round($product['note_moyenne'] ?? 0);
                                                                for($i = 1; $i <= 5; $i++): 
                                                                ?>
                                                                    <li><i class="ri-star-fill <?= $i <= $rating ? 'fill' : ''; ?>"></i></li>
                                                                <?php endfor; ?>
                                                            </ul>
                                                            <h5>Stock : <span class="stock-status"><?= $product['quantite_actuelle'] > 10 ? 'En stock' : ($product['quantite_actuelle'] > 0 ? 'Stock limité' : 'Rupture'); ?></span></h5>
                                                        </div>
                                                    </div>
                                                    <div class="progress">
                                                        <?php 
                                                        $sold_percent = ($product['nombre_ventes'] > 0 && ($product['nombre_ventes'] + $product['quantite_actuelle']) > 0) 
                                                            ? round(($product['nombre_ventes'] / ($product['nombre_ventes'] + $product['quantite_actuelle'])) * 100) 
                                                            : 0;
                                                        ?>
                                                        <div class="progress-bar" style="width: <?= min($sold_percent, 100); ?>%"></div>
                                                    </div>
                                                    <h5><span><?= $product['nombre_ventes'] ?? 0; ?>/<?= ($product['nombre_ventes'] ?? 0) + ($product['quantite_actuelle'] ?? 0); ?></span> Vendus</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="swiper-slide">
                                            <div class="hot-deal-product-box text-center p-4">
                                                <p>Aucune offre du jour pour le moment</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Top Trending & Hot Deal Product Section End -->