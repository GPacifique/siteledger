<!-- Navigation Menu -->
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="/" class="logo">📊 CSMS</a>
        </div>

        <ul class="navbar-menu">
            @auth
                <li><a href="/dashboard" class="nav-link">📊 Dashboard</a></li>
                <li><a href="/projects" class="nav-link">🏗️ Projects</a></li>
                <li><a href="/tasks" class="nav-link">📋 Tasks</a></li>
                <li><a href="/clients" class="nav-link">👥 Clients</a></li>
                <li><a href="/workers" class="nav-link">👷 Workers</a></li>
                <li><a href="/payments" class="nav-link">💳 Payments</a></li>
                <li><a href="/revenues" class="nav-link">💰 Revenues</a></li>
                <li><a href="/expenses" class="nav-link">💸 Expenses</a></li>
            @endauth

            @guest
                <li><a href="/login" class="nav-link">Login</a></li>
                <li><a href="/register" class="nav-link">Register</a></li>
            @endguest
        </ul>

        <div class="navbar-right">
            @auth
                <div class="user-dropdown">
                    <button class="user-menu-trigger" id="userMenuTrigger">
                        <span class="user-avatar">👤</span>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu">
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15), 0 2px 8px rgba(0, 0, 0, 0.08);
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

    .user-avatar {
        font-size: 1.1rem;
    }

    .user-name {
        color: rgba(255, 255, 255, 0.95);
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
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
            padding: 0 1.25rem;
            height: auto;
            min-height: 75px;
        }

        .navbar-menu {
            width: 100%;
            justify-content: flex-start;
            order: 3;
            overflow-x: auto;
            padding: 0.75rem 0;
            gap: 0.25rem;
        }

        .nav-link {
            padding: 0.625rem 1rem;
            font-size: 0.85rem;
            white-space: nowrap;
            border-radius: 6px;
        }

        .navbar-right {
            margin-left: auto;
            gap: 1rem;
        }

        .logo {
            font-size: 1.3rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuTrigger = document.getElementById('userMenuTrigger');
        const dropdownMenu = document.getElementById('dropdownMenu');

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
            });

            // Close dropdown when a menu item is clicked
            dropdownMenu.addEventListener('click', function(e) {
                if (e.target.closest('.dropdown-item')) {
                    userMenuTrigger.classList.remove('active');
                    dropdownMenu.classList.remove('active');
                }
            });
        }
    });
</script>
