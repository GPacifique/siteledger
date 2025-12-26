import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link } from '@inertiajs/react';

export default function Users({ totalUsers, activeUsers, users = [], filterContext = {} }) {
    // Show user list only if admin or system admin
    const canViewAllUsers = filterContext?.is_admin || filterContext?.is_system_admin;

    return (
        <AdminLayout title="Users Management">
            <div className="w-full">
                {/* Access Control Info */}
                <div className="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                    <p className="text-blue-800">
                        <strong>Access Level:</strong> {canViewAllUsers ? 'Full Access (All Users)' : 'Limited Access (Only Your Profile)'}
                    </p>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="text-3xl mb-2">👥</div>
                        <p className="text-gray-500 text-sm">Total Users</p>
                        <p className="text-2xl font-bold text-gray-800">{totalUsers}</p>
                    </div>
                    <div className="bg-white rounded-lg shadow p-6">
                        <div className="text-3xl mb-2">✅</div>
                        <p className="text-gray-500 text-sm">Active Users</p>
                        <p className="text-2xl font-bold text-gray-800">{activeUsers}</p>
                    </div>
                </div>

                {/* Users List */}
                <div className="bg-white rounded-lg shadow p-6">
                    <h2 className="text-xl font-bold mb-4 text-gray-800">
                        {canViewAllUsers ? 'All Users' : 'Your Profile'}
                    </h2>
                    {users && users.length > 0 ? (
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b bg-gray-50">
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Roles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {users.map((user) => (
                                        <tr key={user.id} className="border-b hover:bg-blue-50 cursor-pointer transition" onClick={() => window.location.href=route('admin.user.detail', user.id)}>
                                            <td className="py-3 px-4 text-gray-800">
                                                <Link href={route('admin.user.detail', user.id)} className="hover:text-blue-600 font-medium">
                                                    {user.name}
                                                </Link>
                                            </td>
                                            <td className="py-3 px-4 text-gray-600">{user.email}</td>
                                            <td className="py-3 px-4">
                                                <span className={`px-3 py-1 rounded-full text-sm ${user.email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}`}>
                                                    {user.email_verified_at ? '✓ Verified' : '⏳ Pending'}
                                                </span>
                                            </td>
                                            <td className="py-3 px-4 text-gray-600">
                                                {user.roles && user.roles.length > 0
                                                    ? user.roles.map(r => r.name).join(', ')
                                                    : 'No roles'}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <p className="text-gray-600">No users found</p>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
