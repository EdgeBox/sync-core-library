<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

use EdgeBox\SyncCore\V2\Raw\Model\ContentRecommendationKind;

/**
 * One recommendation carried on an optimize-content trigger.
 *
 * Every kind of recommendation arrives in this one shape: the kind says what
 * the recommendation asks the site for and the parts say what it holds. So a
 * site that meets a kind this library has no name for still shows the entry
 * from its parts, rather than reading it as a kind it does know.
 *
 * A part the trigger carries on every recommendation is read as an empty value
 * of its own type when a body arrives without it — an empty string, a zero, a
 * reference with neither key nor name. A recommendation is rendered inside a
 * page a reader is waiting for, where raising would cost that reader the page,
 * while a site showing what did arrive is at worst one part short.
 */
interface IRecommendationContext
{
    /**
     * @return string the id the optimization names the recommendation by
     */
    public function getId();

    /**
     * What the recommendation asks the site for.
     *
     * The set of kinds grows with the Sync Core, so a kind this library has no
     * name for is handed on as it stands rather than read as a kind it does
     * know.
     *
     * @see ContentRecommendationKind for the kinds this library has names for
     *
     * @return string
     */
    public function getKind();

    /**
     * @return INamedReference the locale the wording is written for: the page's own
     */
    public function getLocale();

    /**
     * @return IAuthoredText the question a FAQ answers, or the working title, depending on the kind
     */
    public function getTitle();

    /**
     * @return null|IAuthoredText an example answer or body to verify and adapt, never to publish as it arrives
     */
    public function getContent();

    /**
     * @return null|IAuthoredText the stance to write on: what to claim, what to lead with, what to leave out
     */
    public function getBrief();

    /**
     * Where this page stands on the recommendation.
     *
     * Absent when the page is not among the recommendation's targets, and when
     * the sender left it off to fit the size a site accepts; the two arrive
     * alike.
     *
     * @return null|IRecommendationTarget
     */
    public function getTarget();

    /**
     * Why the recommendation exists.
     *
     * The sender sheds it before anything else it carries, so this is absent
     * both when there is none and when it was shed; the two arrive alike.
     *
     * @return null|IRecommendationEvidence
     */
    public function getEvidence();
}
