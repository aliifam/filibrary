<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Book extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $appends = [
        'file',
        'cover'
    ];

    protected $fillable = ['title', 'description', 'amount', 'user_id', 'category_id'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')->singleFile();
        $this->addMediaCollection('cover')->singleFile();
    }

    public function file()
    {
        return $this->hasOne(Media::class, 'model_id', 'id')->where('collection_name', 'file');
    }

    public function cover()
    {
        return $this->hasOne(Media::class, 'model_id', 'id')->where('collection_name', 'cover');
    }

    public function getFileAttribute()
    {
        return $this->getMedia('file')->last();
    }

    public function getCoverAttribute()
    {
        $file = $this->getMedia('cover')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl();
            $file->preview   = $file->getUrl();
        }

        return $file;
    }
}
