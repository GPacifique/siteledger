<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f5f7fa; color: #333; }
        .container { max-width: 1000px; margin: 0 auto; padding: 2rem; }
        .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .brand { font-weight: 600; color: #1f2937; }
        .user-actions { display: flex; gap: 0.5rem; align-items: center; }
        .logout-form button { background: #ef4444; color: white; border: none; padding: 0.4rem 0.7rem; border-radius: 6px; cursor: pointer; font-size: 0.9rem; }
        .logout-form button:hover { background: #dc2626; }
        .header { margin-bottom: 1.5rem; }
        .header h1 { font-size: 1.5rem; color: #1f2937; }
        .header p { color: #6b7280; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 1rem; }
        .card .label { font-size: 0.85rem; color: #6b7280; }
        .card .value { margin-top: 0.5rem; font-size: 1.75rem; font-weight: 700; color: #111827; }
        .actions a { display: inline-block; margin-right: 0.5rem; margin-top: 0.5rem; padding: 0.5rem 0.75rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; }
        .btn-primary { background: #4f46e5; color: white; }
        .btn-secondary { background: #111827; color: white; }
        .list { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .list-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem; border-bottom: 1px solid #e5e7eb; }
        .list-header h2 { font-size: 1rem; color: #1f2937; }
        .list-header a { font-size: 0.9rem; color: #4f46e5; text-decoration: none; }
        .list-content { padding: 1rem; }
        .list-item { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb; }
        .list-item:last-child { border-bottom: none; }
        .item-title { font-size: 0.95rem; color: #111827; }
        .item-meta { font-size: 0.8rem; color: #6b7280; }
        .item-actions a { font-size: 0.85rem; color: #4f46e5; text-decoration: none; margin-left: 0.5rem; }

        /* Informational styles */
        .hero { background: #fff; border-radius: 10px; padding: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 1rem; }
        .hero h1 { font-size: 1.25rem; color: #111827; }
        .hero p { color: #6b7280; margin-top: 0.25rem; }
        .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.75rem; margin-top: 0.75rem; }
        .step { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.75rem; }
        .step-title { font-weight: 600; color: #1f2937; }
        .step-desc { font-size: 0.9rem; color: #6b7280; margin-top: 0.25rem; }
        .alert { border: 1px solid #fde68a; background: #fffbeb; color: #92400e; border-radius: 8px; padding: 0.75rem; }

        /* Mobile tweaks */
        @media (max-width: 640px) {
            .container { padding: 1rem; }
            .topbar { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .user-actions { width: 100%; justify-content: space-between; }
            .grid { grid-template-columns: 1fr; }
            .actions a { display: block; width: 100%; margin-right: 0; }
            .list-item { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .item-actions { margin-top: 0.25rem; }
            /* Join tenant form stacks */
            .card form { flex-direction: column; align-items: stretch !important; }
            .card form select, .card form .btn-primary { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div class="brand" style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">S</div>
                <div>
                    <div style="font-weight: 600; color: #1f2937; font-size: 1.1rem;">SiteLedger</div>
                    <div style="font-size: 0.8rem; color: #6b7280;">Personal Dashboard</div>
                </div>
            </div>
            <div class="user-actions">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="text-align: right;">
                        <div style="font-size:0.9rem; font-weight: 500; color:#1f2937;">{{ auth()->user()->name ?? 'User' }}</div>
                        <div style="font-size:0.75rem; color:#6b7280;">
                            @if(auth()->user()->tenants()->exists())
                                {{ auth()->user()->currentTenant()->name ?? 'Company Member' }}
                            @else
                                Pending Access
                            @endif
                        </div>
                    </div>
                    <form class="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; gap: 0.3rem;">🚪 Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="hero">
            <h1>Welcome to SiteLedger, {{ auth()->user()->name ?? 'User' }}! 👋</h1>
            <p>Thank you for joining SiteLedger. Your account has been created successfully.</p>
        </div>

        @php($hasTenant = auth()->user()->tenants()->exists())
        @if(!$hasTenant)
            <div class="card" style="margin-bottom:1rem; border-left: 4px solid #3b82f6; text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">⏳</div>
                <h2 style="color: #1f2937; font-size: 1.5rem; margin-bottom: 1rem;">Account Pending Approval</h2>
                <p style="color: #6b7280; font-size: 1.1rem; line-height: 1.6; margin-bottom: 1.5rem;">
                    Your account is currently pending approval from your company administrator.
                    Once approved, you'll have full access to your company's projects, data, and features.
                </p>

                <div style="background: #eff6ff; border: 1px solid #3b82f6; border-radius: 8px; padding: 1.5rem; margin-top: 2rem; text-align: left;">
                    <h3 style="color: #1d4ed8; margin: 0 0 1rem 0; font-size: 1.1rem;">📋 What happens next?</h3>
                    <ul style="color: #1e40af; margin: 0; padding-left: 1.5rem; line-height: 1.8;">
                        <li>Your company administrator will review and approve your account</li>
                        <li>You'll receive an email notification once approved</li>
                        <li>After approval, you can access all company projects and data</li>
                        <li>If you're waiting longer than expected, contact your administrator</li>
                    </ul>
                </div>

                <div style="background: #f0fdf4; border: 1px solid #10b981; border-radius: 8px; padding: 1.5rem; margin-top: 1.5rem; text-align: left;">
                    <h3 style="color: #059669; margin: 0 0 1rem 0; font-size: 1.1rem;">💡 In the meantime...</h3>
                    <p style="color: #047857; margin: 0; line-height: 1.6;">
                        Feel free to bookmark this page and check back later. Your account is secure and ready -
                        we're just waiting for the final approval step!
                    </p>
                </div>
            </div>
        @else
            <!-- User has been approved and has tenant access -->
            <div class="card" style="text-align: center; padding: 2rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🎉</div>
                <h2 style="color: white; font-size: 1.5rem; margin-bottom: 1rem;">Account Approved!</h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem; margin-bottom: 2rem;">
                    Congratulations! Your account has been approved. You now have access to your company's workspace.
                </p>
                <div class="actions">
                    <a href="{{ route('projects.index') }}" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; margin: 0.5rem;">📊 View Projects</a>
                    <a href="{{ route('dashboard') }}" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; margin: 0.5rem;">🏠 Go to Dashboard</a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
