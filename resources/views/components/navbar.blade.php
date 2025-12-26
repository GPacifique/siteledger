<!-- Navigation Menu -->
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="/" class="logo">📊 SiteLedger</a>
        </div>

        <ul class="navbar-menu">
            @auth
                <li><a href="/dashboard" class="nav-link">Dashboard</a></li>
                <li><a href="/admin/dashboard" class="nav-link">Admin</a></li>
                <li><a href="/projects" class="nav-link">Projects</a></li>
                <li><a href="/clients" class="nav-link">Clients</a></li>
                <li><a href="/workers" class="nav-link">Workers</a></li>
                <li><a href="/payments" class="nav-link">Payments</a></li>
                <li><a href="/revenues" class="nav-link">Revenues</a></li>
                <li><a href="/expenses" class="nav-link">Expenses</a></li>
            @endauth

            @guest
                <li><a href="/login" class="nav-link">Login</a></li>
                <li><a href="/register" class="nav-link">Register</a></li>
            @endguest
        </ul>

        <div class="navbar-right">
            @auth
                <span class="user-name">{{ Auth::user()->name }}</span>
                <form action="/logout" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<style>
    .navbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 70px;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .logo {
        color: white;
        text-decoration: none;
        font-size: 1.3rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .logo:hover {
        opacity: 0.9;
    }

    .navbar-menu {
        display: flex;
        list-style: none;
        gap: 0;
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
        padding: 1rem 1.2rem;
        display: block;
        font-weight: 500;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
    }

    .nav-link:hover {
        background: rgba(255,255,255,0.1);
        border-bottom-color: white;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-shrink: 0;
    }

    .user-name {
        color: white;
        font-weight: 600;
    }

    .logout-btn {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .logout-btn:hover {
        background: rgba(255,255,255,0.3);
    }

    @media (max-width: 768px) {
        .navbar-container {
            flex-wrap: wrap;
            padding: 0 1rem;
            height: auto;
        }

        .navbar-menu {
            width: 100%;
            justify-content: flex-start;
            order: 3;
            overflow-x: auto;
            padding: 0.5rem 0;
        }

        .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .navbar-right {
            margin-left: auto;
        }
    }
</style>
