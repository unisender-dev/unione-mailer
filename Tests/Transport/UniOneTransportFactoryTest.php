<?php


namespace Symfony\Component\Mailer\Bridge\UniOne\Tests\Transport;

use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportFactoryInterface;
use Symfony\Component\Mailer\Test\TransportFactoryTestCase;
use Symfony\Component\Mailer\Bridge\UniOne\Transport\UniOneApiTransport;
use Symfony\Component\Mailer\Bridge\UniOne\Transport\UniOneTransportFactory;

class UniOneTransportFactoryTest extends TransportFactoryTestCase
{
    public function getFactory(): TransportFactoryInterface
    {
        return new UniOneTransportFactory($this->getDispatcher(), $this->getClient(), $this->getLogger());
    }

    public function supportsProvider(): iterable
    {
        yield [
            new Dsn('unione+api', 'default'),
            true,
        ];
    }

    public function createProvider(): iterable
    {
        yield [
            new Dsn('unione+api', 'default', self::USER, null, null, ['username' => 'username']),
            new UniOneApiTransport(
                self::USER,
                'username',
                'en',
                $this->getClient(),
                $this->getDispatcher(),
                $this->getLogger()
            ),
        ];
    }

    public function unsupportedSchemeProvider(): iterable
    {
        yield [
            new Dsn('unione+foo', 'default', self::USER, null, null, ['username' => 'username']),
            'The "unione+foo" scheme is not supported; supported schemes for mailer "unione" are: "unione+api".',
        ];
    }

    public function incompleteDsnProvider(): iterable
    {
        yield [new Dsn('unione+api', 'default')];
    }
}
