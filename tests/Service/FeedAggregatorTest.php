<?php
namespace App\Tests\Service;

use App\Service\FeedAggregator;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class FeedAggregatorTest extends TestCase {
    public function testParseFeed()
    {
        $aggregator = new FeedAggregator();

        $feed = '<?xml version="1.0" encoding="UTF-8"?>
            <feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en">
            <id>tag:theregister.co.uk,2005:feed/theregister.co.uk/software/</id>
            <title>The Register - Software</title>
            <link rel="self" type="application/atom+xml" href="https://www.theregister.co.uk/software/headlines.atom" />
            <link rel="alternate" type="text/html" href="https://www.theregister.co.uk/software/" />
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
                <link rel="alternate" type="text/html" href="http://go.theregister.com/feed/www.theregister.co.uk/2019/09/20/chipotle_online_sales_bug/" />
                <title type="html">The \'$4.4m a year\' bug: Chipotle online orders swallowed by JavaScript credit-card form blunder</title>
                <summary type="html" xml:base="http://www.theregister.co.uk/">&lt;h4&gt;Taco titan\'s e-ordering fails when browser autofill takes over&lt;/h4&gt; &lt;p&gt;Chipotle Mexican Grill has been leaving money on the table, thanks to an apparent bug in the restaurant chain\'s e-commerce operation.…&lt;/p&gt; &lt;p&gt;&lt;!--#include virtual=\'/data_centre/_whitepaper_textlinks_top.html\' --&gt;&lt;/p&gt;</summary>
            </entry>
            </feed>
        ';

        $headlines = $aggregator->parseFeed($feed);

        $this->assertCount(1, $headlines);
        $this->assertEquals('The \'$4.4m a year\' bug: Chipotle online orders swallowed by JavaScript credit-card form blunder', $headlines[0]->getTitle());
        $this->assertEquals('http://go.theregister.com/feed/www.theregister.co.uk/2019/09/20/chipotle_online_sales_bug/', $headlines[0]->getLink());
        $this->assertEquals(\html_entity_decode('&lt;h4&gt;Taco titan\'s e-ordering fails when browser autofill takes over&lt;/h4&gt; &lt;p&gt;Chipotle Mexican Grill has been leaving money on the table, thanks to an apparent bug in the restaurant chain\'s e-commerce operation.…&lt;/p&gt; &lt;p&gt;&lt;!--#include virtual=\'/data_centre/_whitepaper_textlinks_top.html\' --&gt;&lt;/p&gt;'), $headlines[0]->getSummary());
    }
}