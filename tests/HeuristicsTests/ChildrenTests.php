<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

/**
 * Class ChildrenTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class ChildrenTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_has_no_args_specified()
    {
        $args['on'] = 'children';
        $node = $this->crawler->filter('div[class="entry-content"]')->first();
        $this->assertTrue(Heuristics::children($node, []));

        $node = $this->crawler->filter('div[id="content_start"]')->first();
        $this->assertFalse(Heuristics::children($node, []));
    }

}