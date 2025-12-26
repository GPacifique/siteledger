import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link } from '@inertiajs/react';
import { CurrencyFormatter } from '@/Utils/CurrencyFormatter';

export default function Tasks({ tasks = [], filterContext = {} }) {
    const getStatusColor = (status) => {
        const colors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'in_progress': 'bg-blue-100 text-blue-800',
            'completed': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800',
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    };

    const getPriorityColor = (priority) => {
        const colors = {
            'low': 'bg-gray-100 text-gray-800',
            'medium': 'bg-blue-100 text-blue-800',
            'high': 'bg-yellow-100 text-yellow-800',
            'urgent': 'bg-red-100 text-red-800',
        };
        return colors[priority] || 'bg-gray-100 text-gray-800';
    };

    return (
        <AdminLayout>
            <Head title="Tasks Management" />
            <div className="p-8">
                <h1 className="text-4xl font-bold mb-8 text-gray-800">Tasks Management</h1>

                <div className="bg-white rounded-lg shadow p-6">
                    <h2 className="text-xl font-bold mb-4 text-gray-800">Tasks List</h2>
                    {tasks && tasks.length > 0 ? (
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b bg-gray-50">
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Title</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Project</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Priority</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Due Date</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Assigned To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {tasks.map((task) => (
                                        <tr key={task.id} className="border-b hover:bg-blue-50 cursor-pointer transition">
                                            <td className="py-3 px-4 text-gray-800">
                                                <Link href={route('admin.tasks.show', task.id)} className="hover:text-blue-600 font-medium">
                                                    {task.title}
                                                </Link>
                                            </td>
                                            <td className="py-3 px-4 text-gray-600">{task.project?.name || 'N/A'}</td>
                                            <td className="py-3 px-4">
                                                <span className={`px-3 py-1 rounded-full text-sm ${getStatusColor(task.status)}`}>
                                                    {task.status?.replace('_', ' ') || 'N/A'}
                                                </span>
                                            </td>
                                            <td className="py-3 px-4">
                                                <span className={`px-3 py-1 rounded-full text-sm ${getPriorityColor(task.priority)}`}>
                                                    {task.priority || 'N/A'}
                                                </span>
                                            </td>
                                            <td className="py-3 px-4 text-gray-600">{task.due_date || 'N/A'}</td>
                                            <td className="py-3 px-4 text-gray-600">{task.assigned_to?.name || 'N/A'}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <p className="text-gray-600">No tasks found</p>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
