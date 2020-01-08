<?php

namespace Symfony\Component\Mailer\Bridge\UniOne\Tests\Transport;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Bridge\UniOne\Transport\UniOneApiTransport;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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
    
    public function testSend(): void
    {
        $email = new Email();
        $email->from('foo@example.com')
            ->to('bar@example.com')
            ->text('content');
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);
        $response
            ->expects($this->once())
            ->method('toArray')
            ->willReturn(['job_id' => '1']);
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', 'https://one.unisender.com/en/transactional/api/v1/email/send.json', [
                'json' => [
                    'api_key' => 'foo',
                    'username' =>'bar',
                    'message' => [
                        'body' => [
                            'html' => null,
                            'text' => 'content',
                        ],
                        'subject' => null,
                        'from_email' => 'foo@example.com',
                        'recipients' => [['email' => 'bar@example.com']]
                    ],
                ],
            ])
            ->willReturn($response);
        $mailer = new UniOneApiTransport('foo', 'bar', 'en', $httpClient);
        $mailer->send($email);
}
