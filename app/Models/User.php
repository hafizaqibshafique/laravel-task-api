<?php

namespace App\Models;

// Note: this is Laravel's default User model with one addition — the
// `tasks()` relationship — so the diff against a stock `php artisan install:api`
// scaffold stays small and reviewable.

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
  {
        use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
            'name',
            'email',
            'password',
        ];

    protected $hidden = [
            'password',
            'remember_token',
        ];

    protected function casts(): array
    {
              return [
                            'email_verified_at' => 'datetime',
                            'password' => 'hashed',
                        ];
    }

    public function tasks(): HasMany
    {
              return $this->hasMany(Task::class);
    }
  }
