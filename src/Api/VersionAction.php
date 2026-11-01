<?php

declare(strict_types=1);

namespace RadinTech\SystemInfo\Api;

use Composer\InstalledVersions;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class VersionAction
{
    private const PACKAGE_NAME = 'radintech/system-info-module';

    public function __construct(
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $version = InstalledVersions::isInstalled(self::PACKAGE_NAME)
            ? InstalledVersions::getPrettyVersion(self::PACKAGE_NAME)
            : null;

        if (!is_string($version) || $version === '') {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Module version is unavailable.',
            ], 500);
        }

        return $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'module' => self::PACKAGE_NAME,
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
