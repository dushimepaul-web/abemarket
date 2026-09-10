<?php include VIEWPATH . 'includes/frontend/Header.php'; ?>

<style>


:root {
    --primary:    #1e1b4b;
    --primary-md: #312e81;
    --accent:     #f59e0b;
    --accent-lt:  #fef3c7;
    --success:    #10b981;
    --danger:     #ef4444;
    --warning:    #f59e0b;
    --info:       #3b82f6;
    --surface:    #f8f7ff;
    --card:       #ffffff;
    --border:     #e5e3f3;
    --text:       #1e1b4b;
    --muted:      #6b7280;
    --sidebar-w:  280px;
    --radius:     14px;
    --radius-sm:  8px;
    --shadow:     0 4px 24px rgba(30,27,75,.08);
    --shadow-md:  0 8px 32px rgba(30,27,75,.14);
}

/* ── Base ────────────────────────────────────── */
.abe-dashboard * { font-family: 'DM Sans', sans-serif; box-sizing: border-box; }
.abe-dashboard { background: var(--surface); min-height: 100vh; padding: 0 0 60px; }

/* ── Breadcrumb ──────────────────────────────── */
.abe-breadcrumb {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-md) 100%);
    padding: 40px 0 28px;
    position: relative; overflow: hidden;
}
.abe-breadcrumb::after {
    content: ''; position: absolute; right: -60px; top: -40px;
    width: 260px; height: 260px; border-radius: 50%;
    background: rgba(245,158,11,.12);
}
.abe-breadcrumb h2 {
    font-family: 'Syne', sans-serif; font-weight: 800;
    color: #fff; font-size: 1.8rem; margin: 0 0 6px;
}
.abe-breadcrumb .breadcrumb { background: transparent; margin: 0; padding: 0; }
.abe-breadcrumb .breadcrumb-item a,
.abe-breadcrumb .breadcrumb-item.active { color: rgba(255,255,255,.7); font-size: .85rem; }
.abe-breadcrumb .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

/* ── Layout ──────────────────────────────────── */
.abe-layout { 
    display: flex; 
    gap: 24px; 
    padding: 32px 0; 
    align-items: flex-start; 
    position: relative;
    min-height: 100vh;
}

/* ── Sidebar (Desktop - Fixe) ────────────────── */
.abe-sidebar {
    width: var(--sidebar-w); 
    flex-shrink: 0;
    background: var(--card); 
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    position: sticky; 
    top: 20px;
    max-height: calc(100vh - 40px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

/* Structure interne du sidebar */
.abe-profile-box {
    background: linear-gradient(145deg, var(--primary) 0%, var(--primary-md) 100%);
    padding: 28px 20px 24px; 
    text-align: center; 
    position: relative;
    flex-shrink: 0;
}
.abe-profile-box::before {
    content: ''; 
    position: absolute; 
    bottom: -1px; 
    left: 0; 
    right: 0; 
    height: 24px;
    background: var(--card); 
    border-radius: 50% 50% 0 0 / 100% 100% 0 0;
}
.abe-avatar-wrap {
    width: 82px; 
    height: 82px; 
    border-radius: 50%;
    border: 3px solid var(--accent); 
    margin: 0 auto 12px;
    overflow: hidden; 
    position: relative;
}
.abe-avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }
.abe-profile-name { 
    font-family: 'Syne', sans-serif; 
    color: #fff; 
    font-weight: 700; 
    font-size: 1rem; 
    margin: 0 0 3px; 
}
.abe-profile-email { 
    color: rgba(255,255,255,.6); 
    font-size: .78rem; 
    margin: 0; 
    word-break: break-all;
}
.abe-vendor-badge {
    display: inline-block; 
    margin-top: 8px;
    background: var(--accent); 
    color: var(--primary);
    font-size: .72rem; 
    font-weight: 600; 
    padding: 3px 10px;
    border-radius: 20px;
}

/* Navigation scrollable */
.abe-nav { 
    padding: 16px 12px;
    flex: 1;
    overflow-y: auto;
    min-height: 0;
}

/* Scrollbar personnalisée */
.abe-nav::-webkit-scrollbar {
    width: 4px;
}
.abe-nav::-webkit-scrollbar-track {
    background: var(--surface);
    border-radius: 4px;
}
.abe-nav::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 4px;
}
.abe-nav::-webkit-scrollbar-thumb:hover {
    background: var(--accent);
}

.abe-nav-group-label {
    font-size: .68rem; 
    font-weight: 600; 
    color: var(--muted);
    text-transform: uppercase; 
    letter-spacing: .08em;
    padding: 10px 10px 6px; 
    margin: 0;
}
.abe-nav-btn {
    display: flex; 
    align-items: center; 
    gap: 10px;
    width: 100%; 
    padding: 10px 14px; 
    border-radius: var(--radius-sm);
    border: none; 
    background: transparent; 
    color: var(--text);
    font-size: .88rem; 
    font-weight: 500; 
    cursor: pointer;
    transition: all .18s; 
    text-align: left;
}
.abe-nav-btn i { 
    font-size: 1.1rem; 
    color: var(--muted); 
    transition: color .18s; 
    width: 22px;
}
.abe-nav-btn:hover { 
    background: var(--surface); 
    color: var(--primary); 
}
.abe-nav-btn:hover i { color: var(--primary); }
.abe-nav-btn.active { 
    background: var(--accent-lt); 
    color: var(--primary); 
    font-weight: 600; 
}
.abe-nav-btn.active i { color: var(--accent); }
.abe-nav-divider { 
    height: 1px; 
    background: var(--border); 
    margin: 10px 10px; 
}

/* Bouton logout fixe en bas */
.abe-logout {
    padding: 14px 12px 18px;
    border-top: 1px solid var(--border);
    flex-shrink: 0;
    background: var(--card);
}
.abe-logout a {
    display: flex; 
    align-items: center; 
    gap: 8px; 
    justify-content: center;
    background: #fef2f2; 
    color: #dc2626;
    padding: 10px 16px; 
    border-radius: var(--radius-sm);
    font-weight: 600; 
    font-size: .88rem; 
    text-decoration: none;
    transition: background .18s;
}
.abe-logout a:hover { background: #fee2e2; }

/* ── Main Content ─────────────────────────────── */
.abe-main { 
    flex: 1; 
    min-width: 0;
    max-width: calc(100% - var(--sidebar-w) - 24px);
}

/* Modifier dans le CSS - remplacer la classe .abe-sidebar-toggle */
.abe-sidebar-toggle {
    display: none;
    position: fixed;
    top: 25px;
    height: 20px;
    width:20px      /* Changé de bottom à top */
    left: 20px;
    z-index: 1001;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 12px 18px;
    font-weight: 600;
    cursor: pointer;
    gap: 8px;
    align-items: center;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
}
.abe-sidebar-toggle:hover {
    background: var(--primary-md);
    transform: scale(1.05);
}

/* Overlay pour mobile */
.abe-sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.abe-sidebar-overlay.active {
    display: block;
    opacity: 1;
}

/* ── Tab Panes ────────────────────────────────── */
.abe-pane { display: none; }
.abe-pane.active { display: block; animation: fadeUp .3s ease; }
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Card/Box ─────────────────────────────────── */
.abe-box {
    background: var(--card); 
    border-radius: var(--radius);
    box-shadow: var(--shadow); 
    padding: 28px; 
    margin-bottom: 20px;
}
.abe-box-header {
    display: flex; 
    align-items: center; 
    justify-content: space-between;
    flex-wrap: wrap; 
    gap: 12px; 
    margin-bottom: 22px;
    padding-bottom: 16px; 
    border-bottom: 1px solid var(--border);
}
.abe-box-title {
    font-family: 'Syne', sans-serif; 
    font-weight: 700;
    color: var(--primary); 
    font-size: 1.15rem; 
    margin: 0;
}
.abe-box-subtitle { 
    color: var(--muted); 
    font-size: .85rem; 
    margin: 4px 0 0; 
}

/* ── Stat Cards ───────────────────────────────── */
.abe-stats { 
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(160px,1fr)); 
    gap: 16px; 
    margin-bottom: 24px; 
}
.abe-stat {
    background: var(--card); 
    border-radius: var(--radius);
    padding: 20px 18px; 
    box-shadow: var(--shadow);
    cursor: pointer; 
    transition: transform .2s, box-shadow .2s;
    border: 2px solid transparent; 
    position: relative; 
    overflow: hidden;
}
.abe-stat::before {
    content: ''; 
    position: absolute; 
    top: 0; 
    left: 0; 
    right: 0; 
    height: 3px;
    background: var(--accent);
}
.abe-stat:hover { 
    transform: translateY(-3px); 
    box-shadow: var(--shadow-md); 
    border-color: var(--accent-lt); 
}
.abe-stat-icon {
    width: 40px; 
    height: 40px; 
    border-radius: 10px;
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-size: 1.2rem; 
    margin-bottom: 12px;
}
.abe-stat-value {
    font-family: 'Syne', sans-serif; 
    font-weight: 800;
    font-size: 1.5rem; 
    color: var(--primary); 
    line-height: 1; 
    margin-bottom: 4px;
}
.abe-stat-label { 
    font-size: .78rem; 
    color: var(--muted); 
    font-weight: 500; 
}

/* ── Welcome Banner ──────────────────────────── */
.abe-welcome {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-md) 60%, #4338ca 100%);
    border-radius: var(--radius); 
    padding: 32px 36px; 
    color: #fff;
    display: flex; 
    align-items: center; 
    justify-content: space-between;
    gap: 20px; 
    margin-bottom: 24px; 
    position: relative; 
    overflow: hidden;
}
.abe-welcome::after {
    content: ''; 
    position: absolute; 
    right: -30px; 
    top: -30px;
    width: 200px; 
    height: 200px; 
    border-radius: 50%;
    background: rgba(245,158,11,.15);
}
.abe-welcome-text h3 {
    font-family: 'Syne', sans-serif; 
    font-weight: 800; 
    font-size: 1.5rem; 
    margin: 0 0 6px;
}
.abe-welcome-text p { 
    color: rgba(255,255,255,.7); 
    margin: 0; 
    font-size: .9rem; 
}
.abe-welcome-avatar {
    width: 70px; 
    height: 70px; 
    border-radius: 50%;
    border: 3px solid var(--accent); 
    object-fit: cover; 
    flex-shrink: 0;
}

/* ── Tables ───────────────────────────────────── */
.abe-table-wrap { overflow-x: auto; }
.abe-table { 
    width: 100%; 
    border-collapse: collapse; 
    font-size: .88rem; 
}
.abe-table thead tr { background: var(--surface); }
.abe-table th {
    padding: 12px 16px; 
    font-weight: 600; 
    color: var(--primary);
    text-align: left; 
    border-bottom: 2px solid var(--border);
    font-size: .8rem; 
    text-transform: uppercase; 
    letter-spacing: .04em;
}
.abe-table td { 
    padding: 12px 16px; 
    border-bottom: 1px solid var(--border); 
    color: var(--text); 
    vertical-align: middle; 
}
.abe-table tbody tr:last-child td { border-bottom: none; }
.abe-table tbody tr:hover td { background: var(--surface); }
.abe-empty { 
    text-align: center; 
    padding: 48px !important; 
    color: var(--muted); 
}
.abe-empty i { 
    font-size: 2.5rem; 
    display: block; 
    margin-bottom: 10px; 
    opacity: .4; 
}

/* ── Badges ───────────────────────────────────── */
.abe-badge {
    display: inline-block; 
    padding: 3px 10px; 
    border-radius: 20px;
    font-size: .75rem; 
    font-weight: 600;
}
.abe-badge-success { background: #d1fae5; color: #065f46; }
.abe-badge-warning { background: #fef3c7; color: #92400e; }
.abe-badge-danger  { background: #fee2e2; color: #991b1b; }
.abe-badge-info    { background: #dbeafe; color: #1e40af; }
.abe-badge-muted   { background: #f3f4f6; color: #374151; }
.abe-badge-purple  { background: #ede9fe; color: #5b21b6; }

/* ── Buttons ──────────────────────────────────── */
.abe-btn {
    display: inline-flex; 
    align-items: center; 
    gap: 6px;
    padding: 9px 18px; 
    border-radius: var(--radius-sm);
    font-weight: 600; 
    font-size: .85rem; 
    border: none; 
    cursor: pointer;
    transition: all .18s; 
    text-decoration: none;
}
.abe-btn-primary { background: var(--primary); color: #fff; }
.abe-btn-primary:hover { background: var(--primary-md); color: #fff; }
.abe-btn-accent  { background: var(--accent); color: var(--primary); }
.abe-btn-accent:hover { background: #d97706; color: #fff; }
.abe-btn-outline { background: transparent; color: var(--primary); border: 1.5px solid var(--border); }
.abe-btn-outline:hover { border-color: var(--primary); background: var(--surface); }
.abe-btn-danger  { background: #fee2e2; color: #dc2626; }
.abe-btn-danger:hover { background: #dc2626; color: #fff; }
.abe-btn-sm { padding: 5px 12px; font-size: .8rem; }
.abe-btn-xs { padding: 3px 9px; font-size: .75rem; }

/* ── Product Image ────────────────────────────── */
.abe-prod-img {
    width: 48px; 
    height: 48px; 
    border-radius: var(--radius-sm);
    object-fit: cover; 
    background: var(--surface);
}

/* ── Forms ────────────────────────────────────── */
.abe-form-group { margin-bottom: 18px; }
.abe-form-group label { 
    display: block; 
    font-weight: 500; 
    font-size: .85rem; 
    color: var(--text); 
    margin-bottom: 6px; 
}
.abe-form-group label span { color: #ef4444; margin-left: 2px; }
.abe-input, .abe-select, .abe-textarea {
    width: 100%; 
    padding: 10px 14px; 
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm); 
    font-size: .9rem; 
    color: var(--text);
    background: var(--surface); 
    transition: border-color .18s, box-shadow .18s;
    font-family: 'DM Sans', sans-serif;
}
.abe-input:focus, .abe-select:focus, .abe-textarea:focus {
    outline: none; 
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(30,27,75,.08);
    background: #fff;
}
.abe-textarea { resize: vertical; min-height: 90px; }

/* ── Profile Section ──────────────────────────── */
.abe-avatar-editor {
    display: flex; 
    align-items: center; 
    gap: 20px; 
    margin-bottom: 28px;
    padding: 20px; 
    background: var(--surface); 
    border-radius: var(--radius-sm);
}
.abe-avatar-lg { 
    width: 80px; 
    height: 80px; 
    border-radius: 50%; 
    object-fit: cover; 
}
.abe-avatar-info h5 { 
    font-weight: 600; 
    color: var(--primary); 
    margin: 0 0 4px; 
    font-size: .95rem; 
}
.abe-avatar-info p { 
    color: var(--muted); 
    font-size: .8rem; 
    margin: 0 0 10px; 
}

/* ── Address Cards ────────────────────────────── */
.abe-address-card {
    border: 1.5px solid var(--border); 
    border-radius: var(--radius-sm);
    padding: 18px; 
    position: relative; 
    transition: border-color .18s;
}
.abe-address-card.default { border-color: var(--accent); }
.abe-address-type {
    display: inline-block; 
    padding: 2px 10px; 
    border-radius: 20px;
    background: var(--accent-lt); 
    color: var(--primary);
    font-size: .72rem; 
    font-weight: 700; 
    text-transform: uppercase;
    letter-spacing: .05em; 
    margin-bottom: 10px;
}
.abe-address-name { 
    font-weight: 600; 
    color: var(--primary); 
    margin-bottom: 6px; 
}
.abe-address-detail { 
    color: var(--muted); 
    font-size: .85rem; 
    line-height: 1.6; 
    margin: 0; 
}
.abe-address-actions { 
    display: flex; 
    gap: 8px; 
    margin-top: 14px; 
}

/* ── Earnings Cards ───────────────────────────── */
.abe-earning-card {
    border-radius: var(--radius); 
    padding: 24px;
    display: flex; 
    flex-direction: column; 
    gap: 8px;
}
.abe-earning-card .label { 
    font-size: .8rem; 
    font-weight: 600; 
    text-transform: uppercase; 
    letter-spacing: .06em; 
}
.abe-earning-card .amount { 
    font-family: 'Syne', sans-serif; 
    font-weight: 800; 
    font-size: 1.8rem; 
}
.abe-earning-card.total  { background: linear-gradient(135deg,#1e1b4b,#312e81); color: #fff; }
.abe-earning-card.pending{ background: linear-gradient(135deg,#92400e,#d97706); color: #fff; }
.abe-earning-card.avail  { background: linear-gradient(135deg,#065f46,#10b981); color: #fff; }
.abe-earning-card.orders { background: linear-gradient(135deg,#1e3a5f,#3b82f6); color: #fff; }

/* ── Order Timeline ───────────────────────────── */
.abe-timeline { padding: 10px 0; }
.abe-timeline-item {
    display: flex; 
    gap: 14px; 
    padding-bottom: 18px;
    position: relative;
}
.abe-timeline-item::before {
    content: ''; 
    position: absolute; 
    left: 16px; 
    top: 32px;
    width: 2px; 
    bottom: 0; 
    background: var(--border);
}
.abe-timeline-item:last-child::before { display: none; }
.abe-timeline-dot {
    width: 34px; 
    height: 34px; 
    border-radius: 50%;
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-size: .9rem; 
    flex-shrink: 0; 
    z-index: 1;
}
.abe-timeline-body h6 { 
    font-weight: 600; 
    color: var(--primary); 
    margin: 0 0 3px; 
    font-size: .9rem; 
}
.abe-timeline-body small { 
    color: var(--muted); 
    font-size: .78rem; 
}

/* ── Wishlist Grid ────────────────────────────── */
.abe-wishlist-grid {
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(180px,1fr)); 
    gap: 16px;
}
.abe-wish-card {
    border: 1.5px solid var(--border); 
    border-radius: var(--radius-sm);
    overflow: hidden; 
    transition: box-shadow .2s;
}
.abe-wish-card:hover { box-shadow: var(--shadow-md); }
.abe-wish-img { 
    width: 100%; 
    height: 140px; 
    object-fit: cover; 
    background: var(--surface); 
    display: block; 
}
.abe-wish-body { padding: 12px; }
.abe-wish-name { 
    font-weight: 600; 
    font-size: .85rem; 
    color: var(--primary); 
    margin: 0 0 6px; 
    line-height: 1.3; 
}
.abe-wish-price { 
    font-weight: 700; 
    color: var(--accent); 
    font-size: .9rem; 
    margin: 0; 
}
.abe-wish-price s { 
    color: var(--muted); 
    font-weight: 400; 
    font-size: .8rem; 
}

/* ── Review Cards ─────────────────────────────── */
.abe-review-card {
    border: 1.5px solid var(--border); 
    border-radius: var(--radius-sm);
    padding: 16px; 
    margin-bottom: 14px;
}
.abe-stars { color: var(--accent); font-size: .85rem; }
.abe-review-product { 
    font-weight: 600; 
    color: var(--primary); 
    font-size: .9rem; 
}
.abe-review-text { 
    color: var(--muted); 
    font-size: .85rem; 
    margin: 6px 0 0; 
}

/* ── Stock input ──────────────────────────────── */
.abe-stock-input { width: 100px; }

/* ═══════════════════════════════════════════════ */
/* ════════════ STYLES MOBILE (max 991px) ════════ */
/* ═══════════════════════════════════════════════ */
@media (max-width: 991px) {
    /* Le layout passe en colonne sur mobile */
    .abe-layout { 
        flex-direction: column; 
        padding: 16px 0;
    }
    
    /* Sidebar : tiroir latéral gauche */
    .abe-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 85%;
        max-width: 300px;
        z-index: 1000;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        border-radius: 0;
        margin: 0;
        top: 0;
        max-height: 100vh;
        overflow-y: auto;
    }
    
    /* Sidebar ouvert */
    .abe-sidebar.open {
        transform: translateX(0);
    }
    
    /* Bouton toggle visible sur mobile */
    .abe-sidebar-toggle {
        display: flex;
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 1001;
    }
    
    /* Overlay */
    .abe-sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 999;
    }
    .abe-sidebar-overlay.active {
        display: block;
    }
    
    /* Le contenu principal prend toute la largeur */
    .abe-main {
        max-width: 100%;
        width: 100%;
    }
    
    /* Ajustement des marges des box */
    .abe-box {
        padding: 20px;
    }
    
    /* Stats en 2 colonnes */
    .abe-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    /* Welcome banner en colonne */
    .abe-welcome {
        flex-direction: column;
        text-align: center;
        padding: 24px 20px;
    }
    
    /* Wishlist en 2 colonnes */
    .abe-wishlist-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    /* Tableau responsive */
    .abe-table-wrap {
        overflow-x: auto;
    }
    .abe-table {
        min-width: 600px;
    }
    
    /* Formulaires en colonne */
    .abe-avatar-editor {
        flex-direction: column;
        text-align: center;
    }
    
    /* Adresses en 1 colonne */
    .abe-address-card {
        margin-bottom: 12px;
    }
    
    /* Earnings cards */
    .abe-earning-card .amount {
        font-size: 1.4rem;
    }
}

/* ════════════ TRÈS PETITS ÉCRANS (max 480px) ═══ */
@media (max-width: 480px) {
    .abe-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .abe-stat {
        padding: 14px 12px;
    }
    
    .abe-stat-value {
        font-size: 1.2rem;
    }
    
    .abe-box-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .abe-wishlist-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .abe-wish-img {
        height: 120px;
    }
    
    .abe-earning-card {
        padding: 16px;
    }
    
    .abe-earning-card .amount {
        font-size: 1.2rem;
    }
}
</style>



<!-- Dashboard -->
<section class="abe-dashboard">
<div class="custom-container">

    <!-- Flashdata -->
    <?php if ($this->session->flashdata('success')): ?>
    <div style="background:#d1fae5;color:#065f46;padding:12px 18px;border-radius:10px;margin:16px 0;font-weight:600;">
        <i class="ri-checkbox-circle-line"></i> <?= $this->session->flashdata('success') ?>
    </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
    <div style="background:#fee2e2;color:#991b1b;padding:12px 18px;border-radius:10px;margin:16px 0;font-weight:600;">
        <i class="ri-error-warning-line"></i> <?= $this->session->flashdata('error') ?>
    </div>
    <?php endif; ?>

        <!-- Overlay pour mobile - AJOUTER CECI -->
    <div class="abe-sidebar-overlay" id="sidebarOverlay"></div>

    <div class="abe-layout">



        <!-- ══════════ SIDEBAR ══════════ -->
        <button class="abe-sidebar-toggle" id="sidebarToggle">
            <i class="ri-menu-line"></i>
        </button>

        <aside class="abe-sidebar" id="abeSidebar">
            <!-- Profil -->
            <div class="abe-profile-box">
                <?php
                $avatar_url = base_url('assets/images/users/avatar-1.jpg');
                if (!empty($user['avatar_url'])) {
                    $avatar_path = FCPATH . $user['avatar_url'];
                    if (file_exists($avatar_path)) {
                        $avatar_url = base_url($user['avatar_url']);
                    }
                }
                ?>
                <div class="abe-avatar-wrap">
                    <img src="<?= $avatar_url ?>" alt="Avatar" class="update_img" id="sidebarAvatar">
                </div>
                <p class="abe-profile-name"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></p>
                <p class="abe-profile-email"><?= htmlspecialchars($user['email']) ?></p>
                <?php if ($is_vendeur && isset($user['infos_vendeur'])): ?>
                    <span class="abe-vendor-badge">
                        <i class="ri-store-line"></i> <?= htmlspecialchars($user['infos_vendeur']['nom_boutique']) ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Navigation -->
            <nav class="abe-nav">
                <p class="abe-nav-group-label">Principal</p>
                <button class="abe-nav-btn active" data-tab="tab-dashboard">
                    <i class="ri-dashboard-line"></i> Dashboard
                </button>
                <button class="abe-nav-btn" data-tab="tab-orders">
                    <i class="ri-shopping-basket-line"></i> Mes Commandes
                    <?php if (!empty($stats['commandes_en_cours']) && $stats['commandes_en_cours'] > 0): ?>
                        <span class="abe-badge abe-badge-warning ms-auto"><?= $stats['commandes_en_cours'] ?></span>
                    <?php endif; ?>
                </button>
                <button class="abe-nav-btn" data-tab="tab-wishlist">
                    <i class="ri-heart-line"></i> Mes Favoris
                    <?php if (!empty($stats['wishlist_count']) && $stats['wishlist_count'] > 0): ?>
                        <span class="abe-badge abe-badge-danger ms-auto"><?= $stats['wishlist_count'] ?></span>
                    <?php endif; ?>
                </button>
                <button class="abe-nav-btn" data-tab="tab-reviews">
                    <i class="ri-star-line"></i> Mes Avis
                </button>
                <button class="abe-nav-btn" data-tab="tab-addresses">
                    <i class="ri-map-pin-line"></i> Mes Adresses
                </button>

                <?php if ($is_vendeur): ?>
                <div class="abe-nav-divider"></div>
                <p class="abe-nav-group-label">Espace Vendeur</p>
                <button class="abe-nav-btn" data-tab="tab-seller-products">
                    <i class="ri-shopping-bag-line"></i> Mes Produits
                    <?php if (!empty($stats['total_produits'])): ?>
                        <span class="abe-badge abe-badge-info ms-auto"><?= $stats['total_produits'] ?></span>
                    <?php endif; ?>
                </button>
                <button class="abe-nav-btn" data-tab="tab-add-product">
                    <i class="ri-add-circle-line"></i> Ajouter Produit
                </button>
                <button class="abe-nav-btn" data-tab="tab-seller-orders">
                    <i class="ri-receipt-line"></i> Commandes reçues
                    <?php if (!empty($stats['commandes_recues'])): ?>
                        <span class="abe-badge abe-badge-purple ms-auto"><?= $stats['commandes_recues'] ?></span>
                    <?php endif; ?>
                </button>
                <button class="abe-nav-btn" data-tab="tab-stock">
                    <i class="ri-archive-line"></i> Gestion Stock
                </button>
                <button class="abe-nav-btn" data-tab="tab-earnings">
                    <i class="ri-coins-line"></i> Mes Gains
                </button>
                <button class="abe-nav-btn" data-tab="tab-boutique">
                    <i class="ri-store-2-line"></i> Ma Boutique
                </button>
                <?php endif; ?>

                <div class="abe-nav-divider"></div>
                <p class="abe-nav-group-label">Compte</p>
                <button class="abe-nav-btn" data-tab="tab-profile">
                    <i class="ri-user-3-line"></i> Profil
                </button>
                <button class="abe-nav-btn" data-tab="tab-security">
                    <i class="ri-shield-keyhole-line"></i> Sécurité
                </button>
                <button class="abe-nav-btn" data-tab="tab-notifications">
                    <i class="ri-notification-3-line"></i> Notifications
                    <?php
                    $unread_notif = isset($notifications) ? count(array_filter($notifications, fn($n) => !$n['est_lue'])) : 0;
                    if ($unread_notif > 0): ?>
                        <span class="abe-badge abe-badge-danger ms-auto"><?= $unread_notif ?></span>
                    <?php endif; ?>
                </button>
            </nav>

            <div class="abe-logout">
                <a href="<?= base_url('user_dashboard/logout') ?>">
                    <i class="ri-logout-box-line"></i> Déconnexion
                </a>
            </div>
        </aside>

        <!-- ══════════ MAIN ══════════ -->
        <main class="abe-main">

            <!-- ─── TAB: DASHBOARD ─── -->
            <div class="abe-pane active" id="tab-dashboard">

                <!-- Welcome Banner -->
                <div class="abe-welcome">
                    <div class="abe-welcome-text">
                        <h3>Bonjour, <?= htmlspecialchars($user['prenom']) ?> 👋</h3>
                        <p>Bienvenue sur votre tableau de bord<?= $is_vendeur ? ' vendeur' : '' ?>. Retrouvez toutes vos informations ici.</p>
                    </div>
                    <img src="<?= $avatar_url ?>" alt="Avatar" class="abe-welcome-avatar">
                </div>

                <!-- Stats Grid -->
                <div class="abe-stats">
                    <div class="abe-stat" data-nav="tab-orders">
                        <div class="abe-stat-icon" style="background:#ede9fe;">
                            <i class="ri-shopping-basket-line" style="color:#7c3aed;"></i>
                        </div>
                        <div class="abe-stat-value"><?= $stats['total_commandes'] ?></div>
                        <div class="abe-stat-label">Total commandes</div>
                    </div>
                    <div class="abe-stat" data-nav="tab-orders">
                        <div class="abe-stat-icon" style="background:#fef3c7;">
                            <i class="ri-time-line" style="color:#d97706;"></i>
                        </div>
                        <div class="abe-stat-value"><?= $stats['commandes_en_cours'] ?></div>
                        <div class="abe-stat-label">En cours</div>
                    </div>
                    <div class="abe-stat" data-nav="tab-orders">
                        <div class="abe-stat-icon" style="background:#d1fae5;">
                            <i class="ri-checkbox-circle-line" style="color:#059669;"></i>
                        </div>
                        <div class="abe-stat-value"><?= $stats['commandes_livre'] ?></div>
                        <div class="abe-stat-label">Livrées</div>
                    </div>
                    <div class="abe-stat" data-nav="tab-wishlist">
                        <div class="abe-stat-icon" style="background:#fee2e2;">
                            <i class="ri-heart-line" style="color:#dc2626;"></i>
                        </div>
                        <div class="abe-stat-value"><?= $stats['wishlist_count'] ?></div>
                        <div class="abe-stat-label">Favoris</div>
                    </div>
                    <div class="abe-stat" data-nav="tab-addresses">
                        <div class="abe-stat-icon" style="background:#dbeafe;">
                            <i class="ri-map-pin-line" style="color:#2563eb;"></i>
                        </div>
                        <div class="abe-stat-value"><?= $stats['addresses_count'] ?></div>
                        <div class="abe-stat-label">Adresses</div>
                    </div>
                    <div class="abe-stat">
                        <div class="abe-stat-icon" style="background:#d1fae5;">
                            <i class="ri-money-franc-circle-line" style="color:#059669;"></i>
                        </div>
                        <div class="abe-stat-value" style="font-size:1rem;"><?= number_format($stats['total_depense'], 0, ',', ' ') ?></div>
                        <div class="abe-stat-label">Dépensé (FBu)</div>
                    </div>
                    <?php if ($is_vendeur): ?>
                    <div class="abe-stat" data-nav="tab-seller-products">
                        <div class="abe-stat-icon" style="background:#ede9fe;">
                            <i class="ri-shopping-bag-line" style="color:#7c3aed;"></i>
                        </div>
                        <div class="abe-stat-value"><?= $stats['total_produits'] ?? 0 ?></div>
                        <div class="abe-stat-label">Mes produits</div>
                    </div>
                    <div class="abe-stat" data-nav="tab-seller-orders">
                        <div class="abe-stat-icon" style="background:#fef3c7;">
                            <i class="ri-receipt-line" style="color:#d97706;"></i>
                        </div>
                        <div class="abe-stat-value"><?= $stats['commandes_recues'] ?? 0 ?></div>
                        <div class="abe-stat-label">Cmd. reçues</div>
                    </div>
                    <div class="abe-stat" data-nav="tab-earnings">
                        <div class="abe-stat-icon" style="background:#d1fae5;">
                            <i class="ri-coins-line" style="color:#059669;"></i>
                        </div>
                        <div class="abe-stat-value" style="font-size:1rem;"><?= number_format($stats['total_gains'] ?? 0, 0, ',', ' ') ?></div>
                        <div class="abe-stat-label">Gains (FBu)</div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Dernières commandes rapides -->
                <?php if (!empty($commandes)): ?>
                <div class="abe-box">
                    <div class="abe-box-header">
                        <div>
                            <h3 class="abe-box-title">Dernières commandes</h3>
                            <p class="abe-box-subtitle">Vos 5 commandes les plus récentes</p>
                        </div>
                        <button class="abe-btn abe-btn-outline abe-btn-sm" data-nav="tab-orders">
                            Voir tout <i class="ri-arrow-right-line"></i>
                        </button>
                    </div>
                    <div class="abe-table-wrap">
                        <table class="abe-table">
                            <thead><tr>
                                <th>Commande</th><th>Date</th><th>Statut</th><th>Total</th><th></th>
                            </tr></thead>
                            <tbody>
                                <?php foreach (array_slice($commandes, 0, 5) as $c): ?>
                                <tr>
                                    <td><strong>#<?= htmlspecialchars($c['numero_commande']) ?></strong></td>
                                    <td><?= date('d/m/Y', strtotime($c['date_creation'])) ?></td>
                                    <td><?php
                                        $s = $c['statut_commande'];
                                        $cls = in_array($s,['livre']) ? 'success' : (in_array($s,['annule','retourne']) ? 'danger' : (in_array($s,['en_livraison','expedie']) ? 'info' : 'warning'));
                                        echo "<span class='abe-badge abe-badge-$cls'>" . ucfirst(str_replace('_',' ',$s)) . "</span>";
                                    ?></td>
                                    <td><strong><?= number_format($c['montant_total'],0,',',' ') ?> FBu</strong></td>
                                    <td>
                                        <button class="abe-btn abe-btn-outline abe-btn-xs view-order-btn" data-order-id="<?= $c['id_commande'] ?>">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- ─── TAB: MES COMMANDES ─── -->
            <div class="abe-pane" id="tab-orders">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <div>
                            <h3 class="abe-box-title">Mes Commandes</h3>
                            <p class="abe-box-subtitle">Historique de toutes vos commandes</p>
                        </div>
                    </div>
                    <div class="abe-table-wrap">
                        <table class="abe-table">
                            <thead><tr>
                                <th>N° Commande</th><th>Date</th><th>Statut</th>
                                <th>Paiement</th><th>Livraison</th><th>Total</th><th>Action</th>
                            </tr></thead>
                            <tbody>
                                <?php if (!empty($commandes)): ?>
                                    <?php foreach ($commandes as $c): ?>
                                    <tr>
                                        <td><strong>#<?= htmlspecialchars($c['numero_commande']) ?></strong></td>
                                        <td><?= date('d/m/Y', strtotime($c['date_creation'])) ?></td>
                                        <td><?php
                                            $s = $c['statut_commande'];
                                            $cls = in_array($s,['livre']) ? 'success' : (in_array($s,['annule','retourne']) ? 'danger' : (in_array($s,['en_livraison','expedie']) ? 'info' : 'warning'));
                                            echo "<span class='abe-badge abe-badge-$cls'>" . ucfirst(str_replace('_',' ',$s)) . "</span>";
                                        ?></td>
                                        <td><?php
                                            $sp = $c['statut_paiement'];
                                            $cp = $sp == 'paye' ? 'success' : ($sp == 'echoue' ? 'danger' : 'warning');
                                            echo "<span class='abe-badge abe-badge-$cp'>" . ucfirst($sp) . "</span>";
                                        ?></td>
                                        <td><?= ucfirst(str_replace('_',' ', $c['type_livraison'] ?? 'domicile')) ?></td>
                                        <td><strong><?= number_format($c['montant_total'],0,',',' ') ?> FBu</strong></td>
                                        <td>
                                            <button class="abe-btn abe-btn-outline abe-btn-xs view-order-btn" data-order-id="<?= $c['id_commande'] ?>">
                                                <i class="ri-eye-line"></i> Détail
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="abe-empty">
                                        <i class="ri-shopping-basket-line"></i>
                                        Aucune commande trouvée
                                    </td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ─── TAB: WISHLIST ─── -->
            <div class="abe-pane" id="tab-wishlist">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <div>
                            <h3 class="abe-box-title">Mes Favoris</h3>
                            <p class="abe-box-subtitle"><?= $stats['wishlist_count'] ?> produit(s) sauvegardé(s)</p>
                        </div>
                    </div>
                    <?php if (!empty($wishlist)): ?>
                    <div class="abe-wishlist-grid">
                        <?php foreach ($wishlist as $item): ?>
                        <div class="abe-wish-card">
                            <a href="<?= base_url('produit/' . $item['slug_produit']) ?>">
                                <img src="<?= base_url($item['main_image'] ?? 'assets/images/default-product.png') ?>"
                                     alt="<?= htmlspecialchars($item['nom_produit']) ?>" class="abe-wish-img"
                                     onerror="this.src='<?= base_url('assets/images/default-product.png') ?>'">
                            </a>
                            <div class="abe-wish-body">
                                <p class="abe-wish-name"><?= htmlspecialchars($item['nom_produit']) ?></p>
                                <p class="abe-wish-price">
                                    <?php if (!empty($item['prix_promo'])): ?>
                                        <?= number_format($item['prix_promo'],0,',',' ') ?> FBu
                                        <s><?= number_format($item['prix_base'],0,',',' ') ?></s>
                                    <?php else: ?>
                                        <?= number_format($item['prix_base'],0,',',' ') ?> FBu
                                    <?php endif; ?>
                                </p>
                                <button class="abe-btn abe-btn-danger abe-btn-xs mt-2 remove-wishlist-btn" style="width:100%;"
                                    data-product-id="<?= $item['id_produit'] ?>">
                                    <i class="ri-heart-3-line"></i> Retirer
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div style="text-align:center;padding:60px 20px;color:var(--muted);">
                        <i class="ri-heart-line" style="font-size:3rem;display:block;margin-bottom:12px;opacity:.3;"></i>
                        Aucun favori pour le moment. <a href="<?= base_url('produits') ?>" style="color:var(--accent);">Parcourir les produits</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ─── TAB: MES AVIS ─── -->
            <div class="abe-pane" id="tab-reviews">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <h3 class="abe-box-title">Mes Avis</h3>
                    </div>
                    <?php if (!empty($avis)): ?>
                        <?php foreach ($avis as $av): ?>
                        <div class="abe-review-card">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px;">
                                <div>
                                    <p class="abe-review-product"><?= htmlspecialchars($av['nom_produit']) ?></p>
                                    <div class="abe-stars">
                                        <?php for($i=1;$i<=5;$i++): ?>
                                            <i class="ri-star-<?= $i <= $av['note'] ? 'fill' : 'line' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div style="text-align:right;">
                                    <span class="abe-badge <?= $av['est_approuve'] ? 'abe-badge-success' : 'abe-badge-warning' ?>">
                                        <?= $av['est_approuve'] ? 'Approuvé' : 'En attente' ?>
                                    </span>
                                    <p style="color:var(--muted);font-size:.78rem;margin:4px 0 0;"><?= date('d/m/Y', strtotime($av['date_creation'])) ?></p>
                                </div>
                            </div>
                            <?php if (!empty($av['titre'])): ?>
                                <p style="font-weight:600;margin:8px 0 4px;font-size:.9rem;"><?= htmlspecialchars($av['titre']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($av['commentaire'])): ?>
                                <p class="abe-review-text"><?= htmlspecialchars($av['commentaire']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($av['reponse_vendeur'])): ?>
                                <div style="background:var(--surface);border-radius:var(--radius-sm);padding:10px 14px;margin-top:10px;">
                                    <p style="font-size:.78rem;font-weight:600;color:var(--primary);margin:0 0 4px;">Réponse du vendeur :</p>
                                    <p style="font-size:.85rem;color:var(--muted);margin:0;"><?= htmlspecialchars($av['reponse_vendeur']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <div style="text-align:center;padding:60px 20px;color:var(--muted);">
                        <i class="ri-star-line" style="font-size:3rem;display:block;margin-bottom:12px;opacity:.3;"></i>
                        Vous n'avez pas encore laissé d'avis.
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ─── TAB: ADRESSES ─── -->
            <div class="abe-pane" id="tab-addresses">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <div>
                            <h3 class="abe-box-title">Mes Adresses</h3>
                            <p class="abe-box-subtitle"><?= $stats['addresses_count'] ?> adresse(s) enregistrée(s)</p>
                        </div>
                        <button class="abe-btn abe-btn-accent" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="ri-add-line"></i> Ajouter
                        </button>
                    </div>
                    <?php if (!empty($adresses)): ?>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
                        <?php foreach ($adresses as $addr): ?>
                        <div class="abe-address-card <?= $addr['est_par_defaut'] ? 'default' : '' ?>">
                            <?php if ($addr['est_par_defaut']): ?>
                                <span class="abe-badge abe-badge-warning" style="margin-bottom:8px;">⭐ Par défaut</span><br>
                            <?php endif; ?>
                            <span class="abe-address-type"><?= htmlspecialchars(ucfirst($addr['type_adresse'])) ?></span>
                            <p class="abe-address-name"><?= htmlspecialchars($addr['nom_complet']) ?></p>
                            <p class="abe-address-detail">
                                <?= htmlspecialchars($addr['adresse_ligne']) ?><br>
                                <?php if (!empty($addr['province_name'])): ?>
                                    Province : <?= htmlspecialchars($addr['province_name']) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($addr['point_repere'])): ?>
                                    Repère : <?= htmlspecialchars($addr['point_repere']) ?><br>
                                <?php endif; ?>
                                <i class="ri-phone-line"></i> <?= htmlspecialchars($addr['telephone']) ?>
                            </p>
                            <div class="abe-address-actions">
                                <button class="abe-btn abe-btn-outline abe-btn-sm edit-address" data-address-id="<?= $addr['id_adresse'] ?>">
                                    <i class="ri-edit-line"></i> Modifier
                                </button>
                                <button class="abe-btn abe-btn-danger abe-btn-sm delete-address" data-address-id="<?= $addr['id_adresse'] ?>">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div style="text-align:center;padding:60px 20px;color:var(--muted);">
                        <i class="ri-map-pin-line" style="font-size:3rem;display:block;margin-bottom:12px;opacity:.3;"></i>
                        Aucune adresse enregistrée.
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ─── TAB: NOTIFICATIONS ─── -->
            <div class="abe-pane" id="tab-notifications">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <h3 class="abe-box-title">Notifications</h3>
                    </div>
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $notif): ?>
                        <div style="display:flex;gap:14px;padding:14px 0;border-bottom:1px solid var(--border);<?= !$notif['est_lue'] ? 'background:var(--accent-lt);margin:0 -8px;padding:14px 8px;border-radius:8px;' : '' ?>">
                            <?php
                            $cat = $notif['categorie'];
                            $icon_map = ['commande'=>'ri-shopping-basket-line','paiement'=>'ri-bank-card-line','livraison'=>'ri-truck-line','securite'=>'ri-shield-line','systeme'=>'ri-settings-line'];
                            $col_map = ['commande'=>'#7c3aed','paiement'=>'#059669','livraison'=>'#2563eb','securite'=>'#dc2626','systeme'=>'#6b7280'];
                            $icon = $icon_map[$cat] ?? 'ri-notification-3-line';
                            $col  = $col_map[$cat]  ?? '#6b7280';
                            ?>
                            <div style="width:40px;height:40px;border-radius:50%;background:<?= $col ?>22;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="<?= $icon ?>" style="color:<?= $col ?>;"></i>
                            </div>
                            <div style="flex:1;">
                                <p style="font-weight:600;color:var(--primary);margin:0 0 3px;font-size:.9rem;"><?= htmlspecialchars($notif['titre']) ?></p>
                                <p style="color:var(--muted);margin:0;font-size:.83rem;"><?= htmlspecialchars($notif['message']) ?></p>
                                <p style="color:var(--muted);margin:4px 0 0;font-size:.75rem;"><?= date('d/m/Y H:i', strtotime($notif['date_creation'])) ?></p>
                            </div>
                            <?php if (!$notif['est_lue']): ?>
                            <button class="abe-btn abe-btn-outline abe-btn-xs mark-read-btn" data-id="<?= $notif['id_notification'] ?>">
                                <i class="ri-check-line"></i> Lu
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <div style="text-align:center;padding:60px 20px;color:var(--muted);">
                        <i class="ri-notification-off-line" style="font-size:3rem;display:block;margin-bottom:12px;opacity:.3;"></i>
                        Aucune notification.
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($is_vendeur): ?>

            <!-- ─── TAB: MES PRODUITS (VENDEUR) ─── -->
            <div class="abe-pane" id="tab-seller-products">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <div>
                            <h3 class="abe-box-title">Mes Produits</h3>
                            <p class="abe-box-subtitle"><?= $stats['total_produits'] ?? 0 ?> produit(s) en catalogue</p>
                        </div>
                        <button class="abe-btn abe-btn-accent" data-nav="tab-add-product">
                            <i class="ri-add-line"></i> Ajouter
                        </button>
                    </div>
                    <div class="abe-table-wrap">
                        <table class="abe-table">
                            <thead><tr>
                                <th>Image</th><th>Produit</th><th>Catégorie</th>
                                <th>Prix</th><th>Stock</th><th>Statut</th><th>Actions</th>
                            </tr></thead>
                            <tbody>
                                <?php if (!empty($mes_produits)): ?>
                                    <?php foreach ($mes_produits as $p): ?>
                                    <tr>
                                        <td>
                                            <img src="<?= base_url($p['image_url'] ?? 'assets/images/default-product.png') ?>"
                                                 alt="" class="abe-prod-img"
                                                 onerror="this.src='<?= base_url('assets/images/default-product.png') ?>'">
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($p['nom_produit']) ?></strong><br>
                                            <small style="color:var(--muted);"><?= htmlspecialchars($p['sku']) ?></small>
                                        </td>
                                        <td><span class="abe-badge abe-badge-muted"><?= htmlspecialchars($p['nom_categorie'] ?? '—') ?></span></td>
                                        <td>
                                            <strong><?= number_format($p['prix_base'],0,',',' ') ?> FBu</strong>
                                            <?php if (!empty($p['prix_promo'])): ?>
                                                <br><small class="abe-badge abe-badge-danger">Promo: <?= number_format($p['prix_promo'],0,',',' ') ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $q = $p['quantite_actuelle'];
                                            $sc = $q > 10 ? 'success' : ($q > 0 ? 'warning' : 'danger');
                                            ?>
                                            <span class="abe-badge abe-badge-<?= $sc ?>"><?= $q ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $ps = $p['statut'];
                                            $psc = $ps == 'actif' ? 'success' : ($ps == 'en_attente' ? 'warning' : 'muted');
                                            echo "<span class='abe-badge abe-badge-$psc'>" . ucfirst($ps) . "</span>";
                                            ?>
                                        </td>
                                        <td>
                                            <button class="abe-btn abe-btn-outline abe-btn-xs edit-product"
                                                data-id="<?= $p['id_produit'] ?>" style="margin-right:4px;">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="abe-btn abe-btn-danger abe-btn-xs delete-product"
                                                data-id="<?= $p['id_produit'] ?>">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            <a href="<?= base_url('ProduitsVendeur/images/' . $p['slug_produit']) ?>"
                                               class="abe-btn abe-btn-outline abe-btn-xs" title="Gérer les images"
                                               style="margin-right:4px;background:#f0f0f0;">
                                                <i class="ri-image-line"></i>
                                                <?php if (!empty($p['image_count']) && $p['image_count'] > 0): ?>
                                                    <span style="font-size:.7rem;background:var(--accent);color:#fff;border-radius:50%;width:16px;height:16px;display:inline-flex;align-items:center;justify-content:center;margin-left:2px;"><?= $p['image_count'] ?></span>
                                                <?php endif; ?>
                                            </a>
                                            <a href="<?= base_url('ProduitsVendeur/variantes/' . $p['slug_produit']) ?>"
                                               class="abe-btn abe-btn-outline abe-btn-xs" title="Gérer les variantes"
                                               style="background:#f0f0f0;">
                                                <i class="ri-stack-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="abe-empty">
                                        <i class="ri-shopping-bag-line"></i>
                                        Aucun produit. <button class="abe-btn abe-btn-accent abe-btn-sm" data-nav="tab-add-product">Ajouter le premier</button>
                                    </td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ─── TAB: AJOUTER PRODUIT ─── -->
            <div class="abe-pane" id="tab-add-product">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <div>
                            <h3 class="abe-box-title">Nouveau produit</h3>
                            <p class="abe-box-subtitle">Il sera soumis à validation avant publication.</p>
                        </div>
                    </div>
                    <form id="addProductForm" enctype="multipart/form-data">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div class="abe-form-group">
                                <label>Nom du produit <span>*</span></label>
                                <input type="text" name="nom_produit" class="abe-input" required placeholder="Ex: iPhone 15 Pro">
                            </div>
                            <div class="abe-form-group">
                                <label>Catégorie <span>*</span></label>
                                <select name="id_categorie" class="abe-select" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id_categorie'] ?>">
                                                <?= htmlspecialchars($cat['nom_categorie']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="abe-form-group">
                                <label>Prix de base (FBu) <span>*</span></label>
                                <input type="number" name="prix_base" class="abe-input" required min="0" placeholder="Ex: 45000">
                            </div>
                            <div class="abe-form-group">
                                <label>Prix promo (FBu)</label>
                                <input type="number" name="prix_promo" class="abe-input" min="0" placeholder="Laisser vide si aucun">
                            </div>
                            <div class="abe-form-group">
                                <label>Début promo</label>
                                <input type="datetime-local" name="date_debut_promo" class="abe-input">
                            </div>
                            <div class="abe-form-group">
                                <label>Fin promo</label>
                                <input type="datetime-local" name="date_fin_promo" class="abe-input">
                            </div>
                            <div class="abe-form-group">
                                <label>Quantité en stock <span>*</span></label>
                                <input type="number" name="quantite_actuelle" class="abe-input" required min="0" value="0">
                            </div>
                            <div class="abe-form-group">
                                <label>Seuil stock bas</label>
                                <input type="number" name="seuil_stock_bas" class="abe-input" min="0" value="5">
                            </div>
                            <div class="abe-form-group">
                                <label>Marque</label>
                                <input type="text" name="marque" class="abe-input" placeholder="Ex: Samsung">
                            </div>
                            <div class="abe-form-group">
                                <label>Poids (kg)</label>
                                <input type="number" name="poids_kg" class="abe-input" step="0.001" min="0" placeholder="Ex: 0.250">
                            </div>
                            <div class="abe-form-group">
                                <label>Type de produit</label>
                                <select name="type_produit" class="abe-select">
                                    <option value="simple">Simple</option>
                                    <option value="variable">Variable (tailles/couleurs)</option>
                                </select>
                            </div>
                            <div class="abe-form-group" style="grid-column:1/-1;">
                                <label>Description courte</label>
                                <input type="text" name="description_courte" class="abe-input" placeholder="Résumé en une ligne">
                            </div>
                            <div class="abe-form-group" style="grid-column:1/-1;">
                                <label>Description complète</label>
                                <textarea name="description" class="abe-textarea" rows="4" placeholder="Description détaillée du produit..."></textarea>
                            </div>
                            <div class="abe-form-group" style="grid-column:1/-1;">
                                <label>Images du produit <span>*</span></label>
                                <input type="file" name="images[]" class="abe-input" accept="image/*" multiple required>
                                <small style="color:var(--muted);font-size:.78rem;">JPG, PNG, WEBP — max 4MB par image. La 1ère sera l'image principale.</small>
                            </div>
                        </div>
                        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:8px;">
                            <button type="reset" class="abe-btn abe-btn-outline">Réinitialiser</button>
                            <button type="submit" class="abe-btn abe-btn-primary" id="addProductBtn">
                                <i class="ri-send-plane-line"></i> Soumettre pour validation
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ─── TAB: COMMANDES REÇUES ─── -->
            <div class="abe-pane" id="tab-seller-orders">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <div>
                            <h3 class="abe-box-title">Commandes reçues</h3>
                            <p class="abe-box-subtitle">Gérez les commandes de vos acheteurs</p>
                        </div>
                    </div>
                    <div class="abe-table-wrap">
                        <table class="abe-table">
                            <thead><tr>
                                <th>N° Commande</th><th>Client</th><th>Produit</th>
                                <th>Qté</th><th>Total</th><th>Date</th><th>Statut</th><th>Action</th>
                            </tr></thead>
                            <tbody>
                                <?php if (!empty($commandes_recues)): ?>
                                    <?php foreach ($commandes_recues as $cmd): ?>
                                    <tr>
                                        <td><strong>#<?= htmlspecialchars($cmd['numero_commande']) ?></strong></td>
                                        <td>
                                            <?= htmlspecialchars($cmd['prenom'] . ' ' . $cmd['nom']) ?><br>
                                            <small style="color:var(--muted);"><?= htmlspecialchars($cmd['telephone'] ?? '') ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($cmd['nom_produit']) ?></td>
                                        <td><?= $cmd['quantite'] ?></td>
                                        <td><strong><?= number_format($cmd['prix_total'],0,',',' ') ?> FBu</strong></td>
                                        <td><?= date('d/m/Y', strtotime($cmd['date_creation'])) ?></td>
                                        <td>
                                            <select class="abe-select update-order-status"
                                                style="width:140px;padding:6px 8px;font-size:.8rem;"
                                                data-order-id="<?= $cmd['id_commande'] ?>">
                                                <option value="en_attente"    <?= $cmd['statut_commande']=='en_attente'    ?'selected':'' ?>>En attente</option>
                                                <option value="confirme"      <?= $cmd['statut_commande']=='confirme'      ?'selected':'' ?>>Confirmée</option>
                                                <option value="en_preparation"<?= $cmd['statut_commande']=='en_preparation'?'selected':'' ?>>En préparation</option>
                                                <option value="expedie"       <?= $cmd['statut_commande']=='expedie'       ?'selected':'' ?>>Expédiée</option>
                                                <option value="livre"         <?= $cmd['statut_commande']=='livre'         ?'selected':'' ?>>Livrée</option>
                                                <option value="annule"        <?= $cmd['statut_commande']=='annule'        ?'selected':'' ?>>Annulée</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="abe-btn abe-btn-outline abe-btn-xs view-order-btn"
                                                data-order-id="<?= $cmd['id_commande'] ?>">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="8" class="abe-empty">
                                        <i class="ri-receipt-line"></i>
                                        Aucune commande reçue pour le moment.
                                    </td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ─── TAB: STOCK ─── -->
            <div class="abe-pane" id="tab-stock">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <div>
                            <h3 class="abe-box-title">Gestion des stocks</h3>
                            <p class="abe-box-subtitle">Mettez à jour les quantités disponibles</p>
                        </div>
                    </div>
                    <div class="abe-table-wrap">
                        <table class="abe-table">
                            <thead><tr>
                                <th>Produit</th><th>SKU</th><th>Stock actuel</th><th>État</th><th>Nouvelle quantité</th><th>Action</th>
                            </tr></thead>
                            <tbody>
                                <?php if (!empty($mes_produits)): ?>
                                    <?php foreach ($mes_produits as $p): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($p['nom_produit']) ?></strong></td>
                                        <td><code style="font-size:.78rem;background:var(--surface);padding:2px 6px;border-radius:4px;"><?= htmlspecialchars($p['sku']) ?></code></td>
                                        <td><strong><?= $p['quantite_actuelle'] ?></strong></td>
                                        <td>
                                            <?php
                                            $ss = $p['statut_stock'];
                                            $sc = $ss=='en_stock' ? 'success' : ($ss=='stock_bas' ? 'warning' : 'danger');
                                            $sl = $ss=='en_stock' ? 'En stock' : ($ss=='stock_bas' ? 'Stock bas' : 'Rupture');
                                            echo "<span class='abe-badge abe-badge-$sc'>$sl</span>";
                                            ?>
                                        </td>
                                        <td>
                                            <input type="number" class="abe-input abe-stock-input new-stock-<?= $p['id_produit'] ?>"
                                                min="0" placeholder="Ex: 50">
                                        </td>
                                        <td>
                                            <button class="abe-btn abe-btn-primary abe-btn-sm update-stock"
                                                data-id="<?= $p['id_produit'] ?>">
                                                <i class="ri-refresh-line"></i> MàJ
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="abe-empty">
                                        <i class="ri-archive-line"></i> Aucun produit.
                                    </td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ─── TAB: GAINS ─── -->
            <div class="abe-pane" id="tab-earnings">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-bottom:24px;">
                    <div class="abe-earning-card total">
                        <div class="label">Total gagné</div>
                        <div class="amount"><?= number_format($stats['total_gains'] ?? 0, 0, ',', ' ') ?></div>
                        <div style="font-size:.8rem;opacity:.7;">FBu — commandes livrées</div>
                    </div>
                    <div class="abe-earning-card pending">
                        <div class="label">En attente</div>
                        <div class="amount"><?= number_format($stats['gains_en_attente'] ?? 0, 0, ',', ' ') ?></div>
                        <div style="font-size:.8rem;opacity:.7;">FBu — commandes expédiées</div>
                    </div>
                    <div class="abe-earning-card avail">
                        <div class="label">Disponible</div>
                        <div class="amount"><?= number_format($stats['gains_disponible'] ?? 0, 0, ',', ' ') ?></div>
                        <div style="font-size:.8rem;opacity:.7;">FBu — prêt à retirer</div>
                    </div>
                    <div class="abe-earning-card orders">
                        <div class="label">Commandes vendues</div>
                        <div class="amount"><?= $stats['commandes_recues'] ?? 0 ?></div>
                        <div style="font-size:.8rem;opacity:.7;">total historique</div>
                    </div>
                </div>
                <div class="abe-box">
                    <div class="abe-box-header">
                        <h3 class="abe-box-title">Informations de versement</h3>
                    </div>
                    <?php if (!empty($user['infos_vendeur'])): ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div>
                            <p style="color:var(--muted);font-size:.8rem;margin:0;">Boutique</p>
                            <p style="font-weight:600;color:var(--primary);margin:0;"><?= htmlspecialchars($user['infos_vendeur']['nom_boutique']) ?></p>
                        </div>
                        <div>
                            <p style="color:var(--muted);font-size:.8rem;margin:0;">Taux de commission</p>
                            <p style="font-weight:600;color:var(--primary);margin:0;"><?= $user['infos_vendeur']['taux_commission'] ?>%</p>
                        </div>
                        <div>
                            <p style="color:var(--muted);font-size:.8rem;margin:0;">Délai de paiement</p>
                            <p style="font-weight:600;color:var(--primary);margin:0;"><?= $user['infos_vendeur']['delai_paiement_jours'] ?> jours</p>
                        </div>
                        <div>
                            <p style="color:var(--muted);font-size:.8rem;margin:0;">Statut boutique</p>
                            <?php
                            $vs = $user['infos_vendeur']['statut'];
                            $vc = $vs == 'actif' ? 'success' : ($vs == 'en_attente' ? 'warning' : 'danger');
                            echo "<span class='abe-badge abe-badge-$vc'>" . ucfirst($vs) . "</span>";
                            ?>
                        </div>
                    </div>
                    <p style="margin-top:16px;font-size:.85rem;color:var(--muted);">
                        <i class="ri-information-line"></i> Pour demander un virement, contactez le support à
                        <a href="mailto:<?= $site_email ?? 'abemarket@gmail.com' ?>" style="color:var(--accent);"><?= $site_email ?? 'abemarket@gmail.com' ?></a>
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ─── TAB: MA BOUTIQUE ─── -->
            <div class="abe-pane" id="tab-boutique">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <h3 class="abe-box-title">Ma Boutique</h3>
                    </div>
                    <?php if (!empty($user['infos_vendeur'])): $v = $user['infos_vendeur']; ?>
                    <form id="boutiqueForm">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div class="abe-form-group">
                                <label>Nom de la boutique</label>
                                <input type="text" name="nom_boutique" class="abe-input" value="<?= htmlspecialchars($v['nom_boutique']) ?>">
                            </div>
                            <div class="abe-form-group">
                                <label>Téléphone boutique</label>
                                <input type="tel" name="telephone" class="abe-input" value="<?= htmlspecialchars($v['telephone'] ?? '') ?>">
                            </div>
                            <div class="abe-form-group">
                                <label>WhatsApp</label>
                                <input type="tel" name="whatsapp" class="abe-input" value="<?= htmlspecialchars($v['whatsapp'] ?? '') ?>">
                            </div>
                            <div class="abe-form-group">
                                <label>Type</label>
                                <select name="type_vendeur" class="abe-select">
                                    <option value="particulier" <?= $v['type_vendeur']=='particulier'?'selected':'' ?>>Particulier</option>
                                    <option value="entreprise"  <?= $v['type_vendeur']=='entreprise'?'selected':''  ?>>Entreprise</option>
                                </select>
                            </div>
                            <div class="abe-form-group" style="grid-column:1/-1;">
                                <label>Description</label>
                                <textarea name="description" class="abe-textarea"><?= htmlspecialchars($v['description'] ?? '') ?></textarea>
                            </div>
                            <div class="abe-form-group">
                                <label>NIF (entreprise)</label>
                                <input type="text" name="numero_nif" class="abe-input" value="<?= htmlspecialchars($v['numero_nif'] ?? '') ?>">
                            </div>
                            <div class="abe-form-group">
                                <label>Numéro RC</label>
                                <input type="text" name="numero_rc" class="abe-input" value="<?= htmlspecialchars($v['numero_rc'] ?? '') ?>">
                            </div>
                        </div>
                        <div style="display:flex;justify-content:flex-end;margin-top:8px;">
                            <button type="submit" class="abe-btn abe-btn-primary">
                                <i class="ri-save-line"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                    <?php else: ?>
                    <div style="text-align:center;padding:60px;color:var(--muted);">
                        <i class="ri-store-line" style="font-size:3rem;display:block;margin-bottom:12px;opacity:.3;"></i>
                        Boutique non configurée.
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php endif; // end is_vendeur ?>

            <!-- ─── TAB: PROFIL ─── -->
            <div class="abe-pane" id="tab-profile">
                <div class="abe-box">
                    <div class="abe-box-header">
                        <h3 class="abe-box-title">Informations personnelles</h3>
                    </div>
                    <!-- Avatar -->
                    <div class="abe-avatar-editor">
                        <img src="<?= $avatar_url ?>" alt="Avatar" class="abe-avatar-lg" id="profile_img">
                        <div class="abe-avatar-info">
                            <h5>Photo de profil</h5>
                            <p>JPEG, PNG, WEBP — max 2 MB</p>
                            <label for="avatar_input" class="abe-btn abe-btn-outline abe-btn-sm" style="cursor:pointer;">
                                <i class="ri-upload-2-line"></i> Choisir une photo
                            </label>
                            <input id="avatar_input" type="file" accept="image/*" style="display:none;">
                        </div>
                    </div>
                    <!-- Infos -->
                    <form id="profileForm">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div class="abe-form-group">
                                <label>Prénom <span>*</span></label>
                                <input type="text" name="prenom" class="abe-input" required value="<?= htmlspecialchars($user['prenom']) ?>">
                            </div>
                            <div class="abe-form-group">
                                <label>Nom <span>*</span></label>
                                <input type="text" name="nom" class="abe-input" required value="<?= htmlspecialchars($user['nom']) ?>">
                            </div>
                            <div class="abe-form-group" style="grid-column:1/-1;">
                                <label>Téléphone</label>
                                <input type="tel" name="telephone" class="abe-input" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>">
                            </div>
                        </div>
                        <!-- Infos lecture seule -->
                        <div style="background:var(--surface);border-radius:var(--radius-sm);padding:16px;margin-top:4px;">
                            <p style="font-size:.8rem;font-weight:600;color:var(--muted);margin:0 0 10px;text-transform:uppercase;letter-spacing:.05em;">Informations du compte</p>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div>
                                    <p style="font-size:.78rem;color:var(--muted);margin:0;">Email</p>
                                    <p style="font-weight:600;color:var(--primary);margin:0;font-size:.88rem;"><?= htmlspecialchars($user['email']) ?></p>
                                </div>
                                <div>
                                    <p style="font-size:.78rem;color:var(--muted);margin:0;">Email vérifié</p>
                                    <span class="abe-badge <?= $user['email_verifie'] ? 'abe-badge-success' : 'abe-badge-danger' ?>">
                                        <?= $user['email_verifie'] ? '✓ Vérifié' : '✗ Non vérifié' ?>
                                    </span>
                                </div>
                                <div>
                                    <p style="font-size:.78rem;color:var(--muted);margin:0;">Membre depuis</p>
                                    <p style="font-weight:600;color:var(--primary);margin:0;font-size:.88rem;"><?= date('d/m/Y', strtotime($user['date_creation'])) ?></p>
                                </div>
                                <div>
                                    <p style="font-size:.78rem;color:var(--muted);margin:0;">Dernière connexion</p>
                                    <p style="font-weight:600;color:var(--primary);margin:0;font-size:.88rem;"><?= !empty($user['derniere_connexion']) ? date('d/m/Y H:i', strtotime($user['derniere_connexion'])) : '—' ?></p>
                                </div>
                            </div>
                        </div>
                        <div style="display:flex;justify-content:flex-end;margin-top:16px;">
                            <button type="submit" class="abe-btn abe-btn-primary">
                                <i class="ri-save-line"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ─── TAB: SÉCURITÉ ─── -->
            <div class="abe-pane" id="tab-security">
                <!-- Changer email -->
                <div class="abe-box">
                    <div class="abe-box-header">
                        <div>
                            <h3 class="abe-box-title">Adresse email</h3>
                            <p class="abe-box-subtitle">Email actuel : <strong><?= htmlspecialchars($user['email']) ?></strong></p>
                        </div>
                    </div>
                    <form id="emailForm" style="max-width:400px;">
                        <div class="abe-form-group">
                            <label>Nouvel email <span>*</span></label>
                            <input type="email" name="email" class="abe-input" required placeholder="nouveau@email.com">
                        </div>
                        <button type="submit" class="abe-btn abe-btn-primary">
                            <i class="ri-mail-send-line"></i> Mettre à jour
                        </button>
                    </form>
                </div>
                <!-- Changer mot de passe -->
                <div class="abe-box">
                    <div class="abe-box-header">
                        <div>
                            <h3 class="abe-box-title">Mot de passe</h3>
                            <p class="abe-box-subtitle">Utilisez un mot de passe fort d'au moins 8 caractères.</p>
                        </div>
                    </div>
                    <form id="passwordForm" style="max-width:400px;">
                        <div class="abe-form-group">
                            <label>Ancien mot de passe <span>*</span></label>
                            <input type="password" name="old_password" class="abe-input" required placeholder="••••••••">
                        </div>
                        <div class="abe-form-group">
                            <label>Nouveau mot de passe <span>*</span></label>
                            <input type="password" name="new_password" class="abe-input" required placeholder="••••••••">
                        </div>
                        <div class="abe-form-group">
                            <label>Confirmer le mot de passe <span>*</span></label>
                            <input type="password" name="confirm_password" class="abe-input" required placeholder="••••••••">
                        </div>
                        <button type="submit" class="abe-btn abe-btn-primary">
                            <i class="ri-lock-password-line"></i> Changer le mot de passe
                        </button>
                    </form>
                </div>
            </div>

        </main><!-- /.abe-main -->
    </div><!-- /.abe-layout -->
</div>
</section>

<!-- ══════════ MODALS ══════════ -->

<!-- Modal: Modifier produit -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border:none;border-radius:var(--radius);box-shadow:var(--shadow-md);">
            <div class="modal-header" style="border-bottom:1px solid var(--border);padding:18px 24px;">
                <h5 class="modal-title" style="font-family:'Syne',sans-serif;font-weight:700;color:var(--primary);"><i class="ri-edit-line"></i> Modifier le produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProductForm" enctype="multipart/form-data">
                <input type="hidden" name="product_id" id="edit_product_id">
                <div class="modal-body" style="padding:20px 24px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <label>Nom du produit <span style="color:var(--accent);">*</span></label>
                            <input type="text" name="nom_produit" id="edit_nom_produit" class="abe-input" required>
                        </div>
                        <div class="abe-form-group">
                            <label>Catégorie <span style="color:var(--accent);">*</span></label>
                            <select name="id_categorie" id="edit_id_categorie" class="abe-select" required>
                                <option value="">Sélectionner</option>
                                <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="abe-form-group">
                            <label>Marque</label>
                            <input type="text" name="marque" id="edit_marque" class="abe-input" placeholder="Ex: Apple, Samsung...">
                        </div>
                        <div class="abe-form-group">
                            <label>Prix (FBu) <span style="color:var(--accent);">*</span></label>
                            <input type="number" name="prix_base" id="edit_prix_base" class="abe-input" min="0" required>
                        </div>
                        <div class="abe-form-group">
                            <label>Prix promo (FBu)</label>
                            <input type="number" name="prix_promo" id="edit_prix_promo" class="abe-input" min="0" placeholder="Laisser vide si pas de promo">
                        </div>
                        <div class="abe-form-group">
                            <label>Quantité en stock <span style="color:var(--accent);">*</span></label>
                            <input type="number" name="quantite_actuelle" id="edit_quantite" class="abe-input" min="0" required>
                        </div>
                        <div class="abe-form-group">
                            <label>Seuil stock bas</label>
                            <input type="number" name="seuil_stock_bas" id="edit_seuil_stock" class="abe-input" min="0" value="5">
                        </div>
                        <div class="abe-form-group">
                            <label>Statut</label>
                            <select name="statut" id="edit_statut" class="abe-select">
                                <option value="actif">Actif</option>
                                <option value="inactif">Inactif</option>
                            </select>
                        </div>
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <label>Description courte</label>
                            <input type="text" name="description_courte" id="edit_description_courte" class="abe-input" placeholder="Résumé court du produit">
                        </div>
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <label>Description détaillée</label>
                            <textarea name="description" id="edit_description" class="abe-textarea" rows="4" placeholder="Description complète..."></textarea>
                        </div>
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <label>Image principale</label>
                            <input type="file" name="main_image" class="abe-input" accept="image/*" onchange="document.getElementById('edit_img_preview').src=this.files[0]?URL.createObjectURL(this.files[0]):''">
                            <div style="margin-top:8px;">
                                <img id="edit_img_preview" src="" style="max-height:80px;border-radius:8px;display:none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);padding:14px 24px;gap:10px;">
                    <button type="button" class="abe-btn abe-btn-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="abe-btn abe-btn-primary" id="editProductBtn"><i class="ri-check-line"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Ajout adresse -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border:none;border-radius:var(--radius);box-shadow:var(--shadow-md);">
            <div class="modal-header" style="border-bottom:1px solid var(--border);padding:18px 24px;">
                <h5 class="modal-title" style="font-family:'Syne',sans-serif;font-weight:700;color:var(--primary);">Nouvelle adresse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAddressForm">
                <div class="modal-body" style="padding:20px 24px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="abe-form-group">
                            <label>Nom complet <span>*</span></label>
                            <input type="text" name="nom_complet" class="abe-input" required>
                        </div>
                        <div class="abe-form-group">
                            <label>Téléphone <span>*</span></label>
                            <input type="tel" name="telephone" class="abe-input" required>
                        </div>
                        <div class="abe-form-group">
                            <label>Type d'adresse</label>
                            <select name="type_adresse" class="abe-select">
                                <option value="domicile">Domicile</option>
                                <option value="travail">Travail</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="abe-form-group">
                            <label>Province</label>
                            <select name="id_province" class="abe-select">
                                <option value="">Sélectionner</option>
                                <?php if (!empty($provinces)): ?>
                                    <?php foreach ($provinces as $prov): ?>
                                        <option value="<?= $prov['id_province'] ?>"><?= htmlspecialchars($prov['province_name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <label>Adresse <span>*</span></label>
                            <textarea name="adresse_ligne" class="abe-textarea" rows="2" required></textarea>
                        </div>
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <label>Point de repère</label>
                            <input type="text" name="point_repere" class="abe-input" placeholder="Ex: À côté du marché central">
                        </div>
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <label>Instructions de livraison</label>
                            <textarea name="instructions_livraison" class="abe-textarea" rows="2" placeholder="Ex: Sonner à la grille bleue"></textarea>
                        </div>
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <input type="checkbox" name="est_par_defaut" value="1" id="addDefaultCheck" style="width:16px;height:16px;">
                                <label for="addDefaultCheck" style="margin:0;cursor:pointer;">Définir comme adresse par défaut</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);padding:14px 24px;gap:10px;">
                    <button type="button" class="abe-btn abe-btn-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="abe-btn abe-btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Édition adresse -->
<div class="modal fade" id="editAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border:none;border-radius:var(--radius);box-shadow:var(--shadow-md);">
            <div class="modal-header" style="border-bottom:1px solid var(--border);padding:18px 24px;">
                <h5 class="modal-title" style="font-family:'Syne',sans-serif;font-weight:700;color:var(--primary);">Modifier l'adresse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAddressForm">
                <input type="hidden" name="address_id" id="edit_address_id">
                <div class="modal-body" style="padding:20px 24px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="abe-form-group">
                            <label>Nom complet <span>*</span></label>
                            <input type="text" name="nom_complet" id="edit_nom_complet" class="abe-input" required>
                        </div>
                        <div class="abe-form-group">
                            <label>Téléphone <span>*</span></label>
                            <input type="tel" name="telephone" id="edit_telephone" class="abe-input" required>
                        </div>
                        <div class="abe-form-group">
                            <label>Type d'adresse</label>
                            <select name="type_adresse" id="edit_type_adresse" class="abe-select">
                                <option value="domicile">Domicile</option>
                                <option value="travail">Travail</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <label>Adresse <span>*</span></label>
                            <textarea name="adresse_ligne" id="edit_adresse_ligne" class="abe-textarea" rows="2" required></textarea>
                        </div>
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <label>Point de repère</label>
                            <input type="text" name="point_repere" id="edit_point_repere" class="abe-input">
                        </div>
                        <div class="abe-form-group" style="grid-column:1/-1;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <input type="checkbox" name="est_par_defaut" value="1" id="edit_est_par_defaut" style="width:16px;height:16px;">
                                <label for="edit_est_par_defaut" style="margin:0;cursor:pointer;">Définir comme adresse par défaut</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);padding:14px 24px;gap:10px;">
                    <button type="button" class="abe-btn abe-btn-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="abe-btn abe-btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══════════ JAVASCRIPT ══════════ -->
<script>
const BASE_URL = '<?= base_url() ?>';

// ── Sidebar mobile ────────────────────────────────
document.getElementById('sidebarToggle')?.addEventListener('click', function() {
    const s = document.getElementById('abeSidebar');
    s.classList.toggle('open');
    this.innerHTML = s.classList.contains('open')
        ? '<i class="ri-close-line"></i>'
        : '<i class="ri-menu-line"></i>';
});





// ── Navigation des tabs ───────────────────────────
function navigateTo(tabId) {
    document.querySelectorAll('.abe-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.abe-nav-btn').forEach(b => b.classList.remove('active'));
    const pane = document.getElementById(tabId);
    if (pane) pane.classList.add('active');
    const btn = document.querySelector(`[data-tab="${tabId}"]`);
    if (btn) btn.classList.add('active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.querySelectorAll('[data-tab]').forEach(btn => {
    btn.addEventListener('click', () => navigateTo(btn.dataset.tab));
});

document.querySelectorAll('[data-nav]').forEach(btn => {
    btn.addEventListener('click', () => navigateTo(btn.dataset.nav));
});

// Lire l'ancre URL (#tab-xxx)
const hash = window.location.hash.replace('#', '');
if (hash && document.getElementById(hash)) {
    navigateTo(hash);
}

// ── Helpers ───────────────────────────────────────
function showAlert(icon, title, text, cb) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: icon, title: title, text: text, confirmButtonColor: '#f59e0b', timer: icon==='success'?3000:null, showConfirmButton:true })
            .then(function() { if (cb) cb(); });
    } else {
        alert('[' + title + '] ' + text);
        if (cb) cb();
    }
}

async function apiPost(url, data, isJson = false) {
    const opts = {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    };
    if (isJson) {
        opts.headers['Content-Type'] = 'application/json';
        opts.body = JSON.stringify(data);
    } else {
        opts.body = data instanceof FormData ? data : (() => {
            const fd = new FormData();
            Object.entries(data).forEach(([k,v]) => fd.append(k,v));
            return fd;
        })();
    }
    const r = await fetch(BASE_URL + url, opts);
    return r.json();
}

function setLoading(btn, loading, original) {
    btn.disabled = loading;
    btn.innerHTML = loading ? '<i class="ri-loader-4-line" style="animation:spin 1s linear infinite;display:inline-block;"></i> Chargement...' : original;
}

// ── Profil ────────────────────────────────────────
document.getElementById('profileForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('[type=submit]'), orig = btn.innerHTML;
    setLoading(btn, true, orig);
    try {
        const r = await apiPost('user_dashboard/ajax_update_profile', new FormData(this));
        r.success ? showAlert('success', 'Succès !', r.message, () => location.reload())
                  : showAlert('error', 'Erreur', r.message || r.errors || 'Une erreur est survenue');
    } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    finally { setLoading(btn, false, orig); }
});

// ── Avatar ────────────────────────────────────────
document.getElementById('avatar_input')?.addEventListener('change', async function() {
    const file = this.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) { showAlert('error', 'Erreur', 'Max 2 MB'); return; }
    if (!['image/jpeg','image/jpg','image/png','image/gif','image/webp'].includes(file.type)) {
        showAlert('error', 'Erreur', 'Format non supporté (JPG, PNG, WEBP, GIF)'); return;
    }
    const fd = new FormData(); fd.append('avatar', file);
    try {
        const r = await apiPost('user_dashboard/ajax_update_profile', fd);
        if (r.success && r.avatar_url) {
            document.getElementById('profile_img').src = r.avatar_url;
            document.querySelector('#sidebarAvatar').src = r.avatar_url;
            document.querySelector('.abe-welcome-avatar').src = r.avatar_url;
            showAlert('success', 'Succès', 'Photo mise à jour');
        } else { showAlert('error', 'Erreur', r.message); }
    } catch { showAlert('error', 'Erreur', 'Erreur de chargement'); }
});

// ── Mot de passe ──────────────────────────────────
document.getElementById('passwordForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const np = this.querySelector('[name=new_password]').value;
    const cp = this.querySelector('[name=confirm_password]').value;
    if (np !== cp) { showAlert('error', 'Erreur', 'Les mots de passe ne correspondent pas'); return; }
    if (np.length < 6) { showAlert('error', 'Erreur', 'Minimum 6 caractères'); return; }
    const btn = this.querySelector('[type=submit]'), orig = btn.innerHTML;
    setLoading(btn, true, orig);
    try {
        const r = await apiPost('user_dashboard/ajax_change_password', new FormData(this));
        r.success ? showAlert('success', 'Succès !', r.message, () => this.reset())
                  : showAlert('error', 'Erreur', r.message);
    } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    finally { setLoading(btn, false, orig); }
});

// ── Email ─────────────────────────────────────────
document.getElementById('emailForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('[type=submit]'), orig = btn.innerHTML;
    setLoading(btn, true, orig);
    try {
        // URL CORRIGÉE (suppression du préfixe Home/)
        const r = await apiPost('user_dashboard/ajax_change_email', new FormData(this));
        r.success ? showAlert('success', 'Succès !', r.message, () => location.reload())
                  : showAlert('error', 'Erreur', r.message || r.errors || 'Erreur');
    } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    finally { setLoading(btn, false, orig); }
});

// ── Adresse: Ajouter ─────────────────────────────
document.getElementById('addAddressForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('[type=submit]'), orig = btn.innerHTML;
    setLoading(btn, true, orig);
    try {
        const r = await apiPost('user_dashboard/ajax_add_address', new FormData(this));
        if (r.success) {
            bootstrap.Modal.getInstance(document.getElementById('addAddressModal'))?.hide();
            showAlert('success', 'Succès !', r.message, () => location.reload());
        } else { showAlert('error', 'Erreur', r.message); }
    } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    finally { setLoading(btn, false, orig); }
});

// ── Adresse: Ouvrir modale édition ───────────────
document.querySelectorAll('.edit-address').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.addressId;
        try {
            const r = await fetch(BASE_URL + 'user_dashboard/ajax_get_address/' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.json());
            if (r.success) {
                const a = r.data;
                document.getElementById('edit_address_id').value   = a.id_adresse;
                document.getElementById('edit_nom_complet').value  = a.nom_complet;
                document.getElementById('edit_telephone').value    = a.telephone;
                document.getElementById('edit_type_adresse').value = a.type_adresse;
                document.getElementById('edit_adresse_ligne').value= a.adresse_ligne;
                document.getElementById('edit_point_repere').value = a.point_repere || '';
                document.getElementById('edit_est_par_defaut').checked = a.est_par_defaut == 1;
                new bootstrap.Modal(document.getElementById('editAddressModal')).show();
            } else { showAlert('error', 'Erreur', r.message); }
        } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    });
});

// ── Adresse: Soumettre édition ────────────────────
document.getElementById('editAddressForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('[type=submit]'), orig = btn.innerHTML;
    setLoading(btn, true, orig);
    try {
        const r = await apiPost('user_dashboard/ajax_update_address', new FormData(this));
        if (r.success) {
            bootstrap.Modal.getInstance(document.getElementById('editAddressModal'))?.hide();
            showAlert('success', 'Succès !', r.message, () => location.reload());
        } else { showAlert('error', 'Erreur', r.message); }
    } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    finally { setLoading(btn, false, orig); }
});

// ── Adresse: Supprimer ────────────────────────────
document.querySelectorAll('.delete-address').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.addressId;
        const conf = await Swal.fire({ title:'Supprimer cette adresse ?', text:'Cette action est irréversible.', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', cancelButtonColor:'#6b7280', confirmButtonText:'Supprimer', cancelButtonText:'Annuler' });
        if (!conf.isConfirmed) return;
        try {
            const r = await apiPost('user_dashboard/ajax_delete_address/' + id, {});
            r.success ? showAlert('success', 'Supprimée', r.message, () => location.reload())
                      : showAlert('error', 'Erreur', r.message);
        } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    });
});

// ── Wishlist: Retirer ─────────────────────────────
document.querySelectorAll('.remove-wishlist-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.productId;
        try {
            const r = await apiPost('user_dashboard/ajax_remove_wishlist', { product_id: id });
            r.success ? location.reload() : showAlert('error', 'Erreur', r.message);
        } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    });
});

// ── Notifications: Marquer lu ─────────────────────
document.querySelectorAll('.mark-read-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.id;
        try {
            const r = await apiPost('user_dashboard/ajax_mark_notification_read', { notification_id: id });
            if (r.success) this.closest('div[style]').style.background = '';
        } catch {}
    });
});

// ── Détail commande ───────────────────────────────
document.querySelectorAll('.view-order-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.orderId;
        try {
            const r = await fetch(BASE_URL + 'user_dashboard/ajax_order_detail/' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.json());
            if (r.success) {
                const c = r.data;
                const articlesHtml = (c.articles && c.articles.length)
                    ? c.articles.map(a => `<li style="padding:6px 0;border-bottom:1px solid #f3f4f6;">
                        <strong>${a.nom_produit}</strong> × ${a.quantite}
                        <span style="float:right;color:#f59e0b;font-weight:700;">${parseFloat(a.prix_total).toLocaleString()} FBu</span>
                      </li>`).join('')
                    : '<li>Aucun article</li>';
                const histHtml = (c.historique_statuts && c.historique_statuts.length)
                    ? c.historique_statuts.slice(0,5).map(h => `<li style="font-size:.8rem;color:#6b7280;padding:3px 0;">${h.statut} — ${new Date(h.date_creation).toLocaleString()}</li>`).join('')
                    : '';
                Swal.fire({
                    title: `Commande #${c.numero_commande}`,
                    html: `<div style="text-align:left;font-size:.9rem;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                            <div><p style="margin:0;color:#6b7280;font-size:.78rem;">Date</p><p style="margin:0;font-weight:600;">${new Date(c.date_creation).toLocaleDateString()}</p></div>
                            <div><p style="margin:0;color:#6b7280;font-size:.78rem;">Statut</p><p style="margin:0;font-weight:600;">${c.statut_commande}</p></div>
                            <div><p style="margin:0;color:#6b7280;font-size:.78rem;">Paiement</p><p style="margin:0;font-weight:600;">${c.mode_paiement||'N/A'}</p></div>
                            <div><p style="margin:0;color:#6b7280;font-size:.78rem;">Total</p><p style="margin:0;font-weight:700;color:#f59e0b;">${parseFloat(c.montant_total).toLocaleString()} FBu</p></div>
                        </div>
                        <hr style="margin:10px 0;">
                        <strong>Articles :</strong>
                        <ul style="list-style:none;padding:0;margin:8px 0;">${articlesHtml}</ul>
                        ${histHtml ? `<details style="margin-top:10px;"><summary style="cursor:pointer;font-size:.8rem;color:#6b7280;">Historique des statuts</summary><ul style="list-style:none;padding:0;margin:6px 0;">${histHtml}</ul></details>` : ''}
                    </div>`,
                    confirmButtonColor: '#f59e0b', width: '480px'
                });
            } else { showAlert('error', 'Erreur', r.message); }
        } catch { showAlert('error', 'Erreur', 'Erreur de chargement'); }
    });
});

<?php if ($is_vendeur): ?>
// ── Vendeur: Ajouter produit ──────────────────────
document.getElementById('addProductForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('addProductBtn'), orig = btn.innerHTML;
    setLoading(btn, true, orig);
    try {
        const r = await apiPost('user_dashboard/ajax_add_product', new FormData(this));
        r.success ? showAlert('success', 'Soumis !', r.message, () => location.reload())
                  : showAlert('error', 'Erreur', r.message);
    } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    finally { setLoading(btn, false, orig); }
});

// ── Vendeur: Supprimer produit ────────────────────
document.querySelectorAll('.delete-product').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.id;
        const conf = await Swal.fire({ title:'Supprimer ce produit ?', text:'Action irréversible.', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', cancelButtonColor:'#6b7280', confirmButtonText:'Supprimer', cancelButtonText:'Annuler' });
        if (!conf.isConfirmed) return;
        const fd = new FormData(); fd.append('product_id', id);
        try {
            const r = await apiPost('user_dashboard/ajax_delete_product', fd);
            r.success ? showAlert('success', 'Supprimé', r.message, () => location.reload())
                      : showAlert('error', 'Erreur', r.message);
        } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    });
});

// ── Vendeur: Modifier produit ──────────────────
document.querySelectorAll('.edit-product').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.id;
        try {
            const resp = await fetch(BASE_URL + 'user_dashboard/ajax_get_product?product_id=' + id);
            const r = await resp.json();
            if (!r.success) { showAlert('error', 'Erreur', r.message); return; }
            const p = r.product;
            document.getElementById('edit_product_id').value = p.id_produit;
            document.getElementById('edit_nom_produit').value = p.nom_produit;
            document.getElementById('edit_id_categorie').value = p.id_categorie;
            document.getElementById('edit_marque').value = p.marque || '';
            document.getElementById('edit_prix_base').value = p.prix_base;
            document.getElementById('edit_prix_promo').value = p.prix_promo || '';
            document.getElementById('edit_quantite').value = p.quantite_actuelle;
            document.getElementById('edit_seuil_stock').value = p.seuil_stock_bas || 5;
            document.getElementById('edit_statut').value = p.statut || 'actif';
            document.getElementById('edit_description_courte').value = p.description_courte || '';
            document.getElementById('edit_description').value = p.description || '';
            var preview = document.getElementById('edit_img_preview');
            if (p.image_url) { preview.src = BASE_URL + p.image_url; preview.style.display = 'block'; }
            else { preview.style.display = 'none'; }
            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        } catch { showAlert('error', 'Erreur', 'Erreur de chargement'); }
    });
});

// ── Vendeur: Sauvegarder modification produit ──
document.getElementById('editProductForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('editProductBtn'), orig = btn.innerHTML;
    setLoading(btn, true, orig);
    try {
        const r = await apiPost('user_dashboard/ajax_edit_product', new FormData(this));
        if (r.success) {
            bootstrap.Modal.getInstance(document.getElementById('editProductModal'))?.hide();
            showAlert('success', 'Modifié !', r.message, () => location.reload());
        } else {
            showAlert('error', 'Erreur', r.message);
        }
    } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    finally { setLoading(btn, false, orig); }
});

// ── Vendeur: Mise à jour statut commande ──────────
document.querySelectorAll('.update-order-status').forEach(sel => {
    sel.addEventListener('change', async function() {
        try {
            const r = await apiPost('user_dashboard/ajax_update_order_status', { order_id: this.dataset.orderId, status: this.value }, true);
            r.success ? showAlert('success', 'Mis à jour', r.message)
                      : showAlert('error', 'Erreur', r.message);
        } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    });
});

// ── Vendeur: Mise à jour stock ────────────────────
document.querySelectorAll('.update-stock').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.id;
        const qty = document.querySelector(`.new-stock-${id}`)?.value;
        if (!qty || qty < 0) { showAlert('error', 'Erreur', 'Veuillez entrer une quantité valide'); return; }
        try {
            const r = await apiPost('user_dashboard/ajax_update_stock', { product_id: id, quantity: qty }, true);
            r.success ? showAlert('success', 'Stock mis à jour', r.message, () => location.reload())
                      : showAlert('error', 'Erreur', r.message);
        } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    });
});

// ── Vendeur: Boutique ─────────────────────────────
document.getElementById('boutiqueForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('[type=submit]'), orig = btn.innerHTML;
    setLoading(btn, true, orig);
    try {
        const r = await apiPost('user_dashboard/ajax_update_boutique', new FormData(this));
        r.success ? showAlert('success', 'Succès !', r.message) : showAlert('error', 'Erreur', r.message);
    } catch { showAlert('error', 'Erreur', 'Erreur de connexion'); }
    finally { setLoading(btn, false, orig); }
});
<?php endif; ?>

// ── Animation spin CSS ────────────────────────────
const style = document.createElement('style');
style.textContent = '@keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }';
document.head.appendChild(style);
</script>

<?php include VIEWPATH . 'includes/frontend/Footer.php'; ?>