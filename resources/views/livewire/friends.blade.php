<div class="col-md-4 mt-3">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-primary mb-4 text-center" style="font-weight: bold;">
                <i class="fas fa-users me-2"></i>Friends
            </h5>
            @if (empty($user->friends->first()))
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">No friends yet</h6>

                    </div>
                </div>
            @endif
            @foreach ($user->friends as $friend)
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle fa-2x text-secondary me-3"></i>
                        <div>
                            <h6 class="mb-0">{{ $friend->name }}</h6>
                            <small class="text-muted">since {{ $friend->pivot->updated_at }}</small>
                        </div>
                    </div>
                    @if (Auth::id() == $user->id)
                        <button wire:click="unFriend({{$friend->id}})" class="btn btn-m btn-outline-danger">Unfriend</button>
                    @endif
                </div>
            @endforeach

        </div>
    </div>
</div>
