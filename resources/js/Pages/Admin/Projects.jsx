import React from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link } from '@inertiajs/react';
import { CurrencyFormatter } from '@/Utils/CurrencyFormatter';

export default function Projects({ totalProjects, activeProjects, projects = [], filterContext = {} }) {
    const menuItems = [
        { name: 'Dashboard', icon: '📊', route: 'dashboard.admin' },
        { name: 'Users', icon: '👥', route: 'admin.users' },
        { name: 'Projects', icon: '📁', route: 'admin.projects' },
        { name: 'Financial Reports', icon: '📈', route: 'admin.finances' },
        { name: 'System Settings', icon: '⚙️', route: 'admin.settings' },
        { name: 'Activity Logs', icon: '📝', route: 'admin.logs' }
    ];

    const canViewAllProjects = filterContext?.is_admin || filterContext?.is_system_admin || filterContext?.is_accountant;

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

    return (
        <DashboardLayout>
            <Head title="Projects Management" />
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
                        <h1 className="text-4xl font-bold mb-8 text-gray-800">Projects Management</h1>

                        {/* Stats Cards */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div className="bg-white rounded-lg shadow p-6">
                                <div className="text-3xl mb-2">📁</div>
                                <p className="text-gray-500 text-sm">Total Projects</p>
                                <p className="text-2xl font-bold text-gray-800">{totalProjects}</p>
                            </div>
                            <div className="bg-white rounded-lg shadow p-6">
                                <div className="text-3xl mb-2">🚀</div>
                                <p className="text-gray-500 text-sm">Active Projects</p>
                                <p className="text-2xl font-bold text-gray-800">{activeProjects}</p>
                            </div>
                        </div>

                        {/* Projects List */}
                        <div className="bg-white rounded-lg shadow p-6">
                            <h2 className="text-xl font-bold mb-4 text-gray-800">Projects List</h2>
                            {projects && projects.length > 0 ? (
                                <div className="overflow-x-auto">
                                    <table className="w-full">
                                        <thead>
                                            <tr className="border-b bg-gray-50">
                                                <th className="text-left py-3 px-4 font-semibold text-gray-700">Project Name</th>
                                                <th className="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                                <th className="text-left py-3 px-4 font-semibold text-gray-700">Budget</th>
                                                <th className="text-left py-3 px-4 font-semibold text-gray-700">Spent</th>
                                                <th className="text-left py-3 px-4 font-semibold text-gray-700">Progress</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {projects.map((project) => (
                                                <tr key={project.id} className="border-b hover:bg-blue-50 cursor-pointer transition">
                                                    <td className="py-3 px-4 text-gray-800">
                                                        <Link href={route('admin.project.detail', project.id)} className="hover:text-blue-600 font-medium">
                                                            {project.name}
                                                        </Link>
                                                    </td>
                                                    <td className="py-3 px-4">
                                                        <span className={`px-3 py-1 rounded-full text-sm ${getStatusColor(project.status)}`}>
                                                            {project.status?.replace('_', ' ') || 'N/A'}
                                                        </span>
                                                    </td>
                                                    <td className="py-3 px-4 text-gray-600">
                                                        {CurrencyFormatter.format(project.budget || 0)}
                                                    </td>
                                                    <td className="py-3 px-4 text-gray-600">
                                                        {CurrencyFormatter.format(project.spent || 0)}
                                                    </td>
                                                    <td className="py-3 px-4">
                                                        <div className="w-24 bg-gray-200 rounded-full h-2">
                                                            <div
                                                                className="bg-blue-600 h-2 rounded-full"
                                                                style={{width: `${project.completion_percentage || 0}%`}}
                                                            ></div>
                                                        </div>
                                                        <span className="text-xs text-gray-600">{project.completion_percentage || 0}%</span>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <p className="text-gray-600">No projects found</p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
