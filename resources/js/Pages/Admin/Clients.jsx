import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link } from '@inertiajs/react';

export default function Clients({ clients = [], filterContext = {} }) {
    return (
        <AdminLayout>
            <Head title="Clients Management" />
            <div className="p-8">
                <h1 className="text-4xl font-bold mb-8 text-gray-800">Clients Management</h1>

                <div className="bg-white rounded-lg shadow p-6">
                    <h2 className="text-xl font-bold mb-4 text-gray-800">Clients List</h2>
                    {clients && clients.length > 0 ? (
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b bg-gray-50">
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Contact Person</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {clients.map((client) => (
                                        <tr key={client.id} className="border-b hover:bg-blue-50 cursor-pointer transition">
                                            <td className="py-3 px-4 text-gray-800">
                                                <Link href={route('admin.clients.show', client.id)} className="hover:text-blue-600 font-medium">
                                                    {client.name}
                                                </Link>
                                            </td>
                                            <td className="py-3 px-4 text-gray-600">{client.contact_person}</td>
                                            <td className="py-3 px-4 text-gray-600">{client.email}</td>
                                            <td className="py-3 px-4 text-gray-600">{client.phone}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <p className="text-gray-600">No clients found</p>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
