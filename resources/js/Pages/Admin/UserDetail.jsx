import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function UserDetail({ user = {}, filterContext = {} }) {
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
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title={`User: ${user.name}`} />
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
                                <h1 className="text-4xl font-bold text-gray-800">User Profile</h1>
                                <p className="text-gray-600 mt-2">ID: {user.id}</p>
                            </div>
                            <Link href={route('admin.users')} className="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                                ← Back to Users
                            </Link>
                        </div>

                        {/* User Card */}
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            {/* Main Info */}
                            <div className="lg:col-span-2">
                                <div className="bg-white rounded-lg shadow p-8">
                                    <div className="flex items-center mb-6">
                                        <div className="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                            {user.name?.charAt(0) || 'U'}
                                        </div>
                                        <div className="ml-6">
                                            <h2 className="text-3xl font-bold text-gray-800">{user.name}</h2>
                                            <p className="text-gray-600">{user.email}</p>
                                        </div>
                                    </div>

                                    {/* Details Grid */}
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 pt-8 border-t">
                                        <div>
                                            <label className="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                            <p className="text-gray-800">{user.email}</p>
                                        </div>
                                        <div>
                                            <label className="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                                            <p className="text-gray-800">{user.phone || 'Not provided'}</p>
                                        </div>
                                        <div>
                                            <label className="block text-sm font-semibold text-gray-700 mb-2">Email Status</label>
                                            <span className={`inline-block px-4 py-2 rounded-full text-sm font-medium ${
                                                user.email_verified_at
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-yellow-100 text-yellow-800'
                                            }`}>
                                                {user.email_verified_at ? '✓ Verified' : '⏳ Pending'}
                                            </span>
                                        </div>
                                        <div>
                                            <label className="block text-sm font-semibold text-gray-700 mb-2">Verified On</label>
                                            <p className="text-gray-800">{user.email_verified_at ? formatDate(user.email_verified_at) : 'Not verified'}</p>
                                        </div>
                                    </div>
                                </div>

                                {/* Roles Section */}
                                <div className="bg-white rounded-lg shadow p-8 mt-8">
                                    <h3 className="text-2xl font-bold text-gray-800 mb-6">Assigned Roles</h3>
                                    {user.roles && user.roles.length > 0 ? (
                                        <div className="flex flex-wrap gap-3">
                                            {user.roles.map((role, idx) => (
                                                <span key={idx} className="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-medium">
                                                    {role}
                                                </span>
                                            ))}
                                        </div>
                                    ) : (
                                        <p className="text-gray-600">No roles assigned</p>
                                    )}
                                </div>
                            </div>

                            {/* Sidebar Info */}
                            <div>
                                <div className="bg-white rounded-lg shadow p-6">
                                    <h3 className="text-lg font-bold text-gray-800 mb-4">Account Information</h3>
                                    <div className="space-y-4">
                                        <div>
                                            <label className="text-xs font-semibold text-gray-600 uppercase">Created</label>
                                            <p className="text-sm text-gray-800 mt-1">{formatDate(user.created_at)}</p>
                                        </div>
                                        <div className="border-t pt-4">
                                            <label className="text-xs font-semibold text-gray-600 uppercase">Last Updated</label>
                                            <p className="text-sm text-gray-800 mt-1">{formatDate(user.updated_at)}</p>
                                        </div>
                                        <div className="border-t pt-4">
                                            <label className="text-xs font-semibold text-gray-600 uppercase">User ID</label>
                                            <p className="text-sm text-gray-800 mt-1 font-mono">{user.id}</p>
                                        </div>
                                    </div>
                                </div>

                                {/* Actions */}
                                <div className="bg-white rounded-lg shadow p-6 mt-6">
                                    <h3 className="text-lg font-bold text-gray-800 mb-4">Actions</h3>
                                    <div className="space-y-2">
                                        <button className="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition font-medium">
                                            Edit User
                                        </button>
                                        <button className="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition font-medium">
                                            Reset Password
                                        </button>
                                        <button className="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition font-medium">
                                            Deactivate User
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
