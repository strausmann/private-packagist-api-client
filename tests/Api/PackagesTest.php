<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class PackagesTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'acme-website/package',
            ],
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testAllWithFilters()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'acme-website/package',
            ],
        ];

        $filters = [
            'origin' => Packages::ORIGIN_PRIVATE,
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/'), $this->equalTo($filters))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all($filters));
    }

    /**
     * @expectedException \PrivatePackagist\ApiClient\Exception\InvalidArgumentException
     */
    public function testAllWithInvalidFilters()
    {
        $filters = [
            'origin' => 'invalid'
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->never())
            ->method('get');

        $api->all($filters);
    }

    public function testShow()
    {
        $expected = [
            'id' => 1,
            'name' => 'acme-website/package',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show('acme-website/package'));
    }

    public function testShowArtifacts()
    {
        $expected = [
            'name' => 'acme-website/package',
            'repoType' => 'artifact',
            'artifactFiles' => 'artifact',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/acme-website/package/artifacts'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->showArtifacts('acme-website/package'));
    }

    public function testCreateVcsPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/packages/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createVcsPackage('localhost'));
    }

    /**
     * @dataProvider customProvider
     */
    public function testCreateCustomPackage($customJson)
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/packages/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->createCustomPackage($customJson));
    }

    public function customProvider()
    {
        return [
            ['{}'],
            [new \stdClass()],
        ];
    }

    public function testEditVcsPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/packages/acme-website/package/'), $this->equalTo(['repoType' => 'vcs', 'repoUrl' => 'localhost', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editVcsPackage('acme-website/package', 'localhost'));
    }

    public function testEditCustomPackage()
    {
        $expected = [
            'id' => 'job-id',
            'status' => 'queued',
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/packages/acme-website/package/'), $this->equalTo(['repoType' => 'package', 'repoConfig' => '{}', 'credentials' => null]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->editCustomPackage('acme-website/package', '{}'));
    }

    public function testRemove()
    {
        $expected = [];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/packages/acme-website/package/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove('acme-website/package'));
    }

    public function testListPackages()
    {
        $expected = [
            [
                'package' => [
                    'name' => 'composer/composer',
                    'origin' => 'private',
                    'versionConstraint' => null,
                    'expirationDate' => null,
                ],
                'customer' => [
                    'id' => 1,
                    'name' => 'Customer',
                ],
            ]
        ];

        /** @var Customers&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/composer/composer/customers/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listCustomers('composer/composer'));
    }

    public function testListDependents()
    {
        $packageName = 'acme-website/core-package';
        $expected = [
            [
                'id' => 1,
                'name' => 'acme-website/package',
            ],
        ];

        /** @var Packages&\PHPUnit_Framework_MockObject_MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/packages/acme-website/core-package/dependents/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listDependents($packageName));
    }

    protected function getApiClass()
    {
        return Packages::class;
    }
}
