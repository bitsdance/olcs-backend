<?php

/**
 * Expression Builder
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Db\Utility;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Expression Builder
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class ExpressionBuilder
{
    protected $qb;

    protected $em;

    protected $entity;

    protected $params = array();

    public function setQueryBuilder(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function buildWhereExpression($query)
    {
        if (empty($query)) {
            return null;
        }

        $this->params = array();

        $queries = array();

        foreach ($query as $field => $value) {
            $queries[] = $this->buildExpression($field, $value);
        }

        $expression = $this->qb->expr();

        return call_user_func_array(array($expression, 'andX'), $queries);
    }

    private function buildExpression($field, $values, $or = true)
    {
        if (is_array($values)) {
            $queries = array();

            foreach ($values as $value) {
                $queries[] = $this->buildExpression($field, $value, !$or);
            }

            return call_user_func_array(array($this->qb->expr(), $or ? 'orX' : 'andX'), $queries);
        }

        $field = 'a.' . $field;

        if ($values === 'NULL') {
            return $this->qb->expr()->isNull($field);
        }

        if (substr($values, 0, 4) == 'IN [') {
            $values = json_decode(substr($values, 3));
            return $this->qb->expr()->in($field, $values);
        }

        if (is_numeric($values) || $this->isFieldForeignKey($field)) {
            $paramIndex = $this->getNextParamIndex();
            $this->params[$paramIndex] = $values;
            return $this->qb->expr()->eq($field, '?' . $paramIndex);
        }

        list($operator, $values) = $this->getOperator($values);

        $paramIndex = $this->getNextParamIndex();
        $this->params[$paramIndex] = $values;

        $exp = $this->qb->expr();

        switch ($operator) {
            case '<':
                return $exp->lt($field, '?' . $paramIndex);
            case '<=':
                return $exp->lte($field, '?' . $paramIndex);
            case '>=':
                return $exp->gte($field, '?' . $paramIndex);
            case '>':
                return $exp->gt($field, '?' . $paramIndex);
            case '~':
                return $exp->like($field, '?' . $paramIndex);
            case '!~':
                return $exp->notLike($field, '?' . $paramIndex);
            case '!=':
                return $exp->neq($field, '?' . $paramIndex);
            default:
                return $exp->eq($field, '?' . $paramIndex);
        }
    }

    private function getNextParamIndex()
    {
        return count($this->params);
    }

    private function isFieldForeignKey($field)
    {
        $metaData = (array)$this->em->getClassMetadata($this->entity);

        return isset($metaData['associationMappings'][$field]);
    }

    private function getOperator($value)
    {
        if (preg_match('/^(<=|=|<|~|\!~|\!=|>=|>)(\s*)(.+)$/', $value, $matches)) {
            $operator = $matches[1];
            $value = $matches[3];
        } else {
            $operator = '=';
        }

        return array($operator, $value);
    }
}
