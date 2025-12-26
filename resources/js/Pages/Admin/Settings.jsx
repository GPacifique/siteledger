import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Settings({ systemSettings = {} }) {
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
            <Head title="System Settings" />
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
                        <h1 className="text-4xl font-bold mb-8 text-gray-800">System Settings</h1>

                        {/* Application Settings */}
                        <div className="bg-white rounded-lg shadow p-6 mb-8">
                            <h2 className="text-xl font-bold mb-4 text-gray-800">Application Settings</h2>
                            <div className="space-y-4">
                                <div className="border-b pb-4">
                                    <label className="text-gray-600 font-semibold">Application Name</label>
                                    <p className="text-gray-800 mt-1">{systemSettings?.appName || 'SiteLedger'}</p>
                                </div>
                                <div className="border-b pb-4">
                                    <label className="text-gray-600 font-semibold">Application URL</label>
                                    <p className="text-gray-800 mt-1">{systemSettings?.appUrl || 'http://localhost'}</p>
                                </div>
                                <div>
                                    <label className="text-gray-600 font-semibold">Debug Mode</label>
                                    <p className="text-gray-800 mt-1">{systemSettings?.appDebug ? '✅ Enabled' : '❌ Disabled'}</p>
                                </div>
                            </div>
                        </div>

                        {/* Settings Management Placeholder */}
                        <div className="bg-white rounded-lg shadow p-6">
                            <h2 className="text-xl font-bold mb-4 text-gray-800">System Configuration</h2>
                            <p className="text-gray-600">Additional system configuration options coming soon...</p>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
