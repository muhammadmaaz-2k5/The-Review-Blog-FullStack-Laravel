<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Convert an image to WebP format and store it.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string|null $filename
     * @param int $quality
     * @return string|null Path to the stored WebP image
     */
    public function convertToWebp(UploadedFile $file, string $folder, ?string $filename = null, int $quality = 80): ?string
    {
        try {
            // Generate filename if not provided
            if (!$filename) {
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            }
            
            // Clean filename and ensure it's unique
            $filename = Str::slug($filename) . '-' . uniqid() . '.webp';
            
            // Create full path
            $path = trim($folder, '/') . '/' . $filename;
            
            // Load the image based on its type
            $image = $this->createImageFromUploadedFile($file);
            
            if (!$image) {
                // Return null or fallback if image creation fails
                Log::warning('WebP conversion failed: Could not create image resource from file.', [
                    'filename' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType()
                ]);
                return null;
            }
            
            // Save as WebP to a temporary local path
            $tempPath = tempnam(sys_get_temp_dir(), 'webp_');
            
            // Convert and save
            if (!imagewebp($image, $tempPath, $quality)) {
                imagedestroy($image);
                if (file_exists($tempPath)) unlink($tempPath);
                return null;
            }
            
            // Move to storage
            Storage::disk('public')->put($path, file_get_contents($tempPath));
            
            // Clean up resources
            imagedestroy($image);
            if (file_exists($tempPath)) unlink($tempPath);
            
            return $path;
        } catch (\Exception $e) {
            Log::error('Image conversion error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a PHP image resource from an uploaded file.
     */
    private function createImageFromUploadedFile(UploadedFile $file)
    {
        $mime = $file->getMimeType();
        $filePath = $file->getRealPath();
        
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                return @imagecreatefromjpeg($filePath);
            case 'image/png':
                $image = @imagecreatefrompng($filePath);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                return $image;
            case 'image/gif':
                return @imagecreatefromgif($filePath);
            case 'image/webp':
                return @imagecreatefromwebp($filePath);
            default:
                // Try extension if mime fails
                $ext = strtolower($file->getClientOriginalExtension());
                if ($ext === 'jpg' || $ext === 'jpeg') return @imagecreatefromjpeg($filePath);
                if ($ext === 'png') return @imagecreatefrompng($filePath);
                if ($ext === 'gif') return @imagecreatefromgif($filePath);
                if ($ext === 'webp') return @imagecreatefromwebp($filePath);
                return null;
        }
    }
    
    /**
     * Delete an image from storage.
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
