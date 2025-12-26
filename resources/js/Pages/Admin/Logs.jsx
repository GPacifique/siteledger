import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Logs() {
    const menuItems = [
        { name: 'Dashboard', icon: '📊', route: 'dashboard.admin' },
        { name: 'Users', icon: '👥', route: 'admin.users' },
        { name: 'Projects', icon: '📁', route: 'admin.projects' },
        { name: 'Financial Reports', icon: '📈', route: 'admin.finances' },
        { name: 'System Settings', icon: '⚙️', route: 'admin.settings' },
        { name: 'Activity Logs', icon: '📝', route: 'admin.logs' }
    ];

    return (
        <AuthenticatedLayout>
            <Head title="Activity Logs" />
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
                        <h1 className="text-4xl font-bold mb-8 text-gray-800">Activity Logs</h1>

                        {/* Filters */}
                        <div className="bg-white rounded-lg shadow p-6 mb-8">
                            <h2 className="text-lg font-bold mb-4 text-gray-800">Log Filters</h2>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label className="block text-gray-600 font-semibold mb-2">Activity Type</label>
                                    <select className="w-full px-4 py-2 border border-gray-300 rounded">
                                        <option>All Activities</option>
                                        <option>User Login</option>
                                        <option>Data Created</option>
                                        <option>Data Modified</option>
                                        <option>Data Deleted</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-gray-600 font-semibold mb-2">Date Range</label>
                                    <input type="date" className="w-full px-4 py-2 border border-gray-300 rounded" />
                                </div>
                                <div className="flex items-end">
                                    <button className="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                        Filter Logs
                                    </button>
                                </div>
                            </div>
                        </div>

                        {/* Activity Logs Table */}
                        <div className="bg-white rounded-lg shadow p-6">
                            <h2 className="text-xl font-bold mb-4 text-gray-800">Recent Activities</h2>
                            <p className="text-gray-600">Activity logging and audit trail coming soon...</p>
                            <div className="mt-6 overflow-x-auto">
                                <table className="w-full">
                                    <thead>
                                        <tr className="border-b bg-gray-50">
                                            <th className="text-left py-3 px-4 font-semibold text-gray-700">User</th>
                                            <th className="text-left py-3 px-4 font-semibold text-gray-700">Activity</th>
                                            <th className="text-left py-3 px-4 font-semibold text-gray-700">Resource</th>
                                            <th className="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr className="border-b hover:bg-gray-50">
                                            <td className="py-3 px-4 text-gray-800">Admin User</td>
                                            <td className="py-3 px-4 text-gray-800">Logged in</td>
                                            <td className="py-3 px-4 text-gray-600">Dashboard</td>
                                            <td className="py-3 px-4 text-gray-600">Just now</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
