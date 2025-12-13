<?php

namespace Litepie\Layout\Sections;

class WizardSection extends BaseSection
{
    protected array $steps = [];

    protected int $currentStep = 0;

    protected bool $linear = true; // Must complete steps in order

    protected bool $showStepNumbers = true;

    protected string $orientation = 'horizontal'; // horizontal, vertical

    protected bool $validateOnNext = true;

    // Wizard uses dynamic section slots (step_1, step_2, etc.)
    // allowedSections is empty to allow dynamic step sections

    public function __construct(string $name)
    {
        parent::__construct($name, 'wizard');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * Add a step configuration
     * Supports two patterns:
     * 1. addStep($key, $label, $sections, $options) - array of sections
     * 2. addStep($key, $label, function($step) {...}) - callback to configure step
     */
    public function addStep(string $key, string $label, array|\Closure $sectionsOrCallback = [], array $options = []): self
    {
        // Pattern 2: Callback configuration
        if ($sectionsOrCallback instanceof \Closure) {
            $callback = $sectionsOrCallback;

            // Create a section container for this step
            $stepContainer = new \Litepie\Layout\SectionContainer($key, $this);

            // Execute the callback to configure the step
            $callback($stepContainer);

            // Get all components added to the step container
            $sections = $stepContainer->getComponents();

            $this->steps[] = [
                'key' => $key,
                'label' => $label,
                'description' => $options['description'] ?? null,
                'icon' => $options['icon'] ?? null,
                'sections' => $sections,
                'optional' => $options['optional'] ?? false,
                'validation' => $options['validation'] ?? null,
            ];

            return $this;
        }

        // Pattern 1: Array of sections
        $this->steps[] = [
            'key' => $key,
            'label' => $label,
            'description' => $options['description'] ?? null,
            'icon' => $options['icon'] ?? null,
            'sections' => $sectionsOrCallback,
            'optional' => $options['optional'] ?? false,
            'validation' => $options['validation'] ?? null,
        ];

        return $this;
    }

    public function currentStep(string|int $step): self
    {
        if (is_string($step)) {
            // Find the step index by key
            foreach ($this->steps as $index => $stepData) {
                if ($stepData['key'] === $step) {
                    $this->currentStep = $index;

                    return $this;
                }
            }
            // If not found, default to 0
            $this->currentStep = 0;
        } else {
            $this->currentStep = $step;
        }

        return $this;
    }

    public function linear(bool $linear = true): self
    {
        $this->linear = $linear;

        return $this;
    }

    public function showStepNumbers(bool $show = true): self
    {
        $this->showStepNumbers = $show;

        return $this;
    }

    public function orientation(string $orientation): self
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function vertical(): self
    {
        return $this->orientation('vertical');
    }

    public function validateOnNext(bool $validate = true): self
    {
        $this->validateOnNext = $validate;

        return $this;
    }

    public function toArray(): array
    {
        // Serialize steps with their components
        $serializedSteps = array_map(function ($step) {
            return [
                'key' => $step['key'],
                'label' => $step['label'],
                'description' => $step['description'],
                'icon' => $step['icon'],
                'components' => array_map(
                    fn ($comp) => (is_object($comp) && method_exists($comp, 'toArray')) ? $comp->toArray() : (array) $comp,
                    $step['sections'] ?? []
                ),
                'optional' => $step['optional'],
                'validation' => $step['validation'],
            ];
        }, $this->steps);

        return [
            'type' => $this->type,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'steps' => $serializedSteps,
            'current_step' => $this->currentStep,
            'linear' => $this->linear,
            'show_step_numbers' => $this->showStepNumbers,
            'orientation' => $this->orientation,
            'validate_on_next' => $this->validateOnNext,
            'data_source' => $this->dataSource,
            'data_url' => $this->dataUrl,
            'data_params' => $this->dataParams,
            'data_transform' => $this->dataTransform,
            'load_on_mount' => $this->loadOnMount,
            'reload_on_change' => $this->reloadOnChange,
            'use_shared_data' => $this->useSharedData,
            'data_key' => $this->dataKey,
            'actions' => $this->actions,
            'sections' => array_map(
                fn ($comp) => (is_object($comp) && method_exists($comp, 'toArray')) ? $comp->toArray() : (array) $comp,
                $this->sections
            ),
            'order' => $this->order,
            'visible' => $this->visible,
            'permissions' => $this->permissions,
            'roles' => $this->roles,
            'authorized_to_see' => $this->authorizedToSee,
            'meta' => $this->meta,
        ];
    }
}
