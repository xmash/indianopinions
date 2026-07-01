<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaStorage
{
    public static function disk(): string
    {
        return filled(env('R2_BUCKET')) ? 'r2' : 'public';
    }

    public static function storeUpload(UploadedFile $file, string $folder): string
    {
        $disk = static::disk();
        $dir = $folder.'/'.now()->format('Y/m');
        $name = Str::uuid().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs($dir, $name, $disk);

        return Storage::disk($disk)->url($path);
    }

    public static function deleteByUrl(?string $url): void
    {
        if (! is_string($url) || $url === '') {
            return;
        }

        $disk = static::disk();
        $base = rtrim((string) Storage::disk($disk)->url(''), '/');

        if ($base === '' || ! str_starts_with($url, $base)) {
            return;
        }

        $path = ltrim(str_replace($base, '', $url), '/');
        Storage::disk($disk)->delete($path);
    }
}
