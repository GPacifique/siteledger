import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function SystemAdminDashboard({ stats }) {
    const systemStats = [
        { label: 'System Status', value: stats?.systemStatus || 'Unknown', icon: '✅', color: 'green' },
        { label: 'Active Users', value: stats?.activeUsers || 0, icon: '👥', color: 'blue' },
        { label: 'API Uptime', value: stats?.apiUptime ? `${stats.apiUptime}%` : 'N/A', icon: '⚡', color: 'yellow' },
        { label: 'Disk Usage', value: stats?.diskUsage ? `${stats.diskUsage}%` : 'N/A', icon: '💾', color: 'orange' }
    ];

    const menuItems = [
        { name: 'System Status', icon: '⚙️', href: '#' },
        { name: 'Users & Roles', icon: '👤', href: '#' },
        { name: 'Tenants', icon: '🏢', href: '#' },
        { name: 'Database', icon: '🗄️', href: '#' },
        { name: 'Logs', icon: '📋', href: '#' },
        { name: 'Settings', icon: '🔧', href: '#' }
    ];

    return (
        <AuthenticatedLayout>
            <Head title="System Admin Dashboard" />
            <div className="flex h-screen bg-gray-100">
                {/* Sidebar */}
                <div className="w-64 bg-red-900 text-white shadow-lg">
                    <div className="p-6 border-b border-red-700">
                        <h2 className="text-2xl font-bold">SiteLedger</h2>
                        <p className="text-sm text-red-200">System Administration</p>
                    </div>
                    <nav className="p-4">
                        {menuItems.map((item) => (
                            <a key={item.name} href={item.href} className="flex items-center px-4 py-3 mb-2 rounded hover:bg-red-800 transition">
                                <span className="mr-3 text-lg">{item.icon}</span>
                                <span>{item.name}</span>
                            </a>
                        ))}
                    </nav>
                </div>

                {/* Main Content */}
                <div className="flex-1 overflow-auto">
                    <div className="p-8">
                        <h1 className="text-4xl font-bold mb-8 text-gray-800">System Admin Dashboard</h1>

                        {/* System Stats */}
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            {systemStats.map((stat) => (
                                <div key={stat.label} className="bg-white rounded-lg shadow p-6">
                                    <div className="text-3xl mb-2">{stat.icon}</div>
                                    <p className="text-gray-500 text-sm">{stat.label}</p>
                                    <p className="text-2xl font-bold text-gray-800">{stat.value}</p>
                                </div>
                            ))}
                        </div>

                        {/* System Administration Sections */}
                        <div className="grid grid-cols-2 gap-6">
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-xl font-bold mb-4 text-gray-800">User Management</h2>
                                <p className="text-gray-600">Manage system users and permissions</p>
                            </div>
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-xl font-bold mb-4 text-gray-800">Database Health</h2>
                                <p className="text-gray-600">Monitor database performance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
