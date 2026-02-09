@extends('layouts.admin')

@section('title', 'Projects - SiteLedger')

@section('styles')
@endsection

@section('content')
        <div class="container" style="max-width: 1400px;">
            <!-- Professional Header Section -->
            <div class="card-colorful" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%); margin-bottom: 2rem; backdrop-filter: blur(20px);">
                <div class="card-body" style="padding: 3rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                        <div>
                            <h1 style="font-size: 3rem; font-weight: 800; color: var(--primary); margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <span class="icon-bounce">🏗️</span> Construction Projects
                            </h1>
                            <p style="font-size: 1.25rem; color: var(--gray-600); margin: 0.5rem 0 0 0; font-weight: 500;">Professional project management & tracking</p>
                            <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1rem;">
                                <span class="badge-colorful badge-success" style="font-size: 0.9rem;">{{ $projects->count() }} Total Projects</span>
                                <span class="badge-colorful badge-ocean" style="font-size: 0.9rem;">{{ $projects->where('status', 'active')->count() }} Active</span>
                                <span class="badge-colorful badge-purple" style="font-size: 0.9rem;">RWF {{ number_format($projects->sum('contract_value'), 0) }} Total Value</span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <a href="{{ route('projects.create') }}" class="btn-rainbow" style="padding: 1rem 2rem; font-size: 1.1rem; font-weight: 600;">
                                <span class="icon-pulse">✨</span> New Project
                            </a>
                        </div>
                    </div>

                    <!-- Advanced Filter Bar -->
                    <div style="background: var(--white); border-radius: var(--radius-xl); padding: 2rem; box-shadow: var(--shadow-lg); border: 1px solid var(--gray-200);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gray-800); font-weight: 600; margin: 0;">
                                <span class="icon-pulse">🔍</span> Project Filters & Search
                            </h3>
                            <button class="btn-ocean" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Reset Filters</button>
                        </div>
                        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 1.5rem; align-items: end;">
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700); font-size: 0.9rem;">Search Projects</label>
                                <div style="position: relative;">
                                    <input type="text" placeholder="Search by name, client, or manager..." class="form-control-colorful" style="padding-left: 3rem; font-size: 1rem;">
                                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-500); font-size: 1.2rem;">🔍</span>
                                </div>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700); font-size: 0.9rem;">Status</label>
                                <select class="form-control-colorful">
                                    <option>All Statuses</option>
                                    <option>Active</option>
                                    <option>Planning</option>
                                    <option>Completed</option>
                                    <option>On Hold</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700); font-size: 0.9rem;">Client</label>
                                <select class="form-control-colorful">
                                    <option>All Clients</option>
                                    @foreach($projects->pluck('client.name')->filter()->unique() as $client)
                                        <option>{{ $client }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700); font-size: 0.9rem;">Sort By</label>
                                <select class="form-control-colorful">
                                    <option>Latest First</option>
                                    <option>Contract Value</option>
                                    <option>Alphabetical</option>
                                    <option>Progress</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Stats Dashboard -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
                <div class="stat-card-enhanced ocean">
                    <div class="stat-icon" style="background: var(--gradient-ocean);">
                        <span class="icon-pulse">🏗️</span>
                    </div>
                    <div class="stat-card-label">Total Projects</div>
                    <div class="stat-card-value">{{ $projects->count() }}</div>
                    <div class="stat-card-trend positive">
                        <span>⬆️</span> 12% from last month
                    </div>
                    <div class="progress-bar-colorful" style="margin-top: 1rem;">
                        <div class="progress-fill ocean" style="width: 85%;"></div>
                    </div>
                </div>

                <div class="stat-card-enhanced sunset">
                    <div class="stat-icon" style="background: var(--gradient-sunset);">
                        <span class="icon-pulse">✅</span>
                    </div>
                    <div class="stat-card-label">Active Projects</div>
                    <div class="stat-card-value">{{ $projects->where('status', 'active')->count() }}</div>
                    <div class="stat-card-trend positive">
                        <span>🔥</span> 5 new this week
                    </div>
                    <div class="progress-bar-colorful" style="margin-top: 1rem;">
                        <div class="progress-fill sunset" style="width: 92%;"></div>
                    </div>
                </div>

                <div class="stat-card-enhanced purple">
                    <div class="stat-icon" style="background: var(--gradient-purple);">
                        <span class="icon-pulse">💰</span>
                    </div>
                    <div class="stat-card-label">Total Contract Value</div>
                    <div class="stat-card-value">RWF {{ number_format($projects->sum('contract_value'), 0) }}</div>
                    <div class="stat-card-trend positive">
                        <span>📈</span> Portfolio growth
                    </div>
                    <div class="progress-bar-colorful" style="margin-top: 1rem;">
                        <div class="progress-fill purple" style="width: 78%;"></div>
                    </div>
                </div>

                <div class="stat-card-enhanced">
                    <div class="stat-icon" style="background: var(--gradient-green);">
                        <span class="icon-pulse">📊</span>
                    </div>
                    <div class="stat-card-label">Completed Projects</div>
                    <div class="stat-card-value">{{ $projects->where('status', 'completed')->count() }}</div>
                    <div class="stat-card-trend positive">
                        <span>✨</span> Success rate 94%
                    </div>
                    <div class="progress-bar-colorful" style="margin-top: 1rem;">
                        <div class="progress-fill" style="width: 94%;"></div>
                    </div>
                </div>
            </div>

            <!-- Professional Projects Table -->
            <div class="card-colorful" style="background: var(--white); border: 1px solid var(--gray-200); box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div style="background: var(--gradient-primary); color: white; padding: 2rem; border-radius: var(--radius-xl) var(--radius-xl) 0 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0; color: white;">
                                <span class="icon-bounce">📋</span> Project Portfolio
                            </h2>
                            <p style="margin: 0.5rem 0 0 0; color: rgba(255,255,255,0.9); font-weight: 500;">Comprehensive overview of all construction projects</p>
                        </div>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <button class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 0.5rem 1rem; font-size: 0.9rem;">
                                <span>📏</span> Export
                            </button>
                            <button class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 0.5rem 1rem; font-size: 0.9rem;">
                                <span>🗒️</span> Print
                            </button>
                        </div>
                    </div>
                </div>
                <div style="padding: 0;">
                    @if($projects->count() > 0)
                        <div class="table-responsive" style="border-radius: 0 0 var(--radius-xl) var(--radius-xl); overflow: hidden;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background: var(--gray-50);">
                                    <tr>
                                        <th style="padding: 1.5rem 2rem; text-align: left; font-weight: 700; color: var(--gray-800); border-bottom: 2px solid var(--gray-200); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <span class="icon-pulse">🏗️</span> Project Details
                                            </div>
                                        </th>
                                        <th style="padding: 1.5rem 1rem; text-align: left; font-weight: 700; color: var(--gray-800); border-bottom: 2px solid var(--gray-200); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <span class="icon-pulse">👥</span> Client
                                        </th>
                                        <th style="padding: 1.5rem 1rem; text-align: left; font-weight: 700; color: var(--gray-800); border-bottom: 2px solid var(--gray-200); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <span class="icon-pulse">🚀</span> Progress
                                        </th>
                                        <th style="padding: 1.5rem 1rem; text-align: center; font-weight: 700; color: var(--gray-800); border-bottom: 2px solid var(--gray-200); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <span class="icon-pulse">📈</span> Status
                                        </th>
                                        <th style="padding: 1.5rem 1rem; text-align: right; font-weight: 700; color: var(--gray-800); border-bottom: 2px solid var(--gray-200); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <span class="icon-pulse">💰</span> Contract Value
                                        </th>
                                        <th style="padding: 1.5rem 1rem; text-align: right; font-weight: 700; color: var(--gray-800); border-bottom: 2px solid var(--gray-200); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <span class="icon-pulse">✨</span> Profit
                                        </th>
                                        <th style="padding: 1.5rem 2rem; text-align: center; font-weight: 700; color: var(--gray-800); border-bottom: 2px solid var(--gray-200); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <span class="icon-pulse">⚙️</span> Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($projects as $project)
                                    @php
                                        $totalSpent = ($project->total_expenses ?? 0) + ($project->total_payments ?? 0);
                                        $profit = $project->profit ?? (($project->contract_value ?? 0) - $totalSpent);
                                        $progress = $project->contract_value > 0 ? round(($project->amount_paid / $project->contract_value) * 100, 1) : 0;

                                        $statusConfig = match($project->status ?? 'planning') {
                                            'active' => ['class' => 'badge-success', 'icon' => '🚀', 'color' => 'var(--success)'],
                                            'completed' => ['class' => 'badge-ocean', 'icon' => '✅', 'color' => 'var(--info)'],
                                            'on-hold' => ['class' => 'badge-sunset', 'icon' => '⏸️', 'color' => 'var(--warning)'],
                                            'planning' => ['class' => 'badge-purple', 'icon' => '📅', 'color' => 'var(--purple)'],
                                            default => ['class' => 'badge-colorful', 'icon' => '❔', 'color' => 'var(--gray-500)']
                                        };
                                    @endphp
                                    <tr style="cursor: pointer; transition: all 0.3s ease; border-bottom: 1px solid var(--gray-100);"
                                        onclick="location.href='{{ route('projects.show', $project->id) }}'"
                                        onmouseover="this.style.backgroundColor='var(--primary-50)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                                        onmouseout="this.style.backgroundColor=''; this.style.transform=''; this.style.boxShadow=''">

                                        <!-- Project Details Column -->
                                        <td style="padding: 2rem; border-bottom: 1px solid var(--gray-100);">
                                            <div style="display: flex; align-items: center; gap: 1rem;">
                                                <div style="width: 48px; height: 48px; background: var(--gradient-primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.2rem; box-shadow: var(--shadow-md);">
                                                    {{ strtoupper(substr($project->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div style="font-weight: 700; font-size: 1.1rem; color: var(--gray-900); margin-bottom: 0.25rem;">{{ $project->name }}</div>
                                                    <div style="font-size: 0.85rem; color: var(--gray-500); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">{{ $project->project_code ?? 'PRJ-' . str_pad($project->id, 4, '0', STR_PAD_LEFT) }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Client Column -->
                                        <td style="padding: 2rem 1rem; border-bottom: 1px solid var(--gray-100);">
                                            <div style="font-weight: 600; color: var(--gray-800); margin-bottom: 0.25rem;">{{ $project->client->name ?? 'No Client' }}</div>
                                            <div style="font-size: 0.85rem; color: var(--gray-500);">{{ $project->client->contact_person ?? 'Unassigned' }}</div>
                                            @if($project->manager)
                                                <div style="margin-top: 0.5rem; padding: 0.25rem 0.5rem; background: var(--gray-100); border-radius: var(--radius-sm); font-size: 0.8rem; color: var(--gray-700); display: inline-block;">
                                                    👨‍💼 {{ $project->manager->first_name }} {{ $project->manager->last_name }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Progress Column -->
                                        <td style="padding: 2rem 1rem; border-bottom: 1px solid var(--gray-100);">
                                            <div style="margin-bottom: 0.75rem;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--gray-700);">Progress</span>
                                                    <span style="font-size: 0.85rem; font-weight: 700; color: {{ $progress > 70 ? 'var(--success)' : ($progress > 40 ? 'var(--warning)' : 'var(--error)') }};">{{ $progress }}%</span>
                                                </div>
                                                <div class="progress-bar-colorful" style="height: 8px;">
                                                    <div class="progress-fill {{ $progress > 70 ? '' : ($progress > 40 ? 'sunset' : 'purple') }}" style="width: {{ $progress }}%;"></div>
                                                </div>
                                            </div>
                                            <div style="font-size: 0.8rem; color: var(--gray-500); display: flex; justify-content: space-between;">
                                                <span>💵 Paid: RWF {{ number_format($project->amount_paid ?? 0, 0) }}</span>
                                            </div>
                                        </td>

                                        <!-- Status Column -->
                                        <td style="padding: 2rem 1rem; text-align: center; border-bottom: 1px solid var(--gray-100);">
                                            <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; border-radius: var(--radius-full); background: {{ str_contains($statusConfig['class'], 'success') ? 'var(--success-light)' : (str_contains($statusConfig['class'], 'ocean') ? 'var(--info-light)' : (str_contains($statusConfig['class'], 'sunset') ? 'var(--warning-light)' : 'var(--purple-light)')) }}; border: 2px solid {{ $statusConfig['color'] }}; font-weight: 600; font-size: 0.9rem; box-shadow: var(--shadow-sm);">
                                                <span style="font-size: 1.1rem;">{{ $statusConfig['icon'] }}</span>
                                                <span style="color: {{ $statusConfig['color'] }}; text-transform: uppercase; letter-spacing: 0.5px;">{{ ucfirst($project->status ?? 'planning') }}</span>
                                            </div>
                                        </td>

                                        <!-- Contract Value Column -->
                                        <td style="padding: 2rem 1rem; text-align: right; border-bottom: 1px solid var(--gray-100);">
                                            <div style="font-size: 1.1rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.25rem;">
                                                RWF {{ number_format($project->contract_value ?? 0, 0) }}
                                            </div>
                                            <div style="font-size: 0.8rem; color: var(--gray-500);">Contract Total</div>
                                        </td>

                                        <!-- Profit Column -->
                                        <td style="padding: 2rem 1rem; text-align: right; border-bottom: 1px solid var(--gray-100);">
                                            @php
                                                $profitPercentage = $project->contract_value > 0 ? round(($profit / $project->contract_value) * 100, 1) : 0;
                                            @endphp
                                            <div style="font-size: 1.1rem; font-weight: 700; color: {{ $profit >= 0 ? 'var(--success)' : 'var(--error)' }}; margin-bottom: 0.25rem;">
                                                {{ $profit >= 0 ? '+' : '' }}RWF {{ number_format($profit, 0) }}
                                            </div>
                                            <div style="font-size: 0.8rem; color: {{ $profitPercentage >= 0 ? 'var(--success-dark)' : 'var(--error-dark)' }};">
                                                {{ $profitPercentage >= 0 ? '+' : '' }}{{ $profitPercentage }}% Margin
                                            </div>
                                        </td>

                                        <!-- Actions Column -->
                                        <td style="padding: 2rem; text-align: center; border-bottom: 1px solid var(--gray-100);" onclick="event.stopPropagation();">
                                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                                <a href="{{ route('projects.show', $project->id) }}" class="btn-ocean" style="padding: 0.5rem; border-radius: var(--radius-md); font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px;" title="View Project">
                                                    👁️
                                                </a>
                                                <a href="{{ route('projects.edit', $project->id) }}" class="btn-sunset" style="padding: 0.5rem; border-radius: var(--radius-md); font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px;" title="Edit Project">
                                                    ✏️
                                                </a>
                                                <a href="{{ route('projects.tasks.index', $project->id) }}" class="btn-purple" style="padding: 0.5rem; border-radius: var(--radius-md); font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px;" title="View Tasks">
                                                    📋
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">🏗️</div>
                            <h3 class="empty-state-title">No Projects Yet</h3>
                            <p class="empty-state-message">Create your first project to start tracking construction activities and financials.</p>
                            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                                <span>➕</span>
                                <span>Create Project</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
