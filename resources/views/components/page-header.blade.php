{{--
    Page Header Component
    Usage: @include('components.page-header', ['title' => 'Projects', 'subtitle' => 'Manage your projects', 'actions' => [...]])
--}}

@php
    $title = $title ?? 'Page Title';
    $subtitle = $subtitle ?? null;
    $breadcrumbs = $breadcrumbs ?? [];
    $actions = $actions ?? [];
@endphp

<div class="page-header">
    @if(count($breadcrumbs) > 0)
        <nav>
            <ol class="breadcrumb">
                @foreach($breadcrumbs as $crumb)
                    @if($loop->last)
                        <li class="breadcrumb-item active">{{ $crumb['label'] }}</li>
                    @else
                        <li class="breadcrumb-item">
                            <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="page-header-top">
        <div>
            <h1 class="page-title">{{ $title }}</h1>
            @if($subtitle)
                <p class="page-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
        @if(count($actions) > 0)
            <div class="page-actions">
                @foreach($actions as $action)
                    <a href="{{ $action['url'] }}" class="btn {{ $action['class'] ?? 'btn-primary' }}">
                        @if(isset($action['icon']))
                            <span>{!! $action['icon'] !!}</span>
                        @endif
                        <span>{{ $action['label'] }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
