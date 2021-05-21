<?php

namespace EdgeBox\SyncCore\V1\Query\Condition;

/**
 * Class DataCondition
 * A condition that's applied to an individual field of the entity.
 */
class DataCondition extends Condition
{
    /**
     * @var string IS_EQUAL_TO
     *             The field must be equal to the value
     */
    public const IS_EQUAL_TO = '==';

    /**
     * @var string IS_NOT_EQUAL_TO
     *             The field must be different to the value
     */
    public const IS_NOT_EQUAL_TO = '!=';

    /**
     * @var string IS_GREATER_THAN
     *             The field must be greater than the value
     */
    public const IS_GREATER_THAN = '>';

    /**
     * @var string IS_LESS_THAN
     *             The field must be less than the value
     */
    public const IS_LESS_THAN = '<';

    /**
     * @var string IS_GREATER_THAN_OR_EQUAL_TO
     *             The field must be greater than or equal to the value
     */
    public const IS_GREATER_THAN_OR_EQUAL_TO = '>=';

    /**
     * @var string IS_LESS_THAN_OR_EQUAL_TO
     *             The field must be less than or equal to the value
     */
    public const IS_LESS_THAN_OR_EQUAL_TO = '<=';

    /**
     * @var string IS_IN
     *             The array field must contain the value
     */
    public const IS_IN = 'in';

    /**
     * @var string IS_NOT_IN
     *             The array field must not contain the value
     */
    public const IS_NOT_IN = 'not-in';

    /**
     * @var string MATCHES_REGEX
     *             The field must match the regular expression given as value
     */
    public const MATCHES_REGEX = 'regex';

    /**
     * @var string SEARCH
     *             The field must contain the given value. Ignore capitalization.
     */
    public const SEARCH = 'search';

    /**
     * DataCondition constructor.
     *
     * @param string $field
     *                         The entity field to compare
     * @param string $operator
     *                         The operator (see constants above)
     * @param mixed  $value
     *                         The value to check against
     */
    protected function __construct($field, $operator, $value)
    {
        if (self::SEARCH === $operator) {
            $this->condition['values'] = [
                [
                    'source' => 'data',
                    'field' => $field,
                ],
                [
                    'source' => 'value',
                    'value' => preg_quote($value),
                    'flags' => 'i',
                ],
            ];

            parent::__construct(self::MATCHES_REGEX);
        } else {
            $this->condition['values'] = [
                [
                    'source' => 'data',
                    'field' => $field,
                ],
                [
                    'source' => 'value',
                    'value' => $value,
                ],
            ];

            parent::__construct($operator);
        }
    }

    /**
     * @param string                     $field
     * @param bool|int|float|string|null $value
     *
     * @return DataCondition
     */
    public static function equal($field, $value)
    {
        return new DataCondition($field, self::IS_EQUAL_TO, $value);
    }

    /**
     * @param string                     $field
     * @param bool|int|float|string|null $value
     *
     * @return DataCondition
     */
    public static function notEqual($field, $value)
    {
        return new DataCondition($field, self::IS_NOT_EQUAL_TO, $value);
    }

    /**
     * @param string    $field
     * @param int|float $value
     *
     * @return DataCondition
     */
    public static function greater($field, $value)
    {
        return new DataCondition($field, self::IS_GREATER_THAN, $value);
    }

    /**
     * @param string    $field
     * @param int|float $value
     *
     * @return DataCondition
     */
    public static function greaterOrEqual($field, $value)
    {
        return new DataCondition($field, self::IS_GREATER_THAN_OR_EQUAL_TO, $value);
    }

    /**
     * @param string    $field
     * @param int|float $value
     *
     * @return DataCondition
     */
    public static function less($field, $value)
    {
        return new DataCondition($field, self::IS_LESS_THAN, $value);
    }

    /**
     * @param string    $field
     * @param int|float $value
     *
     * @return DataCondition
     */
    public static function lessOrEqual($field, $value)
    {
        return new DataCondition($field, self::IS_LESS_THAN_OR_EQUAL_TO, $value);
    }

    /**
     * @param string $field
     * @param array  $value
     *
     * @return DataCondition
     */
    public static function in($field, $value)
    {
        return new DataCondition($field, self::IS_IN, $value);
    }

    /**
     * @param string $field
     * @param array  $value
     *
     * @return DataCondition
     */
    public static function notIn($field, $value)
    {
        return new DataCondition($field, self::IS_NOT_IN, $value);
    }

    /**
     * @param string $field
     * @param string $value
     *
     * @return DataCondition
     */
    public static function startsWith($field, $value)
    {
        return new DataCondition($field, self::MATCHES_REGEX, '^'.preg_quote($value));
    }

    /**
     * @param string $field
     * @param string $value
     *
     * @return DataCondition
     */
    public static function endsWith($field, $value)
    {
        return new DataCondition($field, self::MATCHES_REGEX, preg_quote($value).'$');
    }

    /**
     * @param string $field
     * @param string $text
     *
     * @return DataCondition
     */
    public static function search($field, $text)
    {
        return new DataCondition($field, self::SEARCH, $text);
    }
}
