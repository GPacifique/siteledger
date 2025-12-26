import React from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link } from '@inertiajs/react';
import { CurrencyFormatter } from '@/Utils/CurrencyFormatter';

export default function AdminDashboard({
    stats,
    dailyStats = [],
    weeklyStats = [],
    financialSummary = {},
    topProjects = [],
    recentPayments = {},
    cashFlowAnalysis = [],
    paymentStatusBreakdown = []
}) {
    const displayStats = [
        { label: 'Total Users', value: stats?.totalUsers || 0, icon: '👥' },
        { label: 'Active Projects', value: stats?.activeProjects || 0, icon: '📊' },
        { label: 'Total Revenue', value: CurrencyFormatter.formatShort(stats?.totalRevenue || 0), icon: '💰' },
        { label: 'System Health', value: stats?.systemHealth || 'N/A', icon: '⚙️' }
    ];

    const menuItems = [
        { name: 'Dashboard', icon: '📊', route: 'dashboard.admin' },
        { name: 'Users', icon: '👥', route: 'admin.users' },
        { name: 'Projects', icon: '📁', route: 'admin.projects' },
        { name: 'Financial Reports', icon: '📈', route: 'admin.finances' },
        { name: 'System Settings', icon: '⚙️', route: 'admin.settings' },
        { name: 'Activity Logs', icon: '📝', route: 'admin.logs' }
    ];

    // Calculate financial metrics
    const thisMonth = financialSummary?.this_month || {};
    const monthlyIncome = thisMonth?.income || 0;
    const monthlyExpense = thisMonth?.expense || 0;
    const monthlyProfit = thisMonth?.balance || 0;

    const thisYear = financialSummary?.this_year || {};
    const yearlyIncome = thisYear?.income || 0;
    const yearlyExpense = thisYear?.expense || 0;
    const yearlyProfit = thisYear?.balance || 0;

    const allTime = financialSummary?.all_time || {};
    const totalIncome = allTime?.income || 0;
    const totalExpense = allTime?.expense || 0;
    const totalProfit = allTime?.balance || 0;

    // Format currency - using RWF
    const formatCurrency = (amount) => {
        return CurrencyFormatter.format(amount);
    };

    // Get chart data for daily stats
    const dailyChartData = dailyStats.slice(-30).map(day => ({
        date: day.date_formatted,
        income: day.income,
        expense: day.expense,
        balance: day.balance
    }));

    // Get chart data for weekly stats
    const weeklyChartData = weeklyStats.map(week => ({
        label: week.week_label,
        income: week.income,
        expense: week.expense,
        balance: week.balance
    }));

    return (
        <DashboardLayout menuItems={menuItems}>
            <Head title="Admin Dashboard" />
            <div className="p-8 bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
                <div className="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                    <h1 className="text-4xl font-extrabold text-blue-800 tracking-tight">Admin Dashboard</h1>
                    {/* Notifications Widget */}
                    <div className="bg-white rounded-xl shadow-lg px-6 py-4 flex items-center gap-3 border border-blue-100 hover:shadow-xl transition">
                        <span className="text-blue-600 text-2xl">🔔</span>
                        <div>
                            <p className="font-semibold text-blue-700">Notifications</p>
                            <p className="text-gray-500 text-sm">No new notifications</p>
                        </div>
                    </div>
                </div>
                {/* Top Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    {displayStats.map((stat) => (
                        <div key={stat.label} className="bg-gradient-to-br from-blue-100 via-white to-purple-100 rounded-2xl shadow-lg p-6 hover:shadow-2xl transition border border-blue-50">
                            <div className="text-3xl mb-2">{stat.icon}</div>
                            <p className="text-gray-500 text-sm font-medium">{stat.label}</p>
                            <p className="text-2xl font-extrabold text-blue-900">{stat.value}</p>
                        </div>
                    ))}
                </div>
                {/* Financial Summary Section */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {/* Daily Finances */}
                    <div className="bg-white rounded-lg shadow p-6">
                        <h3 className="text-lg font-bold text-gray-800 mb-4">Today's Finances</h3>
                        <div className="space-y-3">
                            <div className="flex justify-between items-center border-b pb-2">
                                <span className="text-gray-600">Income:</span>
                                <span className="font-bold text-green-600">{formatCurrency(financialSummary?.today?.income || 0)}</span>
                            </div>
                            <div className="flex justify-between items-center border-b pb-2">
                                <span className="text-gray-600">Expenses:</span>
                                <span className="font-bold text-red-600">{formatCurrency(financialSummary?.today?.expense || 0)}</span>
                            </div>
                            <div className="flex justify-between items-center pt-2">
                                <span className="text-gray-600 font-semibold">Balance:</span>
                                <span className={`font-bold text-lg ${(financialSummary?.today?.balance || 0) >= 0 ? 'text-blue-600' : 'text-red-600'}`}>
                                    {formatCurrency(financialSummary?.today?.balance || 0)}
                                </span>
                            </div>
                        </div>
                    </div>
                    {/* Monthly Finances */}
                    <div className="bg-white rounded-lg shadow p-6">
                        <h3 className="text-lg font-bold text-gray-800 mb-4">This Month</h3>
                        <div className="space-y-3">
                            <div className="flex justify-between items-center border-b pb-2">
                                <span className="text-gray-600">Income:</span>
                                <span className="font-bold text-green-600">{formatCurrency(monthlyIncome)}</span>
                            </div>
                            <div className="flex justify-between items-center border-b pb-2">
                                <span className="text-gray-600">Expenses:</span>
                                <span className="font-bold text-red-600">{formatCurrency(monthlyExpense)}</span>
                            </div>
                            <div className="flex justify-between items-center pt-2">
                                <span className="text-gray-600 font-semibold">Profit:</span>
                                <span className={`font-bold text-lg ${monthlyProfit >= 0 ? 'text-blue-600' : 'text-red-600'}`}>
                                    {formatCurrency(monthlyProfit)}
                                </span>
                            </div>
                        </div>
                    </div>
                    {/* Yearly Finances */}
                    <div className="bg-white rounded-lg shadow p-6">
                        <h3 className="text-lg font-bold text-gray-800 mb-4">This Year</h3>
                        <div className="space-y-3">
                            <div className="flex justify-between items-center border-b pb-2">
                                <span className="text-gray-600">Income:</span>
                                <span className="font-bold text-green-600">{formatCurrency(yearlyIncome)}</span>
                            </div>
                            <div className="flex justify-between items-center border-b pb-2">
                                <span className="text-gray-600">Expenses:</span>
                                <span className="font-bold text-red-600">{formatCurrency(yearlyExpense)}</span>
                            </div>
                            <div className="flex justify-between items-center pt-2">
                                <span className="text-gray-600 font-semibold">Profit:</span>
                                <span className={`font-bold text-lg ${yearlyProfit >= 0 ? 'text-blue-600' : 'text-red-600'}`}>
                                    {formatCurrency(yearlyProfit)}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                {/* All-Time Summary */}
                <div className="bg-white rounded-lg shadow p-6 mb-8">
                    <h3 className="text-lg font-bold text-gray-800 mb-4">All-Time Summary</h3>
                    <div className="grid grid-cols-3 gap-4">
                        <div className="text-center p-4 bg-green-50 rounded">
                            <p className="text-gray-600 text-sm">Total Income</p>
                            <p className="text-2xl font-bold text-green-600">{formatCurrency(totalIncome)}</p>
                        </div>
                        <div className="text-center p-4 bg-red-50 rounded">
                            <p className="text-gray-600 text-sm">Total Expenses</p>
                            <p className="text-2xl font-bold text-red-600">{formatCurrency(totalExpense)}</p>
                        </div>
                        <div className="text-center p-4 bg-blue-50 rounded">
                            <p className="text-gray-600 text-sm">Total Profit</p>
                            <p className={`text-2xl font-bold ${totalProfit >= 0 ? 'text-blue-600' : 'text-red-600'}`}>
                                {formatCurrency(totalProfit)}
                            </p>
                        </div>
                    </div>
                </div>
                {/* Top Projects Section */}
                {topProjects && topProjects.length > 0 && (
                    <div className="bg-white rounded-lg shadow p-6 mb-8">
                        <h3 className="text-lg font-bold text-gray-800 mb-4">Top Performing Projects</h3>
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b">
                                        <th className="text-left py-2 px-4 font-semibold text-gray-700">Project</th>
                                        <th className="text-right py-2 px-4 font-semibold text-gray-700">Income</th>
                                        <th className="text-right py-2 px-4 font-semibold text-gray-700">Target</th>
                                        <th className="text-right py-2 px-4 font-semibold text-gray-700">Completion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {topProjects.map((project) => (
                                        <tr key={project.id} className="border-b hover:bg-gray-50">
                                            <td className="py-3 px-4 text-gray-800">{project.name}</td>
                                            <td className="text-right py-3 px-4 text-green-600 font-semibold">{formatCurrency(project.income)}</td>
                                            <td className="text-right py-3 px-4 text-gray-600">{formatCurrency(project.target)}</td>
                                            <td className="text-right py-3 px-4">
                                                <div className="w-full bg-gray-200 rounded-full h-2">
                                                    <div
                                                        className="bg-blue-600 h-2 rounded-full"
                                                        style={{ width: `${Math.min(project.completion_percent, 100)}%` }}
                                                    ></div>
                                                </div>
                                                <span className="text-sm text-gray-600">{project.completion_percent}%</span>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                )}
                {/* Daily Stats Chart Section */}
                {dailyChartData && dailyChartData.length > 0 && (
                    <div className="bg-white rounded-lg shadow p-6 mb-8">
                        <h3 className="text-lg font-bold text-gray-800 mb-4">Daily Financial Trend (Last 30 Days)</h3>
                        <div className="h-64 flex items-end justify-between gap-1">
                            {dailyChartData.map((day, index) => {
                                const maxValue = Math.max(...dailyChartData.map(d => Math.max(d.income, d.expense)));
                                const incomeHeight = (day.income / maxValue) * 100;
                                const expenseHeight = (day.expense / maxValue) * 100;
                                return (
                                    <div key={index} className="flex-1 flex flex-col items-center gap-1">
                                        <div className="w-full flex gap-0.5 items-end justify-center h-full">
                                            <div
                                                className="flex-1 bg-green-400 opacity-70 rounded-t"
                                                style={{ height: `${incomeHeight}%`, minHeight: '4px' }}
                                                title={`Income: ${formatCurrency(day.income)}`}
                                            ></div>
                                            <div
                                                className="flex-1 bg-red-400 opacity-70 rounded-t"
                                                style={{ height: `${expenseHeight}%`, minHeight: '4px' }}
                                                title={`Expense: ${formatCurrency(day.expense)}`}
                                            ></div>
                                        </div>
                                        <span className="text-xs text-gray-500 mt-1">{day.date}</span>
                                    </div>
                                );
                            })}
                        </div>
                        <div className="flex gap-6 mt-4 justify-center">
                            <div className="flex items-center gap-2">
                                <div className="w-4 h-4 bg-green-400 rounded"></div>
                                <span className="text-sm text-gray-600">Income</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <div className="w-4 h-4 bg-red-400 rounded"></div>
                                <span className="text-sm text-gray-600">Expenses</span>
                            </div>
                        </div>
                    </div>
                )}
                {/* Weekly Stats Chart Section */}
                {weeklyChartData && weeklyChartData.length > 0 && (
                    <div className="bg-white rounded-lg shadow p-6 mb-8">
                        <h3 className="text-lg font-bold text-gray-800 mb-4">Weekly Financial Trend (Last 12 Weeks)</h3>
                        <div className="h-64 flex items-end justify-between gap-2">
                            {weeklyChartData.map((week, index) => {
                                const maxValue = Math.max(...weeklyChartData.map(w => Math.max(w.income, w.expense)));
                                const incomeHeight = (week.income / maxValue) * 100;
                                const expenseHeight = (week.expense / maxValue) * 100;
                                return (
                                    <div key={index} className="flex-1 flex flex-col items-center">
                                        <div className="w-full flex gap-1 items-end justify-center h-full">
                                            <div
                                                className="flex-1 bg-blue-500 rounded-t"
                                                style={{ height: `${incomeHeight}%`, minHeight: '4px' }}
                                                title={`Income: ${formatCurrency(week.income)}`}
                                            ></div>
                                            <div
                                                className="flex-1 bg-orange-500 rounded-t"
                                                style={{ height: `${expenseHeight}%`, minHeight: '4px' }}
                                                title={`Expense: ${formatCurrency(week.expense)}`}
                                            ></div>
                                        </div>
                                        <span className="text-xs text-gray-500 mt-2 text-center">{week.label.split(' - ')[0]}</span>
                                    </div>
                                );
                            })}
                        </div>
                        <div className="flex gap-6 mt-4 justify-center">
                            <div className="flex items-center gap-2">
                                <div className="w-4 h-4 bg-blue-500 rounded"></div>
                                <span className="text-sm text-gray-600">Income</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <div className="w-4 h-4 bg-orange-500 rounded"></div>
                                <span className="text-sm text-gray-600">Expenses</span>
                            </div>
                        </div>
                    </div>
                )}
                {/* Payment Status Breakdown */}
                {paymentStatusBreakdown && paymentStatusBreakdown.length > 0 && (
                    <div className="bg-white rounded-lg shadow p-6">
                        <h3 className="text-lg font-bold text-gray-800 mb-4">Payment Status Breakdown</h3>
                        <div className="space-y-3">
                            {paymentStatusBreakdown.map((status, index) => (
                                <div key={index} className="flex items-center justify-between">
                                    <div className="flex items-center gap-2">
                                        <div className="w-3 h-3 rounded-full" style={{
                                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'][index % 4]
                                        }}></div>
                                        <span className="text-gray-600">{status.status} ({status.count})</span>
                                    </div>
                                    <span className="font-semibold text-gray-800">{formatCurrency(status.total)}</span>
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}
