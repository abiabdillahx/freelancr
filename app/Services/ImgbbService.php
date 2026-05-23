<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ImgbbService
{
    private string $apiKey;
    private string $endpoint = 'https://api.imgbb.com/1/upload';

    public function __construct()
    {
        $this->apiKey = config('services.imgbb.api_key');

        if (empty($this->apiKey)) {
            throw new RuntimeException('IMGBB_API_KEY is not configured in .env');
        }
    }

    /**
     * Upload an image file to Imgbb and return the hosted URL.
     *
     * @param  UploadedFile  $file
     * @param  string|null   $name  Optional image name
     * @return string  Public URL of the uploaded image
     *
     * @throws RuntimeException
     */
    public function upload(UploadedFile $file, ?string $name = null): string
    {
        $base64 = base64_encode(file_get_contents($file->getRealPath()));

        $payload = [
            'key'   => $this->apiKey,
            'image' => $base64,
        ];

        if ($name) {
            $payload['name'] = $name;
        }

        $response = Http::asForm()->post($this->endpoint, $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Imgbb request failed: HTTP ' . $response->status());
        }

        $data = $response->json();

        if (! ($data['success'] ?? false)) {
            $message = $data['error']['message'] ?? 'Unknown Imgbb error';
            throw new RuntimeException('Imgbb upload error: ' . $message);
        }

        return $data['data']['url'];
    }

    /**
     * Upload from a raw base64 string (no file upload required).
     */
    public function uploadBase64(string $base64, ?string $name = null): string
    {
        $payload = [
            'key'   => $this->apiKey,
            'image' => $base64,
        ];

        if ($name) {
            $payload['name'] = $name;
        }

        $response = Http::asForm()->post($this->endpoint, $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Imgbb request failed: HTTP ' . $response->status());
        }

        $data = $response->json();

        if (! ($data['success'] ?? false)) {
            $message = $data['error']['message'] ?? 'Unknown Imgbb error';
            throw new RuntimeException('Imgbb upload error: ' . $message);
        }

        return $data['data']['url'];
    }
}
