import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link } from '@inertiajs/react';

export default function Suppliers({ suppliers = [], filterContext = {} }) {
    return (
        <AdminLayout>
            <Head title="Suppliers Management" />
            <div className="p-8">
                <h1 className="text-4xl font-bold mb-8 text-gray-800">Suppliers Management</h1>

                <div className="bg-white rounded-lg shadow p-6">
                    <h2 className="text-xl font-bold mb-4 text-gray-800">Suppliers List</h2>
                    {suppliers && suppliers.length > 0 ? (
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b bg-gray-50">
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Company</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Phone</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Category</th>
                                        <th className="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {suppliers.map((supplier) => (
                                        <tr key={supplier.id} className="border-b hover:bg-blue-50 cursor-pointer transition">
                                            <td className="py-3 px-4 text-gray-800">
                                                <Link href={route('admin.suppliers.show', supplier.id)} className="hover:text-blue-600 font-medium">
                                                    {supplier.name}
                                                </Link>
                                            </td>
                                            <td className="py-3 px-4 text-gray-600">{supplier.company}</td>
                                            <td className="py-3 px-4 text-gray-600">{supplier.email}</td>
                                            <td className="py-3 px-4 text-gray-600">{supplier.phone}</td>
                                            <td className="py-3 px-4 text-gray-600">{supplier.category}</td>
                                            <td className="py-3 px-4 text-gray-600">{supplier.status}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <p className="text-gray-600">No suppliers found</p>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
