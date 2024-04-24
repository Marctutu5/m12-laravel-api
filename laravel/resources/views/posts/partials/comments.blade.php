<!-- Comments -->
@can('create', App\Model\Comment::class)
@if(!$post->commentedByAuthUser())
<div class="mt-8">
    <form method="POST" action="{{ route('posts.comments.store', $post) }}">
        @csrf
        <div>
            <x-input-label for="comment" :value="__('Comment')" />
            <x-textarea name="comment" id="comment" class="block mt-1 w-full" />
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

@can('viewAny', App\Model\Comment::class)
<div class="mt-8">
    <ul class="list-group">
    @foreach($post->comments as $comment)
        <li class="list-group-item">
            <p><b>{{ $comment->author->name }}</b></p> 
            <p>{{ $comment->comment }}</p>
            <p>{{ $comment->created_at->format('d/m/Y') }}</p>
            @can('delete', $comment)
            <form method="POST" action="{{ route('posts.comments.destroy', [$post, $comment]) }}">
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