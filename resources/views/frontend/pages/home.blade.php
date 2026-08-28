@extends('frontend.layout.app')

@push('header')
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<style>
/* ================================================================
   Root tokens  (shared across all sections)
================================================================ */
:root {
    --pr:      #f85606;
    --pr-dk:   #d94a04;
    --pr-lt:   #fff3ec;
    --pr-grd:  linear-gradient(135deg, #f85606, #ff8a00);
    --dark:    #0f172a;
    --text:    #374151;
    --muted:   #6b7280;
    --border:  #e5e7eb;
    --bg:      #f1f5f9;
    --card:    #ffffff;
    --t:       .22s ease;
    --r-xl:    20px;
    --r-lg:    14px;
    --r-md:    10px;
    --sh-sm:   0 2px 8px rgba(0,0,0,.06);
    --sh-md:   0 8px 28px rgba(0,0,0,.09);
    --sh-lg:   0 20px 50px rgba(0,0,0,.12);
}

*,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { width: 100%; overflow-x: hidden; scroll-behavior: smooth; }
a { text-decoration: none; }
img { max-width: 100%; display: block; }

/* ================================================================
   Section helpers
================================================================ */
.home-section      { padding: 72px 0; }
.home-section.bg-alt { background: var(--bg); }
.home-section.bg-white { background: var(--card); }

.section-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--pr-lt);
    color: var(--pr);
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.section-title {
    font-size: clamp(26px, 4vw, 38px);
    font-weight: 900;
    color: var(--dark);
    letter-spacing: -.5px;
    line-height: 1.2;
    margin: 0;
}

.section-title span { color: var(--pr); }

.section-subtitle {
    font-size: 15px;
    color: var(--muted);
    margin-top: 10px;
    max-width: 540px;
}

.section-bar {
    width: 56px;
    height: 4px;
    background: var(--pr-grd);
    border-radius: 4px;
    margin-top: 14px;
}

.section-head-center {
    text-align: center;
    margin-bottom: 42px;
}

.section-head-center .section-subtitle { margin: 10px auto 0; }

.view-all-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: var(--r-lg);
    padding: 10px 20px;
    font-size: 13px;
    font-weight: 700;
    color: var(--dark);
    transition: all var(--t);
}

.view-all-btn:hover {
    background: var(--pr-lt);
    border-color: var(--pr);
    color: var(--pr);
}

/* ================================================================
   1. HERO
================================================================ */
#home-hero {
    width: 100%;
    overflow: hidden;
    background: #000;
    padding: 0 !important;
}

.hero-carousel .carousel-item {
    position: relative;
    height: 88vh;
    min-height: 560px;
    background: #000;
    overflow: hidden;
}

.hero-carousel .carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.hero-carousel .carousel-item::before {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 1;
    background: linear-gradient(105deg,
        rgba(0,0,0,.80) 0%,
        rgba(0,0,0,.45) 50%,
        rgba(0,0,0,.10) 100%);
}

.hero-inner {
    position: absolute;
    z-index: 3;
    top: 50%;
    left: 8%;
    transform: translateY(-50%);
    max-width: 580px;
    color: #fff;
}

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(248,86,6,.22);
    border: 1px solid rgba(248,86,6,.45);
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: #ffd2b8;
    margin-bottom: 18px;
    backdrop-filter: blur(6px);
}

.hero-title {
    font-size: clamp(28px, 5vw, 54px);
    font-weight: 900;
    line-height: 1.1;
    letter-spacing: -.5px;
    margin-bottom: 16px;
    text-shadow: 0 2px 18px rgba(0,0,0,.3);
}

.hero-desc {
    font-size: clamp(14px, 1.8vw, 17px);
    line-height: 1.65;
    color: rgba(255,255,255,.84);
    margin-bottom: 28px;
    font-weight: 400;
}

.hero-cta-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.hero-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 800;
    font-size: 14px;
    letter-spacing: .3px;
    transition: all var(--t);
    border: 2px solid transparent;
}

.hero-btn-primary {
    background: var(--pr-grd);
    color: #fff;
    box-shadow: 0 10px 30px rgba(248,86,6,.45);
}

.hero-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 36px rgba(248,86,6,.55);
    color: #fff;
}

.hero-btn-outline {
    background: rgba(255,255,255,.1);
    border-color: rgba(255,255,255,.5);
    color: #fff;
    backdrop-filter: blur(6px);
}

.hero-btn-outline:hover {
    background: rgba(255,255,255,.2);
    border-color: #fff;
    color: #fff;
}

/* Indicators */
.hero-carousel .carousel-indicators {
    z-index: 5;
    bottom: 28px;
    gap: 5px;
}

.hero-carousel .carousel-indicators button {
    width: 8px !important;
    height: 8px !important;
    border-radius: 50% !important;
    background: rgba(255,255,255,.55) !important;
    border: none !important;
    padding: 0 !important;
    transition: all var(--t);
}

.hero-carousel .carousel-indicators button.active {
    width: 26px !important;
    border-radius: 4px !important;
    background: var(--pr) !important;
}

/* Controls */
.hero-carousel .carousel-control-prev,
.hero-carousel .carousel-control-next {
    z-index: 4;
    width: 6%;
    opacity: 0;
    transition: opacity var(--t);
}

.hero-carousel:hover .carousel-control-prev,
.hero-carousel:hover .carousel-control-next { opacity: 1; }

.hero-carousel .carousel-control-prev-icon,
.hero-carousel .carousel-control-next-icon {
    width: 44px;
    height: 44px;
    background-color: rgba(255,255,255,.15);
    border-radius: 50%;
    background-size: 50%;
    backdrop-filter: blur(6px);
}

/* Scroll cue */
.hero-scroll-cue {
    position: absolute;
    z-index: 5;
    bottom: 36px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    color: rgba(255,255,255,.6);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    animation: scrollBounce 2s infinite;
}

.hero-scroll-cue i { font-size: 14px; }

@keyframes scrollBounce {
    0%, 100% { transform: translateX(-50%) translateY(0); }
    50%       { transform: translateX(-50%) translateY(6px); }
}

/* ================================================================
   2. STATS STRIP
================================================================ */
.stats-strip {
    background: var(--pr-grd);
    padding: 0;
}

.stats-inner {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    divide-x: 1px solid rgba(255,255,255,.2);
}

.stat-item {
    padding: 28px 20px;
    text-align: center;
    border-right: 1px solid rgba(255,255,255,.18);
    position: relative;
}

.stat-item:last-child { border-right: none; }

.stat-number {
    font-size: clamp(28px, 3.5vw, 40px);
    font-weight: 900;
    color: #fff;
    line-height: 1;
    letter-spacing: -1px;
}

.stat-label {
    font-size: 12px;
    color: rgba(255,255,255,.78);
    font-weight: 600;
    margin-top: 5px;
    letter-spacing: .3px;
}

/* ================================================================
   3. PARTNERS / BRANDS
================================================================ */
#home-partners {
    background: var(--dark);
    padding: 56px 0;
    overflow: hidden;
}

.partners-head {
    text-align: center;
    margin-bottom: 38px;
}

.partners-head .section-title { color: #fff; }
.partners-head .section-bar { margin: 14px auto 0; }

.partners-track-wrap {
    overflow: hidden;
    -webkit-mask-image: linear-gradient(90deg, transparent 0%, #000 10%, #000 90%, transparent 100%);
    mask-image: linear-gradient(90deg, transparent 0%, #000 10%, #000 90%, transparent 100%);
}

.partners-track {
    display: flex;
    gap: 18px;
    animation: partnerScroll 28s linear infinite;
    width: max-content;
}

.partners-track:hover { animation-play-state: paused; }

@keyframes partnerScroll {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}

.partner-logo {
    flex-shrink: 0;
    width: 140px;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: var(--r-xl);
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--t);
    cursor: pointer;
}

.partner-logo:hover {
    background: rgba(255,255,255,.12);
    border-color: rgba(248,86,6,.5);
    transform: translateY(-4px);
}

.partner-logo img {
    width: 100%;
    height: 60px;
    object-fit: contain;
    filter: brightness(0) invert(1);
    opacity: .65;
    transition: all var(--t);
}

.partner-logo:hover img {
    filter: none;
    opacity: 1;
}

/* ================================================================
   4. FEATURED PRODUCTS
================================================================ */
#home-products {
    background: var(--bg);
    padding: 72px 0;
}

.section-topbar {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 36px;
    gap: 16px;
    flex-wrap: wrap;
}

/* Owl product card */
.hpc-wrap { padding: 4px 0; }

.hpc {
    background: var(--card);
    border-radius: var(--r-xl);
    box-shadow: var(--sh-sm);
    overflow: hidden;
    transition: transform var(--t), box-shadow var(--t);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.hpc:hover {
    transform: translateY(-5px);
    box-shadow: var(--sh-md);
}

.hpc-img-wrap {
    position: relative;
    aspect-ratio: 1/1;
    overflow: hidden;
    background: #f8fafc;
}

.hpc-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
    transition: transform .35s ease;
}

.hpc:hover .hpc-img { transform: scale(1.07); }

.hpc-body {
    padding: 12px 14px 14px;
    display: flex;
    flex-direction: column;
    flex: 1;
    gap: 4px;
}

.hpc-name {
    font-size: 13px;
    font-weight: 700;
    color: var(--dark);
    text-transform: uppercase;
    letter-spacing: .2px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 36px;
}

.hpc-code {
    font-size: 11px;
    color: var(--muted);
    font-weight: 500;
}

.hpc-attrs {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 4px;
}

.hpc-attr {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 10px;
    font-weight: 600;
    color: #475569;
    padding: 2px 7px;
    white-space: nowrap;
}

.hpc-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin-top: auto;
    padding-top: 10px;
    font-size: 11px;
    font-weight: 700;
    color: var(--pr);
}

/* Owl overrides for home */
.home-owl .owl-nav button.owl-prev,
.home-owl .owl-nav button.owl-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50% !important;
    background: var(--card) !important;
    color: var(--pr) !important;
    box-shadow: var(--sh-md) !important;
    font-size: 18px !important;
    line-height: 1 !important;
    border: 1.5px solid var(--border) !important;
    transition: all var(--t) !important;
}

.home-owl .owl-nav button.owl-prev:hover,
.home-owl .owl-nav button.owl-next:hover {
    background: var(--pr) !important;
    color: #fff !important;
    border-color: var(--pr) !important;
}

.home-owl .owl-nav button.owl-prev { left: -20px; }
.home-owl .owl-nav button.owl-next { right: -20px; }

.home-owl .owl-dots { margin-top: 20px; }
.home-owl .owl-dot span { background: #ffd4bd !important; width: 8px !important; height: 8px !important; }
.home-owl .owl-dot.active span { background: var(--pr) !important; width: 22px !important; border-radius: 4px !important; }

/* ================================================================
   5. CATEGORIES
================================================================ */
#home-categories {
    background: var(--card);
    padding: 72px 0;
}

.cat-card-wrap { padding: 4px; }

.cat-card {
    display: block;
    border-radius: var(--r-xl);
    overflow: hidden;
    position: relative;
    aspect-ratio: 3/4;
    box-shadow: var(--sh-sm);
    transition: transform var(--t), box-shadow var(--t);
}

.cat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--sh-md);
}

.cat-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s ease;
    display: block;
}

.cat-card:hover img { transform: scale(1.07); }

.cat-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 35%, rgba(0,0,0,.78) 100%);
}

.cat-card-body {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 2;
    padding: 16px;
}

.cat-card-name {
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .4px;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.cat-card-cta {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(248,86,6,.85);
    color: #fff;
    border-radius: 7px;
    padding: 4px 10px;
    font-size: 10px;
    font-weight: 800;
    margin-top: 6px;
    letter-spacing: .3px;
    backdrop-filter: blur(4px);
    transition: background var(--t);
}

.cat-card:hover .cat-card-cta { background: var(--pr); }

/* ================================================================
   6. SERVICES
================================================================ */
#home-services {
    background: var(--bg);
    padding: 72px 0;
}

.svc-card-wrap { padding: 4px; }

.svc-card {
    display: block;
    background: var(--card);
    border-radius: var(--r-xl);
    overflow: hidden;
    box-shadow: var(--sh-sm);
    transition: transform var(--t), box-shadow var(--t);
    color: var(--dark);
    height: 100%;
}

.svc-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--sh-md);
    color: var(--dark);
}

.svc-img-wrap {
    position: relative;
    aspect-ratio: 16/9;
    overflow: hidden;
    background: #f8fafc;
}

.svc-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .35s ease;
    display: block;
}

.svc-card:hover .svc-img { transform: scale(1.06); }

.svc-icon-badge {
    position: absolute;
    bottom: -1px;
    right: 16px;
    width: 44px;
    height: 44px;
    background: var(--pr-grd);
    border-radius: 12px 12px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    box-shadow: 0 -4px 14px rgba(248,86,6,.3);
}

.svc-body {
    padding: 16px 18px 18px;
}

.svc-name {
    font-size: 14px;
    font-weight: 800;
    color: var(--dark);
    text-transform: uppercase;
    letter-spacing: .2px;
    line-height: 1.35;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.svc-desc {
    font-size: 12px;
    color: var(--muted);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.svc-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 18px;
    border-top: 1px solid var(--border);
    font-size: 11px;
    font-weight: 700;
    color: var(--pr);
}

.svc-footer i { transition: transform var(--t); }
.svc-card:hover .svc-footer i { transform: translateX(4px); }

/* ================================================================
   7. NEWS
================================================================ */
#home-news {
    background: var(--card);
    padding: 72px 0;
}

.news-card-wrap { padding: 4px; }

.news-card {
    display: block;
    background: var(--card);
    border-radius: var(--r-xl);
    overflow: hidden;
    box-shadow: var(--sh-sm);
    border: 1.5px solid var(--border);
    transition: transform var(--t), box-shadow var(--t), border-color var(--t);
    color: var(--dark);
    height: 100%;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--sh-md);
    border-color: rgba(248,86,6,.3);
    color: var(--dark);
}

.news-img-wrap {
    aspect-ratio: 16/9;
    overflow: hidden;
    background: #f8fafc;
}

.news-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .35s ease;
    display: block;
}

.news-card:hover .news-img { transform: scale(1.05); }

.news-body {
    padding: 16px 18px 18px;
}

.news-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}

.news-cat-tag {
    display: inline-block;
    background: var(--pr-lt);
    color: var(--pr);
    border-radius: 6px;
    padding: 2px 9px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.news-date {
    font-size: 11px;
    color: var(--muted);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
}

.news-title {
    font-size: 14px;
    font-weight: 800;
    color: var(--dark);
    line-height: 1.4;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-card:hover .news-title { color: var(--pr); }

.news-excerpt {
    font-size: 12px;
    color: var(--muted);
    line-height: 1.65;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-read-more {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 700;
    color: var(--pr);
    margin-top: 12px;
    transition: gap var(--t);
}

.news-card:hover .news-read-more { gap: 8px; }

/* ================================================================
   8. CTA BANNER
================================================================ */
.home-cta-banner {
    background: var(--pr-grd);
    padding: 64px 0;
    position: relative;
    overflow: hidden;
}

.home-cta-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='.04'%3E%3Cpath d='M48 46v-6h-2v6h-6v2h6v6h2v-6h6v-2h-6zm0-40V0h-2v6h-6v2h6v6h2V8h6V6h-6zM8 46v-6H6v6H0v2h6v6h2v-6h6v-2H8zM8 6V0H6v6H0v2h6v6h2V8h6V6H8z'/%3E%3C/g%3E%3C/svg%3E");
    pointer-events: none;
}

.cta-banner-inner {
    position: relative;
    z-index: 1;
    text-align: center;
}

.cta-banner-title {
    font-size: clamp(26px, 4vw, 42px);
    font-weight: 900;
    color: #fff;
    letter-spacing: -.5px;
    line-height: 1.2;
    margin-bottom: 14px;
}

.cta-banner-sub {
    font-size: 16px;
    color: rgba(255,255,255,.82);
    margin-bottom: 30px;
    max-width: 520px;
    margin-left: auto;
    margin-right: auto;
}

.cta-banner-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: var(--pr);
    border-radius: 50px;
    padding: 15px 38px;
    font-weight: 900;
    font-size: 15px;
    transition: all var(--t);
    box-shadow: 0 10px 30px rgba(0,0,0,.2);
    border: 2px solid transparent;
}

.cta-banner-btn:hover {
    background: transparent;
    border-color: #fff;
    color: #fff;
    transform: translateY(-2px);
}

/* ================================================================
   RESPONSIVE
================================================================ */
@media (max-width: 1023px) {
    .stats-inner { grid-template-columns: repeat(2, 1fr); }
    .stat-item:nth-child(2) { border-right: none; }
}

@media (max-width: 767px) {
    .home-section, #home-products, #home-categories,
    #home-services, #home-news, #home-partners,
    .home-cta-banner { padding: 50px 0; }

    .hero-carousel .carousel-item {
        height: 92vh;
        min-height: 580px;
    }

    .hero-inner {
        top: auto;
        bottom: 80px;
        left: 16px;
        right: 16px;
        transform: none;
        max-width: none;
        text-align: center;
    }

    .hero-cta-group { justify-content: center; }

    .hero-carousel .carousel-item::before {
        background: linear-gradient(180deg,
            rgba(0,0,0,.1) 0%,
            rgba(0,0,0,.45) 45%,
            rgba(0,0,0,.88) 100%);
    }

    .hero-scroll-cue { display: none; }

    .hero-carousel .carousel-control-prev,
    .hero-carousel .carousel-control-next { display: none; }

    .stats-inner { grid-template-columns: repeat(2, 1fr); }

    .section-topbar { flex-direction: column; align-items: flex-start; }

    .home-owl .owl-nav { display: none; }

    .partner-logo { width: 110px; }
    .partner-logo img { height: 50px; }

    .section-title { font-size: 24px; }

    .cta-banner-btn { width: 100%; justify-content: center; }
}

@media (max-width: 479px) {
    .hero-btn { padding: 13px 22px; font-size: 13px; }
    .stat-number { font-size: 28px; }
}
</style>
@endpush

@section('content')

{{-- ================================================================
     1. HERO
================================================================ --}}
<section id="home-hero">
    <div id="homeHeroCarousel" class="hero-carousel carousel slide w-100" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="5000">

        <div class="carousel-indicators">
            @foreach ($banners as $banner)
                <button type="button" data-bs-target="#homeHeroCarousel"
                    data-bs-slide-to="{{ $loop->index }}"
                    class="{{ $loop->first ? 'active' : '' }}"
                    aria-current="{{ $loop->first ? 'true' : 'false' }}"
                    aria-label="Slide {{ $loop->iteration }}">
                </button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @foreach ($banners as $banner)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ asset('uploads/resource-images/' . $banner->image) }}"
                        alt="{{ $banner->title ?? 'Banner' }}"
                        loading="{{ $loop->first ? 'eager' : 'lazy' }}">

                    <div class="hero-inner">
                        <div class="hero-eyebrow">
                            <i class="fa-solid fa-bolt" style="font-size:10px;"></i>
                            {{ trans('language.featured_products') }}
                        </div>
                        <h1 class="hero-title" style="color: {{ $banner->title_color ?? '#ffffff' }};">
                            {!! nl2br(e($banner->title)) !!}
                        </h1>
                        @if ($banner->details)
                            <p class="hero-desc" style="color: {{ $banner->details_color ?? 'rgba(255,255,255,.84)' }};">
                                {{ $banner->details }}
                            </p>
                        @endif
                        @if (!empty($banner->button_text))
                            <div class="hero-cta-group">
                                <a href="{{ route('products') }}"
                                    class="hero-btn hero-btn-primary"
                                    style="background: {{ $banner->button_color ?? '' }};">
                                    <i class="fa-solid fa-arrow-right"></i>
                                    {{ $banner->button_text }}
                                </a>
                                <a href="{{ route('contact-us') }}" class="hero-btn hero-btn-outline">
                                    <i class="fa-solid fa-phone"></i>
                                    {{ trans('language.contact') ?? 'Contact Us' }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    {{-- Scroll cue --}}
    <div class="hero-scroll-cue">
        <span>Scroll</span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>
</section>

{{-- ================================================================
     2. STATS STRIP
================================================================ --}}
<div class="stats-strip">
    <div class="container">
        <div class="stats-inner">
            <div class="stat-item">
                <div class="stat-number">{{ $products->count() }}+</div>
                <div class="stat-label">{{ trans('language.featured_products') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $partners->count() }}+</div>
                <div class="stat-label">{{ trans('language.proud_partners') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $categories->count() }}+</div>
                <div class="stat-label">{{ trans('language.product_category') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $services->count() }}+</div>
                <div class="stat-label">{{ trans('language.services') ?? 'Services' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     3. PARTNERS
================================================================ --}}
@if ($partners->count() > 0)
<section id="home-partners">
    <div class="container">
        <div class="partners-head">
            <span class="section-label" style="background:rgba(248,86,6,.15); border:1px solid rgba(248,86,6,.3);">
                <i class="fa-solid fa-handshake" style="font-size:10px;"></i>
                {{ trans('language.proud_partners') }}
            </span>
            <h2 class="section-title" style="color:#fff;">{{ trans('language.proud_partners') }}</h2>
            <div class="section-bar" style="margin:14px auto 0;"></div>
        </div>
    </div>

    <div class="partners-track-wrap">
        <div class="partners-track">
            {{-- Duplicate the list for seamless infinite scroll --}}
            @foreach ([$partners, $partners] as $batch)
                @foreach ($batch as $partner)
                    <a href="{{ route('brand.products', $partner->slug) }}" class="partner-logo" title="{{ $partner->title }}">
                        <img src="{{ asset('uploads/brand-images/' . $partner->image) }}"
                            alt="{{ $partner->title }}" loading="lazy">
                    </a>
                @endforeach
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     4. FEATURED PRODUCTS
================================================================ --}}
@if ($products->count() > 0)
<section id="home-products">
    <div class="container">
        <div class="section-topbar">
            <div>
                <span class="section-label">
                    <i class="fa-solid fa-star" style="font-size:10px;"></i>
                    {{ trans('language.featured_products') }}
                </span>
                <h2 class="section-title">{{ trans('language.featured_products') }}</h2>
                <div class="section-bar"></div>
            </div>
            <a href="{{ route('products') }}" class="view-all-btn">
                {{ trans('language.products') }}
                <i class="fa-solid fa-arrow-right" style="font-size:11px;"></i>
            </a>
        </div>

        <div class="owl-carousel home-owl" id="featuredProductsCarousel">
            @foreach ($products as $product)
                @php
                    $lang = Session::get('language') ?? 'en';
                    $pName = Str::limit($product->getTranslation($lang, 'name') ?? $product->name, 50, '…');
                    $featAttrs = Cache::remember("feature_product_attributes_{$product->id}", now()->addHours(1), fn() => $product->attributes);
                @endphp
                <div class="hpc-wrap">
                    <a href="{{ url('product/' . $product->slug) }}" style="display:block; height:100%;">
                        <div class="hpc">
                            <div class="hpc-img-wrap">
                                <img class="hpc-img"
                                    src="{{ asset('uploads/product-images/' . $product->thumbnail) }}"
                                    alt="{{ $pName }}"
                                    loading="lazy">
                            </div>
                            <div class="hpc-body">
                                <p class="hpc-name">{{ $pName }}</p>
                                <span class="hpc-code">{{ $product->code }}</span>
                                @php $filteredAttrs = $featAttrs->where('is_filter', 1)->take(2); @endphp
                                @if ($filteredAttrs->count())
                                    <div class="hpc-attrs">
                                        @foreach ($filteredAttrs as $attr)
                                            <span class="hpc-attr">{{ $attr->attribute_name }}: {{ $attr->value }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="hpc-arrow">
                                    View Details <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     5. PRODUCT CATEGORIES
================================================================ --}}
@if ($categories->count() > 0)
<section id="home-categories">
    <div class="container">
        <div class="section-topbar">
            <div>
                <span class="section-label">
                    <i class="fa-solid fa-layer-group" style="font-size:10px;"></i>
                    {{ trans('language.product_category') }}
                </span>
                <h2 class="section-title">{{ trans('language.product_category') }}</h2>
                <div class="section-bar"></div>
            </div>
            <a href="{{ route('categories') }}" class="view-all-btn">
                {{ trans('language.categories') ?? 'All Categories' }}
                <i class="fa-solid fa-arrow-right" style="font-size:11px;"></i>
            </a>
        </div>

        <div class="owl-carousel home-owl" id="categoriesCarousel">
            @foreach ($categories as $category)
                @php
                    $lang = Session::get('language') ?? 'en';
                    $catName = $category->getTranslation($lang, 'title') ?? $category->title;
                    $catImg = (!empty($category->image) && file_exists(public_path('uploads/category-images/' . $category->image)))
                        ? asset('uploads/category-images/' . $category->image)
                        : asset('assets/img/medicine.png');
                @endphp
                <div class="cat-card-wrap">
                    <a href="{{ url('category/' . $category->slug) }}" class="cat-card">
                        <img src="{{ $catImg }}" alt="{{ $catName }}" loading="lazy">
                        <div class="cat-card-body">
                            <p class="cat-card-name">{{ $catName }}</p>
                            <span class="cat-card-cta">
                                Browse <i class="fa-solid fa-arrow-right" style="font-size:9px;"></i>
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     6. SERVICES
================================================================ --}}
@if ($services->count() > 0)
<section id="home-services">
    <div class="container">
        <div class="section-topbar">
            <div>
                <span class="section-label">
                    <i class="fa-solid fa-screwdriver-wrench" style="font-size:10px;"></i>
                    {{ trans('language.services') ?? 'Services' }}
                </span>
                <h2 class="section-title">{{ trans('language.services') ?? 'Our Services' }}</h2>
                <div class="section-bar"></div>
            </div>
            <a href="{{ route('services') }}" class="view-all-btn">
                {{ trans('language.services') ?? 'All Services' }}
                <i class="fa-solid fa-arrow-right" style="font-size:11px;"></i>
            </a>
        </div>

        <div class="owl-carousel home-owl" id="servicesCarousel">
            @foreach ($services as $service)
                @php
                    $lang = Session::get('language') ?? 'en';
                    $svcName = $service->getTranslation($lang, 'title') ?? $service->title;
                    $svcDesc = $service->getTranslation($lang, 'short_description') ?? $service->short_description;
                @endphp
                <div class="svc-card-wrap">
                    <a href="{{ route('service.details', $service->id) }}" class="svc-card">
                        <div class="svc-img-wrap">
                            @if ($service->media)
                                <img class="svc-img"
                                    src="{{ asset('uploads/service-images/' . $service->media) }}"
                                    alt="{{ $svcName }}" loading="lazy">
                            @else
                                <div style="width:100%;height:100%;background:var(--bg);display:flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-screwdriver-wrench" style="font-size:36px;color:var(--border);"></i>
                                </div>
                            @endif
                            <div class="svc-icon-badge">
                                <i class="fa-solid fa-gear"></i>
                            </div>
                        </div>
                        <div class="svc-body">
                            <p class="svc-name">{{ $svcName }}</p>
                            @if ($svcDesc)
                                <p class="svc-desc">{{ $svcDesc }}</p>
                            @endif
                        </div>
                        <div class="svc-footer">
                            <span>{{ trans('language.btn_details') ?? 'View Details' }}</span>
                            <i class="fa-solid fa-arrow-right" style="font-size:12px;"></i>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     7. NEWS
================================================================ --}}
@if ($newses->count() > 0)
<section id="home-news">
    <div class="container">
        <div class="section-topbar">
            <div>
                <span class="section-label">
                    <i class="fa-solid fa-newspaper" style="font-size:10px;"></i>
                    {{ trans('language.news') ?? 'News' }}
                </span>
                <h2 class="section-title">{{ trans('language.news') ?? 'Latest News' }}</h2>
                <div class="section-bar"></div>
            </div>
            <a href="{{ route('news') }}" class="view-all-btn">
                {{ trans('language.news') ?? 'All News' }}
                <i class="fa-solid fa-arrow-right" style="font-size:11px;"></i>
            </a>
        </div>

        <div class="owl-carousel home-owl" id="newsCarousel">
            @foreach ($newses as $news)
                @php
                    $lang = Session::get('language') ?? 'en';
                    $newsTitle = $news->getTranslation($lang, 'title') ?? $news->title;
                    $newsDesc  = $news->getTranslation($lang, 'short_description') ?? $news->short_description;
                @endphp
                <div class="news-card-wrap">
                    <a href="{{ route('news.details', $news->id) }}" class="news-card">
                        <div class="news-img-wrap">
                            @if ($news->media)
                                <img class="news-img"
                                    src="{{ asset('uploads/news-images/' . $news->media) }}"
                                    alt="{{ $newsTitle }}" loading="lazy">
                            @else
                                <div style="width:100%;height:100%;background:var(--bg);display:flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-newspaper" style="font-size:36px;color:var(--border);"></i>
                                </div>
                            @endif
                        </div>
                        <div class="news-body">
                            <div class="news-meta">
                                @if ($news->category)
                                    <span class="news-cat-tag">{{ $news->category }}</span>
                                @endif
                                @if ($news->publish_date)
                                    <span class="news-date">
                                        <i class="fa-regular fa-calendar" style="font-size:10px;"></i>
                                        {{ \Carbon\Carbon::parse($news->publish_date)->format('M j, Y') }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="news-title">{{ $newsTitle }}</h3>
                            @if ($newsDesc)
                                <p class="news-excerpt">{{ $newsDesc }}</p>
                            @endif
                            <span class="news-read-more">
                                Read more <i class="fa-solid fa-arrow-right" style="font-size:10px;"></i>
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     8. CTA BANNER
================================================================ --}}
<div class="home-cta-banner">
    <div class="container">
        <div class="cta-banner-inner">
            <h2 class="cta-banner-title">Ready to Find the Right Product?</h2>
            <p class="cta-banner-sub">Browse our full catalogue or get in touch — our team is ready to help.</p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('products') }}" class="cta-banner-btn">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    {{ trans('language.products') }}
                </a>
                <a href="{{ route('contact-us') }}"
                    style="display:inline-flex;align-items:center;gap:8px;border:2px solid rgba(255,255,255,.6);border-radius:50px;padding:15px 32px;font-weight:800;font-size:15px;color:rgba(255,255,255,.9);transition:all .22s ease;"
                    onmouseover="this.style.borderColor='#fff';this.style.color='#fff';"
                    onmouseout="this.style.borderColor='rgba(255,255,255,.6)';this.style.color='rgba(255,255,255,.9)';">
                    <i class="fa-solid fa-envelope"></i>
                    {{ trans('language.contact') ?? 'Contact Us' }}
                </a>
            </div>
        </div>
    </div>
</div>

@push('footer')
<script>
$(function () {

    var owlDefaults = {
        loop: true,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 4500,
        autoplayHoverPause: true,
        margin: 18,
        responsive: {
            0:    { items: 2, margin: 10, nav: false },
            480:  { items: 2, margin: 12, nav: false },
            768:  { items: 3, margin: 14 },
            992:  { items: 4, margin: 16 },
            1200: { items: 5, margin: 18 }
        }
    };

    var owlFewer = {
        loop: true,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 4500,
        autoplayHoverPause: true,
        margin: 18,
        responsive: {
            0:   { items: 1, margin: 10, nav: false },
            480: { items: 2, margin: 12, nav: false },
            768: { items: 2, margin: 14 },
            992: { items: 3, margin: 16 },
            1200:{ items: 4, margin: 18 }
        }
    };

    // Featured Products
    $('#featuredProductsCarousel').owlCarousel(owlDefaults);

    // Categories
    $('#categoriesCarousel').owlCarousel(owlDefaults);

    // Services
    $('#servicesCarousel').owlCarousel(owlFewer);

    // News
    $('#newsCarousel').owlCarousel(owlFewer);

    /* ================================================================
       Counter animation for stats strip
    ================================================================ */
    function animateCounter($el, target) {
        var start = 0;
        var duration = 1800;
        var step = Math.ceil(target / (duration / 16));
        var timer = setInterval(function () {
            start += step;
            if (start >= target) {
                start = target;
                clearInterval(timer);
            }
            $el.text(start + '+');
        }, 16);
    }

    var statsAnimated = false;
    var $stats = $('.stat-number');

    function checkStats() {
        if (statsAnimated) return;
        var rect = document.querySelector('.stats-strip');
        if (!rect) return;
        var bounds = rect.getBoundingClientRect();
        if (bounds.top < window.innerHeight) {
            statsAnimated = true;
            $stats.each(function () {
                var $el = $(this);
                var raw = parseInt($el.text().replace(/\D/g, ''), 10) || 0;
                animateCounter($el, raw);
            });
        }
    }

    $(window).on('scroll', checkStats);
    checkStats();
});
</script>
@endpush

@endsection
