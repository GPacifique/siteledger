import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function StoreKeeperDashboard({ stats }) {
    const inventoryStats = [
        { label: 'Total Products', value: stats?.totalProducts || 0, icon: '📦' },
        { label: 'Low Stock Items', value: stats?.lowStockItems || 0, icon: '⚠️' },
        { label: 'Recent Orders', value: stats?.recentOrders || 0, icon: '🛒' },
        { label: 'Pending Deliveries', value: stats?.pendingDeliveries || 0, icon: '🚚' }
    ];

    const menuItems = [
        { name: 'Dashboard', icon: '📦', href: '#' },
        { name: 'Inventory', icon: '📊', href: '#' },
        { name: 'Orders', icon: '📝', href: '#' },
        { name: 'Suppliers', icon: '🤝', href: '#' },
        { name: 'Stock Transfer', icon: '↔️', href: '#' },
        { name: 'Reports', icon: '📈', href: '#' }
    ];

    return (
        <AuthenticatedLayout>
            <Head title="Store Keeper Dashboard" />
            <div className="flex h-screen bg-gray-100">
                {/* Sidebar */}
                <div className="w-64 bg-orange-900 text-white shadow-lg">
                    <div className="p-6 border-b border-orange-700">
                        <h2 className="text-2xl font-bold">SiteLedger</h2>
                        <p className="text-sm text-orange-200">Inventory</p>
                    </div>
                    <nav className="p-4">
                        {menuItems.map((item) => (
                            <a key={item.name} href={item.href} className="flex items-center px-4 py-3 mb-2 rounded hover:bg-orange-800 transition">
                                <span className="mr-3 text-lg">{item.icon}</span>
                                <span>{item.name}</span>
                            </a>
                        ))}
                    </nav>
                </div>

                {/* Main Content */}
                <div className="flex-1 overflow-auto">
                    <div className="p-8">
                        <h1 className="text-4xl font-bold mb-8 text-gray-800">Store Keeper Dashboard</h1>

                        {/* Inventory Stats */}
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            {inventoryStats.map((stat) => (
                                <div key={stat.label} className="bg-white rounded-lg shadow p-6">
                                    <div className="text-3xl mb-2">{stat.icon}</div>
                                    <p className="text-gray-500 text-sm">{stat.label}</p>
                                    <p className="text-2xl font-bold text-gray-800">{stat.value}</p>
                                </div>
                            ))}
                        </div>

                        {/* Inventory Management */}
                        <div className="grid grid-cols-2 gap-6">
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-xl font-bold mb-4 text-gray-800">Low Stock Alert</h2>
                                <p className="text-gray-600">Items requiring reorder</p>
                            </div>
                            <div className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-xl font-bold mb-4 text-gray-800">Pending Orders</h2>
                                <p className="text-gray-600">Orders awaiting confirmation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
