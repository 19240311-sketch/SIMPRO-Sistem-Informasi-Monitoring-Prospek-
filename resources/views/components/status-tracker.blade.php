@props(['currentStatus', 'statuses'])

@php
    $currentSort = $currentStatus ? $currentStatus->sort_order : 1;
    $isBatal = $currentStatus && $currentStatus->code === 'batal';
    $isDeal = $currentStatus && $currentStatus->code === 'deal';
@endphp

<div class="w-full pt-3 pb-8">
    <div class="flex items-center justify-between w-full">
        @foreach($statuses as $index => $status)
            @php
                $isPassed = $status->sort_order < $currentSort && !$isBatal;
                $isCurrent = $status->id === $currentStatus->id;
                $isUpcoming = $status->sort_order > $currentSort;
            @endphp

            <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
                <!-- Node & Label -->
                <div class="flex flex-col items-center relative group">
                    @if($isCurrent)
                        @if($status->code === 'deal')
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-success text-on-primary ring-4 ring-success-soft shadow-elev-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                        @elseif($status->code === 'batal')
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-error text-on-primary ring-4 ring-error-soft shadow-elev-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-primary bg-surface ring-4 ring-primary-soft shadow-elev-2">
                                <div class="h-2.5 w-2.5 rounded-full bg-primary"></div>
                            </div>
                        @endif
                    @elseif($isPassed)
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-primary text-on-primary shadow-sm">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                    @else
                        <div class="flex h-7 w-7 items-center justify-center rounded-full border-2 border-slate-300 bg-surface">
                            <span class="text-xs font-bold text-slate-500">{{ $status->sort_order }}</span>
                        </div>
                    @endif

                    <!-- Label -->
                    <span class="absolute top-10 text-center whitespace-nowrap text-xs font-semibold {{ $isCurrent ? 'text-primary font-bold' : ($isPassed ? 'text-slate-800' : 'text-slate-400') }}">
                        {{ $status->name }}
                    </span>
                </div>

                <!-- Connector Line -->
                @if(!$loop->last)
                    <div class="flex-1 mx-2 sm:mx-3 h-1 rounded-full {{ $isPassed ? 'bg-primary' : 'bg-slate-200' }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>
