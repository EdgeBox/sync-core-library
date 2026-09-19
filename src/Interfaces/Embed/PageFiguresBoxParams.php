<?php

namespace EdgeBox\SyncCore\Interfaces\Embed;

/**
 * The figures of one page, as the box that renders them takes them.
 *
 * One named array carries both the page's identity and its figures: the
 * identity is three strings and the figures are five numbers, so no signature
 * takes them in a row. The page is named the way any content management system
 * names one — an entity type, an entity uuid and a language — and this class
 * knows no system of its own.
 *
 * An optional figure whose value is outside the range the box accepts is left
 * out of the rendered options rather than sent, so the box shows the figures it
 * did receive instead of refusing the page. A key this class does not name is
 * ignored, which is what keeps the frame's own options out of a caller's reach.
 *
 * It is the one place in this library that knows the names the box reads the
 * figures under.
 */
final class PageFiguresBoxParams
{
    /**
     * The lowest content priority.
     *
     * A priority travels as its number so it sorts and compares; the words are
     * made from the number where a person reads them, which is the box.
     *
     * @var int
     */
    public const PRIORITY_LOW = 100;

    /**
     * A medium content priority.
     *
     * @var int
     */
    public const PRIORITY_MEDIUM = 200;

    /**
     * A high content priority.
     *
     * @var int
     */
    public const PRIORITY_HIGH = 300;

    /**
     * The highest content priority.
     *
     * @var int
     */
    public const PRIORITY_CRITICAL = 400;

    private const ENTITY_TYPE = 'entity_type';
    private const ENTITY_UUID = 'entity_uuid';
    private const LANGCODE = 'langcode';

    private const CONTENT_HEALTH_PERCENT = 'content_health_percent_0_to_100';
    private const CONTENT_HEALTH_SUMMARY = 'content_health_summary';
    private const OPEN_ISSUE_COUNT = 'open_issue_count';
    private const CONTENT_PRIORITY = 'content_priority';
    private const CITED_IN_ANSWERS = 'cited_in_answers_last_30_days';
    private const SUMMARY_UPDATED = 'summary_updated';
    private const TAGS = 'tags';

    private const TAG_KEY = 'key';
    private const TAG_NAME = 'name';

    /**
     * The page's identity: the key it is given under, and the option it becomes.
     */
    private const IDENTITY = [
        self::ENTITY_TYPE => 'entityType',
        self::ENTITY_UUID => 'entityUuid',
        self::LANGCODE => 'langcode',
    ];

    /**
     * The whole numbers a content priority is one of.
     */
    private const PRIORITIES = [
        self::PRIORITY_LOW,
        self::PRIORITY_MEDIUM,
        self::PRIORITY_HIGH,
        self::PRIORITY_CRITICAL,
    ];

    private const PERCENT_MIN = 0;
    private const PERCENT_MAX = 100;

    /**
     * @var array
     */
    private $figures;

    /**
     * One named array; never a run of scalars.
     *
     * Required, each a non-empty string:
     *   entity_type  the site's entity type machine name — any content entity type
     *   entity_uuid  the entity's uuid on the site
     *   langcode     the language these figures describe
     *
     * Optional; a value outside its documented range is omitted from the
     * rendered options rather than sent, so the box degrades to showing the
     * figures it did receive. A whole number is read whether the site's storage
     * hands it back as an int or as the digits of one, and the number is what
     * travels:
     *   content_health_percent_0_to_100  int 0..100
     *   content_health_summary           string
     *   open_issue_count                 int >= 0   (0 is a value, never an absence)
     *   content_priority                 int, one of the PRIORITY_ constants
     *   cited_in_answers_last_30_days    int >= 0   (0 is a value, never an absence)
     *   summary_updated                  int, epoch seconds
     *   tags                             list of ['key' => string, 'name' => string],
     *                                    both halves required on an entry; [] means none
     *
     * A key this list does not name is ignored, so a caller cannot reach the
     * frame's own options — embedSize among them.
     *
     * @throws \InvalidArgumentException a required key missing, empty, or not a string
     */
    public function __construct(array $figures)
    {
        foreach (array_keys(self::IDENTITY) as $key) {
            $value = $figures[$key] ?? null;

            if (!is_string($value) || '' === $value) {
                throw new \InvalidArgumentException(sprintf('The figures of a page need a non-empty %s.', $key));
            }
        }

        $this->figures = $figures;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->figures[self::ENTITY_TYPE];
    }

    /**
     * @return string
     */
    public function getEntityUuid()
    {
        return $this->figures[self::ENTITY_UUID];
    }

    /**
     * @return string
     */
    public function getLangcode()
    {
        return $this->figures[self::LANGCODE];
    }

    /**
     * The embed options, camelCased, with every unusable optional value left out:
     * entityType, entityUuid, langcode, contentHealthPercent, contentHealthSummary,
     * openIssueCount, contentPriority, citedInAnswersLast30Days, summaryUpdated, tags.
     *
     * @return array
     */
    public function toOptions()
    {
        $options = [];

        foreach (self::IDENTITY as $key => $option) {
            $options[$option] = $this->figures[$key];
        }

        $percent = self::wholeNumber($this->figures[self::CONTENT_HEALTH_PERCENT] ?? null);
        if (null !== $percent && $percent >= self::PERCENT_MIN && $percent <= self::PERCENT_MAX) {
            $options['contentHealthPercent'] = $percent;
        }

        $summary = $this->figures[self::CONTENT_HEALTH_SUMMARY] ?? null;
        if (is_string($summary) && '' !== $summary) {
            $options['contentHealthSummary'] = $summary;
        }

        $open = self::wholeNumber($this->figures[self::OPEN_ISSUE_COUNT] ?? null);
        if (null !== $open && $open >= 0) {
            $options['openIssueCount'] = $open;
        }

        $priority = self::wholeNumber($this->figures[self::CONTENT_PRIORITY] ?? null);
        if (in_array($priority, self::PRIORITIES, true)) {
            $options['contentPriority'] = $priority;
        }

        $cited = self::wholeNumber($this->figures[self::CITED_IN_ANSWERS] ?? null);
        if (null !== $cited && $cited >= 0) {
            $options['citedInAnswersLast30Days'] = $cited;
        }

        $updated = self::wholeNumber($this->figures[self::SUMMARY_UPDATED] ?? null);
        if (null !== $updated) {
            $options['summaryUpdated'] = $updated;
        }

        $options['tags'] = $this->tags();

        return $options;
    }

    /**
     * The tags the page carries, each one that names itself whole.
     *
     * An entry missing either half names no tag a reader could act on, so it is
     * dropped while every other entry survives.
     *
     * @return array[]
     */
    private function tags(): array
    {
        $given = $this->figures[self::TAGS] ?? [];

        if (!is_array($given)) {
            return [];
        }

        $tags = [];

        foreach ($given as $tag) {
            if (!is_array($tag)) {
                continue;
            }

            $key = $tag[self::TAG_KEY] ?? null;
            $name = $tag[self::TAG_NAME] ?? null;

            if (!is_string($key) || '' === $key || !is_string($name) || '' === $name) {
                continue;
            }

            $tags[] = [self::TAG_KEY => $key, self::TAG_NAME => $name];
        }

        return $tags;
    }

    /**
     * A figure as the whole number it is, or null when it is not one.
     *
     * The storage a site keeps its figures in decides the type they come back
     * as: a column of whole numbers reaches this library as an int from one
     * system and as the digits of that int from another, and both are the same
     * figure. Both are therefore read, and the number is what travels; a value
     * that is neither is no figure this library can send.
     *
     * @param mixed $value
     */
    private static function wholeNumber($value): ?int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && 1 === preg_match('@^-?\d+$@', $value)) {
            return (int) $value;
        }

        return null;
    }
}
