<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class MediaFile extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'filename', 'path', 'mime_type', 'size',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }


    public function getUrlAttribute(): string
    {
        return url("/media/{$this->id}");
    }

    public static function storeFromUploadedFile(UploadedFile $file): ?string
    {

            $uuid = (string) Str::uuid();
            $filename = $file->getClientOriginalExtension();
            $path = $file->storeAs('media', $filename);

            self::create([
                'filename'   => $filename,
                'path'       => $path,
                'mime_type'  => $file->getMimeType(),
                'size'       => $file->getSize(),
            ]);

            return $uuid;

    }

    public static function storeFromContent(string $content, string $filename, ?string $id = null): self
    {

        $path = "media/{$filename}";

        Storage::disk('local')->put($path, $content);

        return self::create([
            'id' => $id,
            'path' => $path,
            'filename' => $filename,
            'mime_type' => 'image/svg+xml',
            'size' => strlen($content),
        ]);
    }

}
