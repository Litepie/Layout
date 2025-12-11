<?php

namespace Litepie\Layout\Components;

class GridSection extends BaseComponent
{
    protected array $components = [];

    protected int $gridColumns = 3;

    protected string $gap = 'md';

    public function __construct(string $name)
    {
        parent::__construct($name, 'grid');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function columns(int $columns): self
    {
        $this->gridColumns = $columns;

        return $this;
    }

    public function gap(string $gap): self
    {
        $this->gap = $gap;

        return $this;
    }

    public function addComponent($component): self
    {
        $this->components[] = $component;

        return $this;
    }

    public function addComponents(array $components): self
    {
        foreach ($components as $component) {
            $this->addComponent($component);
        }

        return $this;
    }

    public function getComponents(): array
    {
        return $this->components;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'columns' => $this->gridColumns,
            'gap' => $this->gap,
            'actions' => $this->actions,
            'components' => array_map(fn ($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp, $this->components),
            'order' => $this->order,
            'visible' => $this->visible,
            'permissions' => $this->permissions,
            'roles' => $this->roles,
            'authorized_to_see' => $this->authorizedToSee,
            'meta' => $this->meta,
        ];
    }
}
