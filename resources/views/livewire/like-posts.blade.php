<button wire:click="toggle" type="button"
    class=" btn btn-sm {{ $post->isLikedBy(Auth::user()) ? 'btn-danger' : 'btn-outline-danger' }}">
    <i class="fas fa-heart"></i>
    <span>{{ $post->likes->count() }}</span>
</button>
