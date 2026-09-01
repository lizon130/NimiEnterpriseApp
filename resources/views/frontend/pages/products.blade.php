@extends('frontend.layout.app')

@section('content')

@php
    $categories = $categories ?? collect();
    $brands = $brands ?? collect();
    $filter_attributes = $filter_attributes ?? collect();
    $loose_categories = $loose_categories ?? collect();
    $current_category = $current_category ?? null;
    $root_category = $root_category ?? null;
    $current_brand = $current_brand ?? null;
@endphp

<style>
    :root {
        --pr: #f85606;
        --pr-dk: #d94a04;
        --pr-lt: #fff3ec;
        --pr-grd: linear-gradient(135deg, #f85606, #ff8a00);
        --dark: #0f172a;
        --text: #374151;
        --muted: #6b7280;
        --border: #e5e7eb;
        --card: #ffffff;
        --bg: #f1f5f9;
        --sidebar-w: 280px;
        --radius-xl: 20px;
        --radius-lg: 14px;
        --radius-md: 10px;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, .06);
        --shadow-md: 0 8px 28px rgba(0, 0, 0, .10);
        --shadow-lg: 0 16px 48px rgba(0, 0, 0, .12);
        --transition: .22s ease;
    }

    #products-page {
        min-height: 100vh;
        padding-bottom: 60px;
        background: var(--bg);
    }

    .page-hero {
        position: relative;
        overflow: hidden;
        padding: 28px 0 22px;
        background: var(--pr-grd);
    }

    .page-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        opacity: .3;
        background-image:
            radial-gradient(circle at 10% 20%, rgba(255,255,255,.24) 0 1px, transparent 1px),
            radial-gradient(circle at 80% 70%, rgba(255,255,255,.18) 0 1px, transparent 1px);
        background-size: 38px 38px, 52px 52px;
    }

    .page-hero .container {
        position: relative;
    }

    .breadcrumb-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 14px;
        color: rgba(255,255,255,.84);
        font-size: 13px;
    }

    .breadcrumb-row a {
        color: rgba(255,255,255,.95);
        text-decoration: none;
        font-weight: 600;
    }

    .breadcrumb-row .sep {
        opacity: .6;
        font-size: 10px;
    }

    .hero-title {
        margin: 0 0 4px;
        color: #fff;
        font-size: clamp(26px, 4vw, 38px);
        font-weight: 800;
        letter-spacing: -.5px;
    }

    .hero-subtitle {
        margin: 0;
        color: rgba(255,255,255,.8);
        font-size: 14px;
    }

    .mode-switch-wrap {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .mode-switch {
        display: inline-flex;
        gap: 4px;
        padding: 5px;
        border: 1.5px solid var(--border);
        border-radius: 999px;
        background: var(--card);
        box-shadow: var(--shadow-md);
    }

    .mode-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 20px;
        border: none;
        border-radius: 999px;
        background: transparent;
        color: var(--muted);
        font-size: 13px;
        font-weight: 750;
        cursor: pointer;
        transition: var(--transition);
    }

    .mode-btn.active {
        background: var(--pr-grd);
        color: #fff;
        box-shadow: 0 5px 15px rgba(248, 86, 6, .25);
    }

    .products-layout {
        display: grid;
        grid-template-columns: var(--sidebar-w) minmax(0, 1fr);
        gap: 20px;
        align-items: start;
        margin-top: 24px;
    }

    .filter-panel {
        position: sticky;
        top: 82px;
        overflow: hidden;
        border-radius: var(--radius-xl);
        background: var(--card);
        box-shadow: var(--shadow-md);
    }

    .filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 16px 18px;
        background: var(--pr-grd);
    }

    .filter-header-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        color: #fff;
        font-size: 15px;
        font-weight: 800;
    }

    .filter-reset-btn {
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 8px;
        padding: 5px 9px;
        background: rgba(255,255,255,.17);
        color: #fff;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
    }

    .filter-body {
        padding: 12px;
    }

    .filt-accordion .accordion-item,
    .drawer-filter-accordion .accordion-item {
        overflow: hidden;
        margin-bottom: 8px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-md) !important;
        background: var(--card);
    }

    .filt-accordion .accordion-button,
    .drawer-filter-accordion .accordion-button {
        gap: 8px;
        padding: 12px 14px;
        background: var(--card);
        color: var(--dark);
        box-shadow: none !important;
        font-size: 13px;
        font-weight: 750;
    }

    .filt-accordion .accordion-button:not(.collapsed),
    .drawer-filter-accordion .accordion-button:not(.collapsed) {
        background: var(--pr-lt);
        color: var(--pr);
    }

    .filt-accordion .accordion-body,
    .drawer-filter-accordion .accordion-body {
        max-height: 250px;
        overflow-y: auto;
        padding: 9px 14px 13px;
    }

    .filter-check {
        padding: 7px 0 7px 1.6em;
        margin: 0;
    }

    .filter-check .form-check-input {
        cursor: pointer;
        border-color: #cbd5e1;
        border-radius: 5px;
    }

    .filter-check .form-check-input:checked {
        border-color: var(--pr);
        background-color: var(--pr);
    }

    .filter-check .form-check-label {
        padding-left: 3px;
        cursor: pointer;
        color: var(--text);
        font-size: 13px;
        font-weight: 500;
    }

    .listing-col {
        min-width: 0;
    }

    .loose-chips {
        display: none;
        gap: 8px;
        overflow-x: auto;
        margin-bottom: 11px;
        padding: 2px 2px 8px;
        scrollbar-width: none;
    }

    .loose-chips::-webkit-scrollbar {
        display: none;
    }

    #products-page.loose-mode .loose-chips {
        display: flex;
    }

    .loose-chip {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border: 1.5px solid var(--border);
        border-radius: 999px;
        background: var(--card);
        color: var(--text);
        font-size: 12px;
        font-weight: 750;
        cursor: pointer;
    }

    .loose-chip.active {
        border-color: transparent;
        background: var(--pr-grd);
        color: #fff;
        box-shadow: 0 4px 13px rgba(248, 86, 6, .2);
    }

    #products-page.loose-mode .desktop-sidebar {
        display: none !important;
    }

    #products-page.loose-mode .products-layout {
        grid-template-columns: minmax(0, 1fr);
    }

    .search-bar-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        padding: 14px 16px;
        border-radius: var(--radius-xl);
        background: var(--card);
        box-shadow: var(--shadow-md);
    }

    #liveSearchWrapper {
        position: relative;
        min-width: 0;
        flex: 1;
    }

    .search-input-group {
        display: flex;
        align-items: center;
        overflow: hidden;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-lg);
        background: var(--bg);
        transition: var(--transition);
    }

    .search-input-group:focus-within {
        border-color: var(--pr);
        box-shadow: 0 0 0 4px rgba(248, 86, 6, .1);
    }

    .search-icon {
        flex-shrink: 0;
        padding: 0 13px;
        color: var(--muted);
    }

    #productNameInput {
        width: 100%;
        border: 0;
        outline: 0;
        padding: 13px 8px 13px 0;
        background: transparent;
        color: var(--dark);
        font-size: 14px;
        font-weight: 550;
    }

    .search-clear-btn {
        display: none;
        flex-shrink: 0;
        border: 0;
        padding: 0 13px;
        background: transparent;
        color: var(--muted);
        cursor: pointer;
    }

    .search-submit-btn {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 46px;
        padding: 0 22px;
        border: 0;
        border-radius: var(--radius-lg);
        background: var(--pr-grd);
        color: #fff;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 5px 14px rgba(248,86,6,.2);
    }

    .product-suggest-box {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        z-index: 1060;
        max-height: 430px;
        overflow-y: auto;
        padding: 8px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: #fff;
        box-shadow: var(--shadow-lg);
    }

    .ps-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 9px 10px;
        border-radius: 10px;
        color: var(--dark);
        text-decoration: none;
    }

    .ps-item:hover,
    .ps-item.active {
        background: var(--pr-lt);
    }

    .ps-thumb {
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        overflow: hidden;
        border-radius: 9px;
        background: var(--bg);
        color: var(--muted);
    }

    .ps-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ps-info {
        min-width: 0;
        flex: 1;
    }

    .ps-name {
        overflow: hidden;
        color: var(--dark);
        font-size: 13px;
        font-weight: 750;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ps-name mark {
        border-radius: 3px;
        background: rgba(248,86,6,.12);
        color: var(--pr);
        font-weight: 850;
    }

    .ps-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 2px;
        color: var(--muted);
        font-size: 10px;
    }

    .ps-price {
        flex-shrink: 0;
        color: var(--pr);
        font-size: 13px;
        font-weight: 850;
    }

    .ps-loading,
    .ps-empty {
        padding: 18px;
        color: var(--muted);
        text-align: center;
        font-size: 12px;
        font-weight: 650;
    }

    .ps-footer {
        margin-top: 4px;
        padding: 7px 8px 3px;
        border-top: 1px solid var(--border);
        color: var(--muted);
        text-align: center;
        font-size: 10px;
    }

    .listing-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 13px;
    }

    .listing-summary {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .listing-count {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
        font-weight: 550;
    }

    .listing-count strong {
        color: var(--dark);
    }

    .sort-note {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border: 1px solid rgba(22,163,74,.18);
        border-radius: 999px;
        background: rgba(22,163,74,.07);
        color: #15803d;
        font-size: 10px;
        font-weight: 750;
    }

    .view-toggle {
        display: flex;
        gap: 4px;
        padding: 3px;
        border-radius: 10px;
        background: #e9eef5;
    }

    .view-btn {
        width: 32px;
        height: 32px;
        display: grid;
        place-items: center;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: var(--muted);
        cursor: pointer;
    }

    .view-btn.active {
        background: #fff;
        color: var(--pr);
        box-shadow: var(--shadow-sm);
    }

    #productListing {
        min-height: 280px;
        transition: opacity .16s ease;
    }

    #productListing.listing-loading {
        opacity: .45;
        pointer-events: none;
    }

    .skeleton-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .skeleton-card {
        overflow: hidden;
        border-radius: 16px;
        background: #fff;
        box-shadow: var(--shadow-sm);
    }

    .sk-img,
    .sk-line {
        background: linear-gradient(90deg, #f0f1f3 25%, #e3e6ea 50%, #f0f1f3 75%);
        background-size: 200% 100%;
        animation: shimmer 1.25s infinite;
    }

    .sk-img {
        height: 190px;
    }

    .sk-body {
        padding: 12px;
    }

    .sk-line {
        height: 11px;
        margin-bottom: 8px;
        border-radius: 6px;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .infinite-status {
        display: none;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 25px 0 8px;
        color: var(--muted);
        font-size: 12px;
        font-weight: 650;
    }

    .infinite-spinner {
        width: 31px;
        height: 31px;
        border: 3px solid var(--pr-lt);
        border-top-color: var(--pr);
        border-radius: 50%;
        animation: infSpin .7s linear infinite;
    }

    @keyframes infSpin {
        to { transform: rotate(360deg); }
    }

    .infinite-end {
        display: none;
        align-items: center;
        gap: 9px;
        padding: 25px 0 6px;
        color: var(--muted);
        font-size: 11px;
        font-weight: 650;
    }

    .infinite-end::before,
    .infinite-end::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .mobile-filter-bar {
        display: none;
        position: sticky;
        top: 0;
        z-index: 900;
        gap: 8px;
        padding: 9px 15px;
        border-bottom: 1px solid var(--border);
        background: rgba(255,255,255,.96);
        backdrop-filter: blur(8px);
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
    }

    .mob-filter-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        background: var(--bg);
        color: var(--dark);
        font-size: 12px;
        font-weight: 750;
    }

    .mob-filter-btn .badge-dot {
        display: none;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--pr);
    }

    .mob-filter-btn.has-filters .badge-dot {
        display: inline-block;
    }

    .active-chips {
        display: none;
        gap: 6px;
        flex-wrap: wrap;
        padding-top: 9px;
    }

    .active-chips.has-chips {
        display: flex;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        border: 1px solid rgba(248,86,6,.23);
        border-radius: 999px;
        background: var(--pr-lt);
        color: var(--pr);
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }

    .filter-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1040;
        background: rgba(15, 23, 42, .55);
    }

    .filter-overlay.open {
        display: block;
    }

    .filter-drawer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1050;
        max-height: 86vh;
        overflow-y: auto;
        border-radius: 24px 24px 0 0;
        background: #fff;
        transform: translateY(105%);
        transition: transform .28s cubic-bezier(.2,.8,.2,1);
        padding-bottom: env(safe-area-inset-bottom, 10px);
    }

    .filter-drawer.open {
        transform: translateY(0);
    }

    .drawer-handle {
        width: 42px;
        height: 4px;
        margin: 12px auto 0;
        border-radius: 999px;
        background: #d8dee7;
    }

    .drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
    }

    .drawer-title {
        margin: 0;
        color: var(--dark);
        font-size: 16px;
        font-weight: 800;
    }

    .drawer-close {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        border: 0;
        border-radius: 50%;
        background: var(--bg);
        color: var(--muted);
    }

    .drawer-body {
        padding: 12px 14px;
    }

    .drawer-footer {
        position: sticky;
        bottom: 0;
        display: flex;
        gap: 9px;
        padding: 12px 14px;
        border-top: 1px solid var(--border);
        background: rgba(255,255,255,.98);
    }

    .drawer-reset-btn,
    .drawer-apply-btn {
        min-height: 44px;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 800;
    }

    .drawer-reset-btn {
        padding: 0 18px;
        border: 1.5px solid var(--border);
        background: var(--bg);
        color: var(--text);
    }

    .drawer-apply-btn {
        flex: 1;
        border: 0;
        background: var(--pr-grd);
        color: #fff;
    }

    .quantity-bottom-sheet {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 11000;
    }

    .quantity-bottom-sheet.active {
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .qs-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .55);
    }

    .qs-content {
        position: relative;
        width: 100%;
        max-width: 500px;
        padding: 18px;
        border-radius: 24px 24px 0 0;
        background: #fff;
        box-shadow: 0 -15px 45px rgba(0,0,0,.15);
        animation: qsSlide .25s ease;
    }

    @keyframes qsSlide {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }

    .qs-handle {
        width: 40px;
        height: 4px;
        margin: 0 auto 14px;
        border-radius: 999px;
        background: #d9dee7;
    }

    .qs-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .qs-title {
        margin: 0;
        color: var(--dark);
        font-size: 17px;
        font-weight: 800;
    }

    .qs-close {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        border: 0;
        border-radius: 50%;
        background: var(--bg);
        color: var(--muted);
    }

    .qs-product-info {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 13px;
        border-radius: 12px;
        background: #f8fafc;
    }

    .qs-product-image {
        flex: 0 0 72px;
        width: 72px;
        height: 72px;
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: #fff;
    }

    .qs-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .qs-product-details {
        min-width: 0;
        flex: 1;
    }

    .qs-product-details h5 {
        margin: 0 0 5px;
        color: var(--dark);
        font-size: 14px;
        font-weight: 750;
    }

    .qs-price {
        margin: 0;
        color: var(--pr);
        font-size: 16px;
        font-weight: 850;
    }

    .qs-quantity-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin: 17px 0;
        padding: 14px;
        border-radius: 12px;
        background: #f8fafc;
    }

    .qs-btn-qty {
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        border: 1.5px solid var(--border);
        border-radius: 11px;
        background: #fff;
        color: var(--dark);
    }

    .qs-quantity-display {
        min-width: 70px;
        text-align: center;
        color: var(--dark);
        font-size: 22px;
        font-weight: 850;
    }

    .qs-footer {
        display: flex;
        gap: 9px;
    }

    .qs-btn-cancel,
    .qs-btn-add {
        min-height: 46px;
        border: 0;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 800;
    }

    .qs-btn-cancel {
        flex: 1;
        background: #eef2f7;
        color: var(--text);
    }

    .qs-btn-add {
        flex: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        background: var(--pr-grd);
        color: #fff;
    }

    @media (max-width: 1023px) {
        .products-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .desktop-sidebar {
            display: none;
        }

        .mobile-filter-bar {
            display: flex;
        }

        #products-page.loose-mode .mobile-filter-bar {
            display: none;
        }

        .skeleton-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .page-hero {
            padding: 22px 0 18px;
        }

        .mode-switch-wrap {
            margin-top: 14px;
        }

        .mode-btn {
            padding: 9px 14px;
            font-size: 12px;
        }

        .products-layout {
            margin-top: 15px;
        }

        .search-bar-wrap {
            gap: 7px;
            padding: 10px;
            border-radius: 14px;
        }

        .search-submit-btn {
            min-width: 44px;
            padding: 0 13px;
        }

        .search-submit-btn span {
            display: none;
        }

        .sort-note {
            width: 100%;
            justify-content: center;
        }

        .qs-footer {
            flex-direction: column;
        }

        .qs-btn-cancel,
        .qs-btn-add {
            width: 100%;
        }
    }
</style>

<div id="products-page">
    <div class="page-hero">
        <div class="container">
            <div class="breadcrumb-row">
                <a href="{{ route('home') }}">
                    <i class="fa-solid fa-house"></i>
                    {{ trans('language.home') }}
                </a>
                <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
                <span>{{ trans('language.products') }}</span>
            </div>

            <h1 class="hero-title">{{ trans('language.products') }}</h1>
            <p class="hero-subtitle" id="heroSubtitle">Loading products...</p>
        </div>
    </div>

    <div class="mobile-filter-bar" id="mobileFilterBar">
        <button type="button" class="mob-filter-btn" id="openFilterDrawer">
            <i class="fa-solid fa-sliders"></i>
            {{ trans('language.filter') }}
            <span class="badge-dot"></span>
        </button>
    </div>

    <div class="container active-chips" id="activeChips"></div>

    <div class="mode-switch-wrap">
        <div class="mode-switch">
            <button type="button" class="mode-btn active" id="modeAllBtn">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>{{ trans('language.all_products') ?? 'All Products' }}</span>
            </button>

            @if ($loose_categories->count() > 0)
                <button type="button" class="mode-btn" id="modeLooseBtn">
                    <i class="fa-solid fa-scale-balanced"></i>
                    <span>{{ trans('language.loose_products') ?? 'Loose Products' }}</span>
                </button>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="products-layout">
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
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button
                                            class="accordion-button {{ $root_category ? '' : 'collapsed' }}"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#desktopCategories">
                                            <i class="fa-solid fa-layer-group" style="color:var(--pr);"></i>
                                            {{ trans('language.categories') }}
                                        </button>
                                    </h2>

                                    <div id="desktopCategories" class="accordion-collapse collapse {{ $root_category ? 'show' : '' }}">
                                        <div class="accordion-body">
                                            @foreach ($categories as $category)
                                                <div class="form-check filter-check">
                                                    <input
                                                        class="form-check-input category_for_filter"
                                                        type="checkbox"
                                                        value="{{ $category->id }}"
                                                        id="dcat_{{ $category->id }}"
                                                        data-label="{{ $category->title }}"
                                                        @checked(optional($root_category)->id == $category->id)>
                                                    <label class="form-check-label" for="dcat_{{ $category->id }}">
                                                        {{ $category->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button
                                            class="accordion-button {{ $current_brand ? '' : 'collapsed' }}"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#desktopBrands">
                                            <i class="fa-solid fa-tag" style="color:var(--pr);"></i>
                                            {{ trans('language.brands') }}
                                        </button>
                                    </h2>

                                    <div id="desktopBrands" class="accordion-collapse collapse {{ $current_brand ? 'show' : '' }}">
                                        <div class="accordion-body">
                                            @foreach ($brands as $brand)
                                                <div class="form-check filter-check">
                                                    <input
                                                        class="form-check-input brands_for_filter"
                                                        type="checkbox"
                                                        value="{{ $brand->id }}"
                                                        id="dbrand_{{ $brand->id }}"
                                                        data-label="{{ $brand->title }}"
                                                        @checked(optional($current_brand)->id == $brand->id)>
                                                    <label class="form-check-label" for="dbrand_{{ $brand->id }}">
                                                        {{ $brand->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                @foreach ($filter_attributes as $attributes)
                                    @php
                                        $desktopAttrId = 'desktop_attr_' . $loop->index;
                                    @endphp

                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button
                                                class="accordion-button collapsed"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{ $desktopAttrId }}">
                                                <i class="fa-solid fa-circle-dot" style="color:var(--pr);"></i>
                                                {{ $attributes->attribute_name }}
                                            </button>
                                        </h2>

                                        <div id="{{ $desktopAttrId }}" class="accordion-collapse collapse">
                                            <div class="accordion-body">
                                                @foreach ($attributes->attributes_values as $value)
                                                    @php
                                                        $desktopValId = 'dattr_' . $loop->parent->index . '_' . $loop->index;
                                                    @endphp

                                                    <div class="form-check filter-check">
                                                        <input
                                                            class="form-check-input attributes_for_filter"
                                                            type="checkbox"
                                                            value="{{ $value->value }}"
                                                            id="{{ $desktopValId }}"
                                                            data-label="{{ $value->value }}">
                                                        <label class="form-check-label" for="{{ $desktopValId }}">
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

            <div class="listing-col">
                @if ($loose_categories->count() > 0)
                    <div class="loose-chips" id="looseChips">
                        <button type="button" class="loose-chip active" data-cat="">
                            <i class="fa-solid fa-layer-group"></i>
                            {{ trans('language.all_loose') ?? 'All Loose' }}
                        </button>

                        @foreach ($loose_categories as $looseCat)
                            <button type="button" class="loose-chip" data-cat="{{ $looseCat->id }}">
                                <i class="fa-solid fa-tag"></i>
                                {{ $looseCat->title }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <form id="productSearchForm" action="">
                    <div class="search-bar-wrap">
                        <div id="liveSearchWrapper">
                            <div class="search-input-group">
                                <span class="search-icon">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>

                                <input
                                    type="text"
                                    id="productNameInput"
                                    name="name"
                                    autocomplete="off"
                                    spellcheck="false"
                                    placeholder="Search product, code or brand...">

                                <button type="button" class="search-clear-btn" id="searchClearBtn">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <div id="productSuggestBox" class="product-suggest-box d-none"></div>
                        </div>

                        <button type="submit" class="search-submit-btn">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span>{{ trans('language.btn_search') }}</span>
                        </button>
                    </div>
                </form>

                <div class="listing-header">
                    <div class="listing-summary">
                        <p class="listing-count" id="listingCount">&nbsp;</p>
                    </div>

                    <div class="view-toggle">
                        <button type="button" class="view-btn active" id="gridViewBtn" title="Grid view">
                            <i class="fa-solid fa-border-all"></i>
                        </button>
                        <button type="button" class="view-btn" id="listViewBtn" title="List view">
                            <i class="fa-solid fa-list"></i>
                        </button>
                    </div>
                </div>

                <div id="productListing">
                    <div class="skeleton-grid" id="skeletonLoader">
                        @for ($i = 0; $i < 6; $i++)
                            <div class="skeleton-card">
                                <div class="sk-img"></div>
                                <div class="sk-body">
                                    <div class="sk-line" style="width:78%;"></div>
                                    <div class="sk-line" style="width:52%;"></div>
                                    <div class="sk-line" style="width:100%;height:35px;margin-top:12px;"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="infinite-status" id="infiniteStatus">
                    <div class="infinite-spinner"></div>
                    <span>{{ trans('language.loading_more') ?? 'Loading more products...' }}</span>
                </div>

                <div id="infiniteSentinel" style="height:1px;"></div>

                <div class="infinite-end" id="infiniteEnd">
                    <span id="infiniteEndText">You've reached the end</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="filter-overlay" id="filterOverlay"></div>

<div class="filter-drawer" id="filterDrawer">
    <div class="drawer-handle"></div>

    <div class="drawer-header">
        <h2 class="drawer-title">
            <i class="fa-solid fa-sliders" style="color:var(--pr);"></i>
            {{ trans('language.filter') }}
        </h2>

        <button type="button" class="drawer-close" id="closeFilterDrawer">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="drawer-body">
        <div class="accordion drawer-filter-accordion" id="drawerAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mobileCategories">
                        {{ trans('language.categories') }}
                    </button>
                </h2>

                <div id="mobileCategories" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        @foreach ($categories as $category)
                            <div class="form-check filter-check">
                                <input
                                    class="form-check-input category_for_filter"
                                    type="checkbox"
                                    value="{{ $category->id }}"
                                    id="mcat_{{ $category->id }}"
                                    data-label="{{ $category->title }}"
                                    @checked(optional($root_category)->id == $category->id)>
                                <label class="form-check-label" for="mcat_{{ $category->id }}">
                                    {{ $category->title }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mobileBrands">
                        {{ trans('language.brands') }}
                    </button>
                </h2>

                <div id="mobileBrands" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        @foreach ($brands as $brand)
                            <div class="form-check filter-check">
                                <input
                                    class="form-check-input brands_for_filter"
                                    type="checkbox"
                                    value="{{ $brand->id }}"
                                    id="mbrand_{{ $brand->id }}"
                                    data-label="{{ $brand->title }}"
                                    @checked(optional($current_brand)->id == $brand->id)>
                                <label class="form-check-label" for="mbrand_{{ $brand->id }}">
                                    {{ $brand->title }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @foreach ($filter_attributes as $attributes)
                @php
                    $mobileAttrId = 'mobile_attr_' . $loop->index;
                @endphp

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $mobileAttrId }}">
                            {{ $attributes->attribute_name }}
                        </button>
                    </h2>

                    <div id="{{ $mobileAttrId }}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @foreach ($attributes->attributes_values as $value)
                                @php
                                    $mobileValId = 'mattr_' . $loop->parent->index . '_' . $loop->index;
                                @endphp

                                <div class="form-check filter-check">
                                    <input
                                        class="form-check-input attributes_for_filter"
                                        type="checkbox"
                                        value="{{ $value->value }}"
                                        id="{{ $mobileValId }}"
                                        data-label="{{ $value->value }}">
                                    <label class="form-check-label" for="{{ $mobileValId }}">
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
            Reset
        </button>

        <button type="button" class="drawer-apply-btn" id="drawerApplyBtn">
            Apply Filters
        </button>
    </div>
</div>

<div id="quantityModal" class="quantity-bottom-sheet">
    <div class="qs-backdrop" onclick="closeQuantityModal()"></div>

    <div class="qs-content">
        <div class="qs-handle"></div>

        <div class="qs-header">
            <h4 class="qs-title">Select Quantity</h4>
            <button type="button" class="qs-close" onclick="closeQuantityModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="qs-product-info">
            <div class="qs-product-image">
                <img id="qsProductImage" src="" alt="Product">
            </div>

            <div class="qs-product-details">
                <h5 id="qsProductName">Product</h5>
                <p class="qs-price" id="qsProductPrice">৳0</p>
            </div>
        </div>

        <div class="qs-quantity-wrapper">
            <button type="button" class="qs-btn-qty" onclick="updateQuantity(-1)">
                <i class="fa-solid fa-minus"></i>
            </button>

            <div class="qs-quantity-display">
                <span id="qsQuantity">1</span>
            </div>

            <button type="button" class="qs-btn-qty" onclick="updateQuantity(1)">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <div class="qs-footer">
            <button type="button" class="qs-btn-cancel" onclick="closeQuantityModal()">
                Cancel
            </button>

            <button type="button" class="qs-btn-add" onclick="confirmAddToCart()">
                <i class="fa-solid fa-cart-shopping"></i>
                Add to Cart
            </button>
        </div>
    </div>
</div>

@push('footer')
<script>
(function ($) {
    'use strict';

    const SEARCH_URL = @json(route('search.products'));
    const SUGGEST_URL = @json(route('product.suggest'));
    const NO_RESULT = @json(trans('language.no_product_found'));
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    const STATE_KEY = 'nimi_products_infinite_state_v2';

    let currentCategory = @json(optional($current_category)->id ?? '');
    let isListView = false;
    let listingXhr = null;
    let listingRequestVersion = 0;
    let sentinelVisible = false;

    const listState = {
        mode: 'all',
        looseCat: '',
        page: 1,
        lastPage: 1,
        total: 0,
        loading: false
    };

    let suggestXhr = null;
    let suggestTimer = null;
    let liveTimer = null;
    let suggestCache = {};
    let suggestItems = [];
    let activeSuggestIndex = -1;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function highlightMatch(text, query) {
        text = String(text ?? '');
        query = String(query ?? '');

        if (!query) return escapeHtml(text);

        const index = text.toLowerCase().indexOf(query.toLowerCase());
        if (index === -1) return escapeHtml(text);

        return escapeHtml(text.slice(0, index)) +
            '<mark>' + escapeHtml(text.slice(index, index + query.length)) + '</mark>' +
            escapeHtml(text.slice(index + query.length));
    }

    function uniqueChecked(selector) {
        const values = [];
        const seen = new Set();

        $(selector + ':checked').each(function () {
            const value = String($(this).val());
            if (!seen.has(value)) {
                seen.add(value);
                values.push(value);
            }
        });

        return values;
    }

    function syncDuplicateFilter($source) {
        const value = String($source.val());
        const checked = $source.prop('checked');
        const className = $source.hasClass('category_for_filter')
            ? 'category_for_filter'
            : $source.hasClass('brands_for_filter')
                ? 'brands_for_filter'
                : 'attributes_for_filter';

        $('.' + className).each(function () {
            if (String($(this).val()) === value) {
                $(this).prop('checked', checked);
            }
        });
    }

    function updateFilterUI() {
        const chips = [];
        const seen = new Set();

        $('.category_for_filter:checked, .brands_for_filter:checked, .attributes_for_filter:checked').each(function () {
            const cls = $(this).hasClass('category_for_filter')
                ? 'category_for_filter'
                : $(this).hasClass('brands_for_filter')
                    ? 'brands_for_filter'
                    : 'attributes_for_filter';

            const value = String($(this).val());
            const key = cls + '|' + value;

            if (seen.has(key)) return;
            seen.add(key);

            chips.push({
                cls: cls,
                value: value,
                label: $(this).data('label') || value
            });
        });

        $('#openFilterDrawer').toggleClass('has-filters', chips.length > 0);

        const $wrap = $('#activeChips').empty();

        if (!chips.length) {
            $wrap.removeClass('has-chips');
            return;
        }

        $wrap.addClass('has-chips');

        chips.forEach(function (chip) {
            $wrap.append(
                '<span class="filter-chip" data-filter-class="' + escapeHtml(chip.cls) + '" data-filter-value="' + escapeHtml(chip.value) + '">' +
                    escapeHtml(chip.label) +
                    ' <i class="fa-solid fa-xmark"></i>' +
                '</span>'
            );
        });

        $wrap.append(
            '<span class="filter-chip" id="clearAllChips">' +
                '<i class="fa-solid fa-rotate-left"></i> Clear all' +
            '</span>'
        );
    }

    function buildFormData() {
        const fd = new FormData();

        fd.set('name', $('#productNameInput').val().trim());
        fd.set('loose', listState.mode === 'loose' ? 1 : 0);

        if (listState.mode === 'loose') {
            if (listState.looseCat) {
                fd.append('category_for_filter[]', listState.looseCat);
            }
        } else {
            uniqueChecked('.brands_for_filter').forEach(function (value) {
                fd.append('brands_for_filter[]', value);
            });

            uniqueChecked('.category_for_filter').forEach(function (value) {
                fd.append('category_for_filter[]', value);
            });

            uniqueChecked('.attributes_for_filter').forEach(function (value) {
                fd.append('attributes_for_filter[]', value);
            });

            if (currentCategory) {
                fd.append('current_category', currentCategory);
            }
        }

        return fd;
    }

    function applyViewMode() {
        $('#productListing').toggleClass('list-view-active', isListView);
    }

    function updateListingMeta(response) {
        const shown = $('#productListing [role="listitem"]').length;

        if (listState.total > 0) {
            $('#listingCount').html(
                'Showing <strong>' + shown + '</strong> of <strong>' + listState.total + '</strong> products'
            );
        } else {
            $('#listingCount').html('<strong>0</strong> products');
        }

        $('#heroSubtitle').text(
            listState.total + ' product' + (listState.total === 1 ? '' : 's') + ' found'
        );

        const hasMore = Boolean(response.has_more_pages);
        $('#infiniteEnd').toggle(!hasMore && listState.total > 0);

        if (!hasMore && listState.total > 0) {
            $('#infiniteEndText').text(
                "You've reached the end — " + listState.total + ' products found'
            );
        }
    }

    function fetchPage(page, append) {
        if (append && page > listState.lastPage) {
            return $.Deferred().resolve().promise();
        }

        if (append && listState.loading) {
            return $.Deferred().resolve().promise();
        }

        if (!append && listingXhr && listingXhr.readyState !== 4) {
            listingXhr.abort();
        }

        const requestVersion = ++listingRequestVersion;
        listState.loading = true;

        if (!append) {
            listState.page = 1;
            listState.lastPage = 1;
            $('#infiniteStatus').hide();
            $('#infiniteEnd').hide();
            $('#productListing').addClass('listing-loading');
        } else {
            $('#infiniteStatus').css('display', 'flex');
            $('#infiniteEnd').hide();
        }

        const fd = buildFormData();
        fd.set('page', page);

        listingXhr = $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            url: SEARCH_URL,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json'
        });

        return listingXhr
            .done(function (response) {
                if (requestVersion !== listingRequestVersion) return;

                listState.page = Number(response.current_page || page);
                listState.lastPage = Number(response.last_page || 1);
                listState.total = Number(response.total || 0);

                $('#skeletonLoader').remove();
                $('#infiniteStatus').hide();

                if (append) {
                    const parsed = $.parseHTML(response.products_html, document, true);
                    const $parsed = $(parsed);
                    const $items = $parsed.find('[role="listitem"]').addBack('[role="listitem"]');
                    const $grid = $('#productListing .products-grid');

                    if ($grid.length) {
                        $grid.append($items);
                    } else {
                        $('#productListing').html(response.products_html);
                    }
                } else {
                    $('#productListing').html(response.products_html);
                }

                $('#productListing').removeClass('listing-loading');
                applyViewMode();
                updateListingMeta(response);

                /*
                 * Never auto-chain all pages.
                 * Only pull another page automatically if the content is too short
                 * to fill even one viewport.
                 */
                requestAnimationFrame(function () {
                    const pageTooShort = document.documentElement.scrollHeight <= window.innerHeight + 120;

                    if (
                        pageTooShort &&
                        sentinelVisible &&
                        response.has_more_pages &&
                        !listState.loading
                    ) {
                        maybeLoadMore();
                    }
                });
            })
            .fail(function (xhr) {
                if (xhr && xhr.statusText === 'abort') return;

                $('#infiniteStatus').hide();
                $('#productListing').removeClass('listing-loading');
            })
            .always(function () {
                if (requestVersion === listingRequestVersion) {
                    listState.loading = false;
                }

                $('#infiniteStatus').hide();
            });
    }

    function reloadListing() {
        listState.page = 1;
        listState.lastPage = 1;
        $('#infiniteEnd').hide();
        return fetchPage(1, false);
    }

    function maybeLoadMore() {
        if (listState.loading) return;
        if (!sentinelVisible) return;
        if (listState.page >= listState.lastPage) return;

        fetchPage(listState.page + 1, true);
    }

    const sentinel = document.getElementById('infiniteSentinel');

    if ('IntersectionObserver' in window && sentinel) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                sentinelVisible = entry.isIntersecting;

                if (entry.isIntersecting) {
                    maybeLoadMore();
                }
            });
        }, {
            root: null,
            rootMargin: '350px 0px',
            threshold: 0
        });

        observer.observe(sentinel);
    } else {
        $(window).on('scroll.productsInfinite', function () {
            const nearBottom = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 500;
            sentinelVisible = nearBottom;

            if (nearBottom) {
                maybeLoadMore();
            }
        });
    }

    function resetFilters() {
        $('.category_for_filter, .brands_for_filter, .attributes_for_filter').prop('checked', false);
        $('#productNameInput').val('');
        $('#searchClearBtn').hide();
        $('#productSuggestBox').addClass('d-none').empty();
        currentCategory = '';
        listState.looseCat = '';
        $('#looseChips .loose-chip').removeClass('active').first().addClass('active');
        updateFilterUI();
        reloadListing();
    }

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

    $('#desktopResetBtn').on('click', function (e) {
        e.preventDefault();
        resetFilters();
    });

    $('#drawerResetBtn').on('click', function () {
        resetFilters();
        closeDrawer();
    });

    $('#drawerApplyBtn').on('click', function () {
        currentCategory = '';
        updateFilterUI();
        closeDrawer();
        reloadListing();
    });

    $(document).on('change', '.category_for_filter, .brands_for_filter, .attributes_for_filter', function () {
        if (listState.mode === 'loose') return;

        syncDuplicateFilter($(this));
        currentCategory = '';
        updateFilterUI();

        if (window.innerWidth >= 1024) {
            reloadListing();
        }
    });

    $(document).on('click', '.filter-chip[data-filter-value]', function () {
        const cls = $(this).data('filter-class');
        const value = String($(this).data('filter-value'));

        $('.' + cls).each(function () {
            if (String($(this).val()) === value) {
                $(this).prop('checked', false);
            }
        });

        currentCategory = '';
        updateFilterUI();
        reloadListing();
    });

    $(document).on('click', '#clearAllChips', resetFilters);

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

    function applyMode(mode) {
        if (mode === 'loose' && !$('#modeLooseBtn').length) {
            mode = 'all';
        }

        if (listState.mode === mode) return;

        listState.mode = mode;
        listState.looseCat = '';
        currentCategory = '';

        $('#products-page').toggleClass('loose-mode', mode === 'loose');
        $('#modeAllBtn').toggleClass('active', mode === 'all');
        $('#modeLooseBtn').toggleClass('active', mode === 'loose');
        $('#looseChips .loose-chip').removeClass('active').first().addClass('active');

        if (mode === 'loose') {
            $('.category_for_filter, .brands_for_filter, .attributes_for_filter').prop('checked', false);
            updateFilterUI();
        }

        reloadListing();
    }

    $('#modeAllBtn').on('click', function () {
        applyMode('all');
    });

    $('#modeLooseBtn').on('click', function () {
        applyMode('loose');
    });

    $(document).on('click', '.loose-chip', function () {
        if ($(this).hasClass('active')) return;

        $('#looseChips .loose-chip').removeClass('active');
        $(this).addClass('active');
        listState.looseCat = String($(this).data('cat') || '');
        reloadListing();
    });

    function renderSuggestions(items, query) {
        suggestItems = items || [];
        activeSuggestIndex = -1;

        const $box = $('#productSuggestBox');

        if (!suggestItems.length) {
            $box.removeClass('d-none').html(
                '<div class="ps-empty"><i class="fa-solid fa-box-open"></i><br>' + escapeHtml(NO_RESULT) + '</div>'
            );
            return;
        }

        let html = '';

        suggestItems.forEach(function (item) {
            const image = item.thumbnail
                ? '<img src="' + escapeHtml(item.thumbnail) + '" alt="">'
                : '<i class="fa-solid fa-box-open"></i>';

            html +=
                '<a href="' + escapeHtml(item.url) + '" class="ps-item">' +
                    '<div class="ps-thumb">' + image + '</div>' +
                    '<div class="ps-info">' +
                        '<div class="ps-name">' + highlightMatch(item.name, query) + '</div>' +
                        '<div class="ps-meta">' +
                            (item.code ? '<span>Code: ' + escapeHtml(item.code) + '</span>' : '') +
                            (item.brand ? '<span>' + escapeHtml(item.brand) + '</span>' : '') +
                        '</div>' +
                    '</div>' +
                    (item.price ? '<div class="ps-price">৳' + escapeHtml(item.price) + '</div>' : '') +
                '</a>';
        });

        html += '<div class="ps-footer">↑↓ navigate · Enter to open · Esc to close</div>';
        $box.removeClass('d-none').html(html);
    }

    function fetchSuggestions(query) {
        if (suggestXhr && suggestXhr.readyState !== 4) {
            suggestXhr.abort();
        }

        $('#productSuggestBox')
            .removeClass('d-none')
            .html('<div class="ps-loading"><span class="spinner-border spinner-border-sm"></span> Searching...</div>');

        suggestXhr = $.get(SUGGEST_URL, { q: query }, null, 'json')
            .done(function (response) {
                suggestCache[query] = response.suggestions || [];
                renderSuggestions(suggestCache[query], query);
            })
            .fail(function (xhr) {
                if (xhr.statusText !== 'abort') {
                    $('#productSuggestBox').addClass('d-none');
                }
            });
    }

    $('#productNameInput').on('input', function () {
        const query = $(this).val().trim();

        $('#searchClearBtn').toggle(query.length > 0);

        clearTimeout(suggestTimer);
        clearTimeout(liveTimer);

        if (suggestXhr && suggestXhr.readyState !== 4) {
            suggestXhr.abort();
        }

        if (!query) {
            $('#productSuggestBox').addClass('d-none').empty();
            suggestItems = [];
            currentCategory = '';

            liveTimer = setTimeout(function () {
                reloadListing();
            }, 350);

            return;
        }

        suggestTimer = setTimeout(function () {
            if (suggestCache[query]) {
                renderSuggestions(suggestCache[query], query);
            } else {
                fetchSuggestions(query);
            }
        }, suggestCache[query] ? 0 : 180);

        currentCategory = '';

        /*
         * Professional server-side live search:
         * wait 350ms after typing, cancel old request, then search the database.
         */
        liveTimer = setTimeout(function () {
            reloadListing();
        }, 350);
    });

    $('#productNameInput').on('focus', function () {
        const query = $(this).val().trim();
        if (!query) return;

        if (suggestCache[query]) {
            renderSuggestions(suggestCache[query], query);
        } else {
            fetchSuggestions(query);
        }
    });

    $('#productNameInput').on('keydown', function (e) {
        const $box = $('#productSuggestBox');
        if ($box.hasClass('d-none') || !suggestItems.length) return;

        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();

            activeSuggestIndex = e.key === 'ArrowDown'
                ? (activeSuggestIndex + 1) % suggestItems.length
                : (activeSuggestIndex - 1 + suggestItems.length) % suggestItems.length;

            const $items = $box.find('.ps-item').removeClass('active');
            $items.eq(activeSuggestIndex).addClass('active');
        } else if (e.key === 'Enter' && activeSuggestIndex >= 0) {
            e.preventDefault();
            window.location.href = suggestItems[activeSuggestIndex].url;
        } else if (e.key === 'Escape') {
            $box.addClass('d-none');
            activeSuggestIndex = -1;
        }
    });

    $('#searchClearBtn').on('click', function () {
        $('#productNameInput').val('').trigger('input').focus();
    });

    $('#productSearchForm').on('submit', function (e) {
        e.preventDefault();
        clearTimeout(liveTimer);
        clearTimeout(suggestTimer);
        $('#productSuggestBox').addClass('d-none');
        currentCategory = '';
        reloadListing();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#liveSearchWrapper').length) {
            $('#productSuggestBox').addClass('d-none');
        }
    });

    /*
     * Delegated card navigation.
     * It works for products appended later by infinite scroll too.
     */
    $(document).on('click', '.pc-wrap[data-href]', function (e) {
        if ($(e.target).closest('a, button, input, select, textarea').length) return;

        const href = $(this).data('href');
        if (href) window.location.href = href;
    });

    $(document).on('click', '.pc-quick', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const href = $(this).closest('.pc-wrap').data('href');
        if (href) window.location.href = href;
    });

    function saveState() {
        try {
            sessionStorage.setItem(STATE_KEY, JSON.stringify({
                mode: listState.mode,
                looseCat: listState.looseCat,
                page: listState.page,
                scrollY: window.scrollY,
                search: $('#productNameInput').val(),
                brands: uniqueChecked('.brands_for_filter'),
                categories: uniqueChecked('.category_for_filter'),
                attributes: uniqueChecked('.attributes_for_filter')
            }));
        } catch (e) {}
    }

    function readState() {
        try {
            const raw = sessionStorage.getItem(STATE_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    }

    function setCheckedValues(selector, values) {
        (values || []).forEach(function (value) {
            $(selector).each(function () {
                if (String($(this).val()) === String(value)) {
                    $(this).prop('checked', true);
                }
            });
        });
    }

    function restoreState(saved) {
        if (!saved) {
            updateFilterUI();
            reloadListing();
            return;
        }

        if (saved.mode === 'loose' && $('#modeLooseBtn').length) {
            listState.mode = 'loose';
            $('#products-page').addClass('loose-mode');
            $('#modeAllBtn').removeClass('active');
            $('#modeLooseBtn').addClass('active');

            if (saved.looseCat) {
                listState.looseCat = String(saved.looseCat);
                $('#looseChips .loose-chip').removeClass('active');
                $('#looseChips .loose-chip[data-cat="' + String(saved.looseCat).replace(/"/g, '\\"') + '"]').addClass('active');
            }
        } else {
            setCheckedValues('.brands_for_filter', saved.brands);
            setCheckedValues('.category_for_filter', saved.categories);
            setCheckedValues('.attributes_for_filter', saved.attributes);
        }

        if (saved.search) {
            $('#productNameInput').val(saved.search);
            $('#searchClearBtn').show();
        }

        updateFilterUI();

        const targetPage = Math.max(1, Number(saved.page || 1));
        let chain = fetchPage(1, false);

        for (let page = 2; page <= targetPage; page++) {
            chain = chain.then(function () {
                if (listState.page >= listState.lastPage) {
                    return $.Deferred().resolve().promise();
                }

                return fetchPage(listState.page + 1, true);
            });
        }

        chain.then(function () {
            if (typeof saved.scrollY === 'number') {
                window.scrollTo(0, saved.scrollY);
            }
        });
    }

    $(window).on('pagehide', saveState);

    $(window).on('pageshow', function (event) {
        if (event.originalEvent && event.originalEvent.persisted) {
            const saved = readState();
            if (saved && typeof saved.scrollY === 'number') {
                window.scrollTo(0, saved.scrollY);
            }
        }
    });

    let navType = '';
    if (window.performance && performance.getEntriesByType) {
        const entry = performance.getEntriesByType('navigation')[0];
        navType = entry ? entry.type : '';
    }

    restoreState(navType === 'back_forward' ? readState() : null);

})(jQuery);

var qsProduct = null;
var qsQty = 1;

function qsFormatNum(number) {
    return Number(number || 0).toLocaleString('en-US', {
        maximumFractionDigits: 2
    });
}

function openQuantityModal(productId, productName, priceText, productImage) {
    const numericPrice = parseFloat(String(priceText || '0').replace(/[^0-9.]/g, '')) || 0;

    qsProduct = {
        id: productId,
        name: productName,
        unitPrice: numericPrice
    };

    qsQty = 1;

    document.getElementById('qsProductName').textContent = productName || 'Product';
    document.getElementById('qsQuantity').textContent = '1';
    document.getElementById('qsProductPrice').textContent = '৳' + qsFormatNum(numericPrice);

    const image = document.getElementById('qsProductImage');
    if (productImage) {
        image.src = productImage;
        image.parentElement.style.display = '';
    } else {
        image.parentElement.style.display = 'none';
    }

    document.getElementById('quantityModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeQuantityModal() {
    document.getElementById('quantityModal').classList.remove('active');
    document.body.style.overflow = '';
    qsProduct = null;
    qsQty = 1;
}

function updateQuantity(change) {
    if (!qsProduct) return;

    const next = qsQty + change;
    if (next < 1 || next > 99) return;

    qsQty = next;
    document.getElementById('qsQuantity').textContent = String(qsQty);
    document.getElementById('qsProductPrice').textContent =
        '৳' + qsFormatNum(qsProduct.unitPrice) +
        ' × ' + qsQty +
        ' = ৳' + qsFormatNum(qsProduct.unitPrice * qsQty);
}

function showProductNotification(message, type) {
    const success = type !== 'error';

    const notification = document.createElement('div');
    notification.style.cssText = [
        'position:fixed',
        'top:18px',
        'right:18px',
        'z-index:12000',
        'display:flex',
        'align-items:center',
        'gap:9px',
        'max-width:360px',
        'padding:13px 16px',
        'border-radius:12px',
        'color:#fff',
        'font-size:13px',
        'font-weight:700',
        'box-shadow:0 10px 30px rgba(0,0,0,.18)',
        'transition:.25s ease',
        'transform:translateX(120%)',
        'background:' + (success ? '#10b981' : '#ef4444')
    ].join(';');

    notification.innerHTML =
        '<i class="fa-solid ' + (success ? 'fa-circle-check' : 'fa-circle-exclamation') + '"></i>' +
        '<span></span>';

    notification.querySelector('span').textContent = message;
    document.body.appendChild(notification);

    requestAnimationFrame(function () {
        notification.style.transform = 'translateX(0)';
    });

    setTimeout(function () {
        notification.style.transform = 'translateX(120%)';
        setTimeout(function () {
            notification.remove();
        }, 260);
    }, 2300);
}

function confirmAddToCart() {
    if (!qsProduct) return;

    const button = document.querySelector('.qs-btn-add');
    button.disabled = true;

    $.ajax({
        url: '/add-to-cart/product/' + qsProduct.id,
        type: 'POST',
        dataType: 'json',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            quantity: qsQty
        }
    })
    .done(function (response) {
        if (typeof response.cartCount !== 'undefined') {
            $('.cart-count, .cart_count, #cartCount').text(response.cartCount);
        }

        showProductNotification(
            response.message || 'Product added to cart!',
            response.status === 'success' ? 'success' : 'error'
        );

        closeQuantityModal();
    })
    .fail(function () {
        showProductNotification('Something went wrong. Please try again.', 'error');
    })
    .always(function () {
        button.disabled = false;
    });
}

$(document).on('click', '.add-to-cart', function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    event.stopPropagation();

    const $button = $(this);
    const $card = $button.closest('[role="listitem"]');
    const productId = $button.data('product-id') || String($button.attr('href') || '').split('/').pop();

    if (!productId) return;

    openQuantityModal(
        productId,
        $card.find('.pc-name').first().text().trim() || 'Product',
        $card.find('.pc-price-main').first().text().trim() || '৳0',
        $card.find('.pc-img').first().attr('src') || ''
    );
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeQuantityModal();
    }
});
</script>
@endpush

@endsection
