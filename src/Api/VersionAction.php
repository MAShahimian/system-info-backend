<?php

declare(strict_types=1);

namespace RadinTech\SystemInfo\Api;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class VersionAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $composerFile = dirname(__DIR__, 2) . '/composer.json';
        
        if (!is_readable($composerFile)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Module metadata is unavailable.',
            ], 500);
        }

        try {
            $composer = json_decode(
                (string) file_get_contents($composerFile),
                true,
                512,
                JSON_THROW_ON_ERROR,
            );
        } catch (\JsonException) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Module metadata is invalid.',
            ], 500);
        }

        $version = $composer['version'] ?? null;

        if (!is_string($version) || $version === '') {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Module version is not configured.',
            ], 500);
        }

        return $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'module' => $composer['name'] ?? 'system-info',
                'version' => $version,
            ],
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function jsonResponse(
        array $data,
        int $statusCode = 200,
    ): ResponseInterface {
        $response = $this->responseFactory
            ->createResponse($statusCode)
            ->withHeader(
                'Content-Type',
                'application/json; charset=utf-8',
            )
            ->withHeader('Cache-Control', 'no-store');

        $response->getBody()->write(
            json_encode(
                $data,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_THROW_ON_ERROR,
            ),
        );

        return $response;
    }
}
