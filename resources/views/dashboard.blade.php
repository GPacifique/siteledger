<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SiteLedger</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Colorful Theme CSS - For consistent vibrant colors across dashboard -->
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <style>
        :root {
            /* Professional Color Palette */
            --primary-color: #667eea;
            --primary-dark: #5a67d8;
            --primary-light: #e6efff;
            --success-color: #27ae60;
            --success-light: #d4edda;
            --success-dark: #1e8449;
            --warning-color: #f39c12;
            --warning-light: #fff3cd;
            --warning-dark: #d68910;
            --danger-color: #e74c3c;
            --danger-light: #f8d7da;
            --danger-dark: #c0392b;
            --info-color: #3498db;
            --info-light: #d1ecf1;
            --info-dark: #2980b9;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --white: #ffffff;

            /* Enhanced Gradients */
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #48cae4 0%, #023e8a 100%);
            --gradient-warning: linear-gradient(135deg, #ffd60a 0%, #ff8500 100%);
            --gradient-danger: linear-gradient(135deg, #ff006e 0%, #8338ec 100%);
            --gradient-info: linear-gradient(135deg, #06ffa5 0%, #0077be 100%);
            --gradient-rainbow: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
            --gradient-sunset: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --gradient-ocean: linear-gradient(135deg, #209cff 0%, #68e0cf 100%);
            --gradient-purple: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-green: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);

            /* Professional Shadows */
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
            --shadow-xl: 0 20px 40px rgba(0,0,0,0.2);
            --shadow-colorful: 0 10px 30px rgba(102, 126, 234, 0.3);

            /* Border Radius */
            --radius-sm: 6px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-full: 50px;

            /* Transitions */
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);

            /* Typography */
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        body {
            font-family: var(--font-family);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--dark-color);
            line-height: 1.6;
            min-height: 100vh;
        }

        .navbar {
            background: var(--gradient-primary);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-lg);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        .navbar h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            animation: float 3s ease-in-out infinite;
        }
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-lg);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            animation: slideInLeft 0.6s ease-out;
        }

        .navbar h1 {
            font-size: 1.5rem;
            margin: 0;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-menu {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            transition: var(--transition);
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
            text-decoration: none;
            color: white;
            transform: translateY(-2px);
        }
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .navbar .role-badge {
            background: rgba(255,255,255,0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .navbar button {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .navbar button:hover {
            background: #ee5a5a;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            .navbar-menu {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.5rem;
            }
            .nav-link {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            animation: fadeInUp 0.6s ease-out;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        /* Enhanced Stat Cards */
        .stat-card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            transition: var(--transition-bounce);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
            cursor: pointer;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-primary);
            transition: var(--transition);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            transform: rotate(45deg);
            transition: var(--transition);
            opacity: 0;
        }

        .stat-card:hover::before {
            height: 8px;
            background: var(--gradient-rainbow);
        }

        .stat-card:hover::after {
            opacity: 1;
            animation: shimmer 1.5s ease-in-out;
        }

        .stat-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: var(--shadow-xl);
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        }

        .stat-card.success::before { background: var(--gradient-success); }
        .stat-card.warning::before { background: var(--gradient-warning); }
        .stat-card.danger::before { background: var(--gradient-danger); }
        .stat-card.info::before { background: var(--gradient-info); }
        .stat-card.expense::before { background: var(--gradient-sunset); }
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        .stat-card:hover::before {
            left: 100%;
        }
        .stat-card:hover {
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
            transform: translateY(-4px);
            background: linear-gradient(135deg, #f8f9ff 0%, white 100%);
        }
        .stat-card:active {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }
        .stat-card h3 {
            color: #666;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-card .value {
            font-size: 2.2rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            line-height: 1.1;
        }

        .stat-card .change {
            font-size: 0.9rem;
            color: #888;
            font-weight: 500;
            line-height: 1.4;
        }
        .stat-card .change {
            font-size: 0.85rem;
            color: #999;
            line-height: 1.4;
        }
        .stat-card .change.positive {
            color: #27ae60;
        }
        .stat-card .change.negative {
            color: #e74c3c;
        }
        .stat-card .change.neutral {
            color: #667eea;
        }
        /* Quick Actions Styles */
        .quick-actions {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        .quick-actions h2 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }
        .action-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        .action-link:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .action-link:hover .action-icon {
            transform: scale(1.1);
        }
        .action-link:hover .action-count {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .action-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            transition: transform 0.3s ease;
        }
        .action-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        .action-subtitle {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }
        .action-link:hover .action-subtitle {
            color: rgba(255,255,255,0.9);
        }
        .action-count {
            font-size: 0.85rem;
            color: #666;
            background: #f0f0f0;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }
        /* Color variations for action links */
        .action-link.projects { border-left: 4px solid #667eea; }
        .action-link.projects:hover { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .action-link.clients { border-left: 4px solid #00b894; }
        .action-link.clients:hover { background: linear-gradient(135deg, #00b894 0%, #00a382 100%); }
        .action-link.workers { border-left: 4px solid #e17055; }
        .action-link.workers:hover { background: linear-gradient(135deg, #e17055 0%, #d63031 100%); }
        .action-link.revenues { border-left: 4px solid #27ae60; }
        .action-link.revenues:hover { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); }
        .action-link.expenses { border-left: 4px solid #dc3545; }
        .action-link.expenses:hover { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
        .action-link.payments { border-left: 4px solid #f39c12; }
        .action-link.payments:hover { background: linear-gradient(135deg, #f39c12 0%, #d68910 100%); }
        .action-link.tasks { border-left: 4px solid #9b59b6; }
        .action-link.tasks:hover { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); }
        /* Professional Summary Sections */
        .summary-section {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            transition: var(--transition);
            position: relative;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .summary-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-ocean);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .summary-section:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .summary-section h3 {
            font-size: 1.2rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f5f5f5;
            transition: var(--transition);
        }

        .summary-row:hover {
            background: linear-gradient(90deg, #f8f9ff 0%, transparent 100%);
            padding-left: 1rem;
            margin: 0 -1rem;
            border-radius: var(--radius-sm);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #666;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .summary-value {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .summary-value.positive {
            color: var(--success-color);
            text-shadow: 0 1px 2px rgba(39, 174, 96, 0.2);
        }
        .summary-value.negative {
            color: var(--danger-color);
            text-shadow: 0 1px 2px rgba(231, 76, 60, 0.2);
        }
        .summary-value.neutral {
            color: var(--info-color);
            text-shadow: 0 1px 2px rgba(52, 152, 219, 0.2);
        }
        <!-- Enhanced Chart Section -->
        .chart-section {
            background: var(--white);
            padding: 2.5rem;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
            transition: var(--transition);
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }

        .chart-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-rainbow);
        }

        .chart-section:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .chart-section h2 {
            font-size: 1.4rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 2rem;
            text-align: center;
        }

        .chart-container {
            position: relative;
            margin: 0 auto;
            padding: 1rem;
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border-radius: var(--radius-lg);
            border: 1px solid #f0f0f0;
        }
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .chart-section h2 {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1rem;
        }
        .chart-container {
            position: relative;
            height: 350px;
            margin-bottom: 2rem;
        }
        .table-section {
            background: var(--white);
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            position: relative;
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }

        .table-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--bg-gradient);
        }

        .table-section h2 {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: var(--dark-color);
            font-weight: 700;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .table-section h2::before {
            content: '📊';
            font-size: 1.2rem;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            background: var(--white);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--white);
        }

        th {
            background: var(--gradient-primary);
            padding: 1.5rem;
            text-align: left;
            font-weight: 700;
            color: white;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            border: none;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        th:first-child {
            border-radius: 0;
        }

        th:last-child {
            border-radius: 0;
        }

        td {
            padding: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.95rem;
            transition: var(--transition);
            background: var(--white);
        }

        tbody tr {
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        tbody tr::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: transparent;
            transition: var(--transition);
        }

        tbody tr:hover::before {
            background: var(--gradient-primary);
        }

        tbody tr:hover {
            background: linear-gradient(90deg, #f8f9ff 0%, #ffffff 100%);
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.15);
        }

        tbody tr:nth-child(even) {
            background: linear-gradient(90deg, #fafbff 0%, #ffffff 100%);
        }

        tbody tr:nth-child(even):hover {
            background: linear-gradient(90deg, #f0f2ff 0%, #ffffff 100%);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: var(--transition);
            border: 2px solid transparent;
            box-shadow: var(--shadow-sm);
        }

        .badge:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-md);
        }

        .badge.success {
            background: var(--gradient-success);
            color: white;
            border-color: var(--success-color);
        }

        .badge.info {
            background: var(--gradient-info);
            color: white;
            border-color: var(--info-color);
        }

        .badge.warning {
            background: var(--gradient-warning);
            color: white;
            border-color: var(--warning-color);
        }

        .badge.danger {
            background: var(--gradient-danger);
            color: white;
            border-color: var(--danger-color);
        }

        .badge.danger {
            background: var(--danger-light);
            color: var(--danger-color);
            border: 1px solid var(--danger-color);
        }

        .badge.warning {
            background: var(--warning-light);
            color: var(--warning-color);
            border: 1px solid var(--warning-color);
        }

        .badge.info {
            background: var(--info-light);
            color: var(--info-color);
            border: 1px solid var(--info-color);
        }
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .full-width-section {
            width: 100%;
            margin-bottom: 2rem;
        }

        .full-width-section .table-section {
            background: var(--white);
            padding: 1.75rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            animation: fadeInUp 0.6s ease-out;
        }

        .full-width-section .table-section h2 {
            margin-bottom: 1.5rem;
            color: var(--dark-color);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .full-width-section .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .full-width-section table {
            width: 100%;
            min-width: 1000px; /* Increased from 800px for better spacing */
        }

        /* Recent Projects Table Specific Styling */
        .table-section table {
            min-width: 800px;
        }
        .table-section th:nth-child(1) { width: 25%; text-align: left; } /* Project Name */
        .table-section th:nth-child(2) { width: 15%; text-align: left; } /* Client */
        .table-section th:nth-child(3) { width: 15%; text-align: left; } /* Manager */
        .table-section th:nth-child(4) { width: 12%; text-align: right; } /* Contract Value */
        .table-section th:nth-child(5) { width: 15%; text-align: center; } /* Progress */
        .table-section th:nth-child(6) { width: 10%; text-align: center; } /* Status */
        .table-section th:nth-child(7) { width: 8%; text-align: center; }  /* Created */

        /* Align table data cells to match headers */
        .table-section td:nth-child(1) { text-align: left; } /* Project Name */
        .table-section td:nth-child(2) { text-align: left; } /* Client */
        .table-section td:nth-child(3) { text-align: left; } /* Manager */
        .table-section td:nth-child(4) { text-align: right; } /* Contract Value */
        .table-section td:nth-child(5) { text-align: center; } /* Progress */
        .table-section td:nth-child(6) { text-align: center; } /* Status */
        .table-section td:nth-child(7) { text-align: center; } /* Created */

        /* Professional Footer */
        .dashboard-footer {
            background: var(--gradient-primary);
            color: white;
            padding: 3rem 2rem 2rem;
            margin-top: 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .dashboard-footer::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            animation: float 6s ease-in-out infinite;
        }

        .dashboard-footer h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .dashboard-footer p {
            opacity: 0.9;
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .footer-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .footer-stat {
            text-align: center;
        }

        .footer-stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            display: block;
        }

        .footer-stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .footer-stats {
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .two-column {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-card {
                padding: 1.25rem;
            }

            .stat-card h3 {
                font-size: 0.8rem;
            }

            .stat-card .value {
                font-size: 1.5rem;
            }

            .section, .recent-section, .calendar-section {
                padding: 1.25rem;
                border-radius: 12px;
                margin-bottom: 1rem;
            }

            .section h2, .recent-section h2, .calendar-section h2 {
                font-size: 1.15rem;
                margin-bottom: 1rem;
            }

            /* Calendar Mobile */
            .calendar-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .calendar-nav {
                justify-content: center;
            }

            .calendar-month-year {
                text-align: center;
                font-size: 1.1rem;
            }

            .calendar-today-btn {
                width: 100%;
                justify-content: center;
            }

            .calendar-grid {
                gap: 4px;
                padding: 0.75rem;
            }

            .calendar-day {
                font-size: 0.85rem;
                min-height: 42px;
                border-radius: 8px;
            }

            .calendar-day-header {
                font-size: 0.75rem;
                padding: 0.5rem 0.25rem;
            }

            .calendar-kpis {
                justify-content: center;
            }

            .calendar-chip {
                font-size: 0.8rem;
                padding: 8px 12px;
            }

            /* Chart Mobile */
            .chart-container canvas {
                max-height: 280px;
            }

            /* Recent Items Mobile */
            .recent-item {
                padding: 1rem;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            /* Table Mobile */
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-custom {
                font-size: 0.85rem;
                min-width: 600px;
            }

            .table-custom th,
            .table-custom td {
                padding: 0.625rem 0.75rem;
                white-space: nowrap;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0.75rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-card .value {
                font-size: 1.35rem;
            }

            .section, .recent-section, .calendar-section {
                padding: 1rem;
                border-radius: 10px;
            }

            .section h2, .recent-section h2, .calendar-section h2 {
                font-size: 1.05rem;
            }

            .calendar-nav button {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }

            .calendar-grid {
                gap: 3px;
                padding: 0.5rem;
            }

            .calendar-day {
                font-size: 0.75rem;
                min-height: 36px;
            }

            .calendar-day-header {
                font-size: 0.65rem;
            }

            .calendar-day.has-data::after,
            .calendar-day.has-income::after,
            .calendar-day.has-expense::after {
                width: 5px;
                height: 5px;
                bottom: 3px;
            }

            .calendar-day.has-both::after {
                width: 9px;
            }

            .chart-container canvas {
                max-height: 220px;
            }

            .recent-item {
                padding: 0.875rem;
            }
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #999;
        }
        .empty-state p {
            font-size: 0.95rem;
        }

        /* Calendar Section - Enhanced Styling */
        .calendar-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
            overflow: hidden;
        }
        .calendar-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #27ae60 100%);
        }
        .calendar-section h2 {
            font-size: 1.35rem;
            margin-bottom: 1.25rem;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 1.25rem;
            border-bottom: 2px solid #eef2ff;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .calendar-nav {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .calendar-nav button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1.25rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }
        .calendar-nav button:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .calendar-nav button:active {
            transform: translateY(0) scale(0.98);
        }
        .calendar-month-year {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a1a2e;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            padding: 0.5rem 1rem;
        }
        .calendar-today-btn {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border: none;
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .calendar-today-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }
        .calendar-kpis {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin: 0 0 1.25rem 0;
            align-items: center;
        }
        .calendar-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            background: #f3f4f6;
            color: #333;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .calendar-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .calendar-chip.positive {
            background: linear-gradient(135deg, #e8f8f0 0%, #d4f5e4 100%);
            color: #1a7f37;
            border-color: #a8e6cf;
        }
        .calendar-chip.negative {
            background: linear-gradient(135deg, #fef2f2 0%, #fde8e8 100%);
            color: #b91c1c;
            border-color: #fca5a5;
        }
        .calendar-chip.neutral {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            color: #4f46e5;
            border-color: #a5b4fc;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .calendar-day-header {
            text-align: center;
            font-weight: 700;
            color: #64748b;
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 0.5rem;
        }
        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
            font-weight: 600;
            position: relative;
            min-height: 50px;
            background: white;
            border: 2px solid transparent;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .calendar-day:hover:not(.empty):not(.other-month) {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            z-index: 10;
            border-color: transparent;
        }
        .calendar-day.today {
            background: linear-gradient(135deg, #e8f8f0 0%, #d4f5e4 100%);
            font-weight: 800;
            border: 3px solid #27ae60;
            color: #1a7f37;
            box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.15);
        }
        .calendar-day.today:hover {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border-color: #27ae60;
        }
        .calendar-day.other-month {
            color: #cbd5e1;
            background: #f8fafc;
            opacity: 0.6;
        }
        .calendar-day.empty {
            cursor: default;
            background: transparent;
            box-shadow: none;
        }
        .calendar-day.has-data::after {
            content: '';
            position: absolute;
            bottom: 6px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #667eea;
            box-shadow: 0 2px 4px rgba(102, 126, 234, 0.4);
        }
        .calendar-day.has-income::after {
            background: #27ae60;
            box-shadow: 0 2px 4px rgba(39, 174, 96, 0.4);
        }
        .calendar-day.has-expense::after {
            background: #dc3545;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.4);
        }
        .calendar-day.has-both::after {
            background: linear-gradient(90deg, #27ae60 50%, #dc3545 50%);
            width: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .calendar-day:hover:not(.empty):not(.other-month)::after {
            background: white;
            box-shadow: 0 2px 4px rgba(255,255,255,0.4);
        }
        .calendar-day:hover.has-both:not(.empty):not(.other-month)::after {
            background: linear-gradient(90deg, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0.8) 50%);
        }

        /* Calendar Legend */
        .calendar-legend {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
        }
        .calendar-legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }
        .calendar-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .calendar-legend-dot.income { background: #27ae60; }
        .calendar-legend-dot.expense { background: #dc3545; }
        .calendar-legend-dot.both { background: linear-gradient(90deg, #27ae60 50%, #dc3545 50%); width: 16px; }

        /* Calendar Modal - Enhanced */
        .calendar-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(8px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .calendar-modal.active {
            display: flex;
            animation: modalFadeIn 0.3s ease;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .calendar-modal-content {
            background: white;
            border-radius: 20px;
            width: 92%;
            max-width: 650px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.35);
            animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .calendar-modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.75rem;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .calendar-modal-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .calendar-modal-header h3 {
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }
        .calendar-modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.4rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }
        .calendar-modal-close:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: rotate(90deg) scale(1.1);
        }
        }
        .calendar-modal-body {
            padding: 1.5rem;
            max-height: 70vh;
            overflow-y: auto;
        }
        .calendar-modal-loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        .calendar-modal-loading .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f0f0f0;
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .modal-summary-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .modal-summary-section h4 {
            font-size: 1rem;
            color: #333;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .modal-summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .modal-summary-row:last-child {
            border-bottom: none;
        }
        .modal-summary-label {
            color: #666;
        }
        .modal-summary-value {
            font-weight: 600;
        }
        .modal-summary-value.positive { color: #27ae60; }
        .modal-summary-value.negative { color: #dc3545; }
        .modal-summary-value.neutral { color: #667eea; }
        .modal-project-list {
            max-height: 200px;
            overflow-y: auto;
        }
        .modal-project-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border: 1px solid #e9ecef;
        }
        .modal-project-name {
            font-weight: 500;
            color: #333;
        }
        .modal-project-amounts {
            display: flex;
            gap: 1rem;
            font-size: 0.85rem;
        }
        /* Project Card Styles for Calendar Modal */
        .project-summary-card {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 2px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .project-summary-card.office-card {
            border-color: #fd79a8;
            background: linear-gradient(135deg, #fff 0%, #fff5f8 100%);
        }
        .project-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .project-card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .project-card-balance {
            font-size: 1rem;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
        }
        .project-card-balance.positive {
            background: #d4edda;
            color: #155724;
        }
        .project-card-balance.negative {
            background: #f8d7da;
            color: #721c24;
        }
        .project-stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .project-stat-item {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 8px;
            text-align: center;
        }
        .project-stat-item.income {
            background: #d4edda;
        }
        .project-stat-item.expense {
            background: #f8d7da;
        }
        .project-stat-item.materials {
            background: #fff3cd;
        }
        .project-stat-item.labor {
            background: #cce5ff;
        }
        .project-stat-label {
            font-size: 0.75rem;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
        }
        .project-stat-value {
            font-size: 1rem;
            font-weight: 700;
            color: #333;
            margin-top: 0.25rem;
        }
        .project-details-toggle {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            width: 100%;
            margin-top: 0.5rem;
            transition: background 0.3s ease;
        }
        .project-details-toggle:hover {
            background: #5a6fd6;
        }
        .project-details-content {
            display: none;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed #e0e0e0;
        }
        .project-details-content.visible {
            display: block;
        }
        .expense-type-breakdown {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .expense-type-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 0.85rem;
        }
        .detail-list {
            max-height: 150px;
            overflow-y: auto;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }
        .detail-item-info {
            display: flex;
            flex-direction: column;
        }
        .detail-item-desc {
            font-weight: 500;
            color: #333;
        }
        .detail-item-meta {
            font-size: 0.75rem;
            color: #999;
        }
        .modal-no-data {
            text-align: center;
            color: #999;
            padding: 1rem;
            font-style: italic;
        }
    </style>
    <script>
        // Enhanced card interaction functionality
        document.addEventListener('DOMContentLoaded', function() {
            const cardLinks = {
                // Main stats cards
                'Total Income': '/revenues',
                'Total Expenses': '/expenses',
                'Total Projects': '/projects',
                'Active Clients': '/clients',
                'Total Payments': '/payments',
                'Office Expenses': '/expenses',
                'Project Expenses': '/expenses',
                'Today\'s Income': '/revenues',
                'Today\'s Expenses': '/expenses',
                'Today\'s Net': '/admin/dashboard',
                // Expense cards with emojis
                ' This Month Expenses': '/expenses',
                '📆 Today\'s Expenses': '/expenses',
                // Summary cards
                '💵 Overall Financial Summary': '/admin/dashboard',
                '📝 Design Phase': '/projects',
                '🔨 Execution Phase': '/projects',
                '📊 Total Expenses': '/expenses'
            };

            // Partial matches for dynamic titles
            const partialCardLinks = {
                '📊 Today\'s Summary': '/admin/dashboard'
            };

            function getCardLink(titleText) {
                // First try exact match
                if (cardLinks[titleText]) {
                    return cardLinks[titleText];
                }
                // Then try partial match
                for (const [key, url] of Object.entries(partialCardLinks)) {
                    if (titleText.includes(key) || titleText.startsWith(key)) {
                        return url;
                    }
                }
                return null;
            }

            document.querySelectorAll('.stat-card, .summary-section').forEach(card => {
                const title = card.querySelector('h3');
                const href = title && getCardLink(title.textContent.trim());

                if (href) {
                    // Add pointer cursor
                    card.style.cursor = 'pointer';

                    // Click handler with ripple effect
                    card.addEventListener('click', function(e) {
                        // Don't trigger if clicking on a link inside the card
                        if (e.target.closest('a')) return;

                        // Create ripple effect
                        const ripple = document.createElement('span');
                        const rect = card.getBoundingClientRect();
                        const size = Math.max(rect.width, rect.height);
                        const x = e.clientX - rect.left - size / 2;
                        const y = e.clientY - rect.top - size / 2;

                        ripple.style.cssText = `
                            position: absolute;
                            left: ${x}px;
                            top: ${y}px;
                            width: ${size}px;
                            height: ${size}px;
                            background: rgba(102, 126, 234, 0.5);
                            border-radius: 50%;
                            transform: scale(0);
                            animation: ripple 0.6s ease-out;
                            pointer-events: none;
                        `;

                        card.style.position = 'relative';
                        card.style.overflow = 'hidden';
                        card.appendChild(ripple);

                        // Navigate after animation
                        setTimeout(() => {
                            window.location.href = href;
                        }, 300);
                    });

                    // Keyboard support (Enter key)
                    card.setAttribute('tabindex', '0');
                    card.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            card.click();
                        }
                    });

                    // Visual feedback on focus
                    card.addEventListener('focus', function() {
                        this.style.outline = '2px solid #667eea';
                        this.style.outlineOffset = '2px';
                    });

                    card.addEventListener('blur', function() {
                        this.style.outline = 'none';
                    });
                }
            });
        });
    </script>

    <style>
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Header -->
    <nav class="navbar">
        <h1>💰 SiteLedger</h1>
        <div class="navbar-menu">
            <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">📊 Dashboard</a>
            <a href="/projects" class="nav-link {{ request()->is('projects*') ? 'active' : '' }}">🏗️ Projects</a>
            <a href="/clients" class="nav-link {{ request()->is('clients*') ? 'active' : '' }}">👥 Clients</a>
            <a href="/expenses" class="nav-link {{ request()->is('expenses*') ? 'active' : '' }}">💸 Expenses</a>
            <a href="/revenues" class="nav-link {{ request()->is('revenues*') ? 'active' : '' }}">💰 Revenues</a>
            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdminForTenant(Auth::user()->current_tenant_id ?? 0))
                <a href="{{ route('company.staff.index') }}" class="nav-link {{ request()->is('company/staff*') ? 'active' : '' }}">👔 Staff</a>
            @endif
            <a href="/workers" class="nav-link {{ request()->is('workers*') ? 'active' : '' }}">👷 Workers</a>
        </div>
        <div class="user-info">
            <span class="role-badge">{{ auth()->user()->roles->first()->name ?? 'Admin' }}</span>
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </nav>

    @php
        // Detect if user is new (no data access)
        $isNewUser = ($projectsCount ?? 0) == 0 && ($totalClients ?? 0) == 0 && ($incomesTotal ?? 0) == 0 && ($allExpensesTotal ?? 0) == 0 && ($paymentsTotal ?? 0) == 0;
    @endphp

    @if($isNewUser)
        <div class="container">
            <div style="text-align:center; margin-top: 4rem; margin-bottom: 4rem;">
                <h1 style="font-size:2.5rem; color:#667eea; margin-bottom:1rem;">Welcome to SiteLedger!</h1>
                <p style="font-size:1.2rem; color:#555; max-width:600px; margin:0 auto 2rem auto;">
                    We're excited to have you on board. To get started, use the navigation above or the quick actions below to add your first project, client, or transaction.<br><br>
                    Once you add data, your dashboard will come alive with insights and summaries.
                </p>
                <img src="https://cdn.jsdelivr.net/gh/GPacifique/siteledger-assets@main/welcome-illustration.svg" alt="Welcome" style="max-width:320px; width:100%; margin:2rem auto; display:block;">
            </div>
            <div class="quick-actions">
                <h2>⚡ Quick Actions</h2>
                <div class="actions-grid">
                    <a href="{{ route('projects.create') }}" class="action-link projects">
                        <span class="action-icon">📁</span>
                        <span class="action-title">Add Project</span>
                    </a>
                    <a href="{{ route('clients.create') }}" class="action-link clients">
                        <span class="action-icon">👥</span>
                        <span class="action-title">Add Client</span>
                    </a>
                    <a href="{{ route('revenues.create') }}" class="action-link revenues">
                        <span class="action-icon">💰</span>
                        <span class="action-title">Add Revenue</span>
                    </a>
                    <a href="{{ route('expenses.create') }}" class="action-link expenses">
                        <span class="action-icon">💸</span>
                        <span class="action-title">Add Expense</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="container">
        <!-- Main Expense Stats Cards -->
        <div class="stats-grid">
            <!-- This Month Expenses Card -->
            <div class="stat-card expense">
                <h3>📅 This Month Expenses</h3>
                <div class="value">RWF {{ number_format($allExpensesThisMonth ?? 0, 2) }}</div>
                <div class="change">
                    💳 Payments: RWF {{ number_format($paymentsThisMonth ?? 0, 2) }}<br>
                    💸 Other: RWF {{ number_format(($allExpensesThisMonth ?? 0) - ($paymentsThisMonth ?? 0), 2) }}
                </div>
            </div>

            <!-- Today's Expenses Card -->
            <div class="stat-card expense">
                <h3>📆 Today's Expenses</h3>
                <div class="value">RWF {{ number_format($allExpensesToday ?? 0, 2) }}</div>
                <div class="change">
                    � Total Expenses: RWF {{ number_format($allExpensesToday ?? 0, 2) }}
                </div>
            </div>
    </div>

    <!-- Professional Dashboard Footer -->
    <footer class="dashboard-footer">
        <h3>🏆 SiteLedger Dashboard Overview</h3>
        <p>Your comprehensive construction project management solution</p>

        <div class="footer-stats">
            <div class="footer-stat">
                <span class="footer-stat-value">{{ $projectsCount ?? 0 }}</span>
                <span class="footer-stat-label">Projects</span>
            </div>
            <div class="footer-stat">
                <span class="footer-stat-value">{{ $totalClients ?? 0 }}</span>
                <span class="footer-stat-label">Clients</span>
            </div>
            <div class="footer-stat">
                <span class="footer-stat-value">RWF {{ number_format(($incomesTotal ?? 0) / 1000, 0) }}K</span>
                <span class="footer-stat-label">Revenue</span>
            </div>
            <div class="footer-stat">
                <span class="footer-stat-value">{{ date('Y') }}</span>
                <span class="footer-stat-label">Active Since</span>
            </div>
        </div>
    </footer>
        @if(!$isNewUser)
        <div class="quick-actions">
            <h2>⚡ Quick Actions</h2>
            <div class="actions-grid">
                <a href="{{ route('projects.index') }}" class="action-link projects">
                    <span class="action-icon">📁</span>
                    <span class="action-title">Projects</span>
                    <span class="action-subtitle">{{ $projectsCount ?? 0 }} total</span>
                </a>
                <a href="{{ route('clients.index') }}" class="action-link clients">
                    <span class="action-icon">👥</span>
                    <span class="action-title">Clients</span>
                    <span class="action-subtitle">{{ $totalClients ?? 0 }} total</span>
                </a>
                <a href="{{ route('revenues.index') }}" class="action-link revenues">
                    <span class="action-icon">💰</span>
                    <span class="action-title">Revenues</span>
                    <span class="action-subtitle">RWF {{ number_format(($incomesTotal ?? 0) / 1000, 0) }}K</span>
                </a>
                <a href="{{ route('expenses.index') }}" class="action-link expenses">
                    <span class="action-icon">💸</span>
                    <span class="action-title">All Expenses</span>
                    <span class="action-subtitle">RWF {{ number_format(($allExpensesTotal ?? 0) / 1000, 0) }}K</span>
                </a>

                <a href="{{ route('company.staff.index') }}" class="action-link staff">
                    <span class="action-icon">👔</span>
                    <span class="action-title">Staff Management</span>
                    <span class="action-subtitle">Manage Staff</span>
                </a>
            </div>
        </div>
        @endif

        <!-- Summary Sections Row -->
        <div class="two-column">
            <!-- Today's Summary -->
            <div class="summary-section">
                <h3>📊 Today's Summary ({{ \Carbon\Carbon::today()->format('M d, Y') }})</h3>
                <div class="summary-row">
                    <span class="summary-label">Income</span>
                    <span class="summary-value positive">RWF {{ number_format($incomesToday ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total All Expenses</span>
                    <span class="summary-value negative">RWF {{ number_format($allExpensesToday ?? 0, 2) }}</span>
                    <small style="display: block; color: #999; margin-top: 0.25rem; font-size: 0.8rem;">
                        Office + Project expenses
                    </small>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Net Profit/Loss</span>
                    <span class="summary-value {{ ($incomesToday ?? 0) - ($allExpensesToday ?? 0) >= 0 ? 'positive' : 'negative' }}">
                        RWF {{ number_format(($incomesToday ?? 0) - ($allExpensesToday ?? 0), 2) }}
                    </span>
                </div>
            </div>

            <!-- Overall Financial Summary -->
            <div class="summary-section">
                <h3>💵 Overall Financial Summary</h3>
                <div class="summary-row">
                    <span class="summary-label">Total Income</span>
                    <span class="summary-value positive">RWF {{ number_format($incomesTotal ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total All Expenses</span>
                    <span class="summary-value negative">RWF {{ number_format($allExpensesTotal ?? 0, 2) }}</span>
                    <small style="display: block; color: #999; margin-top: 0.25rem; font-size: 0.8rem;">
                        Payments: RWF {{ number_format($paymentsTotal ?? 0, 0) }} + Other: RWF {{ number_format($expensesTotal ?? 0, 0) }}
                    </small>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Net Balance</span>
                    <span class="summary-value {{ ($incomesTotal ?? 0) - ($allExpensesTotal ?? 0) >= 0 ? 'positive' : 'negative' }}">
                        RWF {{ number_format(($incomesTotal ?? 0) - ($allExpensesTotal ?? 0), 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Project Phases Summary -->
        <div class="two-column">
            <div class="summary-section">
                <h3>📝 Design Phase</h3>
                <div class="summary-row">
                    <span class="summary-label">Completed</span>
                    <span class="summary-value positive">{{ $completedDesignPhases }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">In Progress</span>
                    <span class="summary-value neutral">{{ $inProgressDesignPhases }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Pending</span>
                    <span class="summary-value negative">{{ $pendingDesignPhases }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Value</span>
                    <span class="summary-value neutral">RWF {{ number_format($totalDesignValue, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Revenue</span>
                    <span class="summary-value positive">RWF {{ number_format(($totalDesignRevenue ?? 0) + ($designPhaseRevenue ?? 0), 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Amount Paid</span>
                    <span class="summary-value positive">RWF {{ number_format($totalDesignPaid, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Remaining</span>
                    <span class="summary-value negative">RWF {{ number_format($totalDesignValue - $totalDesignPaid, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Progress</span>
                    <span class="summary-value neutral">{{ $totalDesignValue > 0 ? round(($totalDesignPaid / $totalDesignValue) * 100, 1) : 0 }}%</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Expenses</span>
                    <span class="summary-value negative">RWF {{ number_format($totalDesignExpenses ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Net Profit</span>
                    <span class="summary-value {{ ((($totalDesignRevenue ?? 0) + ($designPhaseRevenue ?? 0)) - ($totalDesignExpenses ?? 0)) >= 0 ? 'positive' : 'negative' }}">RWF {{ number_format((($totalDesignRevenue ?? 0) + ($designPhaseRevenue ?? 0)) - ($totalDesignExpenses ?? 0), 2) }}</span>
                </div>
            </div>

            <div class="summary-section">
                <h3>🔨 Execution Phase</h3>
                <div class="summary-row">
                    <span class="summary-label">Total Value</span>
                    <span class="summary-value neutral">RWF {{ number_format($totalExecutionValue, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Revenue</span>
                    <span class="summary-value positive">RWF {{ number_format(($totalExecutionRevenue ?? 0) + ($executionPhaseRevenue ?? 0), 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Amount Paid</span>
                    <span class="summary-value positive">RWF {{ number_format($totalExecutionPaid, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Remaining</span>
                    <span class="summary-value negative">RWF {{ number_format($totalExecutionValue - $totalExecutionPaid, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Progress</span>
                    <span class="summary-value neutral">{{ $totalExecutionValue > 0 ? round(($totalExecutionPaid / $totalExecutionValue) * 100, 1) : 0 }}%</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Expenses</span>
                    <span class="summary-value negative">RWF {{ number_format($totalExecutionExpenses ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Net Profit</span>
                    <span class="summary-value {{ ((($totalExecutionRevenue ?? 0) + ($executionPhaseRevenue ?? 0)) - ($totalExecutionExpenses ?? 0)) >= 0 ? 'positive' : 'negative' }}">RWF {{ number_format((($totalExecutionRevenue ?? 0) + ($executionPhaseRevenue ?? 0)) - ($totalExecutionExpenses ?? 0), 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Project Types Breakdown -->
        <div class="two-column">
            <div class="summary-section">
                <h3>🎯 Project Types Overview</h3>
                <div class="summary-row">
                    <span class="summary-label">Total Projects</span>
                    <span class="summary-value neutral">{{ $totalProjectsByType ?? 0 }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">📝 Design Only</span>
                    <span class="summary-value positive">{{ $designOnlyProjects ?? 0 }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">🔨 Execution Only</span>
                    <span class="summary-value neutral">{{ $executionOnlyProjects ?? 0 }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">🎯 Design + Execution</span>
                    <span class="summary-value negative">{{ $designExecutionProjects ?? 0 }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Project Distribution</span>
                    <span class="summary-value neutral">
                        @if($totalProjectsByType > 0)
                            {{ round(($designOnlyProjects / $totalProjectsByType) * 100, 1) }}% Design,
                            {{ round(($executionOnlyProjects / $totalProjectsByType) * 100, 1) }}% Execution,
                            {{ round(($designExecutionProjects / $totalProjectsByType) * 100, 1) }}% Combined
                        @else
                            No projects yet
                        @endif
                    </span>
                </div>
            </div>

            <div class="summary-section">
                <h3>📊 Type Performance</h3>
                <div class="summary-row">
                    <span class="summary-label">Design Value</span>
                    <span class="summary-value positive">RWF {{ number_format($totalDesignValue ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Execution Value</span>
                    <span class="summary-value neutral">RWF {{ number_format($totalExecutionValue ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Project Value</span>
                    <span class="summary-value positive">RWF {{ number_format(($totalDesignValue ?? 0) + ($totalExecutionValue ?? 0), 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Average per Project</span>
                    <span class="summary-value neutral">
                        @if($totalProjectsByType > 0)
                            RWF {{ number_format((($totalDesignValue ?? 0) + ($totalExecutionValue ?? 0)) / $totalProjectsByType, 2) }}
                        @else
                            RWF 0.00
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Expense Breakdown -->
        <div class="one-column">
            <div class="summary-section">
                <h3>📊 Total Expenses</h3>
                <div class="summary-row">
                    <span class="summary-label">Total</span>
                    <span class="summary-value negative">RWF {{ number_format($allExpensesTotal ?? 0, 2) }}</span>
                    <small style="display: block; color: #999; margin-top: 0.25rem; font-size: 0.8rem;">
                        Office: RWF {{ number_format($officeExpenses ?? 0, 0) }} + Project: RWF {{ number_format($projectExpenses ?? 0, 0) }}
                    </small>
                </div>
                <div class="summary-row">
                    <span class="summary-label">This Month</span>
                    <span class="summary-value negative">RWF {{ number_format($allExpensesThisMonth ?? 0, 2) }}</span>
                    <small style="display: block; color: #999; margin-top: 0.25rem; font-size: 0.8rem;">
                        Office: RWF {{ number_format($officeExpensesThisMonth ?? 0, 0) }} + Project: RWF {{ number_format($projectExpensesThisMonth ?? 0, 0) }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Expense Breakdown by Category -->
        <div class="one-column">
            <div class="summary-section">
                <h3>📊 Expense Breakdown by Category</h3>
                @if(isset($expensesByCategory) && count($expensesByCategory) > 0)
                    @foreach($expensesByCategory as $category)
                        <div class="summary-row">
                            <span class="summary-label">
                                @if($category['category_name'] == 'Materials') 🧱 @endif
                                @if($category['category_name'] == 'Labor') 👷 @endif
                                @if($category['category_name'] == 'Equipment') 🔧 @endif
                                @if($category['category_name'] == 'Transport') 🚚 @endif
                                @if($category['category_name'] == 'Subcontractor') 🤝 @endif
                                @if($category['category_name'] == 'Utilities') ⚡ @endif
                                @if($category['category_name'] == 'Administrative') 📄 @endif
                                @if($category['category_name'] == 'Other') 📦 @endif
                                {{ $category['category_name'] }}
                            </span>
                            <span class="summary-value negative">RWF {{ number_format($category['total'], 2) }}</span>
                            <small style="display: block; color: #999; margin-top: 0.25rem; font-size: 0.8rem;">
                                @php
                                    $totalExpenses = collect($expensesByCategory)->sum('total');
                                    $percentage = $totalExpenses > 0 ? round(($category['total'] / $totalExpenses) * 100, 1) : 0;
                                @endphp
                                {{ $percentage }}% of total expenses
                            </small>
                        </div>
                    @endforeach
                    <div class="summary-row" style="border-top: 2px solid #e0e0e0; margin-top: 1rem; padding-top: 1rem;">
                        <span class="summary-label" style="font-weight: bold;">Total All Categories</span>
                        <span class="summary-value negative" style="font-weight: bold; font-size: 1.1rem;">
                            RWF {{ number_format(collect($expensesByCategory)->sum('total'), 2) }}
                        </span>
                    </div>
                @else
                    <div class="empty-state">
                        <p>No expense data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Financial Calendar -->
        <div class="calendar-section">
            <h2>📅 Financial Calendar</h2>
            <div class="calendar-header">
                <div class="calendar-nav">
                    <button onclick="changeMonth(-1)" title="Previous Month">‹</button>
                    <span class="calendar-month-year" id="calendarMonthYear"></span>
                    <button onclick="changeMonth(1)" title="Next Month">›</button>
                </div>
                <button class="calendar-today-btn" onclick="goToToday()">
                    <span>📍</span> Today
                </button>
            </div>
            <div class="calendar-kpis">
                <div class="calendar-chip positive">💰 Income: RWF {{ number_format($incomesThisMonth ?? 0, 0) }}</div>
                <div class="calendar-chip negative">💸 All Expenses: RWF {{ number_format($allExpensesThisMonth ?? 0, 0) }}</div>
                <div class="calendar-chip {{ (($incomesThisMonth ?? 0) - ($allExpensesThisMonth ?? 0)) >= 0 ? 'positive' : 'negative' }}">
                    ⚖️ Net: RWF {{ number_format(($incomesThisMonth ?? 0) - ($allExpensesThisMonth ?? 0), 0) }}
                </div>
            </div>
            <div class="calendar-grid" id="calendarGrid">
                <div class="calendar-day-header">Sun</div>
                <div class="calendar-day-header">Mon</div>
                <div class="calendar-day-header">Tue</div>
                <div class="calendar-day-header">Wed</div>
                <div class="calendar-day-header">Thu</div>
                <div class="calendar-day-header">Fri</div>
                <div class="calendar-day-header">Sat</div>
            </div>
            <div class="calendar-legend">
                <div class="calendar-legend-item">
                    <div class="calendar-legend-dot income"></div>
                    <span>Income</span>
                </div>
                <div class="calendar-legend-item">
                    <div class="calendar-legend-dot expense"></div>
                    <span>Expense</span>
                </div>
                <div class="calendar-legend-item">
                    <div class="calendar-legend-dot both"></div>
                    <span>Both</span>
                </div>
            </div>
        </div>

        <!-- Calendar Modal -->
        <div class="calendar-modal" id="calendarModal">
            <div class="calendar-modal-content">
                <div class="calendar-modal-header">
                    <h3>📊 <span id="modalDate">Daily Summary</span></h3>
                    <button class="calendar-modal-close" onclick="closeCalendarModal()">×</button>
                </div>
                <div class="calendar-modal-body" id="modalBody">
                    <div class="calendar-modal-loading">
                        <div class="spinner"></div>
                        <p>Loading financial data...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="two-column">
            <div class="chart-section">
                <h2>Monthly Income vs Expenses</h2>
                <div class="chart-container">
                    <canvas id="incomeExpenseChart"></canvas>
                </div>
            </div>
            <div class="chart-section">
                <h2>Monthly Payments</h2>
                <div class="chart-container">
                    <canvas id="paymentsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Daily Stats Chart -->
        <div class="chart-section">
            <h2>Last 30 Days - Daily Revenue vs Expenses</h2>
            <div class="chart-container">
                <canvas id="dailyStatsChart"></canvas>
            </div>
        </div>

        <!-- Expense Breakdown Chart -->
        <div class="chart-section">
            <h2>Expense Breakdown by Category</h2>
            <div class="chart-container" style="max-width: 500px;">
                <canvas id="expenseBreakdownChart"></canvas>
            </div>
        </div>

        <!-- Recent Data Tables -->
        <div class="full-width-section">
            <div class="table-section">
                <h2>Recent Projects</h2>
                @if(($recentProjects ?? collect())->count() > 0)
                    <div class="table-wrapper">
                        <table style="table-layout: fixed; width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="text-align: left; padding: 1.25rem; width: 25%; border-right: 1px solid #ddd; background: var(--gradient-primary); color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                        <span style="animation: bounce 2s infinite;">🏗️</span> Project Name
                                    </th>
                                    <th style="text-align: left; padding: 1.25rem; width: 15%; border-right: 1px solid #ddd; background: var(--gradient-primary); color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                        <span style="animation: pulse 2s infinite;">👥</span> Client
                                    </th>
                                    <th style="text-align: left; padding: 1.25rem; width: 15%; border-right: 1px solid #ddd; background: var(--gradient-primary); color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                        <span style="animation: bounce 2s infinite;">👨‍💼</span> Manager
                                    </th>
                                    <th style="text-align: right; padding: 1.25rem; width: 12%; border-right: 1px solid #ddd; background: var(--gradient-primary); color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                        <span style="animation: pulse 2s infinite;">💰</span> Contract Value
                                    </th>
                                    <th style="text-align: center; padding: 1.25rem; width: 15%; border-right: 1px solid #ddd; background: var(--gradient-primary); color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                        <span style="animation: bounce 2s infinite;">🚀</span> Progress
                                    </th>
                                    <th style="text-align: center; padding: 1.25rem; width: 10%; border-right: 1px solid #ddd; background: var(--gradient-primary); color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                        <span style="animation: pulse 2s infinite;">📈</span> Status
                                    </th>
                                    <th style="text-align: center; padding: 1.25rem; width: 8%; background: var(--gradient-primary); color: white; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                        <span style="animation: bounce 2s infinite;">📅</span> Created
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentProjects as $project)
                                    <tr onclick="window.location.href='/projects/{{ $project->id }}';" style="cursor: pointer;">
                                        <td style="text-align: left; padding: 1.25rem; border-right: 1px solid #f0f0f0; width: 25%; word-wrap: break-word;">
                                            <strong style="color: var(--dark-color);">{{ $project->name ?? 'N/A' }}</strong>
                                        </td>
                                        <td style="text-align: left; padding: 1.25rem; border-right: 1px solid #f0f0f0; color: #666; width: 15%; word-wrap: break-word;">
                                            {{ $project->client->name ?? 'No Client' }}
                                        </td>
                                        <td style="text-align: left; padding: 1.25rem; border-right: 1px solid #f0f0f0; color: #666; width: 15%; word-wrap: break-word;">
                                            {{ $project->manager ? $project->manager->first_name . ' ' . $project->manager->last_name : 'Unassigned' }}
                                        </td>
                                        <td style="text-align: right; padding: 1.25rem; border-right: 1px solid #f0f0f0; width: 12%; word-wrap: break-word;">
                                            <strong style="color: var(--success-color);">RWF {{ number_format($project->contract_value ?? 0, 0) }}</strong>
                                        </td>
                                        <td style="text-align: center; padding: 1.25rem; border-right: 1px solid #f0f0f0; width: 15%;">
                                            @php
                                                $progress = $project->contract_value > 0 ? round(($project->amount_paid / $project->contract_value) * 100, 1) : 0;
                                                $progressColor = $progress > 75 ? 'var(--success-color)' : ($progress > 50 ? 'var(--warning-color)' : 'var(--danger-color)');
                                            @endphp
                                            <div style="display: flex; align-items: center; gap: 10px; justify-content: center;">
                                                <div style="flex: 1; background: #f0f2f5; border-radius: 12px; height: 8px; overflow: hidden; position: relative; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1); max-width: 120px;">
                                                    <div style="height: 100%; background: linear-gradient(90deg, {{ $progressColor }}, {{ $progress > 75 ? '#2ecc71' : ($progress > 50 ? '#f1c40f' : '#e74c3c') }}); width: {{ $progress }}%; border-radius: 12px; transition: width 0.8s ease, background 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></div>
                                                </div>
                                                <span style="font-size: 0.85rem; font-weight: 600; color: {{ $progressColor }}; min-width: 40px;">{{ $progress }}%</span>
                                            </div>
                                        </td>
                                        <td style="text-align: center; padding: 1.25rem; border-right: 1px solid #f0f0f0; width: 10%;">
                                            <span class="badge {{ $project->status === 'completed' ? 'success' : ($project->status === 'active' ? 'info' : 'warning') }}">
                                                {{ ucfirst($project->status ?? 'Planning') }}
                                            </span>
                                        </td>
                                        <td style="text-align: center; padding: 1.25rem; color: #888; font-size: 0.9rem; width: 8%;">
                                            {{ $project->created_at ? $project->created_at->format('M d, Y') : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <p>No projects yet</p>
                    </div>
                @endif
            </div>

        </div>


    </div>

    <script>
        // Income vs Expenses Chart
        const incomeExpenseCtx = document.getElementById('incomeExpenseChart').getContext('2d');
        new Chart(incomeExpenseCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months ?? []) !!},
                datasets: [
                    {
                        label: 'Income',
                        data: {!! json_encode($incomeMonthly ?? []) !!},
                        borderColor: '#27ae60',
                        backgroundColor: 'rgba(39, 174, 96, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Expenses',
                        data: {!! json_encode($expensesMonthly ?? []) !!},
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'RWF ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Payments Chart
        const paymentsCtx = document.getElementById('paymentsChart').getContext('2d');
        new Chart(paymentsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($months ?? []) !!},
                datasets: [
                    {
                        label: 'Payments',
                        data: {!! json_encode($paymentsMonthly ?? []) !!},
                        backgroundColor: '#667eea',
                        borderRadius: 4,
                        borderWidth: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'RWF ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Daily Stats Chart
        const dailyStatsCtx = document.getElementById('dailyStatsChart').getContext('2d');
        new Chart(dailyStatsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyDates ?? []) !!},
                datasets: [
                    {
                        label: 'Daily Revenue',
                        data: {!! json_encode($dailyRevenue ?? []) !!},
                        borderColor: '#27ae60',
                        backgroundColor: 'rgba(39, 174, 96, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#27ae60',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                    },
                    {
                        label: 'Daily Expenses',
                        data: {!! json_encode($dailyExpenses ?? []) !!},
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'RWF ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Expense Breakdown Chart
        const expenseBreakdownCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
        const officeExpenses = {{ $officeExpenses ?? 0 }};
        const projectExpenses = {{ $projectExpenses ?? 0 }};

        new Chart(expenseBreakdownCtx, {
            type: 'doughnut',
            data: {
                labels: ['Office Expenses', 'Project Expenses'],
                datasets: [{
                    data: [officeExpenses, projectExpenses],
                    backgroundColor: ['#dc3545', '#f39c12'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'RWF ' + context.parsed.toLocaleString();
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Make table rows clickable
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', function() {
                // Get the first cell content (ID or name)
                const firstCell = this.querySelector('td');
                if (firstCell) {
                    console.log('Clicked row:', firstCell.textContent);
                    // Add active state styling
                    this.style.backgroundColor = '#c8e6c9';
                    setTimeout(() => {
                        this.style.backgroundColor = '#e8f5e9';
                    }, 200);
                }
            });
        });

        // ===============================
        // Financial Calendar JavaScript - Enhanced
        // ===============================
        let currentDate = new Date();
        let calendarData = {};

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            // Update header with animation
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                               'July', 'August', 'September', 'October', 'November', 'December'];
            const monthYearEl = document.getElementById('calendarMonthYear');
            monthYearEl.style.opacity = '0';
            monthYearEl.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                monthYearEl.textContent = `${monthNames[month]} ${year}`;
                monthYearEl.style.opacity = '1';
                monthYearEl.style.transform = 'translateY(0)';
            }, 150);

            // Get calendar grid and clear existing day cells (keep headers)
            const grid = document.getElementById('calendarGrid');
            const dayHeaders = grid.querySelectorAll('.calendar-day-header');

            // Animate out old days
            const oldDays = grid.querySelectorAll('.calendar-day');
            oldDays.forEach((day, index) => {
                day.style.opacity = '0';
                day.style.transform = 'scale(0.8)';
            });

            setTimeout(() => {
                grid.innerHTML = '';
                dayHeaders.forEach(header => grid.appendChild(header));

                // Get first day of month and number of days
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const daysInPrevMonth = new Date(year, month, 0).getDate();

                const today = new Date();
                const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;

                // Previous month days
                for (let i = firstDay - 1; i >= 0; i--) {
                    const dayEl = document.createElement('div');
                    dayEl.className = 'calendar-day other-month';
                    dayEl.textContent = daysInPrevMonth - i;
                    dayEl.style.opacity = '0';
                    dayEl.style.transform = 'scale(0.8)';
                    grid.appendChild(dayEl);
                }

                // Current month days
                for (let day = 1; day <= daysInMonth; day++) {
                    const dayEl = document.createElement('div');
                    dayEl.className = 'calendar-day';
                    dayEl.textContent = day;
                    dayEl.style.opacity = '0';
                    dayEl.style.transform = 'scale(0.8)';

                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    if (isCurrentMonth && day === today.getDate()) {
                        dayEl.classList.add('today');
                    }

                    // Check for data indicators (use combined expenses = payments + other expenses)
                    if (calendarData[dateStr]) {
                        const data = calendarData[dateStr];
                        const dayPayments = (data.payments ?? data.totalPayments ?? 0);
                        const combinedExpenses = (data.expenses || 0) + dayPayments;
                        const hasIncome = (data.income || 0) > 0;
                        if (hasIncome && combinedExpenses > 0) {
                            dayEl.classList.add('has-both');
                        } else if (hasIncome) {
                            dayEl.classList.add('has-income');
                        } else if (combinedExpenses > 0) {
                            dayEl.classList.add('has-expense');
                        }
                    }

                    dayEl.onclick = () => openCalendarModal(dateStr, day, monthNames[month], year);
                    grid.appendChild(dayEl);
                }

                // Next month days (fill remaining cells)
                const totalCells = firstDay + daysInMonth;
                const remainingCells = totalCells > 35 ? 42 - totalCells : 35 - totalCells;
                for (let i = 1; i <= remainingCells; i++) {
                    const dayEl = document.createElement('div');
                    dayEl.className = 'calendar-day other-month';
                    dayEl.textContent = i;
                    dayEl.style.opacity = '0';
                    dayEl.style.transform = 'scale(0.8)';
                    grid.appendChild(dayEl);
                }

                // Animate in new days with stagger effect
                const newDays = grid.querySelectorAll('.calendar-day');
                newDays.forEach((day, index) => {
                    setTimeout(() => {
                        day.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                        day.style.opacity = '1';
                        day.style.transform = 'scale(1)';
                    }, index * 15);
                });

                // Fetch month data for indicators
                fetchMonthData(year, month + 1);
            }, 200);
        }

        function fetchMonthData(year, month) {
            fetch(`/api/calendar/month-data?year=${year}&month=${month}`)
                .then(response => response.json())
                .then(data => {
                    calendarData = data.dates || {};
                    updateCalendarIndicators();
                })
                .catch(error => console.log('Calendar data not available'));
        }

        function updateCalendarIndicators() {
            const grid = document.getElementById('calendarGrid');
            const days = grid.querySelectorAll('.calendar-day:not(.other-month):not(.calendar-day-header)');
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            days.forEach(dayEl => {
                const day = parseInt(dayEl.textContent);
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                // Remove existing indicators
                dayEl.classList.remove('has-data', 'has-income', 'has-expense', 'has-both');

                if (calendarData[dateStr]) {
                    const data = calendarData[dateStr];
                    const dayPayments = (data.payments ?? data.totalPayments ?? 0);
                    const combinedExpenses = (data.expenses || 0) + dayPayments;
                    const hasIncome = (data.income || 0) > 0;
                    if (hasIncome && combinedExpenses > 0) {
                        dayEl.classList.add('has-both');
                    } else if (hasIncome) {
                        dayEl.classList.add('has-income');
                    } else if (combinedExpenses > 0) {
                        dayEl.classList.add('has-expense');
                    }
                }
            });
        }

        function changeMonth(delta) {
            currentDate.setMonth(currentDate.getMonth() + delta);
            renderCalendar();
        }

        function goToToday() {
            currentDate = new Date();
            renderCalendar();

            // Highlight today with pulse animation
            setTimeout(() => {
                const todayEl = document.querySelector('.calendar-day.today');
                if (todayEl) {
                    todayEl.style.animation = 'todayPulse 0.6s ease';
                    setTimeout(() => {
                        todayEl.style.animation = '';
                    }, 600);
                }
            }, 500);
        }

        // Add pulse animation for today button
        const styleSheet = document.createElement('style');
        styleSheet.textContent = `
            @keyframes todayPulse {
                0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(39, 174, 96, 0.5); }
                50% { transform: scale(1.15); box-shadow: 0 0 0 15px rgba(39, 174, 96, 0); }
                100% { transform: scale(1); box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.15); }
            }
            #calendarMonthYear {
                transition: all 0.3s ease;
            }
        `;
        document.head.appendChild(styleSheet);

        function openCalendarModal(dateStr, day, month, year) {
            const modal = document.getElementById('calendarModal');
            const modalDate = document.getElementById('modalDate');
            const modalBody = document.getElementById('modalBody');

            modalDate.textContent = `${month} ${day}, ${year}`;
            modalBody.innerHTML = `
                <div class="calendar-modal-loading">
                    <div class="spinner"></div>
                    <p>Loading financial data...</p>
                </div>
            `;

            modal.classList.add('active');

            // Fetch daily summary
            fetch(`/api/calendar/daily-summary?date=${dateStr}`)
                .then(response => response.json())
                .then(data => {
                    renderModalContent(data, day, month, year);
                })
                .catch(error => {
                    modalBody.innerHTML = `
                        <div class="modal-no-data">
                            <p>Unable to load data. Please try again.</p>
                        </div>
                    `;
                });
        }

        function renderModalContent(data, day, month, year) {
            const modalBody = document.getElementById('modalBody');
            const totalAllExpenses = (data.totalExpenses || 0) + (data.totalPayments || 0);
            const balance = (data.totalIncome || 0) - totalAllExpenses;
            const balanceClass = balance >= 0 ? 'positive' : 'negative';

            // Build project cards HTML
            let projectCardsHtml = '';
            if (data.projects && data.projects.length > 0) {
                projectCardsHtml = data.projects.map((p, index) => {
                    const projectBalance = (p.income || 0) - (p.expenses || 0);
                    const projectBalanceClass = projectBalance >= 0 ? 'positive' : 'negative';

                    // Build expense details for this project
                    let expenseDetailsHtml = '';
                    if (p.expenseDetails && p.expenseDetails.length > 0) {
                        expenseDetailsHtml = `
                            <div style="margin-top: 0.75rem;">
                                <h5 style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">💸 Expense Items</h5>
                                <div class="detail-list">
                                    ${p.expenseDetails.map(e => `
                                        <div class="detail-item">
                                            <div class="detail-item-info">
                                                <span class="detail-item-desc">${e.item_name || e.description || 'Expense'}</span>
                                                <span class="detail-item-meta">${e.expense_type || e.category || ''}${e.phase ? ' • ' + e.phase : ''}${e.quantity ? ' • ' + e.quantity + ' ' + (e.unit || '') : ''}</span>
                                            </div>
                                            <span class="negative">-RWF ${Number(e.amount).toLocaleString()}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }

                    // Build income details for this project
                    let incomeDetailsHtml = '';
                    if (p.incomeDetails && p.incomeDetails.length > 0) {
                        incomeDetailsHtml = `
                            <div style="margin-top: 0.75rem;">
                                <h5 style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">💰 Income Items</h5>
                                <div class="detail-list">
                                    ${p.incomeDetails.map(i => `
                                        <div class="detail-item">
                                            <span class="detail-item-desc">${i.description || 'Income'}</span>
                                            <span class="positive">+RWF ${Number(i.amount).toLocaleString()}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }

                    return `
                        <div class="project-summary-card">
                            <div class="project-card-header">
                                <span class="project-card-title">🏗️ ${p.name}</span>
                                <span class="project-card-balance ${projectBalanceClass}">
                                    ${projectBalance >= 0 ? '+' : ''}RWF ${Number(projectBalance).toLocaleString()}
                                </span>
                            </div>

                            <div class="project-stats-grid">
                                <div class="project-stat-item income">
                                    <div class="project-stat-label">Income</div>
                                    <div class="project-stat-value">RWF ${Number(p.income || 0).toLocaleString()}</div>
                                </div>
                                <div class="project-stat-item expense">
                                    <div class="project-stat-label">Total Expenses</div>
                                    <div class="project-stat-value">RWF ${Number(p.expenses || 0).toLocaleString()}</div>
                                </div>
                                ${p.materials > 0 ? `
                                    <div class="project-stat-item materials">
                                        <div class="project-stat-label">Materials</div>
                                        <div class="project-stat-value">RWF ${Number(p.materials).toLocaleString()}</div>
                                    </div>
                                ` : ''}
                                ${p.labor > 0 ? `
                                    <div class="project-stat-item labor">
                                        <div class="project-stat-label">Labor</div>
                                        <div class="project-stat-value">RWF ${Number(p.labor).toLocaleString()}</div>
                                    </div>
                                ` : ''}
                            </div>

                            ${(p.labor > 0 || p.materials > 0 || p.otherExpenses > 0) ? `
                                <div class="expense-type-breakdown">
                                    ${p.designLabor > 0 ? `
                                        <div class="expense-type-item">
                                            <span>📝 Design Labor</span>
                                            <span>RWF ${Number(p.designLabor).toLocaleString()}</span>
                                        </div>
                                    ` : ''}
                                    ${p.executionLabor > 0 ? `
                                        <div class="expense-type-item">
                                            <span>🔨 Execution Labor</span>
                                            <span>RWF ${Number(p.executionLabor).toLocaleString()}</span>
                                        </div>
                                    ` : ''}
                                    ${p.otherExpenses > 0 ? `
                                        <div class="expense-type-item">
                                            <span>📦 Other</span>
                                            <span>RWF ${Number(p.otherExpenses).toLocaleString()}</span>
                                        </div>
                                    ` : ''}
                                </div>
                            ` : ''}

                            ${(p.incomeDetails && p.incomeDetails.length > 0) || (p.expenseDetails && p.expenseDetails.length > 0) ? `
                                <button class="project-details-toggle" onclick="toggleProjectDetails(${index})">
                                    📋 View Details
                                </button>
                                <div class="project-details-content" id="projectDetails${index}">
                                    ${incomeDetailsHtml}
                                    ${expenseDetailsHtml}
                                </div>
                            ` : ''}
                        </div>
                    `;
                }).join('');
            }

            // Office Expenses Card
            let officeCardHtml = '';
            if (data.officeExpenses > 0) {
                let officeDetailsHtml = '';
                if (data.officeExpenseDetails && data.officeExpenseDetails.length > 0) {
                    officeDetailsHtml = `
                        <div class="detail-list" style="margin-top: 0.75rem;">
                            ${data.officeExpenseDetails.map(e => `
                                <div class="detail-item">
                                    <div class="detail-item-info">
                                        <span class="detail-item-desc">${e.description || 'Expense'}</span>
                                        <span class="detail-item-meta">${e.category || ''}</span>
                                    </div>
                                    <span class="negative">-RWF ${Number(e.amount).toLocaleString()}</span>
                                </div>
                            `).join('')}
                        </div>
                    `;
                }

                officeCardHtml = `
                    <div class="project-summary-card office-card">
                        <div class="project-card-header">
                            <span class="project-card-title">🏢 Office Expenses</span>
                            <span class="project-card-balance negative">
                                -RWF ${Number(data.officeExpenses).toLocaleString()}
                            </span>
                        </div>
                        ${officeDetailsHtml}
                    </div>
                `;
            }

            modalBody.innerHTML = `
                <!-- Daily Totals Summary -->
                <div class="modal-summary-section">
                    <h4>📊 Daily Summary</h4>
                    <div class="modal-summary-row">
                        <span class="modal-summary-label">Total Income</span>
                        <span class="modal-summary-value positive">RWF ${Number(data.totalIncome || 0).toLocaleString()}</span>
                    </div>
                    <div class="modal-summary-row">
                        <span class="modal-summary-label">Total All Expenses</span>
                        <span class="modal-summary-value negative">RWF ${Number(totalAllExpenses || 0).toLocaleString()}</span>
                        <small style="display: block; color: #999; margin-top: 0.25rem; font-size: 0.8rem;">
                            Payments: RWF ${Number(data.totalPayments || 0).toLocaleString()} + Other: RWF ${Number(data.totalExpenses || 0).toLocaleString()}
                        </small>
                    </div>
                    <div class="modal-summary-row" style="border-top: 2px solid #e0e0e0; padding-top: 0.75rem; margin-top: 0.5rem;">
                        <span class="modal-summary-label"><strong>Net Balance</strong></span>
                        <span class="modal-summary-value ${balanceClass}"><strong>RWF ${Number(balance).toLocaleString()}</strong></span>
                    </div>
                </div>

                <!-- Project Cards -->
                ${projectCardsHtml}

                <!-- Office Card -->
                ${officeCardHtml}

                ${(!data.projects || data.projects.length === 0) && data.officeExpenses <= 0 && data.totalIncome <= 0 ? `
                    <div class="modal-no-data">
                        <p>No transactions recorded for this date.</p>
                    </div>
                ` : ''}
            `;
        }

        function toggleProjectDetails(index) {
            const details = document.getElementById('projectDetails' + index);
            if (details) {
                details.classList.toggle('visible');
            }
        }

        function closeCalendarModal() {
            document.getElementById('calendarModal').classList.remove('active');
        }

        // Close modal on outside click
        document.getElementById('calendarModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCalendarModal();
            }
        });

        // Enhanced Dashboard Features
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading animations to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;

                // Add click animation
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Enhanced table interactions
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                // Add ripple effect on click
                row.addEventListener('click', function(e) {
                    const ripple = document.createElement('div');
                    ripple.classList.add('ripple');

                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;

                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(102, 126, 234, 0.3);
                        transform: scale(0);
                        animation: ripple 600ms linear;
                        left: ${x}px;
                        top: ${y}px;
                        width: 20px;
                        height: 20px;
                        pointer-events: none;
                        z-index: 1000;
                    `;

                    this.style.position = 'relative';
                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });

                // Enhanced hover effects for progress bars
                const progressBars = row.querySelectorAll('[style*="background:"]');
                progressBars.forEach(bar => {
                    bar.addEventListener('mouseenter', function() {
                        this.style.filter = 'brightness(1.1)';
                        this.style.transform = 'scaleY(1.2)';
                    });

                    bar.addEventListener('mouseleave', function() {
                        this.style.filter = '';
                        this.style.transform = '';
                    });
                });
            });

            // Add search functionality to Recent Projects table
            addTableSearch();

            // Add tooltips to badges and progress bars
            addTooltips();

            // Add smooth scroll for anchor links
            addSmoothScroll();

            // Add keyboard navigation
            addKeyboardNavigation();
        });

        // Table search functionality
        function addTableSearch() {
            const tableSection = document.querySelector('.table-section');
            if (!tableSection) return;

            const searchContainer = document.createElement('div');
            searchContainer.style.cssText = `
                margin-bottom: 1.5rem;
                display: flex;
                gap: 1rem;
                align-items: center;
            `;

            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Search projects...';
            searchInput.style.cssText = `
                flex: 1;
                padding: 0.75rem 1rem;
                border: 2px solid #e9ecef;
                border-radius: 25px;
                font-size: 0.95rem;
                transition: all 0.3s ease;
                background: #f8f9fa;
            `;

            searchInput.addEventListener('focus', function() {
                this.style.borderColor = 'var(--primary-color)';
                this.style.background = '#fff';
                this.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
            });

            searchInput.addEventListener('blur', function() {
                this.style.borderColor = '#e9ecef';
                this.style.background = '#f8f9fa';
                this.style.boxShadow = 'none';
            });

            const clearBtn = document.createElement('button');
            clearBtn.innerHTML = '✕';
            clearBtn.style.cssText = `
                padding: 0.75rem;
                border: none;
                background: #6c757d;
                color: white;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                cursor: pointer;
                transition: all 0.3s ease;
                opacity: 0.7;
            `;

            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                filterTable('');
            });

            searchContainer.appendChild(searchInput);
            searchContainer.appendChild(clearBtn);

            const table = tableSection.querySelector('table');
            table.parentNode.insertBefore(searchContainer, table);

            searchInput.addEventListener('input', function() {
                filterTable(this.value);
            });
        }

        function filterTable(query) {
            const rows = document.querySelectorAll('tbody tr');
            const searchTerm = query.toLowerCase();

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const match = text.includes(searchTerm);

                row.style.display = match ? '' : 'none';

                if (match && query) {
                    row.style.animation = 'highlightRow 0.5s ease';
                }
            });
        }

        // Tooltip functionality
        function addTooltips() {
            const badges = document.querySelectorAll('.badge');
            const progressBars = document.querySelectorAll('[style*="width:"]');

            [...badges, ...progressBars].forEach(element => {
                element.addEventListener('mouseenter', function(e) {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'custom-tooltip';

                    let content = '';
                    if (this.classList.contains('badge')) {
                        content = `Status: ${this.textContent}`;
                    } else if (this.style.width) {
                        content = `Progress: ${this.parentNode.querySelector('span').textContent}`;
                    }

                    tooltip.textContent = content;
                    tooltip.style.cssText = `
                        position: absolute;
                        background: rgba(0,0,0,0.8);
                        color: white;
                        padding: 0.5rem 1rem;
                        border-radius: 6px;
                        font-size: 0.8rem;
                        z-index: 1000;
                        pointer-events: none;
                        white-space: nowrap;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    `;

                    document.body.appendChild(tooltip);

                    const rect = this.getBoundingClientRect();
                    tooltip.style.left = `${rect.left + rect.width/2 - tooltip.offsetWidth/2}px`;
                    tooltip.style.top = `${rect.top - tooltip.offsetHeight - 8}px`;

                    setTimeout(() => tooltip.style.opacity = '1', 10);

                    this._tooltip = tooltip;
                });

                element.addEventListener('mouseleave', function() {
                    if (this._tooltip) {
                        this._tooltip.style.opacity = '0';
                        setTimeout(() => {
                            if (this._tooltip && this._tooltip.parentNode) {
                                this._tooltip.parentNode.removeChild(this._tooltip);
                            }
                        }, 300);
                    }
                });
            });
        }

        // Smooth scroll functionality
        function addSmoothScroll() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        }

        // Keyboard navigation
        function addKeyboardNavigation() {
            let currentRow = -1;
            const rows = document.querySelectorAll('tbody tr');

            document.addEventListener('keydown', function(e) {
                if (e.target.tagName.toLowerCase() === 'input') return;

                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        if (currentRow < rows.length - 1) {
                            if (currentRow >= 0) rows[currentRow].classList.remove('keyboard-focus');
                            currentRow++;
                            rows[currentRow].classList.add('keyboard-focus');
                            rows[currentRow].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                        break;

                    case 'ArrowUp':
                        e.preventDefault();
                        if (currentRow > 0) {
                            rows[currentRow].classList.remove('keyboard-focus');
                            currentRow--;
                            rows[currentRow].classList.add('keyboard-focus');
                            rows[currentRow].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                        break;

                    case 'Enter':
                        if (currentRow >= 0) {
                            rows[currentRow].click();
                        }
                        break;

                    case 'Escape':
                        if (currentRow >= 0) {
                            rows[currentRow].classList.remove('keyboard-focus');
                            currentRow = -1;
                        }
                        break;
                }
            });
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }

            @keyframes highlightRow {
                0% { background-color: rgba(102, 126, 234, 0.2); }
                100% { background-color: transparent; }
            }

            .keyboard-focus {
                outline: 2px solid var(--primary-color) !important;
                outline-offset: -2px !important;
                background: rgba(102, 126, 234, 0.05) !important;
            }

            .custom-tooltip {
                box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
            }

            .stat-card:active {
                transform: scale(0.98) !important;
            }

            .quick-action-btn:active {
                transform: translateY(1px) scale(0.98) !important;
            }
        `;
        document.head.appendChild(style);

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCalendarModal();
            }
        });

        // Initialize calendar on page load
        renderCalendar();
    </script>

    <!-- Professional Dashboard Footer -->
    <footer style="background: var(--gradient-primary); color: white; padding: 3rem 2rem 2rem; margin-top: 4rem; text-align: center; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 20px 20px; animation: float 6s ease-in-out infinite;"></div>

        <h3 style="font-size: 1.5rem; margin-bottom: 1rem; font-weight: 700; position: relative; z-index: 1;">
            🏆 SiteLedger Dashboard Overview
        </h3>
        <p style="opacity: 0.9; font-size: 1rem; margin-bottom: 1.5rem; position: relative; z-index: 1;">
            Your comprehensive construction project management solution
        </p>

        <div style="display: flex; justify-content: center; gap: 3rem; margin-top: 2rem; flex-wrap: wrap; position: relative; z-index: 1;">
            <div style="text-align: center;">
                <span style="font-size: 1.8rem; font-weight: 800; display: block;">{{ $projectsCount ?? 0 }}</span>
                <span style="font-size: 0.9rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Projects</span>
            </div>
            <div style="text-align: center;">
                <span style="font-size: 1.8rem; font-weight: 800; display: block;">{{ $totalClients ?? 0 }}</span>
                <span style="font-size: 0.9rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Clients</span>
            </div>
            <div style="text-align: center;">
                <span style="font-size: 1.8rem; font-weight: 800; display: block;">RWF {{ number_format(($incomesTotal ?? 0) / 1000, 0) }}K</span>
                <span style="font-size: 0.9rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Revenue</span>
            </div>
            <div style="text-align: center;">
                <span style="font-size: 1.8rem; font-weight: 800; display: block;">{{ date('Y') }}</span>
                <span style="font-size: 0.9rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">Active Since</span>
            </div>
        </div>
    </footer>
</body>
</html>
