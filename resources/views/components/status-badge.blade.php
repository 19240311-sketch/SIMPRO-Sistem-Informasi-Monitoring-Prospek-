@props(['status'])

@php
    $code = is_object($status) ? $status->code : $status;
    $name = is_object($status) ? $status->name : ucfirst(str_replace('_', ' ', $status));

    $badgeClasses = match($code) {
        'baru' => 'bg-slate-100 text-slate-700 border-slate-200',
        'follow_up' => 'bg-blue-50 text-blue-800 border-blue-200',
        'hot' => 'bg-amber-50 text-amber-800 border-amber-200',
        'deal' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'batal' => 'bg-rose-50 text-rose-800 border-rose-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200'
    };

    $dotClasses = match($code) {
        'baru' => 'bg-slate-500',
        'follow_up' => 'bg-blue-600',
        'hot' => 'bg-amber-500',
        'deal' => 'bg-emerald-600',
        'batal' => 'bg-rose-600',
        default => 'bg-slate-500'
    };
@endphp

<span class="inline-flex items-center gap-1.5 rounded-pill px-2.5 py-0.5 text-caption font-medium border {{ $badgeClasses }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $dotClasses }}"></span>
    {{ $name }}
</span>
