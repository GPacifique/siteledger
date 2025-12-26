import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function AccountantDashboard({ stats }) {
    const financials = [
        { label: 'Total Income', value: '$' + (Math.round(stats?.totalIncome / 1000) || 0) + 'K', change: '+12%', icon: '📈' },
        { label: 'Total Expenses', value: '$' + (Math.round(stats?.totalExpenses / 1000) || 0) + 'K', change: '+5%', icon: '📊' },
        { label: 'Net Profit', value: '$' + (Math.round(stats?.netProfit / 1000) || 0) + 'K', change: '+18%', icon: '💵' },
        { label: 'Unpaid Invoices', value: '$' + (Math.round(stats?.unpaidInvoices / 1000) || 0) + 'K', change: '-3%', icon: '⏰' }
    ];

    const menuItems = [
        { name: 'Dashboard', icon: '💼', href: '#' },
        { name: 'Income', icon: '📈', href: '#' },
        { name: 'Expenses', icon: '💸', href: '#' },
        { name: 'Payments', icon: '💳', href: '#' },
        { name: 'Reports', icon: '📊', href: '#' },
        { name: 'Invoices', icon: '📄', href: '#' }
    ];

    return (
        <AuthenticatedLayout>
            <Head title="Accountant Dashboard" />
            <div className="flex h-screen bg-gray-100">
                {/* Sidebar */}
                <div className="w-64 bg-blue-900 text-white shadow-lg">
                    <div className="p-6 border-b border-blue-700">
                        <h2 className="text-2xl font-bold">SiteLedger</h2>
                        <p className="text-sm text-blue-200">Finance</p>
                    </div>
                    <nav className="p-4">
                        {menuItems.map((item) => (
                            <a key={item.name} href={item.href} className="flex items-center px-4 py-3 mb-2 rounded hover:bg-blue-800 transition">
                                <span className="mr-3 text-lg">{item.icon}</span>
                                <span>{item.name}</span>
                            </a>
                        ))}
                    </nav>
                </div>

                {/* Main Content */}
                <div className="flex-1 overflow-auto">
                    <div className="p-8">
                        <h1 className="text-4xl font-bold mb-8 text-gray-800">Accountant Dashboard</h1>

                        {/* Financial Stats */}
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            {financials.map((stat) => (
                                <div key={stat.label} className="bg-white rounded-lg shadow p-6">
                                    <div className="text-3xl mb-2">{stat.icon}</div>
                                    <p className="text-gray-500 text-sm">{stat.label}</p>
                                    <p className="text-2xl font-bold text-gray-800">{stat.value}</p>
                                    <p className="text-green-600 text-sm mt-2">{stat.change}</p>
                                </div>
                            ))}
                        </div>

                        {/* Financial Summary */}
                        <div className="grid grid-cols-2 gap-6">
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-xl font-bold mb-4 text-gray-800">Recent Transactions</h2>
                                <p className="text-gray-600">View recent income and expense records</p>
                            </div>
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-xl font-bold mb-4 text-gray-800">Budget Overview</h2>
                                <p className="text-gray-600">Monitor spending against budget</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
