<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function getFriendsAttribute()
    {
        $sent = $this->belongsToMany(User::class, 'friends', 'sender_id', 'reciever_id')
            ->withPivot('status',"updated_at")
            ->wherePivot('status', 'accepted');

        $received = $this->belongsToMany(User::class, 'friends', 'reciever_id', 'sender_id')
            ->withPivot('status',"updated_at")
            ->wherePivot('status', 'accepted');

        // Return both as a custom attribute (outside the relationship definition)
        return $sent->get()->merge($received->get());
    }
    public function isFriends(User $user){
        return $this->friends->contains($user);
    }
}
