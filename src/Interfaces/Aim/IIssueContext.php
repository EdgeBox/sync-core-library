<?php

namespace EdgeBox\SyncCore\Interfaces\Aim;

/**
 * One issue carried on an optimize-content trigger.
 */
interface IIssueContext
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string the issue-type tag's machine key
     */
    public function getTypeKey();

    /**
     * @return string the tag's display name
     */
    public function getTypeName();

    /**
     * @return null|string one of 400-critical, 300-high, 200-medium, 100-low
     */
    public function getPriority();

    /**
     * @return null|string the tag's customer-facing instruction, written to be read by a person or a site's AI
     */
    public function getInstruction();

    /**
     * @return null|string
     */
    public function getEvidence();
}
