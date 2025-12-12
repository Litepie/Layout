<?php

namespace Litepie\Layout;

use Litepie\Layout\Components\CustomComponent;
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
     * Create and add any component type dynamically
     * Supports both Sections (containers) and Components (content)
     *
     * @param  string  $type  Component type (header, layout, grid, form, card, table, etc.)
     * @param  string  $name  Component name/identifier
     */
    public function component(string $type, string $name): Component
    {
        // Try Section suffix first (containers: Header, Layout, Grid, Tabs, Accordion)
        $sectionClass = 'Litepie\\Layout\\Sections\\'.ucfirst($type).'Section';
        if (class_exists($sectionClass)) {
            $component = $sectionClass::make($name);
            $component->parentBuilder = $this;
            $this->addComponent($component);

            return $component;
        }

        // Try Component suffix (content: Form, Card, Table, List, etc.)
        $componentClass = 'Litepie\\Layout\\Components\\'.ucfirst($type).'Component';
        if (class_exists($componentClass)) {
            $component = $componentClass::make($name);
            $component->parentBuilder = $this;
            $this->addComponent($component);

            return $component;
        }

        // Fallback to CustomComponent for unknown types
        $component = \Litepie\Layout\Components\CustomComponent::make($name, $type);
        $component->parentBuilder = $this;
        $this->addComponent($component);

        return $component;
    }

    /**
     * Alias for component() method
     * Legacy support for existing code
     *
     * @param  string  $type  Section type (alert, modal, card, table, etc.)
     * @param  string  $name  Section name/identifier
     */
    public function section(string $type, string $name): Component
    {
        return $this->component($type, $name);
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
