<?php
namespace App\Tests\Controller;

use App\Service\FeedAggregator;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ViewControllerTest extends WebTestCase {
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testGetFeed()
    {
        $this->logIn();
        
        $mockFeed = $this->createPartialMock(FeedAggregator::class, ['getFeed']);
        $mockData = '<?xml version="1.0" encoding="UTF-8"?>
        <feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en">
          <id>tag:theregister.co.uk,2005:feed/theregister.co.uk/software/</id>
          <title>The Register - Software</title>
          <link rel="self" type="application/atom+xml" href="https://www.theregister.co.uk/software/headlines.atom"/>
          <link rel="alternate" type="text/html" href="https://www.theregister.co.uk/software/"/>
          <rights>Copyright © 2019, Situation Publishing</rights>
          <author>
            <name>Team Register</name>
            <email>webmaster@theregister.co.uk</email>
            <uri>https://www.theregister.co.uk/odds/about/contact/</uri>
          </author>
          <icon>https://www.theregister.co.uk/Design/graphics/icons/favicon.png</icon>
          <subtitle>Biting the hand that feeds IT — sci/tech news and views for the world</subtitle>
          <logo>https://www.theregister.co.uk/Design/graphics/Reg_default/The_Register_r.png</logo>
          <updated>2019-09-20T23:00:09Z</updated>
          <entry>
            <id>tag:theregister.co.uk,2005:story204944</id>
            <updated>2019-09-20T23:00:09Z</updated>
            <author>
              <name>Thomas Claburn</name>
              <uri>https://search.theregister.co.uk/?author=Thomas%20Claburn</uri>
            </author>
            <link rel="alternate" type="text/html" href="http://go.theregister.com/feed/www.theregister.co.uk/2019/09/20/chipotle_online_sales_bug/"/>
            <title type="html">The \'$4.4m a year\' bug: Chipotle online orders swallowed by JavaScript credit-card form blunder</title>
            <summary type="html" xml:base="http://www.theregister.co.uk/">&lt;h4&gt;Taco titan\'s e-ordering fails when browser autofill takes over&lt;/h4&gt; &lt;p&gt;Chipotle Mexican Grill has been leaving money on the table, thanks to an apparent bug in the restaurant chain\'s e-commerce operation.…&lt;/p&gt; &lt;p&gt;&lt;!--#include virtual=\'/data_centre/_whitepaper_textlinks_top.html\' --&gt;&lt;/p&gt;</summary>
          </entry>
          <entry>
            <id>tag:theregister.co.uk,2005:story204907</id>
            <updated>2019-09-19T16:40:09Z</updated>
            <author>
              <name>Tim Anderson</name>
              <uri>https://search.theregister.co.uk/?author=Tim%20Anderson</uri>
            </author>
            <link rel="alternate" type="text/html" href="http://go.theregister.com/feed/www.theregister.co.uk/2019/09/19/german_government_report_digital_sovereignty/"/>
            <title type="html">German ministry hellbent on taking back control of \'digital sovereignty\', cutting dependency on Microsoft</title>
            <summary type="html" xml:base="http://www.theregister.co.uk/">&lt;h4&gt;\'Pain points\' include data collection, lock-in and uncontrollable costs&lt;/h4&gt; &lt;p&gt;The Federal Ministry of the Interior (Bundesministerium des Innern or BMI) in Germany says it will reduce reliance on specific IT suppliers, especially Microsoft, in order to strengthen its "digital sovereignty".…&lt;/p&gt;</summary>
          </entry>
        </feed>';
        $mockFeed->expects($this->any())->method('getFeed')->willReturn($mockData);

        $container = self::$kernel->getContainer();
        $container->set('App\Service\FeedAggregator', $mockFeed);

        $this->client->xmlHttpRequest('GET', '/feed/get');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(2, $data['headlines']);
        $this->assertCount(10, $data['words']);
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';
        $firewallContext = 'main';

        $token = new UsernamePasswordToken('admin', null, $firewallName, ['ROLE_USER']);
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}