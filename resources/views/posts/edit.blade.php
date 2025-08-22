@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Post</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('posts.update', $post) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="content" class="form-label">Post Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5"
                                required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="d-flex gap-2">
                                <div class="d-flex gap-2">
                                    <div
                                        class="form-check form-switch d-flex justify-content-center align-items-center gap-2">
                                        <input name="privacy" class="form-check-input" type="checkbox" role="switch"
                                            id="privacySwitch" {{ $post->privacy ? 'checked' : '' }}>
                                        <span id="privacyStatus">Public</span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Post</button>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const switchInput = document.getElementById('privacySwitch');
        const statusText = document.getElementById('privacyStatus');
        statusText.textContent = switchInput.checked ? 'Private' : 'Public';
        switchInput.addEventListener('change', () => {
            statusText.textContent = switchInput.checked ? 'Private' : 'Public';
        });
    </script>
@endsection
