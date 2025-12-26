import React from 'react';
import { Link } from '@inertiajs/react';

export default function DashboardLayout({ children, menuItems }) {
  // Default menu if not provided
  const defaultMenu = [
    { name: 'Dashboard', icon: '📊', route: 'dashboard.admin' },
    { name: 'Users', icon: '👥', route: 'admin.users' },
    { name: 'Projects', icon: '📁', route: 'admin.projects' },
    { name: 'Financial Reports', icon: '📈', route: 'admin.finances' },
    { name: 'System Settings', icon: '⚙️', route: 'admin.settings' },
    { name: 'Activity Logs', icon: '📝', route: 'admin.logs' }
  ];
  const items = menuItems || defaultMenu;

  return (
    <div className="min-h-screen bg-gray-100 flex">
      {/* Sidebar */}
      <aside className="w-64 bg-gray-900 text-white shadow-lg flex flex-col">
        <div className="p-6 border-b border-gray-700 sticky top-0 bg-gray-900">
          <h2 className="text-2xl font-bold">SiteLedger</h2>
          <p className="text-sm text-gray-400">Admin Panel</p>
        </div>
        <nav className="p-4 flex-1">
          {items.map((item) => (
            <Link key={item.name} href={route(item.route)} className="flex items-center px-4 py-3 mb-2 rounded hover:bg-gray-800 transition">
              <span className="mr-3 text-lg">{item.icon}</span>
              <span>{item.name}</span>
            </Link>
          ))}
        </nav>
      </aside>
      {/* Main Content */}
      <div className="flex-1 flex flex-col">
        <header className="bg-white shadow px-6 py-4 flex items-center justify-between">
          <h1 className="text-2xl font-bold text-blue-700">SiteLedger Dashboard</h1>
          {/* Add user menu, notifications, etc. here */}
        </header>
        <main className="flex-1 p-6 overflow-auto">
          {children}
        </main>
        <footer className="bg-white text-center py-2 text-gray-400 text-sm border-t">
          &copy; {new Date().getFullYear()} SiteLedger. All rights reserved.
        </footer>
      </div>
    </div>
  );
}
