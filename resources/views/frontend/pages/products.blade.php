@extends('frontend.layout.app')

@section('content')
<style>
    /* ============================================================
       CSS Custom Properties
    ============================================================ */
    :root {
        --pr:        #f85606;
        --pr-dk:     #d94a04;
        --pr-lt:     #fff3ec;
        --pr-grd:    linear-gradient(135deg, #f85606, #ff8a00);
        --dark:      #0f172a;
        --text:      #374151;
        --muted:     #6b7280;
        --border:    #e5e7eb;
        --card:      #ffffff;
        --bg:        #f1f5f9;
        --sidebar-w: 280px;
        --radius-xl: 20px;
        --radius-lg: 14px;
        --radius-md: 10px;
        --shadow-sm: 0 2px 8px rgba(0,0,0,.06);
        --shadow-md: 0 8px 28px rgba(0,0,0,.10);
        --shadow-lg: 0 16px 48px rgba(0,0,0,.12);
        --transition: .22s ease;
    }

    /* ============================================================
       Page shell
    ============================================================ */
    #products-page {
        background: var(--bg);
        min-height: 100vh;
        padding-bottom: 60px;
    }

    /* ============================================================
       Hero / Breadcrumb bar
    ============================================================ */
    .page-hero {
        background: var(--pr-grd);
        padding: 28px 0 22px;
        position: relative;
        overflow: hidden;
    }

    .page-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }

    .page-hero .container {
        position: relative;
    }

    .breadcrumb-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: rgba(255,255,255,.85);
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .breadcrumb-row a {
        color: rgba(255,255,255,.9);
        text-decoration: none;
        font-weight: 500;
        transition: color var(--transition);
    }

    .breadcrumb-row a:hover { color: #fff; }

    .breadcrumb-row .sep {
        opacity: .6;
        font-size: 10px;
    }

    .hero-title {
        color: #fff;
        font-size: clamp(26px, 4vw, 38px);
        font-weight: 800;
        letter-spacing: -.5px;
        margin: 0 0 4px;
        text-shadow: 0 2px 8px rgba(0,0,0,.15);
    }

    .hero-subtitle {
        color: rgba(255,255,255,.78);
        font-size: 14px;
        font-weight: 400;
        margin: 0;
    }

    /* ============================================================
       Layout: sidebar + main
    ============================================================ */
    .products-layout {
        display: grid;
        grid-template-columns: var(--sidebar-w) 1fr;
        gap: 20px;
        margin-top: 24px;
        align-items: start;
    }

    /* ============================================================
       Search bar (above listing)
    ============================================================ */
    .search-bar-wrap {
        background: var(--card);
        border-radius: var(--radius-xl);
        padding: 14px 16px;
        box-shadow: var(--shadow-md);
        margin-bottom: 16px;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    #liveSearchWrapper {
        position: relative;
        flex: 1;
    }

    .search-input-group {
        display: flex;
        align-items: center;
        background: var(--bg);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: border-color var(--transition), box-shadow var(--transition);
    }

    .search-input-group:focus-within {
        border-color: var(--pr);
        box-shadow: 0 0 0 4px rgba(248,86,6,.1);
    }

    .search-icon {
        padding: 0 14px;
        color: var(--muted);
        font-size: 15px;
        pointer-events: none;
        flex-shrink: 0;
    }

    #productNameInput {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
        font-size: 14px;
        color: var(--dark);
        padding: 13px 12px 13px 0;
        font-weight: 500;
    }

    #productNameInput::placeholder { color: var(--muted); font-weight: 400; }

    .search-clear-btn {
        padding: 0 14px;
        background: none;
        border: none;
        color: var(--muted);
        font-size: 14px;
        cursor: pointer;
        transition: color var(--transition);
        display: none;
        flex-shrink: 0;
    }

    .search-clear-btn:hover { color: var(--pr); }

    .search-submit-btn {
        background: var(--pr-grd);
        color: #fff;
        border: none;
        border-radius: var(--radius-lg);
        padding: 13px 24px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap;
        transition: opacity var(--transition), transform var(--transition);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .search-submit-btn:hover {
        opacity: .9;
        transform: translateY(-1px);
    }

    /* ============================================================
       Autocomplete / Suggest box
    ============================================================ */
    .product-suggest-box {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        z-index: 1060;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        max-height: 420px;
        overflow-y: auto;
        overscroll-behavior: contain;
        padding: 8px;
        animation: suggestIn .16s ease;
    }

    @keyframes suggestIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .ps-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 9px 10px;
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--dark);
        transition: background var(--transition);
    }

    .ps-item:hover, .ps-item.active { background: var(--pr-lt); }

    .ps-thumb {
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        border-radius: 10px;
        overflow: hidden;
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
    }

    .ps-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .ps-info { min-width: 0; flex: 1; }

    .ps-name {
        font-size: 13px;
        font-weight: 700;
        line-height: 1.35;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ps-name mark {
        background: rgba(248,86,6,.12);
        color: var(--pr);
        font-weight: 800;
        border-radius: 3px;
        padding: 0 1px;
    }

    .ps-meta {
        font-size: 11px;
        color: var(--muted);
        display: flex;
        gap: 10px;
        margin-top: 2px;
    }

    .ps-price {
        font-size: 13px;
        font-weight: 800;
        color: var(--pr);
        white-space: nowrap;
    }

    .ps-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 20px 0;
        color: var(--muted);
        font-size: 13px;
    }

    .ps-empty {
        padding: 18px;
        text-align: center;
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
    }

    .ps-footer {
        padding: 8px 10px 4px;
        border-top: 1px solid var(--border);
        margin-top: 4px;
        font-size: 11px;
        color: var(--muted);
        text-align: center;
    }

    /* ============================================================
       Sidebar / Filter panel
    ============================================================ */
    .filter-panel {
        background: var(--card);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        position: sticky;
        top: 80px;
    }

    .filter-header {
        background: var(--pr-grd);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .filter-header-title {
        color: #fff;
        font-size: 16px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .filter-header-title i { font-size: 14px; }

    .filter-reset-btn {
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.3);
        color: #fff;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: background var(--transition);
        text-decoration: none;
    }

    .filter-reset-btn:hover { background: rgba(255,255,255,.28); color: #fff; }

    .filter-body { padding: 12px; }

    /* Accordion tweaks */
    .filt-accordion .accordion-item {
        border: 1.5px solid var(--border);
        border-radius: var(--radius-md) !important;
        overflow: hidden;
        margin-bottom: 8px;
        background: var(--card);
    }

    .filt-accordion .accordion-button {
        font-weight: 700;
        font-size: 13px;
        color: var(--dark);
        background: var(--card);
        border-radius: var(--radius-md) !important;
        box-shadow: none !important;
        padding: 12px 14px;
        gap: 8px;
    }

    .filt-accordion .accordion-button::after {
        width: 16px;
        height: 16px;
        background-size: 16px;
        flex-shrink: 0;
    }

    .filt-accordion .accordion-button:not(.collapsed) {
        background: var(--pr-lt);
        color: var(--pr);
    }

    .filt-accordion .accordion-button:focus { box-shadow: none; }

    .filt-accordion .accordion-body {
        padding: 10px 14px 14px;
        max-height: 250px;
        overflow-y: auto;
    }

    .filt-accordion .form-check {
        padding: 7px 0 7px 1.6em;
        margin: 0;
    }

    .filt-accordion .form-check-input {
        cursor: pointer;
        border-color: #cbd5e1;
        border-radius: 5px;
    }

    .filt-accordion .form-check-input:checked {
        background-color: var(--pr);
        border-color: var(--pr);
    }

    .filt-accordion .form-check-label {
        cursor: pointer;
        color: var(--text);
        font-size: 13px;
        font-weight: 500;
        padding-left: 4px;
    }

    /* Active filter count badge */
    .filter-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--pr);
        color: #fff;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        font-weight: 800;
        line-height: 1;
        margin-left: 4px;
    }

    /* ============================================================
       Product listing area
    ============================================================ */
    .listing-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        gap: 10px;
        flex-wrap: wrap;
    }

    .listing-count {
        font-size: 13px;
        color: var(--muted);
        font-weight: 500;
    }

    .listing-count strong {
        color: var(--dark);
        font-weight: 700;
    }

    /* View toggle */
    .view-toggle {
        display: flex;
        gap: 4px;
        background: var(--bg);
        border-radius: 10px;
        padding: 3px;
    }

    .view-btn {
        background: none;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        cursor: pointer;
        font-size: 13px;
        transition: background var(--transition), color var(--transition);
    }

    .view-btn.active {
        background: var(--card);
        color: var(--pr);
        box-shadow: var(--shadow-sm);
    }

    #productListing {
        min-height: 200px;
        transition: opacity .18s ease;
    }

    #productListing.listing-loading {
        opacity: .4;
        pointer-events: none;
    }

    /* Skeleton loader */
    .skeleton-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .skeleton-card {
        background: var(--card);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .sk-img {
        height: 190px;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    .sk-body { padding: 12px; }

    .sk-line {
        height: 12px;
        border-radius: 6px;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        margin-bottom: 8px;
    }

    .sk-line.w-75 { width: 75%; }
    .sk-line.w-50 { width: 50%; }
    .sk-line.w-full { width: 100%; height: 36px; border-radius: 10px; }

    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* ============================================================
       Mode switcher (All Products / Loose Products)
    ============================================================ */
    .mode-switch-wrap {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .mode-switch {
        display: inline-flex;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: 999px;
        padding: 5px;
        gap: 4px;
        box-shadow: var(--shadow-md);
    }

    .mode-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        background: transparent;
        border-radius: 999px;
        padding: 10px 22px;
        font-size: 14px;
        font-weight: 700;
        color: var(--muted);
        cursor: pointer;
        transition: all var(--transition);
        white-space: nowrap;
    }

    .mode-btn i { font-size: 13px; }

    .mode-btn:hover { color: var(--pr); }

    .mode-btn.active {
        background: var(--pr-grd);
        color: #fff;
        box-shadow: 0 4px 14px rgba(248,86,6,.35);
    }

    /* ============================================================
       Loose category chips (outside the filter)
    ============================================================ */
    .loose-chips {
        display: none;
        gap: 8px;
        overflow-x: auto;
        padding: 4px 2px 10px;
        margin-bottom: 10px;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .loose-chips::-webkit-scrollbar { display: none; }

    #products-page.loose-mode .loose-chips { display: flex; }

    .loose-chip {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--card);
        border: 1.5px solid var(--border);
        color: var(--text);
        border-radius: 999px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all var(--transition);
        white-space: nowrap;
    }

    .loose-chip i { font-size: 11px; color: var(--muted); transition: color var(--transition); }

    .loose-chip:hover { border-color: var(--pr); color: var(--pr); }
    .loose-chip:hover i { color: var(--pr); }

    .loose-chip.active {
        background: var(--pr-grd);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 4px 14px rgba(248,86,6,.35);
    }

    .loose-chip.active i { color: #fff; }

    /* Hide the regular filter UI while in loose mode */
    #products-page.loose-mode .desktop-sidebar,
    #products-page.loose-mode .mobile-filter-bar { display: none !important; }

    #products-page.loose-mode .products-layout { grid-template-columns: 1fr; }

    /* ============================================================
       Infinite scroll loader
    ============================================================ */
    .infinite-status {
        padding: 28px 0 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
    }

    .infinite-spinner {
        width: 34px;
        height: 34px;
        border: 3px solid var(--pr-lt);
        border-top-color: var(--pr);
        border-radius: 50%;
        animation: infSpin .7s linear infinite;
    }

    @keyframes infSpin {
        to { transform: rotate(360deg); }
    }

    .infinite-end {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
        padding: 26px 0 6px;
    }

    .infinite-end::before,
    .infinite-end::after {
        content: '';
        height: 1px;
        background: var(--border);
        flex: 1;
    }

    /* ============================================================
       Mobile filter drawer
    ============================================================ */
    .mobile-filter-bar {
        display: none;
        background: var(--card);
        border-bottom: 1px solid var(--border);
        padding: 10px 16px;
        gap: 10px;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 900;
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
    }

    .mob-filter-btn {
        display: flex;
        align-items: center;
        gap: 7px;
        background: var(--bg);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-md);
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 700;
        color: var(--dark);
        cursor: pointer;
        transition: all var(--transition);
        flex: 1;
        justify-content: center;
    }

    .mob-filter-btn:hover, .mob-filter-btn.active {
        background: var(--pr-lt);
        border-color: var(--pr);
        color: var(--pr);
    }

    .mob-filter-btn .badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--pr);
        display: none;
    }

    .mob-filter-btn.has-filters .badge-dot { display: block; }

    /* Drawer overlay */
    .filter-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.5);
        z-index: 1040;
        animation: fadeIn .2s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .filter-overlay.open { display: block; }

    /* Drawer sheet */
    .filter-drawer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--card);
        border-radius: 24px 24px 0 0;
        z-index: 1050;
        max-height: 85vh;
        overflow-y: auto;
        overscroll-behavior: contain;
        transform: translateY(100%);
        transition: transform .3s cubic-bezier(.34,1.56,.64,1);
        padding-bottom: env(safe-area-inset-bottom, 20px);
    }

    .filter-drawer.open { transform: translateY(0); }

    .drawer-handle {
        width: 40px;
        height: 4px;
        background: var(--border);
        border-radius: 2px;
        margin: 14px auto 0;
    }

    .drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px 12px;
        border-bottom: 1px solid var(--border);
    }

    .drawer-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .drawer-title i { color: var(--pr); }

    .drawer-close {
        width: 34px;
        height: 34px;
        background: var(--bg);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--muted);
        font-size: 14px;
        transition: background var(--transition), color var(--transition);
    }

    .drawer-close:hover { background: var(--pr-lt); color: var(--pr); }

    .drawer-body {
        padding: 12px 16px;
    }

    .drawer-footer {
        padding: 14px 16px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 10px;
    }

    .drawer-apply-btn {
        flex: 1;
        background: var(--pr-grd);
        color: #fff;
        border: none;
        border-radius: var(--radius-lg);
        padding: 13px;
        font-weight: 800;
        font-size: 14px;
        cursor: pointer;
        transition: opacity var(--transition);
    }

    .drawer-apply-btn:hover { opacity: .9; }

    .drawer-reset-btn {
        background: var(--bg);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 13px 18px;
        font-weight: 700;
        font-size: 13px;
        color: var(--muted);
        cursor: pointer;
        transition: all var(--transition);
    }

    .drawer-reset-btn:hover { border-color: var(--pr); color: var(--pr); }

    /* Drawer accordion (slightly looser) */
    .drawer-body .accordion-item {
        border: 1.5px solid var(--border);
        border-radius: var(--radius-md) !important;
        overflow: hidden;
        margin-bottom: 8px;
        background: var(--card);
    }

    .drawer-body .accordion-button {
        font-weight: 700;
        font-size: 14px;
        color: var(--dark);
        background: var(--card);
        box-shadow: none !important;
        padding: 13px 16px;
    }

    .drawer-body .accordion-button:not(.collapsed) {
        background: var(--pr-lt);
        color: var(--pr);
    }

    .drawer-body .accordion-button:focus { box-shadow: none; }

    .drawer-body .accordion-body {
        padding: 10px 16px 14px;
        max-height: 240px;
        overflow-y: auto;
    }

    .drawer-body .form-check {
        padding: 8px 0 8px 1.7em;
        margin: 0;
    }

    .drawer-body .form-check-input {
        cursor: pointer;
        border-color: #cbd5e1;
        border-radius: 5px;
    }

    .drawer-body .form-check-input:checked {
        background-color: var(--pr);
        border-color: var(--pr);
    }

    .drawer-body .form-check-label {
        cursor: pointer;
        color: var(--text);
        font-size: 14px;
        font-weight: 500;
        padding-left: 4px;
    }

    /* ============================================================
       Active-filter chips (mobile)
    ============================================================ */
    .active-chips {
        display: none;
        gap: 6px;
        flex-wrap: wrap;
        padding: 10px 16px 0;
        align-items: center;
    }

    .active-chips.has-chips { display: flex; }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--pr-lt);
        border: 1px solid rgba(248,86,6,.25);
        color: var(--pr);
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: background var(--transition);
    }

    .filter-chip:hover { background: rgba(248,86,6,.18); }

    .filter-chip i { font-size: 10px; }

    /* ============================================================
       Responsive
    ============================================================ */
    @media (max-width: 1023px) {
        .products-layout {
            grid-template-columns: 1fr;
        }

        .desktop-sidebar { display: none; }

        .mobile-filter-bar { display: flex; }

        .skeleton-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 575px) {
        .page-hero { padding: 22px 0 18px; }

        .hero-title { font-size: 24px; }

        .search-bar-wrap { padding: 10px 12px; gap: 8px; border-radius: var(--radius-lg); }

        .search-submit-btn { padding: 12px 16px; font-size: 13px; }

        .search-submit-btn span { display: none; }

        .skeleton-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

{{-- ================================================================
     PAGE
================================================================ --}}
<div id="products-page">

    {{-- ---- Hero ---- --}}
    <div class="page-hero">
        <div class="container">
            <div class="breadcrumb-row">
                <a href="{{ route('home') }}"><i class="fa-solid fa-house" style="font-size:11px;"></i> {{ trans('language.home') }}</a>
                <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
                <span>{{ trans('language.products') }}</span>
            </div>
            <h1 class="hero-title">{{ trans('language.products') }}</h1>
            <p class="hero-subtitle" id="heroSubtitle">{{ trans('language.filter') }} &amp; {{ trans('language.btn_search') }}</p>
        </div>
    </div>

    {{-- ---- Mobile sticky bar (filter toggle) ---- --}}
    <div class="mobile-filter-bar" id="mobileFilterBar">
        <button class="mob-filter-btn" id="openFilterDrawer" type="button">
            <i class="fa-solid fa-sliders"></i>
            {{ trans('language.filter') }}
            <span class="badge-dot"></span>
        </button>
    </div>

    {{-- ---- Active filter chips (mobile) ---- --}}
    <div class="active-chips container" id="activeChips"></div>

    {{-- ---- Mode switcher: All Products / Loose Products ---- --}}
    <div class="mode-switch-wrap">
        <div class="mode-switch" role="tablist" aria-label="Product mode">
            <button type="button" class="mode-btn active" id="modeAllBtn" role="tab" aria-selected="true">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>{{ trans('language.all_products') }}</span>
            </button>
            @if ($loose_categories->count() > 0)
                <button type="button" class="mode-btn" id="modeLooseBtn" role="tab" aria-selected="false">
                    <i class="fa-solid fa-scale-balanced"></i>
                    <span>{{ trans('language.loose_products') }}</span>
                </button>
            @endif
        </div>
    </div>

    {{-- ---- Main layout ---- --}}
    <div class="container">
        <div class="products-layout">

            {{-- ======== Desktop Sidebar ======== --}}
            <aside class="desktop-sidebar">
                <div class="filter-panel">
                    <div class="filter-header">
                        <p class="filter-header-title">
                            <i class="fa-solid fa-sliders"></i>
                            {{ trans('language.filter') }}
                        </p>
                        <a href="#" class="filter-reset-btn" id="desktopResetBtn">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    </div>

                    <div class="filter-body">
                        <div class="accordion filt-accordion" id="desktopAccordion">
                            <form id="desktopFilterForm">

                                {{-- Categories --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button {{ isset($root_category) ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#dc-cat" aria-expanded="{{ isset($root_category) ? 'true' : 'false' }}">
                                            <i class="fa-solid fa-layer-group" style="color:var(--pr);font-size:12px;"></i>
                                            {{ trans('language.categories') }}
                                        </button>
                                    </h2>
                                    <div id="dc-cat" class="accordion-collapse collapse {{ isset($root_category) ? 'show' : '' }}">
                                        <div class="accordion-body">
                                            @foreach ($categories as $category)
                                                <div class="form-check">
                                                    <input class="form-check-input category_for_filter" type="checkbox"
                                                        value="{{ $category->id }}" id="dcat_{{ $category->id }}"
                                                        @if (isset($root_category->id) && $root_category->id == $category->id) checked @endif
                                                        name="category" data-label="{{ $category->title }}">
                                                    <label class="form-check-label" for="dcat_{{ $category->id }}">
                                                        {{ $category->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Brands --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button {{ isset($current_brand) ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#dc-brand" aria-expanded="{{ isset($current_brand) ? 'true' : 'false' }}">
                                            <i class="fa-solid fa-tag" style="color:var(--pr);font-size:12px;"></i>
                                            {{ trans('language.brands') }}
                                        </button>
                                    </h2>
                                    <div id="dc-brand" class="accordion-collapse collapse {{ isset($current_brand) ? 'show' : '' }}">
                                        <div class="accordion-body">
                                            @foreach ($brands as $brand)
                                                <div class="form-check">
                                                    <input class="form-check-input brands_for_filter" type="checkbox"
                                                        @if (isset($current_brand->id) && $current_brand->id == $brand->id) checked @endif
                                                        value="{{ $brand->id }}" id="dbrand_{{ $brand->id }}"
                                                        name="brand" data-label="{{ $brand->title }}">
                                                    <label class="form-check-label" for="dbrand_{{ $brand->id }}">
                                                        {{ $brand->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Dynamic attribute filters --}}
                                @foreach ($filter_attributes as $attributes)
                                    @php $attrId = 'dc-attr-' . trim(str_replace(' ', '', $attributes->attribute_name)); @endphp
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#{{ $attrId }}" aria-expanded="false">
                                                <i class="fa-solid fa-circle-dot" style="color:var(--pr);font-size:12px;"></i>
                                                {{ $attributes->attribute_name }}
                                            </button>
                                        </h2>
                                        <div id="{{ $attrId }}" class="accordion-collapse collapse">
                                            <div class="accordion-body">
                                                @foreach ($attributes->attributes_values as $value)
                                                    @php $valId = 'dval-' . trim(str_replace(' ', '', $value->value)); @endphp
                                                    <div class="form-check">
                                                        <input class="form-check-input attributes_for_filter" type="checkbox"
                                                            value="{{ $value->value }}" id="{{ $valId }}"
                                                            data-label="{{ $value->value }}">
                                                        <label class="form-check-label" for="{{ $valId }}">
                                                            {{ $value->value }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- ======== Listing column ======== --}}
            <div class="listing-col">

                {{-- Loose category chips (outside the filter — only in loose mode) --}}
                @if ($loose_categories->count() > 0)
                    <div class="loose-chips" id="looseChips">
                        <button type="button" class="loose-chip active" data-cat="">
                            <i class="fa-solid fa-layer-group"></i> {{ trans('language.all_loose') ?? 'All Loose' }}
                        </button>
                        @foreach ($loose_categories as $looseCat)
                            <button type="button" class="loose-chip" data-cat="{{ $looseCat->id }}">
                                <i class="fa-solid fa-tag"></i> {{ $looseCat->title }}
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Search bar --}}
                <form id="productSearchForm" action="">
                    <div class="search-bar-wrap">
                        <div id="liveSearchWrapper" style="flex:1; position:relative;">
                            <div class="search-input-group">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" class="name" id="productNameInput"
                                    placeholder="{{ trans('language.label_name') }}"
                                    name="name" autocomplete="off" spellcheck="false">
                                <button type="button" class="search-clear-btn" id="searchClearBtn" tabindex="-1">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div id="productSuggestBox" class="product-suggest-box d-none"></div>
                        </div>
                        <button type="submit" class="search-submit-btn" id="searchBtn">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span>{{ trans('language.btn_search') }}</span>
                        </button>
                    </div>
                </form>

                {{-- Listing header --}}
                <div class="listing-header">
                    <p class="listing-count" id="listingCount">&nbsp;</p>
                    <div class="view-toggle" id="viewToggle">
                        <button class="view-btn active" id="gridViewBtn" title="Grid view">
                            <i class="fa-solid fa-grid-2"></i>
                        </button>
                        <button class="view-btn" id="listViewBtn" title="List view">
                            <i class="fa-solid fa-list"></i>
                        </button>
                    </div>
                </div>

                {{-- Products grid --}}
                <div id="productListing">
                    {{-- Skeleton while loading --}}
                    <div class="skeleton-grid" id="skeletonLoader">
                        @for ($s = 0; $s < 6; $s++)
                            <div class="skeleton-card">
                                <div class="sk-img"></div>
                                <div class="sk-body">
                                    <div class="sk-line w-75"></div>
                                    <div class="sk-line w-50"></div>
                                    <div class="sk-line w-full" style="margin-top:12px;"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Infinite scroll --}}
                <div class="infinite-status" id="infiniteStatus" style="display:none;">
                    <div class="infinite-spinner"></div>
                    <span>{{ trans('language.loading_more') }}</span>
                </div>
                <div id="infiniteSentinel" style="height:1px;"></div>
                <div class="infinite-end" id="infiniteEnd" style="display:none;">
                    {{ trans('language.end_of_list') }}
                </div>

            </div>{{-- /listing-col --}}
        </div>{{-- /products-layout --}}
    </div>{{-- /container --}}

</div>{{-- /products-page --}}

{{-- ================================================================
     Mobile Filter Drawer
================================================================ --}}
<div class="filter-overlay" id="filterOverlay"></div>

<div class="filter-drawer" id="filterDrawer" role="dialog" aria-modal="true" aria-label="{{ trans('language.filter') }}">
    <div class="drawer-handle"></div>
    <div class="drawer-header">
        <h2 class="drawer-title"><i class="fa-solid fa-sliders"></i> {{ trans('language.filter') }}</h2>
        <button class="drawer-close" id="closeFilterDrawer" type="button" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="drawer-body">
        <div class="accordion" id="drawerAccordion">

            {{-- Categories --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ isset($root_category) ? '' : 'collapsed' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mob-cat" aria-expanded="{{ isset($root_category) ? 'true' : 'false' }}">
                        {{ trans('language.categories') }}
                    </button>
                </h2>
                <div id="mob-cat" class="accordion-collapse collapse {{ isset($root_category) ? 'show' : '' }}">
                    <div class="accordion-body">
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input category_for_filter" type="checkbox"
                                    value="{{ $category->id }}" id="mcat_{{ $category->id }}"
                                    @if (isset($root_category->id) && $root_category->id == $category->id) checked @endif
                                    name="category" data-label="{{ $category->title }}">
                                <label class="form-check-label" for="mcat_{{ $category->id }}">
                                    {{ $category->title }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Brands --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ isset($current_brand) ? '' : 'collapsed' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mob-brand" aria-expanded="{{ isset($current_brand) ? 'true' : 'false' }}">
                        {{ trans('language.brands') }}
                    </button>
                </h2>
                <div id="mob-brand" class="accordion-collapse collapse {{ isset($current_brand) ? 'show' : '' }}">
                    <div class="accordion-body">
                        @foreach ($brands as $brand)
                            <div class="form-check">
                                <input class="form-check-input brands_for_filter" type="checkbox"
                                    @if (isset($current_brand->id) && $current_brand->id == $brand->id) checked @endif
                                    value="{{ $brand->id }}" id="mbrand_{{ $brand->id }}"
                                    name="brand" data-label="{{ $brand->title }}">
                                <label class="form-check-label" for="mbrand_{{ $brand->id }}">
                                    {{ $brand->title }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Dynamic attribute filters --}}
            @foreach ($filter_attributes as $attributes)
                @php $mobAttrId = 'mob-attr-' . trim(str_replace(' ', '', $attributes->attribute_name)); @endphp
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#{{ $mobAttrId }}" aria-expanded="false">
                            {{ $attributes->attribute_name }}
                        </button>
                    </h2>
                    <div id="{{ $mobAttrId }}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @foreach ($attributes->attributes_values as $value)
                                @php $mobValId = 'mval-' . trim(str_replace(' ', '', $value->value)); @endphp
                                <div class="form-check">
                                    <input class="form-check-input attributes_for_filter" type="checkbox"
                                        value="{{ $value->value }}" id="{{ $mobValId }}"
                                        data-label="{{ $value->value }}">
                                    <label class="form-check-label" for="{{ $mobValId }}">
                                        {{ $value->value }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    <div class="drawer-footer">
        <button type="button" class="drawer-reset-btn" id="drawerResetBtn">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </button>
        <button type="button" class="drawer-apply-btn" id="drawerApplyBtn">
            {{ trans('language.btn_search') }} <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
</div>

@push('footer')
<script>
(function ($) {
    'use strict';

    /* ============================================================
       State
    ============================================================ */
    let current_category = "{{ optional($current_category)->id ?? '' }}";
    let isListView        = false;

    // Infinite scroll + mode state
    const listState = {
        mode:        'all',      // 'all' | 'loose'
        looseCat:    '',         // selected loose category id ('' = all loose)
        page:        1,
        lastPage:    1,
        total:       0,
        loading:     false
    };

    const SEARCH_URL  = "{{ route('search.products') }}";
    const SUGGEST_URL = "{{ route('product.suggest') }}";
    const NO_RESULT   = "{{ trans('language.no_product_found') }}";
    const STATE_KEY   = 'nimi_products_state';

    /* ============================================================
       Helpers
    ============================================================ */
    function escapeHtml(str) {
        return String(str ?? '').replace(/[&<>"']/g, s =>
            ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;' }[s])
        );
    }

    function highlightMatch(text, q) {
        if (!text) return '';
        const idx = text.toLowerCase().indexOf(q.toLowerCase());
        if (idx === -1) return escapeHtml(text);
        return escapeHtml(text.slice(0, idx)) +
            '<mark>' + escapeHtml(text.slice(idx, idx + q.length)) + '</mark>' +
            escapeHtml(text.slice(idx + q.length));
    }

    /* ============================================================
       Active chips (mobile)
    ============================================================ */
    function updateFilterUI() {
        const checkedInputs = $('.category_for_filter:checked, .brands_for_filter:checked, .attributes_for_filter:checked');
        const count         = checkedInputs.length;
        const $bar          = $('#mobileFilterBar');
        const $btn          = $('#openFilterDrawer');
        const $chips        = $('#activeChips');

        // badge dot
        $btn.toggleClass('has-filters', count > 0);

        // chips
        $chips.empty();
        if (count > 0) {
            $chips.addClass('has-chips');
            checkedInputs.each(function () {
                const label = $(this).data('label') || $(this).val();
                const val   = $(this).val();
                const cls   = $(this).hasClass('category_for_filter') ? 'category_for_filter'
                            : $(this).hasClass('brands_for_filter')   ? 'brands_for_filter'
                            : 'attributes_for_filter';

                $chips.append(
                    $('<span class="filter-chip" data-val="' + escapeHtml(val) + '" data-class="' + cls + '">' +
                        escapeHtml(label) +
                        ' <i class="fa-solid fa-xmark"></i></span>')
                );
            });
            $chips.append(
                $('<span class="filter-chip" style="background:rgba(248,86,6,.08);border-color:rgba(248,86,6,.3);" id="clearAllChips">' +
                    '<i class="fa-solid fa-rotate-left"></i> Clear all</span>')
            );
        } else {
            $chips.removeClass('has-chips');
        }
    }

    $(document).on('click', '.filter-chip', function () {
        const val = $(this).data('val');
        const cls = $(this).data('class');
        if (!val) return; // clear-all handled separately

        $('.' + cls + '[value="' + val.replace(/"/g, '\\"') + '"]').prop('checked', false);
        current_category = '';
        updateFilterUI();
        reloadListing();
    });

    $(document).on('click', '#clearAllChips', function () {
        resetFilters();
    });

    /* ============================================================
       Reset filters
    ============================================================ */
    function resetFilters() {
        $('.category_for_filter, .brands_for_filter, .attributes_for_filter').prop('checked', false);
        $('#productNameInput').val('').trigger('input');
        current_category = '';
        updateFilterUI();
        reloadListing();
    }

    $('#desktopResetBtn').on('click', function (e) { e.preventDefault(); resetFilters(); });
    $('#drawerResetBtn').on('click', function () { resetFilters(); closeDrawer(); });

    /* ============================================================
       Mobile drawer
    ============================================================ */
    function openDrawer() {
        $('#filterOverlay, #filterDrawer').addClass('open');
        $('body').css('overflow', 'hidden');
    }

    function closeDrawer() {
        $('#filterOverlay, #filterDrawer').removeClass('open');
        $('body').css('overflow', '');
    }

    $('#openFilterDrawer').on('click', openDrawer);
    $('#closeFilterDrawer, #filterOverlay').on('click', closeDrawer);

    $('#drawerApplyBtn').on('click', function () {
        current_category = '';
        updateFilterUI();
        closeDrawer();
        reloadListing();
    });

    // Swipe down to close drawer
    (function () {
        let startY = 0;
        const drawer = document.getElementById('filterDrawer');
        if (!drawer) return;

        drawer.addEventListener('touchstart', function (e) {
            startY = e.touches[0].clientY;
        }, { passive: true });

        drawer.addEventListener('touchend', function (e) {
            const dy = e.changedTouches[0].clientY - startY;
            if (dy > 80 && drawer.scrollTop === 0) closeDrawer();
        }, { passive: true });
    })();

    /* ============================================================
       View toggle (grid / list)
    ============================================================ */
    $('#gridViewBtn').on('click', function () {
        isListView = false;
        $(this).addClass('active');
        $('#listViewBtn').removeClass('active');
        applyViewMode();
    });

    $('#listViewBtn').on('click', function () {
        isListView = true;
        $(this).addClass('active');
        $('#gridViewBtn').removeClass('active');
        applyViewMode();
    });

    function applyViewMode() {
        const $listing = $('#productListing');
        if (isListView) {
            $listing.find('.row.g-2').addClass('list-view-grid');
            $listing.find('.col-6, .col-md-4, .col-lg-3').each(function () {
                $(this).removeClass('col-6 col-md-4 col-lg-3').addClass('col-12');
            });
            $listing.find('.product-image').css('height', '120px');
            $listing.find('.card').css('flex-direction', 'row');
        } else {
            $listing.find('.row.g-2').removeClass('list-view-grid');
            $listing.find('.col-12').each(function () {
                // Only revert cards that were grid-view cards (check if they have product-card inside)
                if ($(this).find('.product-card').length) {
                    $(this).removeClass('col-12').addClass('col-6 col-md-4 col-lg-3');
                }
            });
            $listing.find('.product-image').css('height', '');
            $listing.find('.card').css('flex-direction', '');
        }
    }

    /* ============================================================
       Build FormData for filters
    ============================================================ */
    function buildFormData() {
        const fd = new FormData();

        fd.set('name', $('#productNameInput').val().trim());
        fd.set('loose', listState.mode === 'loose' ? 1 : 0);

        // In loose mode the category comes from the loose chip (outside the filter)
        if (listState.mode === 'loose') {
            if (listState.looseCat) fd.append('category_for_filter[]', listState.looseCat);
        } else {
            $('.brands_for_filter:checked').each(function () {
                fd.append('brands_for_filter[]', $(this).val());
            });

            $('.category_for_filter:checked').each(function () {
                fd.append('category_for_filter[]', $(this).val());
            });

            $('.attributes_for_filter:checked').each(function () {
                fd.append('attributes_for_filter[]', $(this).val());
            });
        }

        fd.append('current_category', current_category);
        return fd;
    }

    /* ============================================================
       Infinite scroll fetch engine
       fetchPage(page, { append }) — appends or replaces the grid
    ============================================================ */
    function fetchPage(page, append) {
        if (listState.loading) return Promise.resolve();
        if (append && page > listState.lastPage) return Promise.resolve();

        listState.loading = true;

        if (!append) {
            $('#productListing').addClass('listing-loading');
        } else {
            $('#infiniteStatus').show();
            $('#infiniteEnd').hide();
        }

        const fd = buildFormData();
        fd.set('page', page);

        return $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url:         SEARCH_URL,
            type:        'POST',
            data:        fd,
            processData: false,
            contentType: false,
            dataType:    'json'
        }).then(function (response) {
            listState.page     = response.current_page || page;
            listState.lastPage = response.last_page || 1;
            listState.total    = response.total || 0;

            $('#skeletonLoader').remove();
            $('#infiniteStatus').hide();

            if (append) {
                // Extract only the product items so <style>/<script> aren't re-injected
                const items = $($.parseHTML(response.products_html)).find('[role="listitem"]');
                const $grid = $('#productListing .products-grid');
                if ($grid.length) $grid.append(items);
                else $('#productListing').removeClass('listing-loading').html(response.products_html);

                if (isListView) applyViewMode();
            } else {
                $('#productListing')
                    .removeClass('listing-loading')
                    .html(response.products_html);

                if (isListView) applyViewMode();
            }

            // Counts
            const shown = $('#productListing [role="listitem"]').length;
            $('#listingCount').html(
                listState.total > 0
                    ? 'Showing <strong>' + shown + '</strong> of <strong>' + listState.total + '</strong> products'
                    : '&nbsp;'
            );
            $('#heroSubtitle').text(listState.total + ' product' + (listState.total !== 1 ? 's' : '') + ' found');

            // End-of-list marker
            $('#infiniteEnd').toggle(!response.has_more_pages);

            listState.loading = false;
            maybeLoadMore();
        }).catch(function () {
            listState.loading = false;
            $('#infiniteStatus').hide();
            $('#productListing').removeClass('listing-loading');
        });
    }

    function reloadListing() {
        return fetchPage(1, false);
    }

    /* ============================================================
       Infinite scroll observer
    ============================================================ */
    const sentinelObserver = 'IntersectionObserver' in window
        ? new IntersectionObserver(function (entries) {
            if (entries.some(e => e.isIntersecting)) maybeLoadMore();
        }, { rootMargin: '600px 0px' })
        : null;

    function maybeLoadMore() {
        if (!sentinelObserver) {
            // Fallback: classic scroll listener
            return;
        }
        if (listState.loading) return;
        if (listState.page >= listState.lastPage) return;
        if ($('#infiniteEnd').is(':visible')) return;
        fetchPage(listState.page + 1, true);
    }

    if (sentinelObserver) {
        sentinelObserver.observe(document.getElementById('infiniteSentinel'));
    } else {
        $(window).on('scroll', function () {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 700) {
                maybeLoadMore();
            }
        });
    }

    /* ============================================================
       Mode switching (All / Loose) + loose chips
    ============================================================ */
    function applyMode(mode) {
        if (mode !== 'all' && !$('#modeLooseBtn').length) mode = 'all';
        if (listState.mode === mode) return;

        listState.mode     = mode;
        listState.looseCat = '';

        $('#products-page').toggleClass('loose-mode', mode === 'loose');
        $('#modeAllBtn').toggleClass('active', mode === 'all').attr('aria-selected', mode === 'all');
        $('#modeLooseBtn').toggleClass('active', mode === 'loose').attr('aria-selected', mode === 'loose');

        $('#looseChips .loose-chip').removeClass('active').first().addClass('active');

        if (mode === 'loose') {
            // Reset the regular filter so hidden checkboxes don't leak into the query
            $('.category_for_filter, .brands_for_filter, .attributes_for_filter').prop('checked', false);
            $('#productNameInput').val('');
            $('#searchClearBtn').hide();
            updateFilterUI();
        }

        reloadListing();
    }

    $('#modeAllBtn').on('click', function () { applyMode('all'); });
    $('#modeLooseBtn').on('click', function () { applyMode('loose'); });

    $(document).on('click', '.loose-chip', function () {
        if ($(this).hasClass('active')) return;
        $('#looseChips .loose-chip').removeClass('active');
        $(this).addClass('active');
        listState.looseCat = $(this).data('cat') || '';
        reloadListing();
    });

    /* ============================================================
       Scroll position + state restore on back navigation
    ============================================================ */
    function saveRestoreState() {
        try {
            sessionStorage.setItem(STATE_KEY, JSON.stringify({
                mode:     listState.mode,
                looseCat: listState.looseCat,
                page:     listState.page,
                scrollY:  window.scrollY,
                search:   $('#productNameInput').val(),
                brands:   $('.brands_for_filter:checked').map(function () { return this.value; }).get(),
                cats:     $('.category_for_filter:checked').map(function () { return this.value; }).get(),
                attrs:    $('.attributes_for_filter:checked').map(function () { return this.value; }).get()
            }));
        } catch (e) { /* storage unavailable */ }
    }

    function readRestoreState() {
        try {
            const raw = sessionStorage.getItem(STATE_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (e) { return null; }
    }

    // Save state whenever we leave the page (works with bfcache too)
    $(window).on('pagehide', saveRestoreState);

    // bfcache: DOM is intact — just jump back to the old position
    $(window).on('pageshow', function (e) {
        if (!e.originalEvent.persisted) return;
        const saved = readRestoreState();
        if (saved && typeof saved.scrollY === 'number') {
            window.scrollTo(0, saved.scrollY);
        }
    });

    // Fresh load: rebuild the previous state (filters + pages), then restore scroll
    function restoreFromState(saved) {
        if (!saved) { reloadListing(); return; }

        // Mode + chips
        if (saved.mode === 'loose' && $('#modeLooseBtn').length) {
            listState.mode = 'loose';
            $('#products-page').addClass('loose-mode');
            $('#modeAllBtn').removeClass('active').attr('aria-selected', 'false');
            $('#modeLooseBtn').addClass('active').attr('aria-selected', 'true');

            if (saved.looseCat) {
                const $chip = $('#looseChips .loose-chip[data-cat="' + saved.looseCat + '"]');
                if ($chip.length) {
                    $('#looseChips .loose-chip').removeClass('active');
                    $chip.addClass('active');
                    listState.looseCat = saved.looseCat;
                }
            }
        }

        // Filters (only meaningful in "all" mode)
        if (saved.mode !== 'loose') {
            (saved.brands || []).forEach(v => $('.brands_for_filter[value="' + v + '"]').prop('checked', true));
            (saved.cats || []).forEach(v => $('.category_for_filter[value="' + v + '"]').prop('checked', true));
            (saved.attrs || []).forEach(v => $('.attributes_for_filter[value="' + v + '"]').prop('checked', true));
        }
        if (saved.search) {
            $('#productNameInput').val(saved.search);
            $('#searchClearBtn').show();
        }

        updateFilterUI();

        // Load pages 1..N sequentially so the user lands on the same spot
        const targetPage = Math.max(1, saved.page || 1);
        let chain = fetchPage(1, false);
        for (let p = 2; p <= targetPage; p++) {
            chain = chain.then(function () {
                if (listState.page >= listState.lastPage) return Promise.resolve();
                return fetchPage(listState.page + 1, true);
            });
        }
        chain.then(function () {
            if (typeof saved.scrollY === 'number') {
                window.scrollTo(0, saved.scrollY);
            }
        });
    }

    /* ============================================================
       Live suggestions
    ============================================================ */
    let suggestXhr    = null;
    let suggestTimer  = null;
    let liveTimer     = null;
    let suggestCache  = {};
    let suggestItems  = [];
    let activeIndex   = -1;

    function showSuggestLoading() {
        $('#productSuggestBox')
            .removeClass('d-none')
            .html('<div class="ps-loading"><span class="spinner-border spinner-border-sm"></span> Searching…</div>');
    }

    function renderSuggestions(items, q) {
        const $box   = $('#productSuggestBox');
        suggestItems = items || [];
        activeIndex  = -1;

        if (!suggestItems.length) {
            $box.removeClass('d-none')
                .html('<div class="ps-empty"><i class="fa-solid fa-box-open" style="font-size:22px;display:block;margin:0 auto 8px;"></i>' + escapeHtml(NO_RESULT) + '</div>');
            return;
        }

        let html = '';
        suggestItems.forEach(function (item) {
            const img = item.thumbnail
                ? '<img src="' + escapeHtml(item.thumbnail) + '" alt="" loading="lazy">'
                : '<i class="fa-solid fa-box-open"></i>';

            html += '<a class="ps-item" href="' + escapeHtml(item.url) + '">' +
                '<div class="ps-thumb">' + img + '</div>' +
                '<div class="ps-info">' +
                    '<div class="ps-name">' + highlightMatch(item.name, q) + '</div>' +
                    '<div class="ps-meta">' +
                        (item.code  ? '<span>Code: ' + escapeHtml(item.code)  + '</span>' : '') +
                        (item.brand ? '<span>' + escapeHtml(item.brand) + '</span>' : '') +
                    '</div>' +
                '</div>' +
                (item.price ? '<div class="ps-price">৳' + escapeHtml(item.price) + '</div>' : '') +
            '</a>';
        });

        html += '<div class="ps-footer">↑↓ navigate &nbsp;·&nbsp; Enter to open &nbsp;·&nbsp; Esc to close</div>';
        $box.removeClass('d-none').html(html);
    }

    function fetchSuggestions(q) {
        if (suggestXhr && suggestXhr.readyState !== 4) suggestXhr.abort();
        showSuggestLoading();

        suggestXhr = $.get(SUGGEST_URL, { q }, null, 'json')
            .done(function (res) {
                suggestCache[q] = res.suggestions;
                renderSuggestions(res.suggestions, q);
            })
            .fail(function (xhr) {
                if (xhr.statusText !== 'abort') $('#productSuggestBox').addClass('d-none');
            });
    }

    // Clear button visibility
    $('#productNameInput').on('input', function () {
        const hasVal = $(this).val().length > 0;
        $('#searchClearBtn').toggle(hasVal);
    });

    $('#searchClearBtn').on('click', function () {
        $('#productNameInput').val('').trigger('input').focus();
        $('#productSuggestBox').addClass('d-none');
        current_category = '';
        clearTimeout(liveTimer);
        liveTimer = setTimeout(function () { reloadListing(); }, 200);
    });

    $('#productNameInput').on('input', function () {
        const q = $(this).val().trim();
        clearTimeout(suggestTimer);
        clearTimeout(liveTimer);

        if (q.length < 1) {
            $('#productSuggestBox').addClass('d-none').empty();
            suggestItems   = [];
            current_category = '';
            liveTimer = setTimeout(function () { reloadListing(); }, 300);
            return;
        }

        suggestTimer = setTimeout(function () {
            if (suggestCache[q]) renderSuggestions(suggestCache[q], q);
            else fetchSuggestions(q);
        }, suggestCache[q] ? 0 : 180);

        current_category = '';
        liveTimer = setTimeout(function () { reloadListing(); }, 480);
    });

    $('#productNameInput').on('focus', function () {
        const q = $(this).val().trim();
        if (q.length >= 1) {
            if (suggestCache[q]) renderSuggestions(suggestCache[q], q);
            else fetchSuggestions(q);
        }
    });

    $('#productNameInput').on('keydown', function (e) {
        const $box = $('#productSuggestBox');
        if ($box.hasClass('d-none') || !suggestItems.length) return;

        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = e.key === 'ArrowDown'
                ? (activeIndex + 1) % suggestItems.length
                : (activeIndex - 1 + suggestItems.length) % suggestItems.length;

            const $items = $box.find('.ps-item').removeClass('active');
            $items.eq(activeIndex).addClass('active');
            $box.scrollTop($items.eq(activeIndex).position().top + $box.scrollTop() - 80);
        } else if (e.key === 'Enter' && activeIndex >= 0) {
            e.preventDefault();
            window.location.href = suggestItems[activeIndex].url;
        } else if (e.key === 'Escape') {
            $box.addClass('d-none');
            activeIndex = -1;
        }
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#liveSearchWrapper').length) {
            $('#productSuggestBox').addClass('d-none');
        }
    });

    /* ============================================================
       Search form submit
    ============================================================ */
    $('#productSearchForm').on('submit', function (e) {
        e.preventDefault();
        $('#productSuggestBox').addClass('d-none');
        current_category = '';
        reloadListing();
    });

    /* ============================================================
       Filter change listeners
    ============================================================ */
    $(document).on('change', '.brands_for_filter, .category_for_filter, .attributes_for_filter', function () {
        if (listState.mode === 'loose') return; // filter is hidden in loose mode
        current_category = '';
        updateFilterUI();
        reloadListing();
    });

    /* ============================================================
       Init
    ============================================================ */
    updateFilterUI();

    // Initial load — only restore previous state on real back navigation,
    // so arriving fresh (e.g. from the home menu) always starts at the top.
    (function () {
        let navType = '';
        if (window.performance && performance.getEntriesByType) {
            const entry = performance.getEntriesByType('navigation')[0];
            navType = entry ? entry.type : '';
        } else if (window.performance && performance.navigation) {
            navType = performance.navigation.type === 2 ? 'back_forward' : '';
        }

        restoreFromState(navType === 'back_forward' ? readRestoreState() : null);
    })();

})(jQuery);
</script>
@endpush
@endsection
