<div class="d-flex justify-content-center">
    @if (Auth::user()->isFriends($user))
        <div class="col-6 ">
            <button wire:click="unFriend" class="btn btn-m btn-primary">Unfriend</button>
        </div>
    @elseif($isRequestSent)
        <div class="col-6">
            <button wire:click="cancel" class="btn btn-m btn-primary">Cancel</button>
        </div>
    @elseif($isRequestRecieved)
        <div class="col-6 text-center w-full">
            <button wire:click="accept" class="btn btn-sm btn-success">Accept</button>
            <button wire:click="decline" class="btn btn-sm btn-danger ms-1">Decline</button>
        </div>
    @else
        <div class="col-6 ">
            <button wire:click="addFriend" class="btn btn-m btn-primary">Add Friend</button>
        </div>
    @endif
</div>
