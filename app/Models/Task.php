<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
  {
        use HasFactory;

    protected $fillable = [
            'title',
            'description',
            'status',
            'due_date',
        ];

    protected $casts = [
            'due_date' => 'date',
        ];

    public function user(): BelongsTo
    {
              return $this->belongsTo(User::class);
    }

    public function scopeOwnedBy($query, User $user)
    {
              return $query->where('user_id', $user->id);
    }

    public function scopeStatus($query, ?string $status)
    {
              return $status ? $query->where('status', $status) : $query;
    }
  }
