<?php

namespace Litepie\Layout;

use Litepie\Layout\Contracts\Component;

/**
 * SectionContainer
 *
 * Represents a named section slot within a component that can hold multiple components.
 * Provides fluent API for adding components to specific areas of a parent component.
 *
 * Example:
 *   $header->section('left')->add($logo)->add($menu);
 */
class SectionContainer
{
    protected string $name;

    protected Component $parent;

    protected array $components = [];

    protected array $meta = [];

    public function __construct(string $name, Component $parent)
    {
        $this->name = $name;
        $this->parent = $parent;
    }

    /**
     * Get the section name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the parent component
     */
    public function getParent(): Component
    {
        return $this->parent;
    }

    /**
     * Add a component to this section
     */
    public function add(Component $component): self
    {
        $component->parentBuilder = $this;
        $this->components[] = $component;

        return $this;
    }

    /**
     * Add multiple components at once
     */
    public function addMany(array $components): self
    {
        foreach ($components as $component) {
            $this->add($component);
        }

        return $this;
    }

    /**
     * Get all components in this section
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * Set meta data for this section
     */
    public function meta(array $meta): self
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    /**
     * Get meta data
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * End this section and return to parent component
     */
    public function end(): Component
    {
        return $this->parent;
    }

    /**
     * Convenience method to end and return to parent's parent
     */
    public function endSection(): mixed
    {
        if ($this->parent && property_exists($this->parent, 'parentBuilder') && $this->parent->parentBuilder) {
            return $this->parent->parentBuilder;
        }

        return $this->parent;
    }

    // ========================================================================
    // Convenience methods for creating and adding specific component types
    // ========================================================================

    /**
     * Create and add a component by type
     * Tries Sections namespace first (containers), then Components namespace (content)
     */
    public function component(string $type, string $name): Component
    {
        // Try Section suffix first (containers like Header, Layout, Grid, Tabs)
        $sectionClass = 'Litepie\\Layout\\Sections\\'.ucfirst($type).'Section';
        if (class_exists($sectionClass)) {
            $component = $sectionClass::make($name);
            $this->add($component);

            return $component;
        }

        // Try Component suffix (content components like Form, Card, Table, List)
        $componentClass = 'Litepie\\Layout\\Components\\'.ucfirst($type).'Component';
        if (class_exists($componentClass)) {
            $component = $componentClass::make($name);
            $this->add($component);

            return $component;
        }

        return $this->custom($type, $name);
    }

    /**
     * Create and add a FormComponent
     */
    public function form(string $name): Component
    {
        return $this->component('form', $name);
    }

    /**
     * Create and add a TextComponent
     */
    public function text(string $name): Component
    {
        return $this->component('text', $name);
    }

    /**
     * Create and add a CardComponent
     */
    public function card(string $name): Component
    {
        return $this->component('card', $name);
    }

    /**
     * Create and add a TableComponent
     */
    public function table(string $name): Component
    {
        return $this->component('table', $name);
    }

    /**
     * Create and add a GridComponent
     */
    public function grid(string $name): Component
    {
        return $this->component('grid', $name);
    }

    /**
     * Create and add a ListComponent
     */
    public function list(string $name): Component
    {
        return $this->component('list', $name);
    }

    /**
     * Create and add a ChartComponent
     */
    public function chart(string $name): Component
    {
        return $this->component('chart', $name);
    }

    /**
     * Create and add a StatsComponent
     */
    public function stats(string $name): Component
    {
        return $this->component('stats', $name);
    }

    /**
     * Create and add a TabsComponent
     */
    public function tabs(string $name): Component
    {
        return $this->component('tabs', $name);
    }

    /**
     * Create and add an AccordionComponent
     */
    public function accordion(string $name): Component
    {
        return $this->component('accordion', $name);
    }

    /**
     * Create and add a ModalComponent
     */
    public function modal(string $name): Component
    {
        return $this->component('modal', $name);
    }

    /**
     * Create and add an AlertComponent
     */
    public function alert(string $name): Component
    {
        return $this->component('alert', $name);
    }

    /**
     * Create and add a BadgeComponent
     */
    public function badge(string $name): Component
    {
        return $this->component('badge', $name);
    }

    /**
     * Create and add a TimelineComponent
     */
    public function timeline(string $name): Component
    {
        return $this->component('timeline', $name);
    }

    /**
     * Create and add a WizardComponent
     */
    public function wizard(string $name): Component
    {
        return $this->component('wizard', $name);
    }

    /**
     * Create and add a MediaComponent
     */
    public function media(string $name): Component
    {
        return $this->component('media', $name);
    }

    /**
     * Create and add a CommentComponent
     */
    public function comment(string $name): Component
    {
        return $this->component('comment', $name);
    }

    /**
     * Create and add a DocumentComponent
     */
    public function document(string $name): Component
    {
        return $this->component('document', $name);
    }

    /**
     * Create and add a BreadcrumbComponent
     */
    public function breadcrumb(string $name): Component
    {
        return $this->component('breadcrumb', $name);
    }

    /**
     * Create and add a ScrollSpyComponent
     */
    public function scrollspy(string $name): Component
    {
        return $this->component('scrollspy', $name);
    }

    /**
     * Create and add a HeaderComponent
     */
    public function header(string $name): Component
    {
        return $this->component('header', $name);
    }

    /**
     * Create and add a LayoutComponent
     */
    public function layout(string $name): Component
    {
        return $this->component('layout', $name);
    }

    /**
     * Create and add a CustomComponent
     */
    public function custom(string $type, string $name): Component
    {
        $class = 'Litepie\\Layout\\Components\\CustomComponent';
        if (class_exists($class)) {
            $component = $class::make($name, $type);
            $this->add($component);

            return $component;
        }

        throw new \RuntimeException("CustomComponent class not found and cannot create component of type '{$type}'");
    }

    /**
     * Convert section and its components to array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'meta' => $this->meta,
            'components' => array_map(
                fn ($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp,
                $this->components
            ),
        ];
    }
}
