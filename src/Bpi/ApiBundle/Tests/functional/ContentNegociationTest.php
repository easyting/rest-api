<?php

namespace Bpi\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContentNegociationTest extends WebTestCase
{
	public function doRequest($http_accept, $file_extension)
	{
		$client = static::createClient();
		
		$accept = $http_accept ? array('HTTP_ACCEPT' => $http_accept) : array();
		
		$client->request(
			'GET', 
			'/node/list'.$file_extension,
			array(),
			array(),
			$accept
		);
		return $client;
	}
	
	public function testAcceptBpiMediaType()
	{
		$bpi_media_type = 'application/vnd.bpi.api+xml';
		$client = $this->doRequest($bpi_media_type, ".bpi");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertTrue($client->getResponse()->headers->contains('Content-Type', $bpi_media_type));
	}
	
	public function testFallbackOnBpiMediaTypeExtensionWithoutAcceptContentType()
	{
		$client = $this->doRequest(null, ".bpi");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/xml'));
	}
	
	public function testAcceptBpiMediaTypeByClientContentType()
	{
		$bpi_media_type = 'application/vnd.bpi.api+xml';
		$client = $this->doRequest($bpi_media_type, null);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertTrue($client->getResponse()->headers->contains('Content-Type', $bpi_media_type));
	}
	
	public function testJsonMediaType()
	{
		$client = $this->doRequest(null, '.json');
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
	}
	
	public function testXmlMediaType()
	{
		$client = $this->doRequest(null, '.xml');
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/xml'));
	}
}