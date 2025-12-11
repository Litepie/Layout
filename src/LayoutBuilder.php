<?php

namespace Litepie\Layout;

use Litepie\Layout\Components\CustomSection;
use Litepie\Layout\Contracts\Component;
use Litepie\Layout\Traits\Cacheable;
use Litepie\Layout\Traits\Debuggable;
use Litepie\Layout\Traits\Exportable;
use Litepie\Layout\Traits\HandlesComputedFields;
use Litepie\Layout\Traits\Testable;

class LayoutBuilder
{
    use Cacheable, Debuggable, Exportable, HandlesComputedFields, Testable;

    protected string $name;

    protected string $mode;

    protected array $sections = [];

    protected ?string $sharedDataUrl = null; // Single API endpoint for all components

    protected array $sharedDataParams = [];

    public function __construct(string $name, string $mode)
    {
        $this->name = $name;
        $this->mode = $mode;
    }

    /**
     * Set a shared data URL for all components to use
     */
    public function sharedDataUrl(string $url): self
    {
        $this->sharedDataUrl = $url;

        return $this;
    }

    /**
     * Set shared data parameters
     */
    public function sharedDataParams(array $params): self
    {
        $this->sharedDataParams = array_merge($this->sharedDataParams, $params);

        return $this;
    }

    public static function create(string $module, string $context): self
    {
        return new static($module, $context);
    }

    /**
     * Add a component to the layout
     */
    public function addComponent(Component $component): self
    {
        if (method_exists($component, 'getName')) {
            $this->sections[$component->getName()] = $component;
        } else {
            $this->sections[] = $component;
        }

        return $this;
    }

    /**
     * Create and add any section type dynamically
     *
     * @param  string  $type  Section type (alert, modal, card, table, etc.)
     * @param  string  $name  Section name/identifier
     */
    public function section(string $type, string $name): Component
    {
        $className = 'Litepie\\Layout\\Components\\'.ucfirst($type).'Section';

        if (! class_exists($className)) {
            // Fallback to CustomSection if type not found
            $section = CustomSection::make($name, $type);
        } else {
            $section = $className::make($name);
        }

        $section->parentBuilder = $this;
        $this->addComponent($section);

        return $section;
    }

    /**
     * Legacy support: Create a Section (old structure)
     *
     * @deprecated Use section($type, $name) instead
     */
    public function legacySection(string $name): Section
    {
        $section = new Section($name, $this);
        $this->sections[$name] = $section;

        return $section;
    }

    /**
     * Legacy support: Add a Section
     *
     * @deprecated Use addComponent() instead
     */
    public function addSection(Component $section): self
    {
        $this->sections[$section->getName()] = $section;

        return $this;
    }

    public function getModule(): string
    {
        return $this->name;
    }

    public function getContext(): string
    {
        return $this->mode;
    }

    /**
     * Get all components
     */
    public function getComponents(): array
    {
        return $this->sections;
    }

    /**
     * Legacy support: Get sections
     *
     * @deprecated Use getComponents() instead
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * Get component by name
     */
    public function getComponent(string $name): ?Component
    {
        return $this->sections[$name] ?? null;
    }

    /**
     * Legacy support: Get section
     *
     * @deprecated Use getComponent() instead
     */
    public function getSection(string $name): mixed
    {
        return $this->getComponent($name);
    }

    public function build(): Layout
    {
        return new Layout($this->name, $this->mode, $this->sections, $this->sharedDataUrl, $this->sharedDataParams);
    }

    public function toArray(): array
    {
        return [
            'module' => $this->name,
            'context' => $this->mode,
            'shared_data_url' => $this->sharedDataUrl,
            'shared_data_params' => $this->sharedDataParams,
            'sections' => array_map(
                fn ($section) => method_exists($section, 'toArray') ? $section->toArray() : (array) $section,
                $this->sections
            ),
        ];
    }
}
