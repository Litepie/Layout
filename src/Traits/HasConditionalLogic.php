<?php

namespace Litepie\Layout\Traits;

use Litepie\Layout\Conditional\ExpressionEvaluator;

trait HasConditionalLogic
{
    protected array $showWhenConditions = [];

    protected array $hideWhenConditions = [];

    protected array $enableWhenConditions = [];

    protected string $conditionLogic = 'AND'; // AND or OR

    protected bool $enabled = true;

    /**
     * Show component when condition is met
     *
     * Usage:
     * ->showWhen('user.role', '==', 'admin')
     * ->showWhen('user.role == admin') // string expression
     * ->showWhen(['field' => 'user.role', 'operator' => '==', 'value' => 'admin']) // array
     */
    public function showWhen(string|array $field, ?string $operator = null, mixed $value = null): self
    {
        $this->showWhenConditions[] = $this->normalizeCondition($field, $operator, $value);

        return $this;
    }

    /**
     * Hide component when condition is met
     */
    public function hideWhen(string|array $field, ?string $operator = null, mixed $value = null): self
    {
        $this->hideWhenConditions[] = $this->normalizeCondition($field, $operator, $value);

        return $this;
    }

    /**
     * Enable component when condition is met
     */
    public function enableWhen(string|array $field, ?string $operator = null, mixed $value = null): self
    {
        $this->enableWhenConditions[] = $this->normalizeCondition($field, $operator, $value);

        return $this;
    }

    /**
     * Set condition logic (AND/OR)
     */
    public function conditionLogic(string $logic): self
    {
        $this->conditionLogic = strtoupper($logic);

        return $this;
    }

    /**
     * Evaluate all conditions against context
     */
    public function evaluateConditions(array $context): void
    {
        $evaluator = new ExpressionEvaluator;

        // Evaluate show conditions
        if (! empty($this->showWhenConditions)) {
            $this->visible = $evaluator->evaluateMultiple(
                $this->showWhenConditions,
                $context,
                $this->conditionLogic
            );
        }

        // Evaluate hide conditions
        if (! empty($this->hideWhenConditions)) {
            $shouldHide = $evaluator->evaluateMultiple(
                $this->hideWhenConditions,
                $context,
                $this->conditionLogic
            );

            if ($shouldHide) {
                $this->visible = false;
            }
        }

        // Evaluate enable conditions
        if (! empty($this->enableWhenConditions)) {
            $this->enabled = $evaluator->evaluateMultiple(
                $this->enableWhenConditions,
                $context,
                $this->conditionLogic
            );
        }
    }

    /**
     * Normalize condition to array format
     */
    protected function normalizeCondition(string|array $field, ?string $operator = null, mixed $value = null): array
    {
        // If already an array, return as-is
        if (is_array($field)) {
            return $field;
        }

        // If it's a string expression, parse it
        if ($operator === null && $value === null) {
            $evaluator = new ExpressionEvaluator;
            $parsed = $evaluator->parseExpression($field);

            if ($parsed) {
                return $parsed;
            }
        }

        // Otherwise, build from parameters
        return [
            'field' => $field,
            'operator' => $operator ?? '==',
            'value' => $value,
        ];
    }

    /**
     * Add conditional logic to array output
     */
    protected function addConditionalToArray(array $array): array
    {
        if (! empty($this->showWhenConditions)) {
            $array['show_when'] = $this->showWhenConditions;
        }

        if (! empty($this->hideWhenConditions)) {
            $array['hide_when'] = $this->hideWhenConditions;
        }

        if (! empty($this->enableWhenConditions)) {
            $array['enable_when'] = $this->enableWhenConditions;
        }

        if (! empty($this->showWhenConditions) || ! empty($this->hideWhenConditions) || ! empty($this->enableWhenConditions)) {
            $array['condition_logic'] = $this->conditionLogic;
        }

        $array['enabled'] = $this->enabled;

        return $array;
    }

    /**
     * Check if component is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
