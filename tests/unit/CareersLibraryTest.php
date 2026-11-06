<?php

use App\Libraries\CareersLibrray;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CareersLibraryTest extends TestCase
{
    public function testTrimsWhitespaceBeforeBuildingEndpointUrl(): void
    {
        $client = new CareersLibrray(" \r\nhttps://careers.example.com/ \t", '', '');
        $property = new ReflectionProperty($client, 'baseUrl');

        self::assertSame('https://careers.example.com', $property->getValue($client));
    }

    #[DataProvider('invalidUrls')]
    public function testRejectsInvalidConfigurationBeforeAnyRequest(string $url): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('CAREERS_API_BASE_URL');
        new CareersLibrray($url, '', '');
    }

    public static function invalidUrls(): array
    {
        return array_map(static fn ($url) => [$url], [
            '', 'careers.example.com', 'https://careers.example.com /',
            '"https://careers.example.com/"', 'ftp://careers.example.com',
            'https://careers.example.com?x=1', 'https://careers.example.com#jobs',
            'https://user:secret@careers.example.com',
        ]);
    }
}
