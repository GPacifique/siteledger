@extends('layouts.app')

@section('title', 'Admin Dashboard - SiteLedger')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('styles')
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
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
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .stat-card .description {
            color: #999;
            font-size: 0.85rem;
        }
        .stat-card.income {
            border-left-color: #27ae60;
        }
        .stat-card.income .value {
            color: #27ae60;
        }
        .stat-card.expense {
            border-left-color: #e74c3c;
        }
        .stat-card.expense .value {
            color: #e74c3c;
        }
        .stat-card.payment {
            border-left-color: #3498db;
        }
        .stat-card.payment .value {
            color: #3498db;
        }
        .stat-card.project {
            border-left-color: #f39c12;
        }
        .stat-card.project .value {
            color: #f39c12;
        }

        /* Quick Actions */
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
        }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        .action-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.25rem 1rem;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #e1e8ff;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        .action-link:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
        }
        .action-icon {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }
        .action-title {
            font-weight: 600;
            font-size: 0.9rem;
            text-align: center;
        }

        /* Summary sections */
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .summary-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .summary-section h3 {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 1rem;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .summary-row:last-child {
            border-bottom: none;
        }
        .summary-label {
            color: #666;
            font-size: 0.9rem;
        }
        .summary-value {
            font-weight: 700;
            font-size: 1rem;
        }
        .summary-value.positive {
            color: #27ae60;
        }
        .summary-value.negative {
            color: #e74c3c;
        }
        .summary-value.neutral {
            color: #333;
        }

        /* Calendar styles */
        .calendar-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        .calendar-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" fill-opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" fill-opacity="0.1"/><circle cx="25" cy="75" r="0.5" fill="white" fill-opacity="0.1"/><circle cx="75" cy="25" r="0.5" fill="white" fill-opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            animation: float 20s linear infinite;
            pointer-events: none;
        }
        .calendar-section h2 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: white;
            position: relative;
            z-index: 1;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        .calendar-nav {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .calendar-nav button {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        .calendar-nav button:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }
        .calendar-nav button:active {
            transform: scale(0.95);
        }
        .calendar-month-year {
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .calendar-today-btn {
            background: rgba(255,255,255,0.25);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .calendar-today-btn:hover {
            background: rgba(255,255,255,0.4);
            transform: translateY(-1px);
        }
        .calendar-kpis {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        .calendar-chip {
            background: rgba(255,255,255,0.2);
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .calendar-chip:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }
        .calendar-chip.positive {
            background: rgba(39, 174, 96, 0.3);
            border-color: rgba(39, 174, 96, 0.5);
        }
        .calendar-chip.negative {
            background: rgba(231, 76, 60, 0.3);
            border-color: rgba(231, 76, 60, 0.5);
        }
        .calendar-chip.neutral {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.3);
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            position: relative;
            z-index: 1;
        }
        .calendar-day-header {
            text-align: center;
            font-weight: 600;
            padding: 0.5rem 0;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.8);
        }
        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.1);
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .calendar-day:hover {
            background: rgba(255,255,255,0.2);
            transform: scale(1.05);
        }
        .calendar-day.today {
            background: rgba(39, 174, 96, 0.6);
            font-weight: 700;
            box-shadow: 0 0 0 2px rgba(39, 174, 96, 0.8);
        }
        .calendar-day.other-month {
            opacity: 0.3;
            cursor: default;
        }
        .calendar-day.other-month:hover {
            transform: none;
            background: rgba(255,255,255,0.1);
        }
        .calendar-day.has-data::after,
        .calendar-day.has-income::after,
        .calendar-day.has-expense::after {
            content: '';
            position: absolute;
            bottom: 3px;
            right: 3px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #27ae60;
        }
        .calendar-day.has-both::after {
            background: linear-gradient(45deg, #27ae60 50%, #e74c3c 50%);
            border-radius: 50%;
        }
        .calendar-day.has-expense::after {
            background: #e74c3c;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }

        @media (max-width: 1024px) {
            .two-column {
                grid-template-columns: 1fr;
            }
            .calendar-nav {
                flex-wrap: wrap;
            }
            .calendar-kpis {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .calendar-grid {
                gap: 2px;
            }
            .calendar-day {
                font-size: 0.75rem;
            }
        }
@endsection

@section('content')
<div class="container">
    @php
        // Detect if user is new (no data access)
        $isNewUser = ($projectsCount ?? 0) == 0 && ($totalClients ?? 0) == 0 && ($incomesTotal ?? 0) == 0 && ($allExpensesTotal ?? 0) == 0 && ($paymentsTotal ?? 0) == 0;
    @endphp

    @if($isNewUser)
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
            </div>
            <div class="summary-row">
                <span class="summary-label">Payments Made</span>
                <span class="summary-value negative">RWF {{ number_format($paymentsToday ?? 0, 2) }}</span>
            </div>
            @php $todayNet = ($incomesToday ?? 0) - ($allExpensesToday ?? 0) - ($paymentsToday ?? 0); @endphp
            <div class="summary-row">
                <span class="summary-label">Net Balance</span>
                <span class="summary-value {{ $todayNet >= 0 ? 'positive' : 'negative' }}">RWF {{ number_format($todayNet, 2) }}</span>
            </div>
        </div>

        <!-- Overall Summary -->
        <div class="summary-section">
            <h3>💰 Overall Financial Summary</h3>
            <div class="summary-row">
                <span class="summary-label">Total Income</span>
                <span class="summary-value positive">RWF {{ number_format($incomesTotal ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Expenses</span>
                <span class="summary-value negative">RWF {{ number_format($allExpensesTotal ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Payments</span>
                <span class="summary-value negative">RWF {{ number_format($paymentsTotal ?? 0, 2) }}</span>
            </div>
            @php $overallNet = ($incomesTotal ?? 0) - ($allExpensesTotal ?? 0) - ($paymentsTotal ?? 0); @endphp
            <div class="summary-row">
                <span class="summary-label">Net Profit</span>
                <span class="summary-value {{ $overallNet >= 0 ? 'positive' : 'negative' }}">RWF {{ number_format($overallNet, 2) }}</span>
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
                <span class="summary-value {{ ($totalDesignPaid - ($totalDesignExpenses ?? 0)) >= 0 ? 'positive' : 'negative' }}">RWF {{ number_format($totalDesignPaid - ($totalDesignExpenses ?? 0), 2) }}</span>
            </div>
        </div>

        <div class="summary-section">
            <h3>🔨 Execution Phase</h3>
            <div class="summary-row">
                <span class="summary-label">Total Value</span>
                <span class="summary-value neutral">RWF {{ number_format($totalExecutionValue, 2) }}</span>
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
                <span class="summary-value {{ ($totalExecutionPaid - ($totalExecutionExpenses ?? 0)) >= 0 ? 'positive' : 'negative' }}">RWF {{ number_format($totalExecutionPaid - ($totalExecutionExpenses ?? 0), 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Financial Calendar -->
    <div class="calendar-section">
        <div class="calendar-header">
            <h2>📅 Financial Calendar</h2>
            <button class="calendar-today-btn" onclick="goToToday()">Today</button>
        </div>

        <div class="calendar-kpis">
            <div class="calendar-chip positive">💰 Income Days</div>
            <div class="calendar-chip negative">💸 Expense Days</div>
            <div class="calendar-chip neutral">📊 Mixed Days</div>
        </div>

        <div class="calendar-nav">
            <button onclick="changeMonth(-1)">‹</button>
            <span class="calendar-month-year" id="calendarMonthYear"></span>
            <button onclick="changeMonth(1)">›</button>
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
    </div>
</div>

<!-- Calendar Modal -->
<div id="calendarModal" class="calendar-modal">
    <div class="calendar-modal-content">
        <div class="calendar-modal-header">
            <h3 id="modalDate"></h3>
            <button class="calendar-modal-close" onclick="closeCalendarModal()">×</button>
        </div>
        <div id="modalBody" class="calendar-modal-body">
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Calendar functionality here (keeping existing JavaScript)
let currentDate = new Date();
let calendarData = {};

async function fetchCalendarData() {
    try {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;

        const response = await fetch(`/api/calendar/month-data?year=${year}&month=${month}`);
        const data = await response.json();
        calendarData = data.dates || {};

        updateCalendarIndicators();
    } catch (error) {
        console.log('Calendar data not available');
    }
}

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    document.getElementById('calendarMonthYear').textContent =
        currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startDay = firstDay.getDay();

    const grid = document.getElementById('calendarGrid');
    const headers = grid.querySelectorAll('.calendar-day-header');

    grid.innerHTML = '';
    headers.forEach(header => grid.appendChild(header));

    for (let i = 0; i < startDay; i++) {
        const prevDate = new Date(year, month, -startDay + i + 1);
        const dayEl = document.createElement('div');
        dayEl.className = 'calendar-day other-month';
        dayEl.textContent = prevDate.getDate();
        grid.appendChild(dayEl);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dayEl = document.createElement('div');
        dayEl.className = 'calendar-day';
        dayEl.textContent = day;

        const today = new Date();
        if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
            dayEl.classList.add('today');
        }

        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        dayEl.addEventListener('click', () => {
            const monthName = currentDate.toLocaleDateString('en-US', { month: 'long' });
            openCalendarModal(dateStr, day, monthName, year);
        });

        grid.appendChild(dayEl);
    }

    const remainingCells = 42 - grid.children.length;
    for (let i = 1; i <= remainingCells; i++) {
        const nextDate = new Date(year, month + 1, i);
        const dayEl = document.createElement('div');
        dayEl.className = 'calendar-day other-month';
        dayEl.textContent = nextDate.getDate();
        grid.appendChild(dayEl);
    }

    fetchCalendarData();
}

function updateCalendarIndicators() {
    const grid = document.getElementById('calendarGrid');
    const dayElements = grid.querySelectorAll('.calendar-day:not(.other-month)');

    dayElements.forEach(dayEl => {
        const day = parseInt(dayEl.textContent);
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;
        const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

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

// Modal functions
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

function closeCalendarModal() {
    document.getElementById('calendarModal').classList.remove('active');
}

function renderModalContent(data, day, month, year) {
    const modalBody = document.getElementById('modalBody');
    const totalAllExpenses = (data.totalExpenses || 0) + (data.totalPayments || 0);
    const balance = (data.totalIncome || 0) - totalAllExpenses;
    const balanceClass = balance >= 0 ? 'positive' : 'negative';

    let projectCardsHtml = '';
    if (data.projects && data.projects.length > 0) {
        projectCardsHtml = data.projects.map((p, index) => {
            const projectBalance = (p.income || 0) - (p.expenses || 0);
            const projectBalanceClass = projectBalance >= 0 ? 'positive' : 'negative';

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

    modalBody.innerHTML = `
        <div class="modal-summary">
            <div class="modal-summary-item">
                <span>Total Income</span>
                <span class="positive">+RWF ${Number(data.totalIncome || 0).toLocaleString()}</span>
            </div>
            <div class="modal-summary-item">
                <span>Total Expenses</span>
                <span class="negative">-RWF ${Number(totalAllExpenses).toLocaleString()}</span>
            </div>
            <div class="modal-summary-item">
                <span>Net Balance</span>
                <span class="${balanceClass}">${balance >= 0 ? '+' : ''}RWF ${Number(balance).toLocaleString()}</span>
            </div>
        </div>
        ${projectCardsHtml}
    `;
}

function toggleProjectDetails(index) {
    const details = document.getElementById(`projectDetails${index}`);
    details.classList.toggle('visible');
}

// Modal event listeners
document.getElementById('calendarModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCalendarModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCalendarModal();
    }
});

// Initialize calendar on page load
renderCalendar();
</script>

<style>
.calendar-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    backdrop-filter: blur(5px);
}
.calendar-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}
.calendar-modal-content {
    background: white;
    border-radius: 12px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}
.calendar-modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.calendar-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #999;
}
.calendar-modal-body {
    padding: 1.5rem;
}
.modal-summary {
    display: grid;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}
.modal-summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.project-summary-card {
    margin-bottom: 1rem;
    padding: 1rem;
    border: 1px solid #eee;
    border-radius: 8px;
}
.project-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}
.project-details-toggle {
    background: #667eea;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 0.5rem;
}
.project-details-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}
.project-details-content.visible {
    max-height: 300px;
    margin-top: 1rem;
}
.detail-list {
    display: grid;
    gap: 0.5rem;
}
.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 4px;
}
.detail-item-info {
    flex: 1;
}
.detail-item-desc {
    font-weight: 500;
    display: block;
}
.detail-item-meta {
    font-size: 0.8rem;
    color: #666;
}
.positive {
    color: #27ae60;
}
.negative {
    color: #e74c3c;
}
@keyframes todayPulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(39, 174, 96, 0.5); }
    50% { transform: scale(1.15); box-shadow: 0 0 0 15px rgba(39, 174, 96, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.15); }
}
</style>
@endsection
