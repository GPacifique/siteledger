{{--
    Action Card Component
    Usage: @include('components.action-card', ['icon' => '🏗️', 'title' => 'New Project', 'description' => 'Start a new project', 'link' => route('projects.create')])
--}}

@php
    $icon = $icon ?? '📋';
    $title = $title ?? 'Action Title';
    $description = $description ?? 'Action description';
    $link = $link ?? '#';
    $variant = $variant ?? 'default';
@endphp

<a href="{{ $link }}" class="card card-interactive {{ $variant }}" style="text-decoration: none; color: inherit;">
    <div class="card-body text-center">
        <div style="font-size: 3rem; margin-bottom: var(--space-md);">
            {!! $icon !!}
        </div>
        <h4 class="text-lg font-semibold mb-xs">{{ $title }}</h4>
        <p class="text-sm text-secondary">{{ $description }}</p>
    </div>
</a>
