<?php

namespace EdgeBox\SyncCore\Interfaces\Embed;

use EdgeBox\SyncCore\V2\Raw\Model\ContentPriority;

/**
 * The figures of one page, as the box that renders them takes them.
 *
 * One named array carries both the page's identity and its figures: the
 * identity is three strings and the figures are five numbers, so no signature
 * takes them in a row. The page is named the way any content management system
 * names one — an entity type, an entity uuid and a language, each a string of
 * the site's own choosing — and this class knows no system of its own.
 *
 * **Two vocabularies, and which is which.** The named array carries the names
 * the page-figures record itself carries, in the record's own snake_case, so a
 * site hands the record over rather than copying it figure by figure. The
 * options this class emits carry the names the box reads them under, in the
 * box's own camelCase. This class is the one place in the library that knows
 * either set, and the one place that knows the limits the box holds them to.
 *
 * The box holds every value it is sent to its type and its range, and **one
 * value it refuses replaces every figure with an alert**. So an optional figure
 * this class cannot vouch for is left out rather than sent, and the box shows
 * the figures that did arrive.
 *
 * Only the names below travel. The record belongs to the site that sends it and
 * carries more than the box shows, so a name this class does not know is passed
 * over rather than objected to: the figures are rendered inside a page a reader
 * is waiting for, where raising would cost that reader the page, while the box
 * is at worst one figure short. Passing a name over is also what keeps the
 * frame's own options — its size among them — out of a caller's reach.
 */
final class PageFiguresBoxParams
{
    /**
     * The longest each part of a page's identity may be.
     *
     * @var int
     */
    public const MAX_IDENTITY_LENGTH = 255;

    /**
     * The longest a health summary may be.
     *
     * It is one sentence the site composes, so the limit is far above any
     * sentence and is there to stop a whole document arriving in a box.
     *
     * @var int
     */
    public const MAX_SUMMARY_LENGTH = 2000;

    /**
     * The longest a tag's key or its name may be.
     *
     * @var int
     */
    public const MAX_TAG_LENGTH = 255;

    /**
     * How far ahead of now the moment the figures were written may be.
     *
     * Room for a clock that runs fast, not for another unit: the stamp is in
     * whole seconds since the epoch, never milliseconds, and a stamp in
     * milliseconds lands tens of thousands of years out.
     *
     * @var int
     */
    public const MAX_SECONDS_AHEAD_OF_NOW = 365 * 24 * 60 * 60;

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
     * The page's identity: the name it is given under, and the option it becomes.
     */
    private const IDENTITY = [
        self::ENTITY_TYPE => 'entityType',
        self::ENTITY_UUID => 'entityUuid',
        self::LANGCODE => 'langcode',
    ];

    /**
     * The characters the box strips from either end before it reads a value.
     *
     * The set its own runtime strips: the ASCII blanks, the line terminators,
     * every space separator, and the two no-break spaces that are none.
     */
    private const TRIMMED = '\x{0009}-\x{000D}\x{0020}\x{00A0}\x{1680}\x{2000}-\x{200A}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}\x{FEFF}';

    private const PERCENT_MIN = 0;
    private const PERCENT_MAX = 100;

    /**
     * @var array
     */
    private $figures;

    /**
     * One named array; never a run of scalars.
     *
     * Required, each a non-empty string of at most MAX_IDENTITY_LENGTH
     * characters, surrounding whitespace trimmed:
     *   entity_type  the site's entity type machine name — any content entity type
     *   entity_uuid  the entity's id on the site, in whatever form the site has;
     *                a site whose system has no uuids passes its own id
     *   langcode     the language these figures describe
     *
     * Optional; a value outside its documented range is omitted from the
     * rendered options rather than sent, because one value the box refuses
     * costs the page every figure. A whole number is read whether the site's
     * storage hands it back as an int or as the digits of one, and the number
     * is what travels:
     *   content_health_percent_0_to_100  int 0..100
     *   content_health_summary           string of at most MAX_SUMMARY_LENGTH characters
     *   open_issue_count                 int >= 0   (0 is a value, never an absence)
     *   content_priority                 int, one of self::priorities()
     *   cited_in_answers_last_30_days    int >= 0   (0 is a value, never an absence)
     *   summary_updated                  int, whole EPOCH SECONDS — never milliseconds —
     *                                    from the epoch to at most a year ahead of now
     *   tags                             list of ['key' => string, 'name' => string],
     *                                    both halves required on an entry and each at most
     *                                    MAX_TAG_LENGTH characters; [] means none
     *
     * Every tag is passed on. The box decides how many of them it shows and
     * says how many it left out, so cutting the list here would hide from a
     * reader that the page carries more.
     *
     * A name this list does not carry is passed over, so a site hands its whole
     * record here and only what the box shows travels.
     *
     * @throws \InvalidArgumentException a required name missing, empty, not a string or too long
     */
    public function __construct(array $figures)
    {
        foreach (array_keys(self::IDENTITY) as $key) {
            $value = self::text($figures[$key] ?? null, self::MAX_IDENTITY_LENGTH);

            if (null === $value) {
                throw new \InvalidArgumentException(sprintf(
                    'The figures of a page need a %s: text of 1 to %d characters.',
                    $key,
                    self::MAX_IDENTITY_LENGTH
                ));
            }

            $figures[$key] = $value;
        }

        $this->figures = $figures;
    }

    /**
     * The whole numbers a content priority is one of.
     *
     * Read off the enum the library already ships rather than written down a
     * second time: a priority Sync Core adds or drops travels here with the
     * generated model, and the box derives its own accepted numbers from that
     * same enum. A member whose value opens with no number names no number and
     * is left out.
     *
     * @return int[]
     */
    public static function priorities()
    {
        return self::numbersOf(ContentPriority::getAllowableEnumValues());
    }

    /**
     * The number each member opens with, in the order the members come.
     *
     * A member whose value opens with no digits names no number and is left
     * out, exactly as the box leaves it out.
     *
     * @param string[] $members
     *
     * @return int[]
     */
    public static function numbersOf(array $members)
    {
        $numbers = [];

        foreach ($members as $member) {
            if (1 === preg_match('@^(\d+)@', (string) $member, $matches)) {
                $numbers[] = (int) $matches[1];
            }
        }

        return $numbers;
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

        $summary = self::text($this->figures[self::CONTENT_HEALTH_SUMMARY] ?? null, self::MAX_SUMMARY_LENGTH);
        if (null !== $summary) {
            $options['contentHealthSummary'] = $summary;
        }

        $open = self::wholeNumber($this->figures[self::OPEN_ISSUE_COUNT] ?? null);
        if (null !== $open && $open >= 0) {
            $options['openIssueCount'] = $open;
        }

        $priority = self::wholeNumber($this->figures[self::CONTENT_PRIORITY] ?? null);
        if (null !== $priority && in_array($priority, self::priorities(), true)) {
            $options['contentPriority'] = $priority;
        }

        $cited = self::wholeNumber($this->figures[self::CITED_IN_ANSWERS] ?? null);
        if (null !== $cited && $cited >= 0) {
            $options['citedInAnswersLast30Days'] = $cited;
        }

        $updated = self::wholeNumber($this->figures[self::SUMMARY_UPDATED] ?? null);
        if (null !== $updated && $updated >= 0 && $updated <= time() + self::MAX_SECONDS_AHEAD_OF_NOW) {
            $options['summaryUpdated'] = $updated;
        }

        $options['tags'] = $this->tags();

        return $options;
    }

    /**
     * The tags the page carries, each one that names itself whole.
     *
     * An entry missing either half, or carrying a half longer than the box
     * holds one to, names no tag a reader could act on, so it is dropped while
     * every other entry survives.
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

            $key = self::text($tag[self::TAG_KEY] ?? null, self::MAX_TAG_LENGTH);
            $name = self::text($tag[self::TAG_NAME] ?? null, self::MAX_TAG_LENGTH);

            if (null === $key || null === $name) {
                continue;
            }

            $tags[] = [self::TAG_KEY => $key, self::TAG_NAME => $name];
        }

        return $tags;
    }

    /**
     * A value as the text it is, or null when it is no text the box would take.
     *
     * The box strips the same characters from either end that its own runtime
     * strips, then refuses what it holds to be too long, so the value trimmed
     * that way is what travels and a longer one travels not at all.
     *
     * @param mixed $value
     */
    private static function text($value, int $max): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $text = preg_replace('/^['.self::TRIMMED.']+|['.self::TRIMMED.']+$/u', '', $value);

        if (null === $text || '' === $text) {
            return null;
        }

        $characters = self::characters($text);

        if (null === $characters || $characters > $max) {
            return null;
        }

        return $text;
    }

    /**
     * How many characters a text is, or null when it is no text at all.
     *
     * The box counts characters, a pair standing for one character counted
     * once, so that is what is counted here: the pattern's `u` modifier makes
     * PCRE walk the same characters, and PCRE is what this library already has
     * without asking a site for anything its build may not carry.
     *
     * Text that is not valid UTF-8 counts as no text: PCRE cannot walk it, and
     * the options it would sit in are JSON, which cannot carry it either.
     *
     * @return null|int
     */
    private static function characters(string $text)
    {
        $count = preg_match_all('/./us', $text);

        return false === $count ? null : $count;
    }

    /**
     * A figure as the whole number it is, or null when it is not one.
     *
     * The storage a site keeps its figures in decides the type they come back
     * as: a column of whole numbers reaches this library as an int from one
     * system and as the digits of that int from another, and both are the same
     * figure. Digits count as that number only when the number writes them back
     * exactly, which is what keeps a value too large for an int from arriving as
     * a number nobody stored.
     *
     * @param mixed $value
     */
    private static function wholeNumber($value): ?int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && (string) (int) $value === $value) {
            return (int) $value;
        }

        return null;
    }
}
