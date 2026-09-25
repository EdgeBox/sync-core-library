<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

/**
 * One reading of the page behind the state its target names.
 */
interface ITargetEvidenceItem
{
    /**
     * @return string the part of the page that was read
     */
    public function getPath();

    /**
     * @return mixed what that part held, in whatever form it held it
     */
    public function getValue();

    /**
     * @return bool true when the value above is only the beginning of what that part held
     */
    public function wasTruncated();

    /**
     * @return null|string why that reading puts the page at the state it does
     */
    public function getReasoning();
}
