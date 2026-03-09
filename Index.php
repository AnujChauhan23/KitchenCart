<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KitchenCart — Smarter B2B Procurement for Restaurants</title>
    <meta name="description" content="KitchenCart helps restaurants compare daily raw material prices from multiple vendors and place orders instantly. Built for the modern kitchen.">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        /* Landing Page Specific Overrides */
        body {
            display: block; /* Override app-container flex */
        }

        /* Top Navigation */
        .landing-nav {
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: hsl(var(--card) / 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid hsl(var(--border));
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 4rem;
        }

        .landing-nav .logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: hsl(var(--foreground));
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .landing-nav .logo i {
            color: hsl(var(--primary));
            font-size: 1.5rem;
        }

        .nav-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: calc(var(--radius) - 2px);
            font-size: 0.875rem;
            font-weight: 500;
            color: hsl(var(--foreground));
            transition: background-color 0.2s;
        }

        .btn-ghost:hover {
            background-color: hsl(var(--muted));
        }

        /* Hero */
        .hero {
            padding: 6rem 2rem 5rem;
            text-align: center;
            max-width: 860px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.875rem;
            background-color: hsl(var(--primary) / 0.1);
            color: hsl(var(--primary));
            border: 1px solid hsl(var(--primary) / 0.2);
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1.75rem;
            letter-spacing: 0.03em;
        }

        .hero h1 {
            font-size: clamp(2.25rem, 5vw, 3.75rem);
            line-height: 1.15;
            font-weight: 700;
            letter-spacing: -0.04em;
            color: hsl(var(--foreground));
            margin-bottom: 1.25rem;
        }

        .hero h1 span {
            color: hsl(var(--primary));
        }

        .hero p {
            font-size: 1.125rem;
            color: hsl(var(--muted-foreground));
            max-width: 580px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-lg {
            padding: 0.75rem 1.75rem;
            font-size: 1rem;
            border-radius: calc(var(--radius));
        }

        /* Stats Bar */
        .stats-bar {
            border-top: 1px solid hsl(var(--border));
            border-bottom: 1px solid hsl(var(--border));
            background-color: hsl(var(--card));
            padding: 2rem;
            display: flex;
            justify-content: center;
            gap: 4rem;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item .num {
            font-size: 2rem;
            font-weight: 700;
            color: hsl(var(--primary));
            display: block;
        }

        .stat-item .label {
            font-size: 0.875rem;
            color: hsl(var(--muted-foreground));
            margin-top: 0.25rem;
        }

        /* Sections */
        .section {
            padding: 5rem 2rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .section-tag {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 600;
            color: hsl(var(--primary));
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.75rem;
        }

        .section-title {
            font-size: clamp(1.5rem, 3vw, 2.25rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            margin-bottom: 0.75rem;
        }

        .section-subtitle {
            color: hsl(var(--muted-foreground));
            font-size: 1.0625rem;
            max-width: 520px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
            background-color: hsl(var(--primary) / 0.1);
            border-radius: calc(var(--radius) - 2px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: hsl(var(--primary));
            font-size: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .feature-card p {
            color: hsl(var(--muted-foreground));
            font-size: 0.9375rem;
            line-height: 1.65;
        }

        /* How It Works */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 1.5rem;
            position: relative;
        }

        .step-card {
            padding: 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .step-number {
            width: 2.5rem;
            height: 2.5rem;
            background-color: hsl(var(--primary));
            color: hsl(var(--primary-foreground));
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .step-card h3 {
            font-size: 1.0625rem;
            font-weight: 600;
        }

        .step-card p {
            color: hsl(var(--muted-foreground));
            font-size: 0.9rem;
            line-height: 1.65;
        }

        /* CTA Section */
        .cta-section {
            background-color: hsl(var(--primary));
            padding: 5rem 2rem;
            text-align: center;
        }

        .cta-section h2 {
            font-size: clamp(1.75rem, 3vw, 2.5rem);
            font-weight: 700;
            color: hsl(var(--primary-foreground));
            margin-bottom: 1rem;
            letter-spacing: -0.03em;
        }

        .cta-section p {
            color: hsl(var(--primary-foreground) / 0.8);
            font-size: 1.0625rem;
            margin-bottom: 2.5rem;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-white {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.75rem;
            border-radius: var(--radius);
            background-color: hsl(var(--primary-foreground));
            color: hsl(var(--primary));
            font-size: 1rem;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .btn-white:hover {
            opacity: 0.92;
        }

        /* Footer */
        .landing-footer {
            border-top: 1px solid hsl(var(--border));
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .landing-footer .logo {
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: hsl(var(--foreground));
        }

        .landing-footer .logo i {
            color: hsl(var(--primary));
        }

        .landing-footer p {
            font-size: 0.875rem;
            color: hsl(var(--muted-foreground));
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="landing-nav">
    <div class="logo">
        <i class="ph-fill ph-storefront"></i>
        KitchenCart
    </div>
    <div class="nav-actions">
        <a href="auth/login.php" class="btn-ghost">Sign In</a>
        <a href="auth/register.php" class="btn-primary btn-lg" style="font-size:0.875rem; padding: 0.5rem 1.25rem;">Get Started</a>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="hero-badge">
        <i class="ph ph-sparkle"></i> B2B Procurement, Simplified
    </div>
    <h1>Buy smarter.<br><span>Spend less.</span> Cook better.</h1>
    <p>KitchenCart connects restaurants with verified vendors — compare daily raw material prices, place orders instantly, and track every delivery in one place.</p>
    <div class="hero-actions">
        <a href="auth/register.php" class="btn-primary btn-lg">
            <i class="ph ph-arrow-right" style="margin-right: 0.5rem;"></i> Start Ordering
        </a>
        <a href="#how-it-works" class="btn-ghost btn-lg" style="border: 1px solid hsl(var(--border));">
            See How It Works
        </a>
    </div>
</section>

<!-- Stats Bar -->
<div class="stats-bar">
    <div class="stat-item">
        <span class="num">3x</span>
        <span class="label">Faster Procurement</span>
    </div>
    <div class="stat-item">
        <span class="num">100%</span>
        <span class="label">Price Transparency</span>
    </div>
    <div class="stat-item">
        <span class="num">Live</span>
        <span class="label">Daily Vendor Prices</span>
    </div>
    <div class="stat-item">
        <span class="num">Real-time</span>
        <span class="label">Order Tracking</span>
    </div>
</div>

<!-- Features -->
<div class="section">
    <div class="section-header">
        <span class="section-tag">Features</span>
        <h2 class="section-title">Everything you need to run a smart kitchen</h2>
        <p class="section-subtitle">From price comparison to order delivery tracking — we've built it all into one clean platform.</p>
    </div>
    <div class="features-grid">
        <div class="card-elevated feature-card">
            <div class="feature-icon"><i class="ph ph-chart-bar"></i></div>
            <h3>Live Price Comparison</h3>
            <p>See daily prices updated by multiple vendors for every ingredient. Instantly spot the best deal with our "Best Price" indicator.</p>
        </div>
        <div class="card-elevated feature-card">
            <div class="feature-icon"><i class="ph ph-shopping-bag"></i></div>
            <h3>One-Click Ordering</h3>
            <p>Place orders directly from the price comparison view. Set your quantity and submit — it's that simple.</p>
        </div>
        <div class="card-elevated feature-card">
            <div class="feature-icon"><i class="ph ph-trend-up"></i></div>
            <h3>Price Trend Tracking</h3>
            <p>Track how prices have moved since yesterday. Make smarter buying decisions based on real historical context.</p>
        </div>
        <div class="card-elevated feature-card">
            <div class="feature-icon"><i class="ph ph-package"></i></div>
            <h3>Order Status Updates</h3>
            <p>Vendors update order status from Pending to Accepted to Delivered. You always know exactly where your goods are.</p>
        </div>
        <div class="card-elevated feature-card">
            <div class="feature-icon"><i class="ph ph-shield-check"></i></div>
            <h3>Verified Vendors</h3>
            <p>Only verified vendors can list products on KitchenCart. No more guessing about supplier reliability.</p>
        </div>
        <div class="card-elevated feature-card">
            <div class="feature-icon"><i class="ph ph-squares-four"></i></div>
            <h3>Analytics Dashboard</h3>
            <p>Track your total spend, active orders, and procurement trends from a clean, data-driven dashboard.</p>
        </div>
    </div>
</div>

<!-- How It Works -->
<div style="background-color: hsl(var(--card)); border-top: 1px solid hsl(var(--border)); border-bottom: 1px solid hsl(var(--border));">
    <div class="section" id="how-it-works">
        <div class="section-header">
            <span class="section-tag">How It Works</span>
            <h2 class="section-title">From price to plate in 3 steps</h2>
            <p class="section-subtitle">KitchenCart makes daily procurement as smooth as possible for your team.</p>
        </div>
        <div class="steps-grid">
            <div class="card-elevated step-card">
                <div class="step-number">1</div>
                <i class="ph ph-storefront" style="font-size: 2rem; color: hsl(var(--primary));"></i>
                <h3>Vendors Post Prices</h3>
                <p>Verified vendors update their daily ingredient prices every morning on the platform.</p>
            </div>
            <div class="card-elevated step-card">
                <div class="step-number">2</div>
                <i class="ph ph-magnifying-glass" style="font-size: 2rem; color: hsl(var(--primary));"></i>
                <h3>Restaurants Compare</h3>
                <p>Your team views live prices from all vendors side by side. The lowest price is automatically highlighted.</p>
            </div>
            <div class="card-elevated step-card">
                <div class="step-number">3</div>
                <i class="ph ph-check-circle" style="font-size: 2rem; color: hsl(var(--primary));"></i>
                <h3>Order & Track</h3>
                <p>Place your order with a single click and track its status from Pending through to Delivered.</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="cta-section">
    <h2>Ready to transform your procurement?</h2>
    <p>Join restaurants already saving time and money with KitchenCart every day.</p>
    <a href="auth/register.php" class="btn-white">
        <i class="ph ph-arrow-right" style="margin-right: 0.5rem;"></i> Get Started Now
    </a>
</div>

<!-- Footer -->
<footer class="landing-footer">
    <div class="logo">
        <i class="ph-fill ph-storefront"></i> KitchenCart
    </div>
    <p>© <?= date('Y') ?> KitchenCart. Built for the modern kitchen.</p>
</footer>

</body>
</html>
