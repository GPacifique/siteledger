import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { CurrencyFormatter } from '@/Utils/CurrencyFormatter';

export default function Finances({ financialSummary = {}, dailyStats = [], weeklyStats = [], cashFlowAnalysis = [] }) {
    const menuItems = [
        { name: 'Dashboard', icon: '📊', route: 'dashboard.admin' },
        { name: 'Users', icon: '👥', route: 'admin.users' },
        { name: 'Projects', icon: '📁', route: 'admin.projects' },
        { name: 'Financial Reports', icon: '📈', route: 'admin.finances' },
        { name: 'System Settings', icon: '⚙️', route: 'admin.settings' },
        { name: 'Activity Logs', icon: '📝', route: 'admin.logs' }
    ];

    const allTime = financialSummary?.all_time || {};
    const formatCurrency = (amount) => {
        return CurrencyFormatter.format(amount);
    };

    return (
        <AuthenticatedLayout>
            <Head title="Financial Reports" />
            <div className="flex h-screen bg-gray-100">
                {/* Sidebar */}
                <div className="w-64 bg-gray-900 text-white shadow-lg overflow-y-auto">
                    <div className="p-6 border-b border-gray-700 sticky top-0 bg-gray-900">
                        <h2 className="text-2xl font-bold">SiteLedger</h2>
                        <p className="text-sm text-gray-400">Admin Panel</p>
                    </div>
                    <nav className="p-4">
                        {menuItems.map((item) => (
                            <Link key={item.name} href={route(item.route)} className="flex items-center px-4 py-3 mb-2 rounded hover:bg-gray-800 transition">
                                <span className="mr-3 text-lg">{item.icon}</span>
                                <span>{item.name}</span>
                            </Link>
                        ))}
                    </nav>
                </div>

                {/* Main Content */}
                <div className="flex-1 overflow-auto">
                    <div className="p-8">
                        <h1 className="text-4xl font-bold mb-8 text-gray-800">Financial Reports</h1>

                        {/* All-Time Summary */}
                        <div className="bg-white rounded-lg shadow p-6 mb-8">
                            <h3 className="text-lg font-bold text-gray-800 mb-4">All-Time Summary</h3>
                            <div className="grid grid-cols-3 gap-4">
                                <div className="text-center p-4 bg-green-50 rounded">
                                    <p className="text-gray-600 text-sm">Total Income</p>
                                    <p className="text-2xl font-bold text-green-600">{formatCurrency(allTime?.income || 0)}</p>
                                </div>
                                <div className="text-center p-4 bg-red-50 rounded">
                                    <p className="text-gray-600 text-sm">Total Expenses</p>
                                    <p className="text-2xl font-bold text-red-600">{formatCurrency(allTime?.expense || 0)}</p>
                                </div>
                                <div className="text-center p-4 bg-blue-50 rounded">
                                    <p className="text-gray-600 text-sm">Total Profit</p>
                                    <p className={`text-2xl font-bold ${(allTime?.balance || 0) >= 0 ? 'text-blue-600' : 'text-red-600'}`}>
                                        {formatCurrency(allTime?.balance || 0)}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Detailed Reports Placeholder */}
                        <div className="bg-white rounded-lg shadow p-6">
                            <h2 className="text-xl font-bold mb-4 text-gray-800">Detailed Financial Reports</h2>
                            <p className="text-gray-600">Detailed financial reports and export features coming soon...</p>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
