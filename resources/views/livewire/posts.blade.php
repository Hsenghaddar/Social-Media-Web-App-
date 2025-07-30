<div class="col-md-8">
    <h4>{{ $user->name }}'s Posts</h4>

    @forelse($posts as $post)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                    @auth
                        @if ($post->user_id === Auth::id())
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">Edit</a>
                                    </li>
                                    <li>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @endauth
                </div>

                <p class="card-text">{{ $post->content }}</p>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @auth
                            <livewire:LikePosts :post="$post" />
                        @else
                            <span class="btn btn-sm btn-outline-secondary disabled me-2">
                                <i class="fas fa-heart"></i>
                                {{ $post->likes->count() }}
                            </span>
                        @endauth

                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-comment"></i> View
                        </a>
                        @if ($post->privacy)
                            <span class="btn btn-sm btn-outline-secondary disabled">
                                <i class="fas fa-lock"></i> Private
                            </span>
                        @else
                            <span class="btn btn-sm btn-outline-success disabled">
                                <i class="fas fa-globe"></i> Public
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center">
                <h5 class="text-muted">No posts yet</h5>
                <p>{{ $user->name }} hasn't shared anything yet.</p>
            </div>
        </div>
    @endforelse
</div>
