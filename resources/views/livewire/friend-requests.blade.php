<div class="col-md-8 mb-4">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Friend Requests</h5>

            <h6 class="mt-3 text-primary">Received Requests</h6>
            <ul class="list-group mb-3">
                @if (empty($receivedRequests->first()))
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>No Received requests</span>
                    </li>
                @endif
                @foreach ($receivedRequests as $request)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $request->sender->name }}</span>
                        <div>
                            <button wire:click="accept({{$request->sender->id}})" class="btn btn-sm btn-success">Accept</button>
                            <button wire:click="decline({{$request->sender->id}})" class="btn btn-sm btn-danger ms-1">Decline</button>
                        </div>
                    </li>
                @endforeach
            </ul>

            <h6 class="mt-3 text-secondary">Sent Requests</h6>
            <ul class="list-group">
                @if (empty($sentRequests->first()))
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>No sent requests</span>
                    </li>
                @endif
                @foreach ($sentRequests as $request)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $request->reciever->name }}</span>
                        <span class="badge bg-warning text-dark">Pending</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
