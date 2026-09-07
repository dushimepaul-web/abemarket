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
        /* ========== TOUT VOTRE CSS ICI (garde l'existant) ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f5f5;
        }

        .jumia-header {
            background: #f97316;
            padding: 12px 16px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .jumia-header h1 {
            font-size: 20px;
            font-weight: bold;
        }

        .jumia-header h1 span {
            font-size: 10px;
            font-weight: normal;
            margin-left: 5px;
        }

        .burger-menu {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 28px;
            height: 20px;
            cursor: pointer;
            z-index: 1001;
        }

        .burger-menu span {
            display: block;
            height: 3px;
            background: white;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .burger-menu.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .burger-menu.active span:nth-child(2) {
            opacity: 0;
        }

        .burger-menu.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        .menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 998;
        }

        .menu-overlay.active {
            display: block;
        }

        .mobile-menu-panel {
            position: fixed;
            top: 0;
            left: -100%;
            width: 85%;
            max-width: 320px;
            height: 100vh;
            background: white;
            z-index: 999;
            transition: left 0.3s ease;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
        }

        .mobile-menu-panel.active {
            left: 0;
        }

        .mobile-menu-header {
            background: #f97316;
            padding: 18px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
        }

        .mobile-menu-header h3 {
            font-size: 16px;
        }

        .close-menu {
            font-size: 24px;
            cursor: pointer;
            font-weight: bold;
        }

        .mobile-category {
            border-bottom: 1px solid #eee;
        }

        .mobile-category .cat-title {
            padding: 14px 18px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-category .cat-title:active {
            background: #fef3e8;
        }

        .mobile-category .cat-title .arrow {
            font-size: 12px;
            color: #999;
            transition: transform 0.2s;
        }

        .mobile-category .cat-title.open .arrow {
            transform: rotate(90deg);
            color: #f97316;
        }

        .mobile-subs {
            display: none;
            background: #fafafa;
            padding: 10px 18px 20px 35px;
            border-top: 1px solid #eee;
        }

        .mobile-subs.open {
            display: block;
            animation: slideDown 0.2s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .mobile-sub-group {
            margin-bottom: 15px;
        }

        .mobile-sub-group h4 {
            color: #f97316;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .mobile-sub-group ul {
            list-style: none;
        }

        .mobile-sub-group ul li {
            padding: 7px 0;
            font-size: 13px;
            color: #555;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .mobile-sub-group ul li:last-child {
            border-bottom: none;
        }

        .mobile-main {
            padding: 16px;
        }

        .mobile-carousel {
            background: linear-gradient(135deg, #fef3e8, #ffe4cc);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
            position: relative;
        }

        .mobile-slides {
            position: relative;
            height: 280px;
        }

        .mobile-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .mobile-slide.active {
            opacity: 1;
        }

        .mobile-slide img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .mobile-slide h3 {
            font-size: 20px;
            color: #333;
        }

        .mobile-slide h3 span {
            color: #f97316;
        }

        .mobile-slide .discount {
            font-size: 24px;
            font-weight: bold;
            color: #f97316;
            margin: 10px 0;
        }

        .mobile-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.4);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            font-size: 16px;
        }

        .mobile-arrow.prev { left: 8px; }
        .mobile-arrow.next { right: 8px; }

        .mobile-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            padding: 12px;
        }

        .mobile-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ccc;
            cursor: pointer;
        }

        .mobile-dot.active {
            background: #f97316;
            width: 20px;
            border-radius: 10px;
        }

        .mobile-assistance {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .assist-card {
            background: #fef3e8;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 12px;
            text-align: center;
        }

        .assist-card h4 {
            color: #f97316;
            font-size: 14px;
        }

        .whatsapp-card {
            background: #25D366;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            color: white;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .sell-card {
            background: #f97316;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            color: white;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .mobile-brand {
            text-align: center;
            padding: 16px;
            background: white;
            border-radius: 12px;
        }

        .mobile-brand .logo {
            font-size: 22px;
            font-weight: bold;
            color: #f97316;
        }

        /* Desktop */
        .desktop-view {
            display: block;
        }

        .jumia-container {
            background: white;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .mega-menu {
            display: grid;
            grid-template-columns: 280px 1fr 280px;
            min-height: 480px;
            background: white;
        }

        .col-categories {
            background: #fff;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
        }

        .col-categories .category-item {
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .col-categories .category-item:hover {
            background: #fef3e8;
            color: #f97316;
            transform: translateX(3px);
            border-left-color: #f97316;
        }

        .col-subs {
            padding: 20px 25px;
            background: #fff;
        }

        .default-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }

        .desktop-carousel-container {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            background: linear-gradient(135deg, #fef3e8, #ffe4cc);
        }

        .desktop-slides {
            position: relative;
            height: 350px;
        }

        .desktop-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .desktop-slide.active {
            opacity: 1;
        }

        .desktop-slide img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .desktop-slide h3 {
            font-size: 22px;
            color: #333;
        }

        .desktop-slide h3 span {
            color: #f97316;
        }

        .desktop-slide .discount {
            font-size: 28px;
            font-weight: bold;
            color: #f97316;
            margin: 10px 0;
        }

        .desktop-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.5);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
        }

        .desktop-arrow.prev { left: 10px; }
        .desktop-arrow.next { right: 10px; }

        .desktop-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin: 15px 0;
        }

        .desktop-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ccc;
            cursor: pointer;
        }

        .desktop-dot.active {
            background: #f97316;
            width: 25px;
            border-radius: 10px;
        }

        .subs-content {
            display: none;
        }

        .subs-content.active {
            display: block;
        }

        .default-content.hide {
            display: none;
        }

        .sub-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .sub-section h4 {
            color: #f97316;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .sub-section ul {
            list-style: none;
        }

        .sub-section ul li {
            padding: 6px 0;
            font-size: 13px;
            color: #555;
            cursor: pointer;
        }

        .sub-section ul li:hover {
            color: #f97316;
        }

        .col-promo {
            background: #fff;
            border-left: 1px solid #e0e0e0;
            padding: 20px;
        }

        .assistance-card {
            background: #fef3e8;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .assistance-card h4 {
            color: #f97316;
        }

        .brand-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .brand-footer .jumia-logo {
            font-size: 24px;
            font-weight: bold;
            color: #f97316;
        }

        @media (max-width: 768px) {
            .desktop-view { display: none; }
        }

        @media (min-width: 769px) {
            .mobile-menu-panel, .menu-overlay, .mobile-main { display: none; }
            .burger-menu { display: none; }
        }
    </style>


<!-- HEADER -->
<div class="jumia-header">
    <div class="burger-menu" id="burgerBtn">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <h1>ABEMARKET® <span>14 ans, Jumia m3ak</span></h1>
    <div style="width: 28px;"></div>
</div>

<!-- OVERLAY -->
<div class="menu-overlay" id="menuOverlay"></div>

<!-- MENU LATÉRAL MOBILE -->
<div class="mobile-menu-panel" id="mobileMenuPanel">
    <div class="mobile-menu-header">
        <h3>📂 Toutes les catégories</h3>
        <div class="close-menu" id="closeMenu">✕</div>
    </div>
    <div id="mobileCategoriesContainer"></div>
</div>

<!-- CONTENU PRINCIPAL MOBILE -->
<div class="mobile-main">
    <!-- Carrousel Mobile -->
    <div class="mobile-carousel">
        <div class="mobile-arrow prev" id="mobilePrev">❮</div>
        <div class="mobile-arrow next" id="mobileNext">❯</div>
        <div class="mobile-slides" id="mobileSlides"></div>
        <div class="mobile-dots" id="mobileDots"></div>
    </div>

    <!-- Assistance Mobile -->
    <div class="mobile-assistance">
        <div class="assist-card">
            <h4>📞 Centre d'assistance</h4>
            <p style="font-size: 12px;">Guide du service client</p>
        </div>
        <div class="whatsapp-card">
            💬 WhatsApp - Discuter pour commander
        </div>
        <div class="sell-card">
            🛍️ Vendez sur AbeMarket - Ouvrez votre shop ici
        </div>
    </div>

    <!-- Marques -->
    <div class="mobile-brand">
        <div class="logo">ABEMARKET®</div>
        <p style="font-size: 11px; color: #999; margin: 5px 0;">trendyol</p>
        <p style="color: #f97316; font-size: 12px; font-weight: 600;">DÉCOUVRIER →</p>
    </div>
</div>

<!-- VERSION DESKTOP -->
<div class="desktop-view">
    <div class="jumia-container">
        <div class="mega-menu">
            <div class="col-categories" id="desktopCategories"></div>
            <div class="col-subs" id="subsContainer">
                <div class="default-content" id="defaultContent">
                    <div class="desktop-carousel-container">
                        <div class="desktop-arrow prev" id="desktopPrev">❮</div>
                        <div class="desktop-arrow next" id="desktopNext">❯</div>
                        <div class="desktop-slides" id="desktopSlides"></div>
                        <div class="desktop-dots" id="desktopDots"></div>
                    </div>
                </div>
                <div class="subs-content" id="subsContent"></div>
            </div>
            <div class="col-promo">
                <div class="assistance-card">
                    <h4>📞 Centre d'assistance</h4>
                    <p>Guide du service client</p>
                </div>
                <div class="whatsapp-card">💬 WhatsApp - Discuter pour commander</div>
                <div class="sell-card">🛍️ Vendez sur AbeMarket - Ouvrez votre shop ici</div>
                <div class="brand-footer">
                    <div class="jumia-logo">ABEMARKET®</div>
                    <div style="font-size:12px; color:#999;">trendyol</div>
                    <div style="color:#f97316; margin-top:8px;">DÉCOUVRIER →</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ========== DONNÉES DYNAMIQUES DEPUIS PHP ==========
const categoriesData = <?= json_encode($categories_hierarchy) ?>;
const bannersData = <?= json_encode($slider_banners) ?>;

// Transformation des catégories pour le format attendu
function transformCategoriesForMobile(data) {
    const result = [];
    
    function processCategory(cat, level = 0) {
        const item = {
            id: cat.id_categorie,
            name: cat.nom_categorie,
            subs: []
        };
        
        if (cat.children && cat.children.length > 0) {
            const group = {
                title: cat.nom_categorie.toUpperCase(),
                items: []
            };
            
            cat.children.forEach(child => {
                group.items.push(child.nom_categorie);
                // Ajouter les sous-sous-catégories si existantes
                if (child.children && child.children.length > 0) {
                    child.children.forEach(subChild => {
                        group.items.push('  • ' + subChild.nom_categorie);
                    });
                }
            });
            item.subs.push(group);
        }
        
        return item;
    }
    
    categoriesData.forEach(cat => {
        result.push(processCategory(cat));
    });
    
    return result;
}

const mobileCategoriesData = transformCategoriesForMobile(categoriesData);

// ========== GÉNÉRATION DU MENU MOBILE ==========
function generateMobileMenu() {
    const container = document.getElementById('mobileCategoriesContainer');
    if (!container) return;
    
    let html = '';
    mobileCategoriesData.forEach(cat => {
        html += `
            <div class="mobile-category" data-cat="${cat.id}">
                <div class="cat-title">
                    <span>${cat.name}</span>
                    <span class="arrow">▶</span>
                </div>
                <div class="mobile-subs" id="mobileSubs_${cat.id}">
                    ${generateSubsHtml(cat.subs)}
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
    
    // Événements pour chaque catégorie mobile
    document.querySelectorAll('.mobile-category').forEach(cat => {
        const title = cat.querySelector('.cat-title');
        const subsDiv = cat.querySelector('.mobile-subs');
        
        title.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = subsDiv.classList.contains('open');
            document.querySelectorAll('.mobile-subs').forEach(s => s.classList.remove('open'));
            document.querySelectorAll('.cat-title').forEach(t => t.classList.remove('open'));
            if (!isOpen) {
                subsDiv.classList.add('open');
                title.classList.add('open');
            }
        });
    });
}

function generateSubsHtml(groups) {
    if (!groups || groups.length === 0) return '';
    let html = '';
    groups.forEach(group => {
        html += `
            <div class="mobile-sub-group">
                <h4>${group.title}</h4>
                <ul>
                    ${group.items.map(item => `<li>${item}</li>`).join('')}
                </ul>
            </div>
        `;
    });
    return html;
}

// ========== GÉNÉRATION CATÉGORIES DESKTOP ==========
function generateDesktopCategories() {
    const container = document.getElementById('desktopCategories');
    if (!container) return;
    
    let html = '';
    categoriesData.forEach(cat => {
        html += `<div class="category-item" data-cat="${cat.id_categorie}">${cat.nom_categorie}</div>`;
    });
    container.innerHTML = html;
}

// ========== GESTION SOUS-CATÉGORIES DESKTOP ==========
const subsData = {};

// Construire les données des sous-catégories
categoriesData.forEach(cat => {
    subsData[cat.id_categorie] = { groups: [] };
    if (cat.children && cat.children.length > 0) {
        const group = {
            title: cat.nom_categorie.toUpperCase(),
            items: cat.children.map(child => child.nom_categorie)
        };
        subsData[cat.id_categorie].groups.push(group);
        
        // Ajouter les sous-sous-catégories
        cat.children.forEach(child => {
            if (child.children && child.children.length > 0) {
                const subGroup = {
                    title: child.nom_categorie.toUpperCase(),
                    items: child.children.map(sub => sub.nom_categorie)
                };
                subsData[cat.id_categorie].groups.push(subGroup);
            }
        });
    }
});

const defaultContent = document.getElementById('defaultContent');
const subsContentDesktop = document.getElementById('subsContent');
let hoverTimeout;

function renderDesktopSubs(categoryId) {
    const data = subsData[categoryId];
    if (!data || data.groups.length === 0) {
        subsContentDesktop.innerHTML = '<div style="padding:20px; text-align:center;">Aucune sous-catégorie</div>';
        return;
    }
    
    let html = '<div class="sub-grid-2">';
    data.groups.forEach(group => {
        html += `
            <div class="sub-section">
                <h4>${group.title}</h4>
                <ul>
                    ${group.items.map(item => `<li>${item}</li>`).join('')}
                </ul>
            </div>
        `;
    });
    html += '</div>';
    subsContentDesktop.innerHTML = html;
}

function showDefaultDesktop() {
    defaultContent.classList.remove('hide');
    subsContentDesktop.classList.remove('active');
}

function showSubsDesktop(categoryId) {
    renderDesktopSubs(categoryId);
    defaultContent.classList.add('hide');
    subsContentDesktop.classList.add('active');
}

// ========== GÉNÉRATION CARROUSEL ==========
function generateCarousels() {
    // Carrousel mobile
    const mobileSlidesContainer = document.getElementById('mobileSlides');
    const mobileDotsContainer = document.getElementById('mobileDots');
    
    if (mobileSlidesContainer && bannersData.length > 0) {
        let mobileSlidesHtml = '';
        let mobileDotsHtml = '';
        
        bannersData.forEach((banner, index) => {
            const activeClass = index === 0 ? 'active' : '';
            const imageUrl = banner.image ? '<?= base_url("uploads/banners/") ?>' + banner.image : 'https://img.icons8.com/color/96/000000/shopping-bag.png';
            mobileSlidesHtml += `
                <div class="mobile-slide ${activeClass}" data-index="${index}">
                    <img src="${imageUrl}" alt="${banner.title}">
                    <h3>${banner.title ? banner.title : 'ABEMARKET<span>®</span>'}</h3>
                    ${banner.subtitle ? `<p style="margin:5px 0;">${banner.subtitle}</p>` : ''}
                    <div class="discount">${banner.subtitle ? banner.subtitle : 'JUSQU\'À -35%'}</div>
                    <button class="btn-decouvrir">DÉCOUVRIER →</button>
                </div>
            `;
            mobileDotsHtml += `<div class="mobile-dot ${activeClass}" data-index="${index}"></div>`;
        });
        
        mobileSlidesContainer.innerHTML = mobileSlidesHtml;
        mobileDotsContainer.innerHTML = mobileDotsHtml;
    }
    
    // Carrousel desktop
    const desktopSlidesContainer = document.getElementById('desktopSlides');
    const desktopDotsContainer = document.getElementById('desktopDots');
    
    if (desktopSlidesContainer && bannersData.length > 0) {
        let desktopSlidesHtml = '';
        let desktopDotsHtml = '';
        
        bannersData.forEach((banner, index) => {
            const activeClass = index === 0 ? 'active' : '';
            const imageUrl = banner.image ? '<?= base_url("uploads/banners/") ?>' + banner.image : 'https://img.icons8.com/color/96/000000/shopping-bag.png';
            desktopSlidesHtml += `
                <div class="desktop-slide ${activeClass}" data-index="${index}">
                    <img src="${imageUrl}" alt="${banner.title}">
                    <h3>${banner.title ? banner.title : 'ABEMARKET<span>®</span>'}</h3>
                    ${banner.subtitle ? `<p>${banner.subtitle}</p>` : ''}
                    <div class="discount">${banner.subtitle ? banner.subtitle : 'JUSQU\'À -35%'}</div>
                    <button class="btn-decouvrir">DÉCOUVRIER →</button>
                </div>
            `;
            desktopDotsHtml += `<div class="desktop-dot ${activeClass}" data-index="${index}"></div>`;
        });
        
        desktopSlidesContainer.innerHTML = desktopSlidesHtml;
        desktopDotsContainer.innerHTML = desktopDotsHtml;
    }
}

// ========== CARROUSEL MOBILE ==========
let mobileCurrent = 0;
let mobileInterval;
let mobileSlides, mobileDots;

function initMobileCarousel() {
    mobileSlides = document.querySelectorAll('.mobile-slide');
    mobileDots = document.querySelectorAll('.mobile-dot');
    
    if (mobileSlides.length === 0) return;
    
    function goToMobileSlide(index) {
        mobileSlides.forEach((s, i) => {
            s.classList.remove('active');
            if (i === index) s.classList.add('active');
        });
        mobileDots.forEach((d, i) => {
            d.classList.remove('active');
            if (i === index) d.classList.add('active');
        });
        mobileCurrent = index;
    }
    
    function nextMobileSlide() {
        goToMobileSlide((mobileCurrent + 1) % mobileSlides.length);
    }
    
    function prevMobileSlide() {
        goToMobileSlide((mobileCurrent - 1 + mobileSlides.length) % mobileSlides.length);
    }
    
    function startMobileCarousel() {
        if (mobileInterval) clearInterval(mobileInterval);
        mobileInterval = setInterval(nextMobileSlide, 5000);
    }
    
    const mobilePrev = document.getElementById('mobilePrev');
    const mobileNext = document.getElementById('mobileNext');
    
    if (mobilePrev) mobilePrev.addEventListener('click', () => { nextMobileSlide(); startMobileCarousel(); });
    if (mobileNext) mobileNext.addEventListener('click', () => { nextMobileSlide(); startMobileCarousel(); });
    
    mobileDots.forEach(dot => {
        dot.addEventListener('click', () => {
            const index = parseInt(dot.getAttribute('data-index'));
            goToMobileSlide(index);
            startMobileCarousel();
        });
    });
    
    startMobileCarousel();
}

// ========== CARROUSEL DESKTOP ==========
let desktopCurrent = 0;
let desktopInterval;
let desktopSlides, desktopDots;

function initDesktopCarousel() {
    desktopSlides = document.querySelectorAll('.desktop-slide');
    desktopDots = document.querySelectorAll('.desktop-dot');
    
    if (desktopSlides.length === 0) return;
    
    function goToDesktopSlide(index) {
        desktopSlides.forEach((s, i) => {
            s.classList.remove('active');
            if (i === index) s.classList.add('active');
        });
        desktopDots.forEach((d, i) => {
            d.classList.remove('active');
            if (i === index) d.classList.add('active');
        });
        desktopCurrent = index;
    }
    
    function nextDesktopSlide() {
        goToDesktopSlide((desktopCurrent + 1) % desktopSlides.length);
    }
    
    function prevDesktopSlide() {
        goToDesktopSlide((desktopCurrent - 1 + desktopSlides.length) % desktopSlides.length);
    }
    
    function startDesktopCarousel() {
        if (desktopInterval) clearInterval(desktopInterval);
        desktopInterval = setInterval(() => {
            if (!defaultContent.classList.contains('hide')) nextDesktopSlide();
        }, 5000);
    }
    
    const desktopPrev = document.getElementById('desktopPrev');
    const desktopNext = document.getElementById('desktopNext');
    
    if (desktopPrev) desktopPrev.addEventListener('click', () => { nextDesktopSlide(); startDesktopCarousel(); });
    if (desktopNext) desktopNext.addEventListener('click', () => { nextDesktopSlide(); startDesktopCarousel(); });
    
    desktopDots.forEach(dot => {
        dot.addEventListener('click', () => {
            const index = parseInt(dot.getAttribute('data-index'));
            goToDesktopSlide(index);
            startDesktopCarousel();
        });
    });
    
    startDesktopCarousel();
}

// ========== MENU BURGER MOBILE ==========
const burgerBtn = document.getElementById('burgerBtn');
const mobileMenuPanel = document.getElementById('mobileMenuPanel');
const menuOverlay = document.getElementById('menuOverlay');
const closeMenuBtn = document.getElementById('closeMenu');

function openMobileMenu() {
    mobileMenuPanel.classList.add('active');
    menuOverlay.classList.add('active');
    burgerBtn.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeMobileMenu() {
    mobileMenuPanel.classList.remove('active');
    menuOverlay.classList.remove('active');
    burgerBtn.classList.remove('active');
    document.body.style.overflow = '';
}

if (burgerBtn) burgerBtn.addEventListener('click', openMobileMenu);
if (closeMenuBtn) closeMenuBtn.addEventListener('click', closeMobileMenu);
if (menuOverlay) menuOverlay.addEventListener('click', closeMobileMenu);

// ========== ÉVÉNEMENTS DESKTOP ==========
function initDesktopEvents() {
    const categoryItems = document.querySelectorAll('.category-item');
    const subsContainerDesktopElem = document.getElementById('subsContainer');
    
    categoryItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            if (hoverTimeout) clearTimeout(hoverTimeout);
            const catId = item.getAttribute('data-cat');
            showSubsDesktop(catId);
        });
    });
    
    if (subsContainerDesktopElem) {
        subsContainerDesktopElem.addEventListener('mouseleave', () => {
            hoverTimeout = setTimeout(() => showDefaultDesktop(), 100);
        });
        subsContainerDesktopElem.addEventListener('mouseenter', () => {
            if (hoverTimeout) clearTimeout(hoverTimeout);
        });
    }
}

// ========== INITIALISATION ==========
document.addEventListener('DOMContentLoaded', () => {
    generateMobileMenu();
    generateDesktopCategories();
    generateCarousels();
    initMobileCarousel();
    initDesktopCarousel();
    initDesktopEvents();
    showDefaultDesktop();
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





<!-- Latest & Upcoming product Start -->
<section class="mt-20">
    <div class="custom-container">
        <div class="row g-sm-4 g-3">
            <!-- Top Kitchen Items -->
            <div class="col-xl-4 col-md-6">
                <div class="light-bg-color">
                    <div class="title d-block">
                        <h3>Articles de cuisine</h3>
                    </div>
                    <div class="row g-3">
                        <?php 
                        $kitchen_products = array_slice($categoryProducts[3]['products'] ?? [], 0, 5);
                        if(!empty($kitchen_products)): 
                            foreach($kitchen_products as $product):
                        ?>
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

            <!-- Top Tech Items (Électronique) -->
            <div class="col-xl-4 col-md-6">
                <div class="light-bg-color">
                    <div class="title d-block">
                        <h3>High-Tech</h3>
                    </div>
                    <div class="row g-3">
                        <?php 
                        $tech_products = array_slice($categoryProducts[1]['products'] ?? [], 0, 5);
                        if(!empty($tech_products)): 
                            foreach($tech_products as $product):
                        ?>
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

            <!-- Top Kitchen Appliances (Électroménager) -->
            <div class="col-xl-4 col-md-6">
                <div class="light-bg-color">
                    <div class="title d-block">
                        <h3>Électroménager</h3>
                    </div>
                    <div class="row g-3">
                        <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                            <div class="latest-product-box">
                                <a href="<?= base_url('shop?category=3'); ?>">
                                    <img src="<?= base_url('assets/frontend/images/product/18.png'); ?>" class="img-fluid" alt="Four">
                                </a>
                                <h4>Four inox</h4>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                            <div class="latest-product-box">
                                <a href="<?= base_url('shop?category=3'); ?>">
                                    <img src="<?= base_url('assets/frontend/images/product/21.png'); ?>" class="img-fluid" alt="Réfrigérateur">
                                </a>
                                <h4>Réfrigérateur</h4>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-sm-4 col-6">
                            <div class="latest-product-box">
                                <a href="<?= base_url('shop?category=3'); ?>">
                                    <img src="<?= base_url('assets/frontend/images/product/20.png'); ?>" class="img-fluid" alt="Four pro">
                                </a>
                                <h4>Four professionnel</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="latest-product-box">
                                <a href="<?= base_url('shop?category=3'); ?>">
                                    <img src="<?= base_url('assets/frontend/images/product/19.png'); ?>" class="img-fluid" alt="Machine à café">
                                </a>
                                <h4>Machine à café</h4>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-lg-12 col-sm-6">
                            <div class="latest-product-box">
                                <a href="<?= base_url('shop?category=3'); ?>">
                                    <img src="<?= base_url('assets/frontend/images/product/18.png'); ?>" class="img-fluid" alt="Four rôtissoire">
                                </a>
                                <h4>Four rôtissoire</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Latest & Upcoming product End -->

<!-- Banner Section Start -->
<section class="mt-20">
    <div class="custom-container">
        <div class="row g-sm-4 g-3">
            <div class="col-xl-8">
                <a href="<?= base_url('shop'); ?>" class="banner-box">
                    <?php if(!empty($banners) && isset($banners[1])): ?>
                    <img src="<?= base_url('uploads/banners/' . $banners[1]['image']); ?>" class="img-fluid" alt="Bannière promotion">
                    <?php else: ?>
                    <img src="<?= base_url('assets/frontend/images/banner/8.jpg'); ?>" class="img-fluid" alt="Promotion">
                    <?php endif; ?>
                </a>
            </div>
            <div class="col-xl-4">
                <a href="<?= base_url('offres'); ?>" class="banner-box h-100">
                    <?php if(!empty($banners) && isset($banners[2])): ?>
                    <img src="<?= base_url('uploads/banners/' . $banners[2]['image']); ?>" class="img-fluid" alt="Offres flash">
                    <?php else: ?>
                    <img src="<?= base_url('assets/frontend/images/banner/9.jpg'); ?>" class="img-fluid" alt="Offres flash">
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