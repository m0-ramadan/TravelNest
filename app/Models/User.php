<?php

namespace App\Models;

use App\Models\Wallet\LedgerEntry;
use App\Models\Wallet\UserWallet;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'apple_id',
        'phone',
        'image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function notifications()
    {
        return $this->morphMany(\App\Models\Notification::class, 'notifiable');
    }

    public function favourites()
    {
        return $this->hasMany(\App\Models\Favourite::class);
    }

    public function favouriteProducts()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'favourites');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Create user wallet
     */
    public function createUserWallet()
    {
        if (! $this->userWallet) {
            return UserWallet::create([
                'user_id' => $this->id,
                'balance' => 0,
                'held_balance' => 0,
                'currency' => 'EGP',
                'status' => 'active',
            ]);
        }
        return $this->userWallet;
    }

    /**
     * Get wallet
     */
    public function wallet()
    {
        return $this->userWallet;
    }

    public function userWallet()
    {
        return $this->hasOne(UserWallet::class, 'user_id');
    }

    /**
     * Check if user can transact
     */
    public function canTransact(float $amount, string $type = 'withdrawal'): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $wallet = $this->wallet();

        if (! $wallet || $wallet->status !== 'active') {
            return false;
        }

        return true;
    }

    /**
     * Get ledger entries
     */
    public function ledgerEntries()
    {
        return LedgerEntry::where('owner_type', 'user')
            ->where('owner_id', $this->id);
    }
}
