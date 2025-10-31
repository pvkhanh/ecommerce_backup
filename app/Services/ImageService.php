<?php

namespace App\Services;

use App\Models\Image;
use App\Repositories\Contracts\ImageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as InterventionImage;

class ImageService
{
    public function __construct(
        protected ImageRepositoryInterface $imageRepository
    ) {
    }

    /**
     * Upload single image
     */
    public function upload(UploadedFile $file, string $type = 'other', ?string $altText = null): Image
    {
        $fileName = $this->generateFileName($file);
        $path = $this->storeImage($file, $fileName);
        $imageInfo = getimagesize($file->getRealPath());

        return $this->imageRepository->create([
            'type' => $type,
            'path' => 'storage/images/' . $fileName,
            'alt_text' => $altText ?? $fileName,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'width' => $imageInfo[0] ?? null,
            'height' => $imageInfo[1] ?? null,
            'is_active' => true,
        ]);
    }

    /**
     * Upload multiple images
     */
    public function uploadMultiple(array $files, string $type = 'other', array $altTexts = []): array
    {
        $images = [];

        foreach ($files as $index => $file) {
            $images[] = $this->upload(
                $file,
                $type,
                $altTexts[$index] ?? null
            );
        }

        return $images;
    }

    /**
     * Delete image
     */
    public function delete(Image $image): bool
    {
        // Delete physical file
        $this->deletePhysicalFile($image->path);

        // Delete thumbnail if exists
        $thumbnailPath = $this->getThumbnailPath($image->path);
        $this->deletePhysicalFile($thumbnailPath);

        // Delete from database
        return $image->delete();
    }

    /**
     * Update image
     */
    public function update(Image $image, array $data): bool
    {
        return $image->update($data);
    }

    /**
     * Create thumbnail
     */
    public function createThumbnail(Image $image, int $width = 300, int $height = 300): ?string
    {
        if (!class_exists(InterventionImage::class)) {
            return null;
        }

        try {
            $storagePath = storage_path('app/' . str_replace('storage/', 'public/', $image->path));
            $thumbnailDir = storage_path('app/public/images/thumbnails');

            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            $fileName = basename($image->path);
            $thumbnailPath = $thumbnailDir . '/' . $fileName;

            InterventionImage::make($storagePath)
                ->fit($width, $height)
                ->save($thumbnailPath);

            return 'storage/images/thumbnails/' . $fileName;
        } catch (\Exception $e) {
            \Log::error('Failed to create thumbnail: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Resize image
     */
    public function resize(Image $image, int $width, int $height): bool
    {
        if (!class_exists(InterventionImage::class)) {
            return false;
        }

        try {
            $storagePath = storage_path('app/' . str_replace('storage/', 'public/', $image->path));

            $img = InterventionImage::make($storagePath);
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save();

            // Update dimensions in database
            $image->update([
                'width' => $img->width(),
                'height' => $img->height(),
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to resize image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Optimize image (compress)
     */
    public function optimize(Image $image, int $quality = 85): bool
    {
        if (!class_exists(InterventionImage::class)) {
            return false;
        }

        try {
            $storagePath = storage_path('app/' . str_replace('storage/', 'public/', $image->path));

            InterventionImage::make($storagePath)
                ->save($storagePath, $quality);

            // Update size in database
            $newSize = filesize($storagePath);
            $image->update(['size' => $newSize]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to optimize image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get images by type
     */
    public function getByType(string $type)
    {
        return $this->imageRepository->ofType($type);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Image $image): bool
    {
        return $image->update(['is_active' => !$image->is_active]);
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(array $imageIds): int
    {
        $count = 0;
        $images = Image::whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            if ($this->delete($image)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Generate unique file name
     */
    protected function generateFileName(UploadedFile $file): string
    {
        return time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Store image to storage
     */
    protected function storeImage(UploadedFile $file, string $fileName): string
    {
        return $file->storeAs('public/images', $fileName);
    }

    /**
     * Delete physical file
     */
    protected function deletePhysicalFile(string $path): bool
    {
        $storagePath = str_replace('storage/', 'public/', $path);

        if (Storage::exists($storagePath)) {
            return Storage::delete($storagePath);
        }

        return false;
    }

    /**
     * Get thumbnail path
     */
    protected function getThumbnailPath(string $originalPath): string
    {
        return str_replace('images/', 'images/thumbnails/', $originalPath);
    }

}