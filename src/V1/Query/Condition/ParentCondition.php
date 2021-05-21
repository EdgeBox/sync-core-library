<?php

namespace EdgeBox\SyncCore\V1\Query\Condition;

/**
 * Class ParentCondition
 * A nested condition. This condition contains one or more child conditions.
 * These are just basic logical operators.
 */
class ParentCondition extends Condition
{
    /**
     * @var string MATCH_ALL
     *             All child conditions must match
     */
    public const MATCH_ALL = 'and';

    /**
     * @var string MATCH_ANY
     *             Any child condition must match
     */
    public const MATCH_ANY = 'or';

    /**
     * @var string MATCH_NONE
     *             None of the child conditions may match
     */
    public const MATCH_NONE = 'nor';

    /**
     * DataCondition constructor.
     *
     * @param string      $operator
     * @param Condition[] $conditions
     */
    protected function __construct($operator, $conditions = [])
    {
        $this->condition['conditions'] = $conditions;

        parent::__construct($operator);
    }

    /**
     * Create an instance.
     *
     * @param string      $operator
     * @param Condition[] $conditions
     *
     * @return ParentCondition
     *
     * @throws \Exception
     */
    public static function create($operator, $conditions)
    {
        return new ParentCondition($operator, $conditions);
    }

    /**
     * @param Condition[] $conditions
     *
     * @return ParentCondition
     */
    public static function all($conditions = [])
    {
        return new ParentCondition(self::MATCH_ALL, $conditions);
    }

    /**
     * @param Condition[] $conditions
     *
     * @return ParentCondition
     */
    public static function any($conditions = [])
    {
        return new ParentCondition(self::MATCH_ANY, $conditions);
    }

    /**
     * @param Condition[] $conditions
     *
     * @return ParentCondition
     */
    public static function none($conditions = [])
    {
        return new ParentCondition(self::MATCH_NONE, $conditions);
    }

    /**
     * @param Condition $condition
     *
     * @return $this
     */
    public function add($condition)
    {
        $this->condition['conditions'][] = $condition;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $result = $this->condition;

        /**
         * @var Condition $condition
         */
        foreach ($result['conditions'] as $i => $condition) {
            $result['conditions'][$i] = $condition->toArray();
        }

        return $result;
    }
}
