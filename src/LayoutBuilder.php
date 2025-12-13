<?php

namespace Litepie\Layout;

use Litepie\Layout\Components\CustomComponent;
use Litepie\Layout\Contracts\Component;
use Litepie\Layout\Sections\LayoutSection;
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

    protected array $meta = [];

    protected array $beforeRenderCallbacks = [];

    protected array $afterRenderCallbacks = [];

    protected $authUser = null;

    public function __construct(string $name, string $mode)
    {
        $this->name = $name;
        $this->mode = $mode;
    }

    /**
     * Set the layout title
     */
    public function title(string $title): self
    {
        $this->meta['title'] = $title;

        return $this;
    }

    /**
     * Set shared data for all components
     */
    public function setSharedData(array $data): self
    {
        $this->sharedDataParams = array_merge($this->sharedDataParams, $data);

        return $this;
    }

    /**
     * Set layout metadata
     */
    public function meta(array $meta): self
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    /**
     * Alias for cacheTtl() - set cache TTL in seconds
     */
    public function ttl(int $seconds): self
    {
        return $this->cacheTtl($seconds);
    }

    /**
     * Alias for cacheKey() - set custom cache key
     */
    public function key(string $key): self
    {
        return $this->cacheKey($key);
    }

    /**
     * Alias for cacheInvalidateOn() - add cache invalidation tags
     */
    public function tags(string|array $tags): self
    {
        return $this->cacheInvalidateOn($tags);
    }

    /**
     * Register a callback to run before rendering
     */
    public function beforeRender(\Closure $callback): self
    {
        $this->beforeRenderCallbacks[] = $callback;

        return $this;
    }

    /**
     * Register a callback to run after rendering
     */
    public function afterRender(\Closure $callback): self
    {
        $this->afterRenderCallbacks[] = $callback;

        return $this;
    }

    /**
     * Set the user for authorization resolution
     */
    public function resolveAuthorization($user): self
    {
        $this->authUser = $user;

        return $this;
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
     * Create a section with optional callback configuration
     * Supports two patterns:
     * 1. section('type', 'name') - creates a section of type with name
     * 2. section('name', function($section) {...}) - creates a layout section with callback
     *
     * @param  string  $typeOrName  Section type or section name
     * @param  string|\Closure  $nameOrCallback  Section name or configuration callback
     */
    public function section(string $typeOrName, string|\Closure $nameOrCallback): self|Component
    {
        // Pattern 2: section('name', function($section) {...})
        if ($nameOrCallback instanceof \Closure) {
            $sectionName = $typeOrName;
            $callback = $nameOrCallback;
            
            // Create a LayoutSection (container for other components)
            $layoutSection = LayoutSection::make($sectionName);
            $layoutSection->parentBuilder = $this;
            
            // Create a section container for the 'body' slot (default slot for LayoutSection)
            $sectionContainer = $layoutSection->section('body');
            
            // Execute the callback with the section container
            $callback($sectionContainer);
            
            $this->addComponent($layoutSection);
            return $this;
        }
        
        // Pattern 1: section('type', 'name')
        return $this->component($typeOrName, $nameOrCallback);
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
        $layout = new Layout($this->name, $this->mode, $this->sections, $this->sharedDataUrl, $this->sharedDataParams);
        if (!empty($this->meta)) {
            $layout->meta($this->meta);
        }
        return $layout;
    }

    public function render(): array
    {
        return $this->build()->render();
    }

    public function toArray(): array
    {
        return [
            'module' => $this->name,
            'context' => $this->mode,
            'shared_data_url' => $this->sharedDataUrl,
            'shared_data_params' => $this->sharedDataParams,
            'meta' => $this->meta,
            'sections' => array_map(
                fn ($section) => method_exists($section, 'toArray') ? $section->toArray() : (array) $section,
                $this->sections
            ),
        ];
    }
}
