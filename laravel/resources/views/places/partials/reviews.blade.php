<!-- Reviews -->
@can('create', App\Model\Review::class)
@if(!$place->reviewedByAuthUser())
<div class="mt-8">
    <form method="POST" action="{{ route('places.reviews.store', $place) }}">
        @csrf
        <div>
            <x-input-label for="review" :value="__('Review')" />
            <x-textarea name="review" id="review" class="block mt-1 w-full" />
        </div>
        <div class="mt-8">
            <x-primary-button>
                {{ __('Create') }}
            </x-primary-button>
            <x-secondary-button type="reset">
                {{ __('Reset') }}
            </x-secondary-button>
        </div>
    </form>
</div>
@endif
@endcan

@can('viewAny', App\Model\Review::class)
<div class="mt-8">
    <ul class="list-group">
    @foreach($place->reviews as $review)
        <li class="list-group-item">
            <p><b>{{ $review->author->name }}</b></p> 
            <p>{{ $review->review }}</p>
            <p>{{ $review->created_at->format('d/m/Y') }}</p>
            @can('delete', $review)
            <form method="POST" action="{{ route('places.reviews.destroy', [$place, $review]) }}">
                @csrf
                @method("DELETE")
                <x-danger-button>
                    {{ __('Delete') }}
                </x-danger-button>
            </form>
            @endcan
        </li>
    @endforeach
    </ul>
</div>
@endcan