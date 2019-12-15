<?php

namespace Symfony\Component\Mailer\Bridge\UniOne\Tests\Transport;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Bridge\UniOne\Transport\UniOneApiTransport;

class UniOneApiTransportTest extends TestCase
{
    /**
     * @param UniOneApiTransport $transport
     * @param string             $expected
     *
     * @dataProvider getTransportData
     */
    public function testToString(UniOneApiTransport $transport, string $expected)
    {
        $this->assertSame($expected, (string)$transport);
    }

    /**
     * @return array
     */
    public function getTransportData(): array
    {
        return [
            [
                new UniOneApiTransport('KEY', 'username'),
                'unione+api://one.unisender.com',
            ],
            [
                (new UniOneApiTransport('KEY', 'username'))->setHost('example.com'),
                'unione+api://example.com',
            ],
            [
                (new UniOneApiTransport('KEY', 'username'))->setPort(99),
                'unione+api://one.unisender.com:99',
            ],
        ];
    }
}
