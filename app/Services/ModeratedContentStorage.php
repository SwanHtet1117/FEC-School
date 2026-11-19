<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ModeratedContentStorage
{
    public function storePending(UploadedFile $file, string $type): string
    {
        $path = $file->store("pending-content/{$type}", 'local');
        if ($path === false) {
            throw new RuntimeException('The content image could not be uploaded.');
        }

        return $path;
    }

    public function publishPending(string $path, string $directory): string
    {
        if (! $this->isPending($path)) {
            return $path;
        }

        $publishedPath = $directory.'/'.basename($path);
        $contents = Storage::disk('local')->get($path);

        if (! Storage::disk('public')->put($publishedPath, $contents)) {
            throw new RuntimeException('The approved content image could not be published.');
        }

        return $publishedPath;
    }

    public function delete(string $path): void
    {
        if (! $this->isManagedUpload($path)) {
            return;
        }

        $disk = $this->isPending($path) ? 'local' : 'public';
        if (! Storage::disk($disk)->delete($path)) {
            throw new RuntimeException('The content image could not be removed from storage.');
        }
    }

    public function isManagedUpload(string $path): bool
    {
        return $this->isPending($path)
            || str_starts_with($path, 'hero-slides/')
            || str_starts_with($path, 'gallery-photos/')
            || str_starts_with($path, 'posts/');
    }

    private function isPending(string $path): bool
    {
        return str_starts_with($path, 'pending-content/');
    }
}
