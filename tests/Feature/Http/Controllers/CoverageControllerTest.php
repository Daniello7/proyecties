<?php

use App\Http\Controllers\CoverageController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

beforeEach(function () {
    $this->controller = new CoverageController();
    Storage::fake('local');
});

describe('CoverageController', function () {
    it('returns index.html by default with correct mime type', function () {
        // Arrange
        Storage::disk('local')->put('coverage/index.html', 'test content');

        // Act
        $response = $this->controller->__invoke();

        // Assert
        expect($response)
            ->toBeInstanceOf(BinaryFileResponse::class)
            ->and($response->headers->get('Content-Type'))
            ->toBe('text/html');
    });

    it('returns file with correct mime type for different extensions', function () {
        $testCases = [
            ['file.css', 'text/css'],
            ['script.js', 'application/javascript'],
            ['image.svg', 'image/svg+xml'],
            ['photo.png', 'image/png'],
            ['picture.jpg', 'image/jpeg'],
            ['image.jpeg', 'image/jpeg'],
            ['animation.gif', 'image/gif'],
        ];

        foreach ($testCases as [$file, $mimeType]) {
            // Arrange
            Storage::disk('local')->put('coverage/' . $file, 'test content');

            // Act
            $response = $this->controller->__invoke($file);

            // Assert
            expect($response)
                ->toBeInstanceOf(BinaryFileResponse::class)
                ->and($response->headers->get('Content-Type'))
                ->toBe($mimeType);
        }
    });

    it('returns text/plain for unknown extensions', function () {
        // Arrange
        Storage::disk('local')->put('coverage/file.xyz', 'test content');

        // Act
        $response = $this->controller->__invoke('file.xyz');

        // Assert
        expect($response)
            ->toBeInstanceOf(BinaryFileResponse::class)
            ->and($response->headers->get('Content-Type'))
            ->toBe('text/plain');
    });

    it('throws 404 when file does not exist', function () {
        // Assert & Act
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Reporte no encontrado');

        // Act
        $this->controller->__invoke('nonexistent.html');
    });

    it('handles nested paths correctly', function () {
        // Arrange
        Storage::disk('local')->put('coverage/assets/css/style.css', 'test content');

        // Act
        $response = $this->controller->__invoke('assets/css/style.css');

        // Assert
        expect($response)
            ->toBeInstanceOf(BinaryFileResponse::class)
            ->and($response->headers->get('Content-Type'))
            ->toBe('text/css');
    });
});