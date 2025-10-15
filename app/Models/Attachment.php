<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'filename',
        'path',
        'mime_type',
        'size',
    ];

    /**
     * The task this attachment belongs to
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * The user who uploaded this attachment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the file size in human readable format
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get the download URL for this attachment
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('attachments.download', $this);
    }

    /**
     * Check if the file is an image
     */
    public function getIsImageAttribute(): bool
    {
        return in_array($this->mime_type, [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml'
        ]);
    }

    /**
     * Get file icon based on mime type
     */
    public function getIconAttribute(): string
    {
        if ($this->is_image) {
            return '🖼️';
        }

        return match(true) {
            str_contains($this->mime_type, 'pdf') => '📄',
            str_contains($this->mime_type, 'word') || str_contains($this->mime_type, 'document') => '📝',
            str_contains($this->mime_type, 'sheet') || str_contains($this->mime_type, 'excel') => '📊',
            str_contains($this->mime_type, 'presentation') || str_contains($this->mime_type, 'powerpoint') => '📈',
            str_contains($this->mime_type, 'zip') || str_contains($this->mime_type, 'rar') => '🗜️',
            str_contains($this->mime_type, 'video') => '🎥',
            str_contains($this->mime_type, 'audio') => '🎵',
            default => '📎'
        };
    }

    /**
     * Delete the attachment file when model is deleted
     */
    protected static function booted()
    {
        static::deleting(function ($attachment) {
            if (Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }
        });
    }
}
