{{-- Loop through events and display --}}
@forelse ($events as $event)
    {{-- Create new row for every 2 courses (2-column layout) --}}
    @if($loop->iteration % 2)
        <div class="row flex-row row-cols-2 mb-5">
    @endif

    {{-- Include individual course tile component --}}
    <x-event-tile :event="$event"/>

    {{-- Close row after every 2nd course or at the end of the list --}}
    @if($loop->iteration % 2 == 0 || $loop->last)
        {{-- Add empty column if last row has odd number of courses --}}
        @if($loop->last && $loop->iteration % 2 == 1)
            <div class="col-12 col-sm-6"></div>
        @endif
        </div>
    @endif
@empty
    <div class="text-center py-5">
        <p class="h3 m-0">
            @if (($type ?? null) === 'upcoming')
                No upcoming courses at this time.
            @else
                No courses available at this time.
            @endif
        </p>
    </div>
@endforelse
