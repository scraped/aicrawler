<?php namespace FinalProject\Heuristics;

use FinalProject\Support\Articrawler;
use FinalProject\Support\Considerations;

/**
 * Some general rules for our Heuristics to abide by.
 *
 * @package FinalProject\Heuristics
 */
interface HeuristicInterface {

    /**
     * Drop an nodes that that will jumble our data abstraction. Merge singular nodes with others were beneficial.
     *
     * @param Articrawler $top
     * @return Articrawler
     */
    public static function prep(Articrawler $top);

    /**
     * Score your nodes.
     *
     * @param Articrawler $node
     * @param Considerations $c
     * @return Considerations
     */
    public static function score(Articrawler &$node, Considerations $c);

    /**
     * Last change to examine all the considerations that were scored and return one.
     *
     * @param Considerations $c
     * @return mixed|null
     */
    public static function choose(Considerations $c);

    /**
     * Last change to examine all the considerations that were scored and return a collection.
     *
     * @param Considerations $c
     * @return Considerations
     */
    public static function chooseMany(Considerations $c);

}