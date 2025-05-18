<?php

namespace App\Service;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FileUploadService
{
    public static string $fileName;
    public static string $fileExtension;
    public static string $fileURL;

    /**
     * @param $file - here pass the base644 source(src) file
     * @param $name - get the file name . here have a by default file name
     * @return string|array here must be return a string as url or array with file information
     * @throws ValidationException
     */

    public static function uploadBase64($file, $name = null): string|array
    {
        if (!str_contains($file, 'base64')) {
            throw ValidationException::withMessages(['message' => 'Please upload a valid base64 file.']);
        }

        self::$fileName = $name ?? self::getDefaultFilename();
        $fileArray = explode(';base64,', $file);
        self::$fileExtension = $fileArray[0];
        $src = $fileArray[1];

        $fileType = explode('/', self::$fileExtension);
        self::$fileExtension = $fileType[1];

        if (mime_content_type($file) === 'image/svg+xml') {
            self::$fileExtension = "svg";
        }

        self::$fileName = self::$fileName . '.' . self::$fileExtension;
        $encodedSrc = str_replace(' ', '+', $src);
        $folderPath = public_path() . '/' . 'uploads/';
        $file = $folderPath . self::$fileName;
        self::$fileURL = url('/uploads') . '/' . self::$fileName;

        file_put_contents($file, base64_decode($encodedSrc));

        return self::$fileURL;
    }

    /**
     * @return string - here generate a default file name
     */
    public static function getDefaultFilename(): string
    {
        return self::$fileName = Str::lower(Str::random(5)) . now()->format('Ymdhis');
    }
}
