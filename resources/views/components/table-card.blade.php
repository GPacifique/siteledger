{{--
    Table Card Component
    Usage: @include('components.table-card', ['title' => 'Recent Projects', 'subtitle' => 'Latest activity', 'data' => $projects])
--}}

@php
    $title = $title ?? 'Table Title';
    $subtitle = $subtitle ?? null;
    $viewAllLink = $viewAllLink ?? null;
    $columns = $columns ?? [];
    $data = $data ?? [];
    $emptyIcon = $emptyIcon ?? '📊';
    $emptyTitle = $emptyTitle ?? 'No Data';
    $emptyMessage = $emptyMessage ?? 'No records found.';
    $emptyAction = $emptyAction ?? null;
@endphp

<div class="card animate-fade-in-up">
    <div class="card-header">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold">{{ $title }}</h3>
                @if($subtitle)
                    <p class="text-sm text-secondary">{{ $subtitle }}</p>
                @endif
            </div>
            @if($viewAllLink)
                <a href="{{ $viewAllLink }}" class="btn btn-outline btn-sm">View All</a>
            @endif
        </div>
    </div>
    <div class="table-wrapper">
        @if(count($data) > 0)
            <table class="table">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th class="{{ $column['class'] ?? '' }}">{{ $column['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">{{ $emptyIcon }}</div>
                <h3 class="empty-state-title">{{ $emptyTitle }}</h3>
                <p class="empty-state-message">{{ $emptyMessage }}</p>
                @if($emptyAction)
                    <a href="{{ $emptyAction['link'] }}" class="btn btn-primary">{{ $emptyAction['label'] }}</a>
                @endif
            </div>
        @endif
    </div>
</div>
