{{--
    Stat Card Component
    Usage: @include('components.stat-card', ['icon' => '🏗️', 'label' => 'Total Projects', 'value' => '24', 'variant' => 'primary'])
--}}

@php
    $variant = $variant ?? 'primary';
    $icon = $icon ?? '📊';
    $label = $label ?? 'Stat Label';
    $value = $value ?? '0';
    $trend = $trend ?? null;
    $trendDirection = $trendDirection ?? 'positive';
    $link = $link ?? null;
@endphp

@if($link)
    <a href="{{ $link }}" class="stat-card {{ $variant }}" style="text-decoration: none; color: inherit; display: block;">
@else
    <div class="stat-card {{ $variant }}">
@endif
        <div class="stat-card-icon">
            {!! $icon !!}
        </div>
        <div class="stat-card-label">{{ $label }}</div>
        <div class="stat-card-value">{{ $value }}</div>
        @if($trend)
            <div class="stat-card-trend {{ $trendDirection }}">
                <span>{{ $trendDirection === 'positive' ? '↗' : '↘' }}</span>
                <span>{{ $trend }}</span>
            </div>
        @endif
@if($link)
    </a>
@else
    </div>
@endif
