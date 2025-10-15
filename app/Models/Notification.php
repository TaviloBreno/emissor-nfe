<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $fillable = [
        'id', 'type', 'notifiable_id', 'notifiable_type', 'data', 'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Scopes para facilitar consultas
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('notifiable_type', 'App\Models\User')
                    ->where('notifiable_id', $userId);
    }

    // Helpers
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function isUnread()
    {
        return is_null($this->read_at);
    }

    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    public function markAsUnread()
    {
        if (!is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
        }
    }

    // Getters para dados específicos
    public function getTitle()
    {
        return $this->data['title'] ?? 'Notificação';
    }

    public function getMessage()
    {
        return $this->data['message'] ?? '';
    }

    public function getIcon()
    {
        return $this->data['icon'] ?? 'fas fa-bell';
    }

    public function getColor()
    {
        return $this->data['color'] ?? 'blue';
    }

    public function getUrl()
    {
        return $this->data['url'] ?? null;
    }

    public function getTimeAgo()
    {
        return $this->created_at->diffForHumans();
    }
}