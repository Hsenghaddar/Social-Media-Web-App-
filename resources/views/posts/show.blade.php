@extends('layouts.app')

@section('title', 'Post by ' . $post->user->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="card-subtitle mb-1">
                                <a href="{{ route('profile', $post->user) }}" class="text-decoration-none">
                                    {{ $post->user->name }}
                                </a>
                            </h6>
                            <small class="text-muted">{{ $post->created_at->format('F j, Y \a\t g:i A') }}</small>
                        </div>
                        @auth
                            @if ($post->user_id === Auth::id())
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">Edit</a></li>
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

                    <p class="card-text fs-5">{{ $post->content }}</p>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            @auth
                                <button data-post-id="{{ $post->id }}" type="button"
                                    class="like-btn btn {{ $post->isLikedBy(Auth::user()) ? 'btn-danger' : 'btn-outline-danger' }}">
                                    <i class="fas fa-heart"></i>
                                    <span>{{ $post->likes->count() }}</span><span> {{ Str::plural('like', $post->likes->count()) }}</span>
                                </button>
                            @else
                                <span class="btn btn-outline-secondary disabled me-3">
                                    <i class="fas fa-heart"></i>
                                    {{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}
                                </span>
                            @endauth
                        </div>

                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-comments"></i>
                        Comments ({{ $post->allComments->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @auth
                        <!-- Add Comment Form -->
                        <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="3"
                                    placeholder="Write a comment..." required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Comment</button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <a href="{{ route('login', ['intended' => url()->current()]) }}">Login</a> to add a comment.
                        </div>
                    @endauth

                    <!-- Comments List -->
                    @forelse($post->comments as $comment)
                        <div class="comment mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-circle fa-2x text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('profile', $comment->user) }}"
                                                    class="text-decoration-none">
                                                    {{ $comment->user->name }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        @auth
                                            @if ($comment->user_id === Auth::id())
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Delete this comment?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                    <p class="mb-2">{{ $comment->content }}</p>

                                    @auth
                                        <button class="btn btn-sm btn-outline-secondary reply-btn"
                                            data-comment-id="{{ $comment->id }}">
                                            <i class="fas fa-reply"></i> Reply
                                        </button>
                                    @endauth

                                    <!-- Reply Form (Hidden by default) -->
                                    @auth
                                        <form action="{{ route('comments.store', $post) }}" method="POST"
                                            class="reply-form mt-2" id="reply-form-{{ $comment->id }}" style="display: none;">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <div class="mb-2">
                                                <textarea class="form-control" name="content" rows="2" placeholder="Write a reply..." required></textarea>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-sm btn-primary">Reply</button>
                                                <button type="button" class="btn btn-sm btn-secondary cancel-reply"
                                                    data-comment-id="{{ $comment->id }}">Cancel</button>
                                            </div>
                                        </form>
                                    @endauth

                                    <!-- Replies -->
                                    @foreach ($comment->replies as $reply)
                                        <div class="reply ms-4 mt-3">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-user-circle fa-lg text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <a href="{{ route('profile', $reply->user) }}"
                                                                    class="text-decoration-none">
                                                                    {{ $reply->user->name }}
                                                                </a>
                                                            </h6>
                                                            <small
                                                                class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        @auth
                                                            @if ($reply->user_id === Auth::id())
                                                                <form action="{{ route('comments.destroy', $reply) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-outline-danger"
                                                                        onclick="return confirm('Delete this reply?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endauth
                                                    </div>
                                                    <p class="mb-0">{{ $reply->content }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No comments yet. Be the first to comment!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reply button functionality
            document.querySelectorAll('.reply-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    const replyForm = document.getElementById(`reply-form-${commentId}`);
                    replyForm.style.display = 'block';
                    this.style.display = 'none';
                });
            });

            // Cancel reply button functionality
            document.querySelectorAll('.cancel-reply').forEach(button => {
                button.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    const replyForm = document.getElementById(`reply-form-${commentId}`);
                    const replyBtn = document.querySelector(
                        `[data-comment-id="${commentId}"].reply-btn`);
                    replyForm.style.display = 'none';
                    replyBtn.style.display = 'inline-block';
                });
            });

            document.querySelectorAll('.like-btn').forEach(button => {
                document.querySelectorAll('.like-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        // alert("Attempting to Like!");
                        // console.log(this.dataset.postId);
                        // console.log("{{ csrf_token() }}");//we can open this and echo php code like normal
                        fetch(`/posts/${this.dataset.postId}/like`, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json", //the format the request body is in.
                                    "Accept": "application/json", //Please respond in JSON
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}" //When you’re logged into a Laravel app, it sets a CSRF token in your session, On every POST, PUT, DELETE, PATCH request, Laravel expects the same token sent back in the request headers.
                                },
                            })
                            .then((response) => response.json())
                            .then((result) => {
                                this.classList.toggle(
                                    "btn-danger") //if the class is found remove it else put it
                                this.classList.toggle("btn-outline-danger")
                                this.children[1].textContent = result.likes;
                            })
                            .catch((error) => console.log(error))
                    });
                });
            });
        });
    </script>
@endsection
