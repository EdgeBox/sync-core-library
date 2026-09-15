<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

/**
 * The subset of a taxonomy term a site reads by key.
 */
interface ITaxonomyTermSummary
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return null|string
     */
    public function getName();

    /**
     * @return null|string one of 400-critical, 300-high, 200-medium, 100-low
     */
    public function getPriority();

    /**
     * @return null|string the customer-facing instruction
     */
    public function getInstruction();
}
