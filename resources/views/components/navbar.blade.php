<!-- Navigation Menu -->
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="/" class="logo">
                <span class="logo-icon">🏗️</span>
                <span class="logo-text">
                    <span class="logo-name">SiteLedger</span>
                    <span class="logo-tagline">Construction Sites Management</span>
                </span>
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <ul class="navbar-menu" id="navbarMenu">
            @auth
                <li><a href="/dashboard" class="nav-link">📊 Dashboard</a></li>
                <li><a href="/projects" class="nav-link">🏗️ Projects</a></li>
                <li><a href="/clients" class="nav-link">👥 Clients</a></li>
                <li><a href="/workers" class="nav-link">👷 Workers</a></li>
                <li><a href="/payments" class="nav-link">💳 Company Payments</a></li>
                <li><a href="/revenues" class="nav-link">💰 Revenues</a></li>
                <li><a href="/expenses" class="nav-link">💸 Expenses</a></li>
                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdminForTenant(Auth::user()->current_tenant_id ?? 0))
                    <li><a href="{{ route('company.staff.index') }}" class="nav-link admin-link">👔 Staff</a></li>
                @endif
            @endauth

            @guest
                <li><a href="/login" class="nav-link">Login</a></li>
                <li><a href="/register" class="nav-link">Register</a></li>
            @endguest
        </ul>

        <div class="navbar-right">
            @auth
                @php
                    $nameParts = explode(' ', Auth::user()->name);
                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                    if (count($nameParts) > 1) {
                        $initials .= strtoupper(substr(end($nameParts), 0, 1));
                    }
                @endphp
                <div class="user-dropdown">
                    <button class="user-menu-trigger" id="userMenuTrigger" title="{{ Auth::user()->name }}">
                        <div class="avatar-circle">
                            <span class="avatar-initials">{{ $initials }}</span>
                        </div>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <div class="dropdown-header">
                            <div class="dropdown-avatar-circle">
                                <span class="dropdown-avatar-initials">{{ $initials }}</span>
                            </div>
                            <div class="dropdown-user-info">
                                <span class="dropdown-user-name">{{ Auth::user()->name }}</span>
                                <span class="dropdown-user-email">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item logout-item">
                                <span class="item-icon">🚪</span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>

<style>
    .navbar {
        background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
        color: white;
        padding: 0;
        box-shadow: 0 8px 32px rgba(39, 174, 96, 0.25), 0 2px 8px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 0;
        z-index: 1000;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .navbar-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 75px;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .logo {
        color: white;
        text-decoration: none;
        font-size: 1.5rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        letter-spacing: -0.5px;
        transition: all 0.3s ease;
    }

    .logo:hover {
        transform: translateY(-2px);
        filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.2));
    }

    .logo-icon {
        font-size: 1.75rem;
    }

    .logo-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .logo-name {
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .logo-tagline {
        font-size: 0.65rem;
        font-weight: 500;
        opacity: 0.85;
        letter-spacing: 0.3px;
        color: rgba(255, 255, 255, 0.9);
        -webkit-text-fill-color: rgba(255, 255, 255, 0.9);
    }

    .navbar-menu {
        display: flex;
        list-style: none;
        gap: 0.5rem;
        margin: 0;
        padding: 0;
        flex: 1;
        justify-content: center;
    }

    .navbar-menu li {
        margin: 0;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        padding: 0.75rem 1.5rem;
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 8px;
        position: relative;
        overflow: hidden;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        transition: left 0.3s ease;
        z-index: -1;
    }

    .nav-link:hover::before {
        left: 0;
    }

    .nav-link:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Admin Link Styling */
    .nav-link.admin-link {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.2) 0%, rgba(255, 152, 0, 0.2) 100%);
        border: 1px solid rgba(255, 193, 7, 0.4);
        color: #fff8e1;
    }

    .nav-link.admin-link:hover {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.35) 0%, rgba(255, 152, 0, 0.35) 100%);
        border-color: rgba(255, 193, 7, 0.6);
        box-shadow: 0 4px 16px rgba(255, 193, 7, 0.3);
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 1.75rem;
        flex-shrink: 0;
    }

    /* User Dropdown Menu */
    .user-dropdown {
        position: relative;
    }

    .user-menu-trigger {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1.5px solid rgba(255, 255, 255, 0.3);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
    }

    .user-menu-trigger:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ffffff 0%, #e8f5e9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15), inset 0 1px 2px rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
    }

    .user-menu-trigger:hover .avatar-circle {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2), inset 0 1px 2px rgba(255, 255, 255, 0.5);
    }

    .avatar-initials {
        font-size: 15px;
        font-weight: 800;
        color: #1e8449;
        letter-spacing: -0.5px;
        text-transform: uppercase;
    }

    .dropdown-avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #e8f5e9;
        box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);
        flex-shrink: 0;
    }

    .dropdown-avatar-initials {
        font-size: 18px;
        font-weight: 800;
        color: white;
        letter-spacing: -0.5px;
        text-transform: uppercase;
    }

    .user-avatar-svg {
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .user-menu-trigger:hover .user-avatar-svg {
        transform: scale(1.05);
    }

    .user-avatar {
        font-size: 1.1rem;
    }

    .user-name {
        color: rgba(255, 255, 255, 0.95);
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
    }

    .dropdown-header {
        padding: 1rem;
        border-bottom: 1px solid #e0e0e0;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .dropdown-user-info {
        display: flex;
        flex-direction: column;
    }

    .dropdown-user-name {
        display: block;
        font-weight: 700;
        color: #333;
        font-size: 0.95rem;
    }

    .dropdown-user-email {
        display: block;
        font-size: 0.8rem;
        color: #666;
        margin-top: 0.25rem;
    }

    .dropdown-arrow {
        font-size: 0.65rem;
        transition: transform 0.3s ease;
        display: inline-block;
    }

    .user-menu-trigger.active .dropdown-arrow {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 0.5rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 32px rgba(0, 0, 0, 0.2), 0 2px 8px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px) scale(0.95);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 999;
        overflow: hidden;
    }

    .dropdown-menu.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .dropdown-item {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: none;
        background: none;
        color: #333;
        text-align: left;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .dropdown-item:hover {
        background: #f5f5f5;
    }

    .dropdown-item:first-child {
        border-top: none;
    }

    .item-icon {
        font-size: 1rem;
    }

    .logout-item {
        color: #e74c3c;
        border-top: 1px solid #f0f0f0;
    }

    .logout-item:hover {
        background: #fff5f5;
    }

    .user-name {
        color: rgba(255, 255, 255, 0.95);
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
    }

    .logout-btn {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        padding: 0.625rem 1.375rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.9rem;
        letter-spacing: 0.3px;
        backdrop-filter: blur(10px);
    }

    .logout-btn:hover {
        background: rgba(255, 255, 255, 0.35);
        border-color: rgba(255, 255, 255, 0.6);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .logout-btn:active {
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .navbar-container {
            flex-wrap: wrap;
            padding: 0 1rem;
            height: auto;
            min-height: 60px;
        }

        .navbar-brand {
            flex: 1;
        }

        .logo {
            font-size: 1.1rem;
        }

        /* Hamburger Menu Button */
        .mobile-menu-toggle {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            cursor: pointer;
            padding: 8px;
            order: 2;
            margin-left: 10px;
        }

        .hamburger-line {
            width: 100%;
            height: 3px;
            background: white;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Mobile Menu */
        .navbar-menu {
            display: none;
            width: 100%;
            flex-direction: column;
            order: 4;
            padding: 1rem 0;
            gap: 0.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 0.5rem;
        }

        .navbar-menu.active {
            display: flex;
        }

        .navbar-menu li {
            width: 100%;
        }

        .nav-link {
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            transform: none;
            background: rgba(255, 255, 255, 0.15);
        }

        /* Navbar Right */
        .navbar-right {
            order: 3;
            margin-left: 0;
            gap: 0.5rem;
        }

        .user-menu-trigger {
            padding: 0.4rem 0.6rem;
        }

        .user-menu-trigger .user-name,
        .user-menu-trigger .dropdown-arrow {
            display: none;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
        }

        .avatar-initials {
            font-size: 13px;
        }

        .dropdown-menu {
            right: -10px;
            min-width: 220px;
        }
    }

    @media (max-width: 480px) {
        .navbar-container {
            padding: 0 0.75rem;
        }

        .logo {
            font-size: 0.95rem;
        }

        .mobile-menu-toggle {
            width: 36px;
            height: 36px;
            padding: 7px;
        }

        .nav-link {
            padding: 0.75rem 0.875rem;
            font-size: 0.9rem;
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
        }

        .avatar-initials {
            font-size: 12px;
        }
    }

    /* Hide hamburger on desktop */
    @media (min-width: 769px) {
        .mobile-menu-toggle {
            display: none;
        }

        .navbar-menu {
            display: flex !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuTrigger = document.getElementById('userMenuTrigger');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const navbarMenu = document.getElementById('navbarMenu');

        // Mobile menu toggle
        if (mobileMenuToggle && navbarMenu) {
            mobileMenuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                mobileMenuToggle.classList.toggle('active');
                navbarMenu.classList.toggle('active');
            });
        }

        if (userMenuTrigger && dropdownMenu) {
            // Toggle dropdown on button click
            userMenuTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenuTrigger.classList.toggle('active');
                dropdownMenu.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.user-dropdown')) {
                    userMenuTrigger.classList.remove('active');
                    dropdownMenu.classList.remove('active');
                }
                // Close mobile menu when clicking outside
                if (!e.target.closest('.navbar') && navbarMenu) {
                    mobileMenuToggle?.classList.remove('active');
                    navbarMenu.classList.remove('active');
                }
            });

            // Close dropdown when a menu item is clicked
            dropdownMenu.addEventListener('click', function(e) {
                if (e.target.closest('.dropdown-item')) {
                    userMenuTrigger.classList.remove('active');
                    dropdownMenu.classList.remove('active');
                }
            });
        }

        // Close mobile menu when nav link is clicked
        if (navbarMenu) {
            navbarMenu.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenuToggle?.classList.remove('active');
                    navbarMenu.classList.remove('active');
                });
            });
        }
    });
</script>
