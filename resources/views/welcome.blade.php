<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiteLedger - Smart Construction & Site Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            color: #fff;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        .bg-animation span {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(102, 126, 234, 0.1);
            animation: move 25s infinite;
            bottom: -150px;
            border-radius: 50%;
        }
        .bg-animation span:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .bg-animation span:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .bg-animation span:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .bg-animation span:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .bg-animation span:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .bg-animation span:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .bg-animation span:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .bg-animation span:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .bg-animation span:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .bg-animation span:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

        @keyframes move {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
        }

        /* Navigation */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(26, 26, 46, 0.9);
            backdrop-filter: blur(10px);
        }
        .logo {
            font-size: 1.8em;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
        }
        .logo span {
            color: #667eea;
        }
        .nav-buttons {
            display: flex;
            gap: 15px;
        }
        .nav-buttons a {
            padding: 10px 25px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-login {
            background: transparent;
            color: #fff;
            border: 2px solid #667eea;
        }
        .btn-login:hover {
            background: #667eea;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 60px 60px;
        }
        .hero-content {
            max-width: 600px;
        }
        .hero-badge {
            display: inline-block;
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            margin-bottom: 20px;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }
        .hero h1 {
            font-size: 3.5em;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .hero h1 span {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero p {
            font-size: 1.2em;
            color: #aaa;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        .hero-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .btn-primary {
            padding: 15px 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.1em;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        .btn-outline {
            padding: 15px 35px;
            background: transparent;
            color: #fff;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.1em;
            border: 2px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }
        .hero-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .hero-graphic {
            width: 500px;
            height: 400px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .dashboard-preview {
            width: 90%;
            height: 85%;
            background: rgba(30, 41, 59, 0.9);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .preview-header {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }
        .preview-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        .preview-dot:nth-child(1) { background: #ff5f57; }
        .preview-dot:nth-child(2) { background: #ffbd2e; }
        .preview-dot:nth-child(3) { background: #28c840; }
        .preview-content {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .preview-card {
            background: rgba(102, 126, 234, 0.15);
            border-radius: 8px;
            padding: 15px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }
        .preview-card-title {
            font-size: 0.75em;
            color: #888;
            margin-bottom: 5px;
        }
        .preview-card-value {
            font-size: 1.4em;
            font-weight: 700;
            color: #667eea;
        }

        /* Features Section */
        .features {
            padding: 100px 60px;
            background: rgba(0,0,0,0.2);
        }
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        .section-header h2 {
            font-size: 2.5em;
            margin-bottom: 15px;
        }
        .section-header p {
            color: #888;
            font-size: 1.1em;
            max-width: 600px;
            margin: 0 auto;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .feature-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 35px;
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            border-color: rgba(102, 126, 234, 0.5);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .feature-icon svg {
            width: 30px;
            height: 30px;
            fill: #fff;
        }
        .feature-card h3 {
            font-size: 1.3em;
            margin-bottom: 12px;
        }
        .feature-card p {
            color: #888;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            padding: 80px 60px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }
        .stat-item h3 {
            font-size: 3em;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        .stat-item p {
            color: #888;
            font-size: 1em;
        }

        /* CTA Section */
        .cta {
            padding: 100px 60px;
            text-align: center;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
        .cta h2 {
            font-size: 2.8em;
            margin-bottom: 20px;
        }
        .cta p {
            color: #aaa;
            font-size: 1.2em;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Benefits Banner */
        .benefits-banner {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            padding: 30px 60px;
            display: flex;
            justify-content: center;
            gap: 60px;
            flex-wrap: wrap;
        }
        .benefit-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            font-weight: 500;
        }
        .benefit-item svg {
            width: 24px;
            height: 24px;
            fill: #fff;
        }

        /* Footer */
        .footer {
            padding: 40px 60px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .footer p {
            color: #666;
        }

        /* WhatsApp Floating Button */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #25d366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(37, 211, 102, 0.5);
        }
        .whatsapp-float svg {
            width: 32px;
            height: 32px;
            fill: #fff;
        }
        .whatsapp-tooltip {
            position: absolute;
            right: 70px;
            background: #fff;
            color: #333;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.9em;
            font-weight: 500;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .whatsapp-float:hover .whatsapp-tooltip {
            opacity: 1;
            visibility: visible;
        }
        .whatsapp-tooltip::after {
            content: '';
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            border: 8px solid transparent;
            border-left-color: #fff;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 120px 30px 60px;
            }
            .hero-content {
                margin-bottom: 50px;
            }
            .hero h1 {
                font-size: 2.5em;
            }
            .hero-buttons {
                justify-content: center;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
            .hero, .features, .stats, .cta {
                padding-left: 20px;
                padding-right: 20px;
            }
            .hero h1 {
                font-size: 2em;
            }
            .hero-graphic {
                width: 100%;
                height: 300px;
            }
            .benefits-banner {
                padding: 20px;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <a href="/" class="logo">Site<span>Ledger</span></a>
        <div class="nav-buttons">
            <a href="/login" class="btn-login">Login</a>
            <a href="/register" class="btn-register">Get Started</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <span class="hero-badge">🚀 Streamline Your Operations</span>
            <h1>Smart <span>Construction & Site</span> Management System</h1>
            <p>Transform the way you manage projects, track expenses, monitor workers, and control every aspect of your construction sites—all from one powerful platform.</p>
            <div class="hero-buttons">
                <a href="/register" class="btn-primary">
                    Start Free Trial
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                <a href="#features" class="btn-outline">Learn More</a>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-graphic">
                <div class="dashboard-preview">
                    <div class="preview-header">
                        <div class="preview-dot"></div>
                        <div class="preview-dot"></div>
                        <div class="preview-dot"></div>
                    </div>
                    <div class="preview-content">
                        <div class="preview-card">
                            <div class="preview-card-title">Active Projects</div>
                            <div class="preview-card-value">24</div>
                        </div>
                        <div class="preview-card">
                            <div class="preview-card-title">Total Revenue</div>
                            <div class="preview-card-value">$2.4M</div>
                        </div>
                        <div class="preview-card">
                            <div class="preview-card-title">Workers</div>
                            <div class="preview-card-value">186</div>
                        </div>
                        <div class="preview-card">
                            <div class="preview-card-title">Completion</div>
                            <div class="preview-card-value">87%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Banner -->
    <div class="benefits-banner">
        <div class="benefit-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            <span>No Setup Fees</span>
        </div>
        <div class="benefit-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            <span>Multi-Tenant Support</span>
        </div>
        <div class="benefit-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            <span>Real-Time Analytics</span>
        </div>
        <div class="benefit-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            <span>24/7 WhatsApp Support</span>
        </div>
    </div>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header">
            <h2>Everything You Need to Succeed</h2>
            <p>Powerful features designed to help you manage your construction business efficiently</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                </div>
                <h3>Project Management</h3>
                <p>Track project phases, milestones, and deliverables. Set budgets, monitor progress, and stay on schedule with visual timelines.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                </div>
                <h3>Financial Tracking</h3>
                <p>Monitor income, expenses, and payments in real-time. Generate detailed financial reports and keep your books organized.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                </div>
                <h3>Workforce Management</h3>
                <p>Manage your workers, track attendance, assign roles, and monitor productivity across all your construction sites.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                </div>
                <h3>Analytics Dashboard</h3>
                <p>Get actionable insights with beautiful charts and reports. Make data-driven decisions to grow your business.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                </div>
                <h3>Role-Based Access</h3>
                <p>Control who sees what with comprehensive role management. From admins to site managers, everyone gets the right access.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                </div>
                <h3>Task Management</h3>
                <p>Create, assign, and track tasks across your team. Never miss a deadline with automated reminders and progress tracking.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <h3>500+</h3>
                <p>Active Companies</p>
            </div>
            <div class="stat-item">
                <h3>10K+</h3>
                <p>Projects Managed</p>
            </div>
            <div class="stat-item">
                <h3>$50M+</h3>
                <p>Transactions Tracked</p>
            </div>
            <div class="stat-item">
                <h3>99.9%</h3>
                <p>Uptime Guaranteed</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>Ready to Transform Your Business?</h2>
        <p>Join hundreds of construction companies already using SiteLedger to streamline their operations and boost productivity.</p>
        <div class="cta-buttons">
            <a href="/register" class="btn-primary">
                Get Started Free
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
            <a href="https://wa.me/250786163963" target="_blank" class="btn-outline" style="display: inline-flex; align-items: center; gap: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Chat on WhatsApp
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} SiteLedger. All rights reserved. Built with ❤️ for Construction Professionals.</p>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/250786163963" target="_blank" class="whatsapp-float" title="Chat with us on WhatsApp">
        <span class="whatsapp-tooltip">Need Help? Chat with us!</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
</body>
</html>
