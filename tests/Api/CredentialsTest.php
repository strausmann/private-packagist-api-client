<?php

namespace PrivatePackagist\ApiClient\Api;

class CredentialsTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                'id' => 1,
                'description' => 'My secret credential',
                'domain' => 'localhost',
                'username' => 'username',
                'credential' => 'password',
                'type' => 'http-basic',
            ],
        ];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/credentials/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->all());
    }

    public function testShow()
    {
        $expected = [
            'id' => 1,
            'description' => 'My secret credential',
            'domain' => 'localhost',
            'username' => 'username',
            'credential' => 'password',
            'type' => 'http-basic',
        ];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/credentials/1/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->show(1));
    }

    public function testCreate()
    {
        $expected = [
            'id' => 1,
            'description' => 'My secret credential',
            'domain' => 'localhost',
            'username' => 'username',
            'credential' => 'password',
            'type' => 'http-basic',
        ];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/credentials/'), $this->equalTo(['domain' => 'localhost', 'description' => 'My secret credential', 'type' => 'http-basic', 'username' => 'username', 'credential' => 'password']))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->create('My secret credential', 'http-basic', 'localhost', 'username', 'password'));
    }

    public function testUpdate()
    {
        $expected = [
            'id' => 1,
            'description' => 'My secret credential',
            'domain' => 'localhost',
            'username' => 'username',
            'credential' => 'password',
            'type' => 'http-basic',
        ];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/credentials/1/'), $this->equalTo(['type' => 'http-basic', 'username' => 'username', 'credential' => 'password']))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->update(1, 'http-basic', 'username', 'password'));
    }

    public function testRemove()
    {
        $expected = [];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/credentials/1/'))
            ->will($this->returnValue($expected));

        $this->assertSame($expected, $api->remove(1));
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Credentials::class;
    }
}