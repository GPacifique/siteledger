import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { CurrencyFormatter } from '@/Utils/CurrencyFormatter';

export default function ProjectDetail({ project = {}, filterContext = {} }) {
    const menuItems = [
        { name: 'Dashboard', icon: '📊', route: 'dashboard.admin' },
        { name: 'Users', icon: '👥', route: 'admin.users' },
        { name: 'Projects', icon: '📁', route: 'admin.projects' },
        { name: 'Financial Reports', icon: '📈', route: 'admin.finances' },
        { name: 'System Settings', icon: '⚙️', route: 'admin.settings' },
        { name: 'Activity Logs', icon: '📝', route: 'admin.logs' }
    ];

    const formatDate = (date) => {
        if (!date) return 'N/A';
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    };

    const getStatusColor = (status) => {
        const colors = {
            'active': 'bg-green-100 text-green-800',
            'in_progress': 'bg-blue-100 text-blue-800',
            'completed': 'bg-gray-100 text-gray-800',
            'on_hold': 'bg-yellow-100 text-yellow-800',
            'cancelled': 'bg-red-100 text-red-800',
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    };

    const remaining = (project.budget || 0) - (project.spent || 0);
    const budgetPercentage = project.budget ? ((project.spent / project.budget) * 100).toFixed(1) : 0;

    return (
        <AuthenticatedLayout>
            <Head title={`Project: ${project.name}`} />
            <div className="flex h-screen bg-gray-100">
                {/* Sidebar */}
                <div className="w-64 bg-gray-900 text-white shadow-lg overflow-y-auto">
                    <div className="p-6 border-b border-gray-700 sticky top-0 bg-gray-900">
                        <h2 className="text-2xl font-bold">SiteLedger</h2>
                        <p className="text-sm text-gray-400">Admin Panel</p>
                        {filterContext?.roles && (
                            <p className="text-xs text-gray-500 mt-2">Role: {filterContext.roles.join(', ')}</p>
                        )}
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
                        {/* Header with Back Button */}
                        <div className="flex items-center justify-between mb-8">
                            <div>
                                <h1 className="text-4xl font-bold text-gray-800">{project.name}</h1>
                                <p className="text-gray-600 mt-2">Project ID: {project.id}</p>
                            </div>
                            <Link href={route('admin.projects')} className="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                                ← Back to Projects
                            </Link>
                        </div>

                        {/* Status and Quick Info */}
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                            <div className="bg-white rounded-lg shadow p-6">
                                <p className="text-gray-600 text-sm mb-2">Status</p>
                                <span className={`inline-block px-3 py-1 rounded-full text-sm font-semibold ${getStatusColor(project.status)}`}>
                                    {project.status?.replace('_', ' ') || 'N/A'}
                                </span>
                            </div>
                            <div className="bg-white rounded-lg shadow p-6">
                                <p className="text-gray-600 text-sm mb-2">Progress</p>
                                <p className="text-2xl font-bold text-blue-600">{project.completion_percentage || 0}%</p>
                            </div>
                            <div className="bg-white rounded-lg shadow p-6">
                                <p className="text-gray-600 text-sm mb-2">Team Members</p>
                                <p className="text-2xl font-bold text-purple-600">{project.workers_count || 0}</p>
                            </div>
                            <div className="bg-white rounded-lg shadow p-6">
                                <p className="text-gray-600 text-sm mb-2">Tasks</p>
                                <p className="text-2xl font-bold text-orange-600">{project.tasks_count || 0}</p>
                            </div>
                        </div>

                        {/* Main Content Grid */}
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            {/* Left Column */}
                            <div className="lg:col-span-2">
                                {/* Description */}
                                <div className="bg-white rounded-lg shadow p-8 mb-8">
                                    <h2 className="text-2xl font-bold text-gray-800 mb-4">Project Description</h2>
                                    <p className="text-gray-700 leading-relaxed">
                                        {project.description || 'No description provided'}
                                    </p>
                                </div>

                                {/* Progress Section */}
                                <div className="bg-white rounded-lg shadow p-8 mb-8">
                                    <h2 className="text-2xl font-bold text-gray-800 mb-6">Project Progress</h2>
                                    <div className="space-y-4">
                                        <div>
                                            <div className="flex justify-between mb-2">
                                                <span className="font-medium text-gray-700">Completion</span>
                                                <span className="font-bold text-blue-600">{project.completion_percentage || 0}%</span>
                                            </div>
                                            <div className="w-full bg-gray-200 rounded-full h-3">
                                                <div
                                                    className="bg-blue-600 h-3 rounded-full"
                                                    style={{width: `${project.completion_percentage || 0}%`}}
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Dates */}
                                <div className="bg-white rounded-lg shadow p-8">
                                    <h2 className="text-2xl font-bold text-gray-800 mb-6">Timeline</h2>
                                    <div className="grid grid-cols-2 gap-6">
                                        <div>
                                            <label className="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                                            <p className="text-gray-800">{formatDate(project.start_date)}</p>
                                        </div>
                                        <div>
                                            <label className="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                                            <p className="text-gray-800">{formatDate(project.end_date)}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Right Column */}
                            <div>
                                {/* Financial Info */}
                                <div className="bg-white rounded-lg shadow p-6 mb-8">
                                    <h3 className="text-lg font-bold text-gray-800 mb-6">Budget & Spending</h3>
                                    <div className="space-y-4">
                                        <div className="bg-blue-50 p-4 rounded-lg">
                                            <p className="text-gray-600 text-sm mb-1">Total Budget</p>
                                            <p className="text-2xl font-bold text-blue-600">
                                                {CurrencyFormatter.format(project.budget || 0)}
                                            </p>
                                        </div>
                                        <div className="bg-orange-50 p-4 rounded-lg">
                                            <p className="text-gray-600 text-sm mb-1">Amount Spent</p>
                                            <p className="text-2xl font-bold text-orange-600">
                                                {CurrencyFormatter.format(project.spent || 0)}
                                            </p>
                                        </div>
                                        <div className={`${remaining >= 0 ? 'bg-green-50' : 'bg-red-50'} p-4 rounded-lg`}>
                                            <p className="text-gray-600 text-sm mb-1">Remaining</p>
                                            <p className={`text-2xl font-bold ${remaining >= 0 ? 'text-green-600' : 'text-red-600'}`}>
                                                {CurrencyFormatter.format(remaining)}
                                            </p>
                                        </div>
                                        <div className="border-t pt-4 mt-4">
                                            <p className="text-gray-600 text-sm mb-2">Budget Used</p>
                                            <div className="w-full bg-gray-200 rounded-full h-2 mb-2">
                                                <div
                                                    className={`h-2 rounded-full ${budgetPercentage > 90 ? 'bg-red-600' : 'bg-blue-600'}`}
                                                    style={{width: `${Math.min(budgetPercentage, 100)}%`}}
                                                ></div>
                                            </div>
                                            <p className="text-sm font-medium text-gray-700">{budgetPercentage}%</p>
                                        </div>
                                    </div>
                                </div>

                                {/* Client Info */}
                                <div className="bg-white rounded-lg shadow p-6 mb-8">
                                    <h3 className="text-lg font-bold text-gray-800 mb-4">Client</h3>
                                    <p className="text-gray-800 text-lg font-medium">{project.client_name}</p>
                                    <p className="text-gray-600 text-sm mt-2">Client ID: {project.client_id}</p>
                                </div>

                                {/* Actions */}
                                <div className="bg-white rounded-lg shadow p-6">
                                    <h3 className="text-lg font-bold text-gray-800 mb-4">Actions</h3>
                                    <div className="space-y-2">
                                        <button className="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition font-medium text-sm">
                                            Edit Project
                                        </button>
                                        <button className="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition font-medium text-sm">
                                            View Tasks
                                        </button>
                                        <button className="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition font-medium text-sm">
                                            Download Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
