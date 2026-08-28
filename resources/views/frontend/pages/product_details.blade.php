@extends('frontend.layout.app')

@section('content')

@php
    $originalPrice   = $product->price;
    $discount        = $product->discount ?? 0;
    $discountType    = $product->discount_type ?? 'percent';
    $discountedPrice = $originalPrice;

    if ($discount > 0) {
        $discountedPrice = ($discountType === 'amount')
            ? $originalPrice - $discount
            : $originalPrice - ($originalPrice * $discount / 100);
    }

    $lang        = Session::get('language') ?? 'en';
    $productName = $product->getTranslation($lang, 'name') ?? $product->name;
    $keyFeatures = $product->getTranslation($lang, 'key_features') ?? $product->key_features;
    $furtherInfo = $product->getTranslation($lang, 'further_information') ?? $product->further_information;

    // Build full image list: extras first, then thumbnail
    $galleryImages = collect($product->images ?? [])
        ->push($product->thumbnail)
        ->unique()
        ->values();
@endphp

<style>
/* ================================================================
   CSS Variables
================================================================ */
:root {
    --pr:        #f85606;
    --pr-dk:     #d94a04;
    --pr-lt:     #fff3ec;
    --pr-grd:    linear-gradient(135deg, #f85606, #ff8a00);
    --dark:      #0f172a;
    --text:      #374151;
    --muted:     #6b7280;
    --border:    #e5e7eb;
    --bg:        #f1f5f9;
    --card:      #ffffff;
    --radius-xl: 20px;
    --radius-lg: 14px;
    --radius-md: 10px;
    --shadow-sm: 0 2px 8px rgba(0,0,0,.06);
    --shadow-md: 0 8px 28px rgba(0,0,0,.09);
    --shadow-lg: 0 20px 60px rgba(0,0,0,.12);
    --t:         .22s ease;
}

/* ================================================================
   Page shell
================================================================ */
#pd-page {
    background: var(--bg);
    min-height: 100vh;
    padding-bottom: 80px;
}

/* ================================================================
   Hero / Breadcrumb
================================================================ */
.pd-hero {
    background: var(--pr-grd);
    padding: 20px 0 18px;
    position: relative;
    overflow: hidden;
}

.pd-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
    pointer-events: none;
}

.pd-hero .container { position: relative; }

.pd-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    font-size: 13px;
    color: rgba(255,255,255,.8);
    margin: 0;
}

.pd-breadcrumb a {
    color: rgba(255,255,255,.9);
    text-decoration: none;
    font-weight: 500;
    transition: color var(--t);
    display: flex;
    align-items: center;
    gap: 4px;
}

.pd-breadcrumb a:hover { color: #fff; }
.pd-breadcrumb .sep { font-size: 9px; opacity: .5; }
.pd-breadcrumb .current { color: #fff; font-weight: 600; }

/* ================================================================
   Back button
================================================================ */
.pd-back-row {
    padding: 18px 0 0;
}

.pd-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    font-weight: 700;
    color: var(--muted);
    text-decoration: none;
    background: var(--card);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    padding: 7px 14px;
    transition: all var(--t);
}

.pd-back-btn:hover {
    background: var(--pr-lt);
    border-color: var(--pr);
    color: var(--pr);
}

/* ================================================================
   Main grid
================================================================ */
.pd-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-top: 20px;
    align-items: start;
}

/* ================================================================
   Gallery panel (left)
================================================================ */
.pd-gallery {
    background: var(--card);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    position: sticky;
    top: 80px;
}

/* Main image */
.pd-main-img-wrap {
    position: relative;
    background: #f8fafc;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    cursor: zoom-in;
}

.pd-main-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform .4s ease;
    display: block;
    padding: 8px;
}

.pd-main-img-wrap:hover .pd-main-img { transform: scale(1.06); }

/* Discount badge */
.pd-discount-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    background: linear-gradient(135deg, #ef4444, #f97316);
    color: #fff;
    font-size: 12px;
    font-weight: 800;
    border-radius: 10px;
    padding: 5px 12px;
    z-index: 2;
    letter-spacing: .3px;
    box-shadow: 0 4px 12px rgba(239,68,68,.3);
}

/* Zoom hint */
.pd-zoom-hint {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(255,255,255,.85);
    border-radius: 8px;
    padding: 5px 10px;
    font-size: 11px;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 5px;
    backdrop-filter: blur(4px);
    pointer-events: none;
    opacity: 0;
    transition: opacity var(--t);
}

.pd-main-img-wrap:hover .pd-zoom-hint { opacity: 1; }

/* Thumbnails strip */
.pd-thumbs {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 14px;
    background: #f8fafc;
    border-top: 1px solid var(--border);
    overflow-x: auto;
    overscroll-behavior-x: contain;
    scrollbar-width: none;
}

.pd-thumbs::-webkit-scrollbar { display: none; }

.pd-thumb-prev,
.pd-thumb-next {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--card);
    border: 1.5px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    color: var(--muted);
    transition: all var(--t);
    box-shadow: var(--shadow-sm);
}

.pd-thumb-prev:hover,
.pd-thumb-next:hover {
    background: var(--pr-lt);
    border-color: var(--pr);
    color: var(--pr);
}

.pd-thumb-track {
    display: flex;
    gap: 8px;
    flex: 1;
    overflow-x: auto;
    scrollbar-width: none;
}

.pd-thumb-track::-webkit-scrollbar { display: none; }

.pd-thumb {
    flex-shrink: 0;
    width: 68px;
    height: 68px;
    border-radius: var(--radius-md);
    overflow: hidden;
    border: 2px solid transparent;
    cursor: pointer;
    transition: border-color var(--t), box-shadow var(--t);
    background: #fff;
}

.pd-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.pd-thumb.active {
    border-color: var(--pr);
    box-shadow: 0 0 0 3px rgba(248,86,6,.18);
}

.pd-thumb:hover:not(.active) {
    border-color: rgba(248,86,6,.4);
}

/* ================================================================
   Info panel (right)
================================================================ */
.pd-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Card wrapper */
.pd-card {
    background: var(--card);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    padding: 22px 24px;
}

/* Product header */
.pd-category-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--pr-lt);
    color: var(--pr);
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .5px;
    text-transform: uppercase;
    margin-bottom: 10px;
}

.pd-title {
    font-size: clamp(20px, 3vw, 28px);
    font-weight: 800;
    color: var(--dark);
    line-height: 1.3;
    letter-spacing: -.3px;
    margin: 0 0 8px;
}

.pd-code-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.pd-code {
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: 8px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    font-family: 'Courier New', monospace;
    letter-spacing: .5px;
}

.pd-stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 11px;
    font-weight: 800;
}

.pd-stock-badge.in-stock {
    background: #dcfce7;
    color: #16a34a;
}

.pd-stock-badge.in-stock::before {
    content: '';
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #16a34a;
    animation: pulse-green 1.5s infinite;
}

@keyframes pulse-green {
    0%, 100% { opacity: 1; }
    50%       { opacity: .4; }
}

/* ================================================================
   Specs (attributes)
================================================================ */
.pd-specs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}

.pd-spec-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    background: var(--bg);
    border-radius: var(--radius-md);
    padding: 10px 12px;
    border: 1px solid var(--border);
}

.pd-spec-icon {
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    background: var(--pr-lt);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--pr);
    font-size: 12px;
}

.pd-spec-label {
    font-size: 11px;
    color: var(--muted);
    font-weight: 500;
    display: block;
    line-height: 1.3;
}

.pd-spec-value {
    font-size: 13px;
    color: var(--dark);
    font-weight: 700;
    display: block;
    line-height: 1.3;
}

/* ================================================================
   Price block
================================================================ */
.pd-price-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.pd-price-main {
    font-size: clamp(26px, 4vw, 34px);
    font-weight: 900;
    color: var(--pr);
    line-height: 1;
    letter-spacing: -1px;
}

.pd-price-original {
    font-size: 16px;
    color: var(--muted);
    text-decoration: line-through;
    font-weight: 500;
}

.pd-price-save {
    background: #dcfce7;
    color: #16a34a;
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 800;
}

/* ================================================================
   Action buttons
================================================================ */
.pd-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.pd-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-radius: var(--radius-lg);
    font-weight: 800;
    font-size: 15px;
    padding: 14px 28px;
    cursor: pointer;
    border: none;
    text-decoration: none;
    transition: all var(--t);
    line-height: 1;
    white-space: nowrap;
}

.pd-btn-primary {
    background: var(--pr-grd);
    color: #fff;
    flex: 1;
    box-shadow: 0 6px 20px rgba(248,86,6,.3);
}

.pd-btn-primary:hover {
    opacity: .92;
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(248,86,6,.38);
    color: #fff;
}

.pd-btn-primary:active { transform: translateY(0); }

.pd-btn-inquiry {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    flex: 1;
    box-shadow: 0 6px 20px rgba(99,102,241,.28);
}

.pd-btn-inquiry:hover {
    opacity: .92;
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(99,102,241,.36);
    color: #fff;
}

.pd-btn-wishlist {
    width: 52px;
    height: 52px;
    padding: 0;
    flex-shrink: 0;
    background: var(--bg);
    border: 1.5px solid var(--border);
    color: var(--muted);
    border-radius: var(--radius-lg);
}

.pd-btn-wishlist:hover {
    background: #fff0f0;
    border-color: #ef4444;
    color: #ef4444;
    transform: scale(1.08);
}

.pd-btn-wishlist.wishlisted {
    background: #fff0f0;
    border-color: #ef4444;
    color: #ef4444;
}

/* ================================================================
   Downloads
================================================================ */
.pd-section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 800;
    color: var(--dark);
    margin: 0 0 14px;
}

.pd-section-title i {
    width: 32px;
    height: 32px;
    background: var(--pr-lt);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--pr);
    font-size: 13px;
    flex-shrink: 0;
}

.pd-download-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.pd-download-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    padding: 12px 16px;
    text-decoration: none;
    color: var(--dark);
    font-weight: 700;
    font-size: 14px;
    transition: all var(--t);
}

.pd-download-item:hover {
    background: var(--pr-lt);
    border-color: var(--pr);
    color: var(--pr);
    transform: translateX(3px);
}

.pd-download-item i.icon {
    width: 36px;
    height: 36px;
    background: var(--card);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: var(--pr);
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
}

.pd-download-item .dl-arrow {
    margin-left: auto;
    font-size: 12px;
    color: var(--muted);
    transition: color var(--t);
}

.pd-download-item:hover .dl-arrow { color: var(--pr); }

/* ================================================================
   Tabs (Key Features / Further Info)
================================================================ */
.pd-tabs {
    background: var(--card);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.pd-tab-nav {
    display: flex;
    border-bottom: 2px solid var(--border);
    background: var(--bg);
}

.pd-tab-btn {
    flex: 1;
    padding: 14px 16px;
    background: none;
    border: none;
    font-size: 14px;
    font-weight: 700;
    color: var(--muted);
    cursor: pointer;
    position: relative;
    transition: color var(--t);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    border-radius: 0;
}

.pd-tab-btn::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--pr);
    border-radius: 3px 3px 0 0;
    transform: scaleX(0);
    transition: transform var(--t);
}

.pd-tab-btn.active {
    color: var(--pr);
    background: var(--card);
}

.pd-tab-btn.active::after { transform: scaleX(1); }

.pd-tab-content { display: none; padding: 24px; }
.pd-tab-content.active { display: block; }

.pd-tab-content h1,
.pd-tab-content h2,
.pd-tab-content h3,
.pd-tab-content h4 {
    color: var(--dark);
    font-weight: 800;
    margin-top: 1.2em;
    margin-bottom: .5em;
}

.pd-tab-content p {
    color: var(--text);
    line-height: 1.75;
    font-size: 14px;
    margin-bottom: .9em;
}

.pd-tab-content ul, .pd-tab-content ol {
    padding-left: 20px;
    color: var(--text);
    font-size: 14px;
    line-height: 1.75;
}

.pd-tab-content table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.pd-tab-content table th,
.pd-tab-content table td {
    padding: 10px 14px;
    border: 1px solid var(--border);
    text-align: left;
}

.pd-tab-content table th {
    background: var(--bg);
    font-weight: 700;
    color: var(--dark);
}

.pd-tab-content table tr:nth-child(even) td { background: #f8fafc; }

/* ================================================================
   Related parts carousel
================================================================ */
.pd-related {
    margin-top: 32px;
}

.pd-related-title {
    font-size: clamp(20px, 3vw, 26px);
    font-weight: 800;
    color: var(--dark);
    margin: 0 0 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.pd-related-title::before {
    content: '';
    width: 5px;
    height: 26px;
    background: var(--pr-grd);
    border-radius: 3px;
    flex-shrink: 0;
}

.pd-carousel-wrap {
    position: relative;
}

.pd-carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--card);
    border: 1.5px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    font-size: 13px;
    color: var(--muted);
    transition: all var(--t);
    box-shadow: var(--shadow-sm);
}

.pd-carousel-btn:hover {
    background: var(--pr);
    border-color: var(--pr);
    color: #fff;
    box-shadow: 0 4px 14px rgba(248,86,6,.3);
}

.pd-carousel-btn.prev-btn { left: -20px; }
.pd-carousel-btn.next-btn { right: -20px; }

/* Part card */
.part-card-wrap { padding: 4px; }

.part-card {
    background: var(--card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: transform var(--t), box-shadow var(--t);
    text-decoration: none;
    color: var(--dark);
    display: block;
}

.part-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    color: var(--pr);
}

.part-card-img {
    aspect-ratio: 1/1;
    overflow: hidden;
    background: #f8fafc;
}

.part-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .35s ease;
    display: block;
}

.part-card:hover .part-card-img img { transform: scale(1.07); }

.part-card-body {
    padding: 10px 12px 12px;
}

.part-card-name {
    font-size: 12px;
    font-weight: 700;
    line-height: 1.4;
    text-transform: uppercase;
    letter-spacing: .2px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ================================================================
   Mobile sticky CTA bar
================================================================ */
.pd-sticky-cta {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 950;
    background: var(--card);
    border-top: 1px solid var(--border);
    padding: 12px 16px;
    padding-bottom: calc(12px + env(safe-area-inset-bottom, 0px));
    box-shadow: 0 -8px 28px rgba(0,0,0,.1);
    gap: 10px;
    align-items: center;
}

.pd-sticky-price {
    flex: 0 0 auto;
}

.pd-sticky-price .amount {
    font-size: 18px;
    font-weight: 900;
    color: var(--pr);
    line-height: 1;
    display: block;
}

.pd-sticky-price .original {
    font-size: 11px;
    color: var(--muted);
    text-decoration: line-through;
}

.pd-sticky-cta .pd-btn {
    font-size: 14px;
    padding: 13px 20px;
}

/* ================================================================
   Section divider
================================================================ */
.pd-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border), transparent);
    margin: 4px 0;
}

/* ================================================================
   Responsive
================================================================ */
@media (max-width: 1023px) {
    .pd-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .pd-gallery { position: static; }

    .pd-specs { grid-template-columns: 1fr 1fr; }

    .pd-sticky-cta { display: flex; }

    #pd-page { padding-bottom: 100px; }
}

@media (max-width: 767px) {
    .pd-hero { padding: 16px 0 14px; }

    .pd-card { padding: 16px; }

    .pd-title { font-size: 20px; }

    .pd-price-main { font-size: 28px; }

    .pd-btn { font-size: 14px; padding: 13px 18px; }

    .pd-btn-wishlist { width: 48px; height: 48px; }

    .pd-tab-btn { font-size: 13px; padding: 12px 10px; }

    .pd-tab-content { padding: 16px; }

    .pd-carousel-btn.prev-btn { left: -14px; }
    .pd-carousel-btn.next-btn { right: -14px; }

    .pd-specs { grid-template-columns: 1fr; }

    .pd-thumb { width: 56px; height: 56px; }
}

@media (max-width: 480px) {
    .pd-actions { gap: 8px; }

    .pd-btn-primary,
    .pd-btn-inquiry { font-size: 13px; padding: 12px 14px; }

    .pd-related-title { font-size: 18px; }
}
</style>

{{-- ================================================================
     PAGE
================================================================ --}}
<div id="pd-page">

    {{-- Hero breadcrumb --}}
    <div class="pd-hero">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="pd-breadcrumb">
                    <li><a href="{{ route('home') }}"><i class="fa-solid fa-house" style="font-size:11px;"></i> {{ trans('language.home') }}</a></li>
                    <li class="sep"><i class="fa-solid fa-chevron-right"></i></li>
                    <li><a href="{{ route('products') }}">{{ trans('language.products') }}</a></li>
                    <li class="sep"><i class="fa-solid fa-chevron-right"></i></li>
                    <li class="current" aria-current="page">{{ Str::limit($productName, 42, '…') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">

        {{-- Back button --}}
        <div class="pd-back-row">
            <a href="{{ route('products') }}" class="pd-back-btn">
                <i class="fa-solid fa-angle-left"></i>
                {{ trans('language.go_back') }}
            </a>
        </div>

        {{-- Main 2-col grid --}}
        <div class="pd-grid">

            {{-- ====================================================
                 LEFT — Gallery
            ==================================================== --}}
            <div>
                <div class="pd-gallery">

                    {{-- Main image --}}
                    <div class="pd-main-img-wrap" id="pdMainImgWrap">
                        <img
                            id="pdMainImg"
                            class="pd-main-img"
                            src="{{ asset('uploads/product-images/' . $product->thumbnail) }}"
                            alt="{{ $productName }}">

                        @if ($discount > 0)
                            <span class="pd-discount-badge">
                                @if ($discountType === 'amount')
                                    Save ৳{{ number_format($discount, 0) }}
                                @else
                                    {{ $discount }}% OFF
                                @endif
                            </span>
                        @endif

                        <div class="pd-zoom-hint">
                            <i class="fa-solid fa-magnifying-glass-plus"></i> Zoom
                        </div>
                    </div>

                    {{-- Thumbnails --}}
                    @if ($galleryImages->count() > 0)
                        <div class="pd-thumbs">
                            <button class="pd-thumb-prev" id="pdThumbPrev" aria-label="Previous image">
                                <i class="fa-solid fa-angle-left"></i>
                            </button>
                            <div class="pd-thumb-track" id="pdThumbTrack">
                                @foreach ($galleryImages as $i => $image)
                                    <div class="pd-thumb {{ $i === $galleryImages->count() - 1 ? 'active' : '' }}"
                                        data-src="{{ asset('uploads/product-images/' . $image) }}"
                                        role="button"
                                        tabindex="0"
                                        aria-label="Product image {{ $i + 1 }}">
                                        <img src="{{ asset('uploads/product-images/' . $image) }}" alt="" loading="lazy">
                                    </div>
                                @endforeach
                            </div>
                            <button class="pd-thumb-next" id="pdThumbNext" aria-label="Next image">
                                <i class="fa-solid fa-angle-right"></i>
                            </button>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ====================================================
                 RIGHT — Info
            ==================================================== --}}
            <div class="pd-info">

                {{-- Title card --}}
                <div class="pd-card">

                    {{-- Category badge + title row --}}
                    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
                        @if ($product->category)
                            <div class="pd-category-tag">
                                <i class="fa-solid fa-layer-group" style="font-size:10px;"></i>
                                {{ $product->category->title ?? '' }}
                            </div>
                        @endif
                        @if ($product->brand)
                            <div style="display:inline-flex; align-items:center; gap:5px; background:#f1f5f9; border:1px solid #e5e7eb; border-radius:20px; padding:4px 12px; font-size:11px; font-weight:800; color:#374151;">
                                <i class="fa-solid fa-tag" style="color:var(--pr); font-size:10px;"></i>
                                {{ $product->brand->title }}
                            </div>
                        @endif
                    </div>

                    <h1 class="pd-title">{{ $productName }}</h1>

                    <div class="pd-code-row">
                        <span class="pd-code">
                            <i class="fa-solid fa-barcode" style="font-size:11px; margin-right:4px;"></i>{{ $product->code }}
                        </span>
                        <span class="pd-stock-badge in-stock">
                            In Stock
                        </span>
                    </div>

                    {{-- Specs / Attributes --}}
                    @if (count($product->attributes) > 0)
                        <div style="margin-top:16px; margin-bottom:4px;">
                            <p style="font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.7px; margin-bottom:10px;">
                                Specifications
                            </p>
                            <div class="pd-specs">
                                @foreach ($product->attributes as $attribute)
                                    @if ($attribute->attribute_name)
                                        <div class="pd-spec-item">
                                            <div class="pd-spec-icon">
                                                <i class="fa-solid fa-circle-dot"></i>
                                            </div>
                                            <div>
                                                <span class="pd-spec-label">{{ $attribute->attribute_name }}</span>
                                                <span class="pd-spec-value">{{ $attribute->value }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Price + CTA card --}}
                <div class="pd-card">

                    {{-- Price --}}
                    @if ($originalPrice > 0)
                        <div style="margin-bottom: 18px;">
                            <p style="font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.7px; margin-bottom:8px;">
                                {{ trans('language.price') }}
                            </p>
                            <div class="pd-price-wrap">
                                <span class="pd-price-main">৳{{ number_format($discountedPrice, 0) }}</span>
                                @if ($discount > 0)
                                    <span class="pd-price-original">৳{{ number_format($originalPrice, 0) }}</span>
                                    <span class="pd-price-save">
                                        @if ($discountType === 'amount')
                                            Save ৳{{ number_format($discount, 0) }}
                                        @else
                                            Save {{ $discount }}%
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="pd-divider" style="margin-bottom:18px;"></div>

                    {{-- CTA Buttons --}}
                    <div class="pd-actions">
                        @if ($product->price)
                            <a href="{{ route('add.to.cart', ['type' => 'product', 'id' => $product->id]) }}"
                                class="pd-btn pd-btn-primary add-to-cart">
                                <i class="fa-solid fa-cart-shopping"></i>
                                {{ trans('language.btn_order') }}
                            </a>
                        @else
                            <a href="{{ route('add.to.inquiry', $product->id) }}"
                                class="pd-btn pd-btn-inquiry inquiry-btn">
                                <i class="fa-solid fa-circle-question"></i>
                                {{ trans('language.btn_add_to_inquiry_list') }}
                            </a>
                        @endif

                        <a href="{{ route('add.to.wishlist', ['type' => 'product', 'id' => $product->id]) }}"
                            class="pd-btn pd-btn-wishlist add-to-wishlist"
                            title="{{ trans('language.wishlist') ?? 'Wishlist' }}"
                            aria-label="Add to wishlist">
                            <i class="fa-solid fa-heart"></i>
                        </a>
                    </div>

                    {{-- Trust badges --}}
                    <div style="display:flex; gap:14px; flex-wrap:wrap; margin-top:18px; padding-top:16px; border-top:1px solid var(--border);">
                        <div style="display:flex; align-items:center; gap:6px; font-size:11px; color:var(--muted); font-weight:600;">
                            <i class="fa-solid fa-shield-halved" style="color:#16a34a; font-size:14px;"></i>
                            Secure Checkout
                        </div>
                        <div style="display:flex; align-items:center; gap:6px; font-size:11px; color:var(--muted); font-weight:600;">
                            <i class="fa-solid fa-truck-fast" style="color:var(--pr); font-size:14px;"></i>
                            Fast Delivery
                        </div>
                        <div style="display:flex; align-items:center; gap:6px; font-size:11px; color:var(--muted); font-weight:600;">
                            <i class="fa-solid fa-rotate-left" style="color:#6366f1; font-size:14px;"></i>
                            Easy Returns
                        </div>
                    </div>

                </div>

                {{-- Downloads card --}}
                @if ($catalogue || true)
                    <div class="pd-card">
                        <h2 class="pd-section-title">
                            <i class="fa-solid fa-download"></i>
                            {{ trans('language.downloads') }}
                        </h2>
                        <div class="pd-download-list">
                            @if ($catalogue)
                                <a href="{{ route('view.catalogue', $catalogue->id) }}" class="pd-download-item" target="_blank">
                                    <i class="fa-solid fa-book-open icon"></i>
                                    <span>{{ trans('language.catalogues_page') }}</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square dl-arrow"></i>
                                </a>
                            @endif
                            <a href="" class="pd-download-item">
                                <i class="fa-solid fa-file-lines icon"></i>
                                <span>{{ trans('language.specification_sheet') }}</span>
                                <i class="fa-solid fa-arrow-down dl-arrow"></i>
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- ====================================================
             Tabs — Key Features + Further Information
        ==================================================== --}}
        @if ($keyFeatures || $furtherInfo)
            <div class="pd-tabs" style="margin-top: 24px;">
                <div class="pd-tab-nav" role="tablist">
                    @if ($keyFeatures)
                        <button class="pd-tab-btn active" role="tab" aria-selected="true"
                            data-target="tab-features" aria-controls="tab-features">
                            <i class="fa-solid fa-star" style="font-size:12px;"></i>
                            {{ trans('language.key_features') }}
                        </button>
                    @endif
                    @if ($furtherInfo)
                        <button class="pd-tab-btn {{ $keyFeatures ? '' : 'active' }}" role="tab" aria-selected="{{ $keyFeatures ? 'false' : 'true' }}"
                            data-target="tab-info" aria-controls="tab-info">
                            <i class="fa-solid fa-circle-info" style="font-size:12px;"></i>
                            {{ trans('language.further_information') }}
                        </button>
                    @endif
                </div>

                @if ($keyFeatures)
                    <div class="pd-tab-content active" id="tab-features" role="tabpanel">
                        {!! $keyFeatures !!}
                    </div>
                @endif

                @if ($furtherInfo)
                    <div class="pd-tab-content {{ $keyFeatures ? '' : 'active' }}" id="tab-info" role="tabpanel">
                        {!! $furtherInfo !!}
                    </div>
                @endif
            </div>
        @endif

        {{-- ====================================================
             Related Parts Carousel
        ==================================================== --}}
        @if (count($releted_parts) > 0)
            <div class="pd-related">
                <h2 class="pd-related-title">{{ trans('language.related_product_parts') ?? trans('language.related_products') ?? 'Related Products' }}</h2>
                <div class="pd-carousel-wrap">
                    <button class="pd-carousel-btn prev-btn" id="rPrev" aria-label="Previous">
                        <i class="fa-solid fa-angle-left"></i>
                    </button>
                    <div class="owl-carousel owl-theme" id="relatedCarousel">
                        @foreach ($releted_parts as $part)
                            <div class="part-card-wrap">
                                <a href="{{ url('product/' . $part->slug) }}" class="part-card">
                                    <div class="part-card-img">
                                        <img src="{{ asset('uploads/product-images/' . $part->thumbnail) }}"
                                            alt="{{ $part->getTranslation(Session::get('language') ?? 'en', 'name') ?? $part->name }}"
                                            loading="lazy">
                                    </div>
                                    <div class="part-card-body">
                                        <p class="part-card-name">
                                            {{ $part->getTranslation(Session::get('language') ?? 'en', 'name') ?? $part->name }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <button class="pd-carousel-btn next-btn" id="rNext" aria-label="Next">
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                </div>
            </div>
        @endif

    </div>{{-- /container --}}

</div>{{-- /pd-page --}}

{{-- ================================================================
     Mobile sticky CTA bar
================================================================ --}}
@if ($originalPrice > 0)
    <div class="pd-sticky-cta" id="pdStickyCta">
        <div class="pd-sticky-price">
            <span class="amount">৳{{ number_format($discountedPrice, 0) }}</span>
            @if ($discount > 0)
                <span class="original">৳{{ number_format($originalPrice, 0) }}</span>
            @endif
        </div>
        <a href="{{ route('add.to.cart', ['type' => 'product', 'id' => $product->id]) }}"
            class="pd-btn pd-btn-primary add-to-cart" style="flex:1;">
            <i class="fa-solid fa-cart-shopping"></i>
            {{ trans('language.btn_order') }}
        </a>
        <a href="{{ route('add.to.wishlist', ['type' => 'product', 'id' => $product->id]) }}"
            class="pd-btn pd-btn-wishlist add-to-wishlist" style="flex-shrink:0;">
            <i class="fa-solid fa-heart"></i>
        </a>
    </div>
@elseif (!$originalPrice)
    <div class="pd-sticky-cta" id="pdStickyCta">
        <a href="{{ route('add.to.inquiry', $product->id) }}"
            class="pd-btn pd-btn-inquiry" style="flex:1;">
            <i class="fa-solid fa-circle-question"></i>
            {{ trans('language.btn_add_to_inquiry_list') }}
        </a>
    </div>
@endif

@push('footer')
<script>
$(function () {

    /* ============================================================
       Thumbnail gallery
    ============================================================ */
    var $mainImg   = $('#pdMainImg');
    var $thumbs    = $('.pd-thumb');
    var $track     = $('#pdThumbTrack');
    var activeIdx  = $thumbs.length - 1; // thumbnail matches thumbnail by default

    function setActive(idx) {
        activeIdx = idx;
        $thumbs.removeClass('active').eq(idx).addClass('active');
        var src = $thumbs.eq(idx).data('src');
        $mainImg.css('opacity', 0).attr('src', src)
            .on('load', function () {
                $(this).animate({ opacity: 1 }, 180);
            });
    }

    $thumbs.on('click keydown', function (e) {
        if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') return;
        e.preventDefault();
        setActive($thumbs.index(this));
    });

    $('#pdThumbPrev').on('click', function () {
        var next = (activeIdx - 1 + $thumbs.length) % $thumbs.length;
        setActive(next);
        scrollThumbIntoView(next);
    });

    $('#pdThumbNext').on('click', function () {
        var next = (activeIdx + 1) % $thumbs.length;
        setActive(next);
        scrollThumbIntoView(next);
    });

    function scrollThumbIntoView(idx) {
        var $t = $thumbs.eq(idx);
        if (!$t.length) return;
        var trackLeft  = $track[0].scrollLeft;
        var trackW     = $track[0].clientWidth;
        var thumbLeft  = $t[0].offsetLeft;
        var thumbW     = $t[0].offsetWidth;
        if (thumbLeft < trackLeft) {
            $track.animate({ scrollLeft: thumbLeft - 8 }, 200);
        } else if (thumbLeft + thumbW > trackLeft + trackW) {
            $track.animate({ scrollLeft: thumbLeft + thumbW - trackW + 8 }, 200);
        }
    }

    // Touch swipe on main image
    var swipeStartX = 0;
    var $wrap = $('#pdMainImgWrap')[0];
    if ($wrap) {
        $wrap.addEventListener('touchstart', function (e) {
            swipeStartX = e.touches[0].clientX;
        }, { passive: true });

        $wrap.addEventListener('touchend', function (e) {
            var dx = e.changedTouches[0].clientX - swipeStartX;
            if (Math.abs(dx) < 40) return;
            if (dx < 0) {
                var n = (activeIdx + 1) % $thumbs.length;
            } else {
                var n = (activeIdx - 1 + $thumbs.length) % $thumbs.length;
            }
            setActive(n);
            scrollThumbIntoView(n);
        }, { passive: true });
    }

    /* ============================================================
       Tabs
    ============================================================ */
    $(document).on('click', '.pd-tab-btn', function () {
        var target = $(this).data('target');
        $('.pd-tab-btn').removeClass('active').attr('aria-selected', 'false');
        $(this).addClass('active').attr('aria-selected', 'true');
        $('.pd-tab-content').removeClass('active');
        $('#' + target).addClass('active');
    });

    /* ============================================================
       Related parts carousel
    ============================================================ */
    if ($('#relatedCarousel').length) {
        $('#relatedCarousel').owlCarousel({
            loop: true,
            margin: 14,
            nav: false,
            dots: false,
            responsive: {
                0:    { items: 2 },
                600:  { items: 3 },
                900:  { items: 4 },
                1200: { items: 5 }
            }
        });

        $('#rPrev').on('click', function () {
            $('#relatedCarousel').trigger('prev.owl.carousel');
        });

        $('#rNext').on('click', function () {
            $('#relatedCarousel').trigger('next.owl.carousel');
        });
    }

    /* ============================================================
       Sticky CTA — hide when desktop CTA is visible
    ============================================================ */
    var $stickyCta = $('#pdStickyCta');
    var $desktopCta = $('.pd-actions').first();

    function checkCtaVisibility() {
        if (!$desktopCta.length || !$stickyCta.length) return;
        var rect = $desktopCta[0].getBoundingClientRect();
        var inView = rect.top >= 0 && rect.bottom <= window.innerHeight;
        $stickyCta.css('opacity', inView ? '0' : '1').css('pointer-events', inView ? 'none' : 'auto');
    }

    $(window).on('scroll', checkCtaVisibility);
    checkCtaVisibility();

});
</script>
@endpush

<!-- Quantity Selector Bottom Sheet Modal -->
<div id="quantityModal" class="quantity-bottom-sheet">
    <div class="qs-backdrop" onclick="closeQuantityModal()"></div>
    <div class="qs-content">
        <div class="qs-handle"></div>
        
        <div class="qs-header">
            <h4 class="qs-title">Select Quantity</h4>
            <button type="button" class="qs-close" onclick="closeQuantityModal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        
        <div class="qs-product-info">
            <div class="qs-product-image">
                <img id="qsProductImage" src="" alt="Product">
            </div>
            <div class="qs-product-details">
                <h5 id="qsProductName">Product Name</h5>
                <p id="qsProductPrice" class="qs-price">$0.00</p>
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
            <button type="button" class="qs-btn-cancel" onclick="closeQuantityModal()">Cancel</button>
            <button type="button" class="qs-btn-add" onclick="confirmAddToCart()">
                <i class="fa-solid fa-cart-shopping"></i>
                Add to Cart
            </button>
        </div>
    </div>
</div>

<style>
    /* ============================================================
       Quantity Bottom Sheet Modal Styles
    ============================================================ */
    .quantity-bottom-sheet {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        animation: qsFadeIn 0.2s ease;
    }
    
    .quantity-bottom-sheet.active {
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }
    
    @keyframes qsFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .qs-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        animation: qsBackdropFadeIn 0.2s ease;
    }
    
    @keyframes qsBackdropFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .qs-content {
        position: relative;
        background: white;
        width: 100%;
        max-width: 500px;
        border-radius: 24px 24px 0 0;
        padding: 20px;
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.15);
        animation: qsSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    @keyframes qsSlideUp {
        from { 
            transform: translateY(100%);
            opacity: 0;
        }
        to { 
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .qs-handle {
        width: 40px;
        height: 4px;
        background: #e0e0e0;
        border-radius: 2px;
        margin: 0 auto 16px;
    }
    
    .qs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .qs-title {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }
    
    .qs-close {
        background: none;
        border: none;
        font-size: 20px;
        color: #6b7280;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .qs-close:hover {
        background: #f3f4f6;
        color: #1f2937;
    }
    
    .qs-product-info {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        padding: 16px;
        background: #f9fafb;
        border-radius: 12px;
    }
    
    .qs-product-image {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        border: 1px solid #e5e7eb;
        flex-shrink: 0;
    }
    
    .qs-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .qs-product-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .qs-product-details h5 {
        margin: 0 0 8px;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .qs-price {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #f85606;
    }
    
    .qs-quantity-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-bottom: 24px;
        padding: 16px;
        background: #f9fafb;
        border-radius: 12px;
    }
    
    .qs-btn-qty {
        width: 48px;
        height: 48px;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 12px;
        font-size: 16px;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .qs-btn-qty:hover {
        border-color: #f85606;
        color: #f85606;
        background: #fff7ed;
    }
    
    .qs-btn-qty:active {
        transform: scale(0.95);
    }
    
    .qs-quantity-display {
        width: 80px;
        text-align: center;
    }
    
    .qs-quantity-display span {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .qs-footer {
        display: flex;
        gap: 12px;
    }
    
    .qs-btn-cancel {
        flex: 1;
        padding: 14px 20px;
        background: #f3f4f6;
        color: #374151;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .qs-btn-cancel:hover {
        background: #e5e7eb;
    }
    
    .qs-btn-add {
        flex: 2;
        padding: 14px 20px;
        background: linear-gradient(135deg, #f85606, #ff8a00);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .qs-btn-add:hover {
        background: linear-gradient(135deg, #d94a04, #f85606);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(248, 86, 6, 0.3);
    }
    
    .qs-btn-add:active {
        transform: translateY(0);
    }
    
    .qs-btn-add i {
        font-size: 18px;
    }
    
    /* Responsive */
    @media (max-width: 576px) {
        .qs-content {
            padding: 16px;
            border-radius: 20px 20px 0 0;
        }
        
        .qs-title {
            font-size: 16px;
        }
        
        .qs-product-image {
            width: 60px;
            height: 60px;
        }
        
        .qs-product-details h5 {
            font-size: 14px;
        }
        
        .qs-price {
            font-size: 16px;
        }
        
        .qs-btn-qty {
            width: 40px;
            height: 40px;
            font-size: 14px;
        }
        
        .qs-quantity-display span {
            font-size: 20px;
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

<script>
/* ============================================================
   Quantity Bottom Sheet — GLOBAL scope (works with onclick="")
============================================================ */
var qsProduct   = null;   // { id, name, unitPrice, currency }
var qsQty       = 1;

function qsFormatNum(n) {
    return n.toLocaleString('en-US', { maximumFractionDigits: 2 });
}

function openQuantityModal(productId, productName, priceText, productImage) {
    var currency = ((priceText || '').replace(/[0-9.,]/g, '').trim()) || '৳';
    var unit     = parseFloat((priceText || '0').replace(/[^0-9.]/g, '')) || 0;

    qsProduct = { id: productId, name: productName, unitPrice: unit, currency: currency };
    qsQty     = 1;

    document.getElementById('qsProductName').textContent = productName;
    document.getElementById('qsQuantity').textContent   = qsQty;
    document.getElementById('qsProductPrice').textContent = currency + qsFormatNum(unit) + ' x 1 = ' + currency + qsFormatNum(unit);

    var img = document.getElementById('qsProductImage');
    if (productImage) {
        img.src = productImage;
        img.parentElement.style.display = '';
    } else {
        img.parentElement.style.display = 'none';
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
    var next = qsQty + change;
    if (next < 1 || next > 99) return;

    qsQty = next;
    document.getElementById('qsQuantity').textContent = qsQty;
    document.getElementById('qsProductPrice').textContent =
        qsProduct.currency + qsFormatNum(qsProduct.unitPrice) + ' x ' + qsQty +
        ' = ' + qsProduct.currency + qsFormatNum(qsProduct.unitPrice * qsQty);
}

function confirmAddToCart() {
    if (!qsProduct) return;

    var $btn = $('.qs-btn-add').prop('disabled', true);

    $.post('/add-to-cart/product/' + qsProduct.id, {
        _token:   $('meta[name="csrf-token"]').attr('content'),
        quantity: qsQty
    }).done(function (res) {
        if (res.status === 'success') {
            if (typeof res.cartCount !== 'undefined') {
                $('.cart-count, .cart_count, #cartCount').text(res.cartCount);
            }
            showNotification(res.message || 'Product added to cart!', 'success');
        } else {
            showNotification(res.message || 'Could not add product.', 'error');
        }
        closeQuantityModal();
    }).fail(function () {
        showNotification('Something went wrong. Please try again.', 'error');
    }).always(function () {
        $btn.prop('disabled', false);
    });
}

function showNotification(message, type) {
    var $n = $('<div class="cart-notification"></div>').css({
        position: 'fixed', top: '20px', right: '20px', zIndex: 10000,
        display: 'flex', alignItems: 'center', gap: '12px',
        padding: '16px 20px', borderRadius: '12px', color: '#fff',
        background: type === 'success' ? '#10b981' : '#ef4444',
        boxShadow: '0 4px 12px rgba(0,0,0,.15)',
        fontSize: '14px', fontWeight: '500'
    }).html('<i class="fa-solid ' + (type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation') + '"></i><span></span>');

    $n.find('span').text(message);
    $n.css({ transform: 'translateX(120%)', transition: 'transform .3s ease' });
    $('body').append($n);
    requestAnimationFrame(function () { $n.css('transform', 'translateX(0)'); });

    setTimeout(function () {
        $n.css('transform', 'translateX(120%)');
        setTimeout(function () { $n.remove(); }, 300);
    }, 2500);
}

/* Close with ESC */
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeQuantityModal();
});

/* Product info comes straight from Blade (both desktop + sticky mobile CTA) */
$(document).on('click', '.add-to-cart', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation(); // stop footer's global add-to-cart AJAX
    e.stopPropagation();

    var href = $(this).attr('href') || '';
    var productId = href.split('/').pop();
    if (!productId) return;

    openQuantityModal(
        productId,
        @json($product->name ?? 'Product'),
        '৳' + @json(number_format((float)($product->price ?? 0), 0)),
        @json($product->thumbnail ? asset('uploads/product-images/' . $product->thumbnail) : '')
    );
});
</script>

@endsection
