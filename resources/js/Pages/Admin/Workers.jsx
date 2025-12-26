import React from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link } from '@inertiajs/react';
import { CurrencyFormatter } from '@/Utils/CurrencyFormatter';

export default function Workers({ workers = [], filterContext = {} }) {
    return (
        <DashboardLayout>
            <Head title="Workers Management" />
            <div className="p-8">
                <h1 className="text-4xl font-bold mb-8 text-gray-800">Workers Management</h1>

                <div className="bg-white rounded-lg shadow p-6">
                    <h2 className="text-xl font-bold mb-4 text-gray-800">Workers List</h2>
                    {workers && workers.length > 0 ? (
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b bg-gray-50">
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Position</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Phone</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Payment Type</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Daily Rate</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {workers.map((worker) => (
                                        <tr key={worker.id} className="border-b hover:bg-blue-50 cursor-pointer transition" onClick={() => window.location = route('admin.workers.show', worker.id)}>
                                            <td className="py-3 px-4 text-gray-800">
                                                <Link href={route('admin.workers.show', worker.id)} className="hover:text-blue-600 font-medium">
                                                    {worker.first_name} {worker.last_name}
                                                </Link>
                                            </td>
                                            <td className="py-3 px-4 text-gray-600">{worker.position}</td>
                                            <td className="py-3 px-4 text-gray-600">{worker.email}</td>
                                            <td className="py-3 px-4 text-gray-600">{worker.phone}</td>
                                            <td className="py-3 px-4 text-gray-600">{worker.status}</td>
                                            <td className="py-3 px-4 text-gray-600">{worker.payment_type || 'N/A'}</td>
                                            <td className="py-3 px-4 text-gray-600">{worker.daily_rate ? CurrencyFormatter.format(worker.daily_rate) : 'N/A'}</td>
                                            <td className="py-3 px-4 text-gray-600">{worker.attendance_days || 0}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <p className="text-gray-600">No workers found</p>
                    )}
                </div>
            </div>
        </DashboardLayout>
    );
}
