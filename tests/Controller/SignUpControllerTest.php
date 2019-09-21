<?php
namespace App\Tests\Controller;

use App\Service\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SignUpControllerTest extends WebTestCase {
    public function testValidateEmail()
    {
        $client = static::createClient();

        $mockRepository = $this->createMock(UserRepository::class);
        $mockRepository->expects($this->any())->method('getByEmail')->with($this->anything())->willReturn(null);
        
        $container = self::$kernel->getContainer();
        $container->set('App\Service\UserRepository', $mockRepository);

        $client->xmlHttpRequest('GET', '/user/validate?email=test@test.com');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(true, json_decode($client->getResponse()->getContent()));

        $client = static::createClient();
        
        $container->set('App\Service\UserRepository', $mockRepository);
        $client->xmlHttpRequest('GET', '/user/validate?email=test');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testSignUp()
    {
        $client = static::createClient();

        $mockRepository = $this->createMock(UserRepository::class);
        $mockRepository->expects($this->any())->method('getByEmail')->with($this->anything())->willReturn(null);
        $mockRepository->expects($this->any())->method('save')->with($this->anything())->willReturn(true);

        $container = self::$kernel->getContainer();
        $container->set('App\Service\UserRepository', $mockRepository);

        $client->Request('POST', '/user/signup',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
             json_encode([
                'email' => 'test@test.com',
                'password' => [
                    'first' => 'securePassword123', 
                    'second' => 'securePassword123'
                ]
            ]));


        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals(['type' => 'success', 'message' => 'User saved'], json_decode($client->getResponse()->getContent(), true));
    }
}