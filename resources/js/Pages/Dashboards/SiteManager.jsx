import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function SiteManagerDashboard({ stats }) {
    const siteStats = [
        { label: 'Active Projects', value: stats?.activeProjects || 0, icon: '🏗️' },
        { label: 'Team Members', value: stats?.teamMembers || 0, icon: '👷' },
        { label: 'Tasks Completed', value: stats?.tasksCompleted || 0, icon: '✅' },
        { label: 'On-Time Rate', value: stats?.onTimeRate || 'N/A', icon: '⏱️' }
    ];

    const menuItems = [
        { name: 'Dashboard', icon: '📍', href: '#' },
        { name: 'Projects', icon: '🏗️', href: '#' },
        { name: 'Tasks', icon: '📋', href: '#' },
        { name: 'Team', icon: '👷', href: '#' },
        { name: 'Equipment', icon: '🔧', href: '#' },
        { name: 'Reports', icon: '📊', href: '#' }
    ];

    return (
        <AuthenticatedLayout>
            <Head title="Site Manager Dashboard" />
            <div className="flex h-screen bg-gray-100">
                {/* Sidebar */}
                <div className="w-64 bg-green-900 text-white shadow-lg">
                    <div className="p-6 border-b border-green-700">
                        <h2 className="text-2xl font-bold">SiteLedger</h2>
                        <p className="text-sm text-green-200">Site Operations</p>
                    </div>
                    <nav className="p-4">
                        {menuItems.map((item) => (
                            <a key={item.name} href={item.href} className="flex items-center px-4 py-3 mb-2 rounded hover:bg-green-800 transition">
                                <span className="mr-3 text-lg">{item.icon}</span>
                                <span>{item.name}</span>
                            </a>
                        ))}
                    </nav>
                </div>

                {/* Main Content */}
                <div className="flex-1 overflow-auto">
                    <div className="p-8">
                        <h1 className="text-4xl font-bold mb-8 text-gray-800">Site Manager Dashboard</h1>

                        {/* Site Stats */}
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            {siteStats.map((stat) => (
                                <div key={stat.label} className="bg-white rounded-lg shadow p-6">
                                    <div className="text-3xl mb-2">{stat.icon}</div>
                                    <p className="text-gray-500 text-sm">{stat.label}</p>
                                    <p className="text-2xl font-bold text-gray-800">{stat.value}</p>
                                </div>
                            ))}
                        </div>

                        {/* Project Overview */}
                        <div className="grid grid-cols-2 gap-6">
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-xl font-bold mb-4 text-gray-800">Active Projects</h2>
                                <p className="text-gray-600">Track ongoing construction projects</p>
                            </div>
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-xl font-bold mb-4 text-gray-800">Team Schedule</h2>
                                <p className="text-gray-600">View work schedule and assignments</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
