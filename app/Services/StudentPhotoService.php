<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageFactory;

class StudentPhotoService
{
    private const DIRECTORY = 'public/images';
    private const WIDTH = 600;
    private const HEIGHT = 800;
    private const QUALITY = 85;

    public function store(UploadedFile $uploadedFile, ?string $currentImage = null): string
    {
        $this->ensureDirectoryExists();

        $imageName = time() . '_' . uniqid('', true) . '.jpg';
        $absolutePath = storage_path('app/' . self::DIRECTORY . '/' . $imageName);

        try {
            $image = ImageFactory::make($uploadedFile->getRealPath());

            if (method_exists($image, 'orientate')) {
                $image->orientate();
            }

            $image->resize(self::WIDTH, self::HEIGHT, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $canvas = ImageFactory::canvas(self::WIDTH, self::HEIGHT, '#ffffff');
            $canvas->insert($image, 'center');
            $canvas->save($absolutePath, self::QUALITY, 'jpg');
        } catch (\Throwable $exception) {
            $uploadedFile->storeAs(self::DIRECTORY, $imageName);
        }

        $this->delete($currentImage);

        return $imageName;
    }

    public function delete(?string $imageName): void
    {
        if (!$imageName) {
            return;
        }

        Storage::delete(self::DIRECTORY . '/' . $imageName);
    }

    private function ensureDirectoryExists(): void
    {
        $directory = storage_path('app/' . self::DIRECTORY);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}
