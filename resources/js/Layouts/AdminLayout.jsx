import React from 'react';
import { Link, Head, usePage } from '@inertiajs/react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import Dropdown from '@/Components/Dropdown';

export default function AdminLayout({ children, title }) {
    const { props } = usePage();
    const { auth, filterContext } = props;

    const menuItems = [
        { name: 'Dashboard', icon: '📊', route: 'dashboard.admin', group: 'Home' },

        { name: 'Projects', icon: '📁', route: 'admin.projects', group: 'Business' },
        { name: 'Tasks', icon: '✅', route: 'admin.tasks', group: 'Business' },
        { name: 'Clients', icon: '💼', route: 'admin.clients', group: 'Business' },
        { name: 'Workers', icon: '👷', route: 'admin.workers', group: 'Business' },
        { name: 'Suppliers', icon: '🚚', route: 'admin.suppliers', group: 'Business' },

        { name: 'Incomes', icon: '💰', route: 'admin.incomes', group: 'Financial' },
        { name: 'Expenses', icon: '💸', route: 'admin.expenses', group: 'Financial' },
        { name: 'Payments', icon: '💳', route: 'admin.payments', group: 'Financial' },
        { name: 'Transactions', icon: '🧾', route: 'admin.transactions', group: 'Financial' },
        { name: 'Financial Reports', icon: '📈', route: 'admin.finances', group: 'Financial' },

        { name: 'Products', icon: '📦', route: 'admin.products', group: 'Inventory' },
        { name: 'Orders', icon: '🛒', route: 'admin.orders', group: 'Inventory' },

        { name: 'Users', icon: '👥', route: 'admin.users', group: 'System' },
        { name: 'Reports', icon: '📄', route: 'admin.reports', group: 'System' },
        { name: 'Settings', icon: '⚙️', route: 'admin.settings', group: 'System' },
        { name: 'Activity Logs', icon: '📝', route: 'admin.logs', group: 'System' },
    ];

    const groupedMenuItems = menuItems.reduce((acc, item) => {
        if (!acc[item.group]) {
            acc[item.group] = [];
        }
        acc[item.group].push(item);
        return acc;
    }, {});

    return (
        <div className="flex h-screen bg-gray-100">
            <Head title={title} />

            {/* Sidebar */}
            <div className="w-64 bg-gray-900 text-white shadow-lg overflow-y-auto flex-shrink-0">
                <div className="p-6 border-b border-gray-700 sticky top-0 bg-gray-900 z-10">
                    <Link href={route('dashboard.admin')}>
                        <ApplicationLogo className="block h-9 w-auto fill-current text-white" />
                    </Link>
                    <p className="text-sm text-gray-400 mt-2">Admin Panel</p>
                    {filterContext?.roles && (
                        <p className="text-xs text-gray-500 mt-2">Role: {filterContext.roles.join(', ')}</p>
                    )}
                </div>
                <nav className="p-4">
                    {Object.entries(groupedMenuItems).map(([group, items]) => (
                        <div key={group} className="mb-6">
                            <h3 className="px-4 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">{group}</h3>
                            {items.map((item) => (
                                <Link
                                    key={item.name}
                                    href={route(item.route)}
                                    className={`flex items-center px-4 py-2 mb-1 rounded transition-all duration-200 ${route().current(item.route) ? 'bg-blue-600 text-white font-bold' : 'hover:bg-gray-800'}`}
                                >
                                    <span className="mr-3 text-lg">{item.icon}</span>
                                    <span>{item.name}</span>
                                </Link>
                            ))}
                        </div>
                    ))}
                </nav>
            </div>

            {/* Main Content */}
            <div className="flex-1 flex flex-col overflow-hidden">
                <header className="bg-white shadow-sm border-b">
                    <div className="w-full mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center justify-between h-16">
                            <h1 className="text-2xl font-bold text-gray-800">{title}</h1>
                            <div className="flex items-center">
                                <div className="relative">
                                    <Dropdown>
                                        <Dropdown.Trigger>
                                            <span className="inline-flex rounded-md">
                                                <button
                                                    type="button"
                                                    className="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                                >
                                                    {auth.user.name}
                                                    <svg className="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </Dropdown.Trigger>
                                        <Dropdown.Content>
                                            <Dropdown.Link href={route('profile.edit')}>Profile</Dropdown.Link>
                                            <Dropdown.Link href={route('logout')} method="post" as="button">Log Out</Dropdown.Link>
                                        </Dropdown.Content>
                                    </Dropdown>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <main className="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">
                    {children}
                </main>
            </div>
        </div>
    );
}
