import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';

export default function Placeholder({ page }) {
    return (
        <AdminLayout title={page}>
            <div className="bg-white rounded-lg shadow p-8 text-center">
                <h1 className="text-4xl font-bold text-gray-800 mb-4">🚧 {page} Page 🚧</h1>
                <p className="text-gray-600">This page is under construction. Please check back later!</p>
            </div>
        </AdminLayout>
    );
}
