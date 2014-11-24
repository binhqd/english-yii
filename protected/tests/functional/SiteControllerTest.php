<?php
class SiteControllerTest extends WUnitTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
 
        $crawler = $client->request('GET', 'http://www.google.com.vn');
			
		debug($crawler);
        //$this->assertTrue($crawler->filter('html:contains("Congratulations!")')->count() > 0);
    }
}
