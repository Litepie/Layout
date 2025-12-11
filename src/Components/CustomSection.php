<?php

namespace Litepie\Layout\Components;

class CustomSection extends BaseComponent
{
    protected ?string $view = null;

    protected array $data = [];

    protected ?string $component = null;

    public function __construct(string $name, string $type = 'custom')
    {
        parent::__construct($name, $type);
    }

    public static function make(string $name, string $type = 'custom'): self
    {
        return new static($name, $type);
    }

    public function view(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function component(string $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function data(array $data): self
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function with(string $key, mixed $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'view' => $this->view,
            'component' => $this->component,
            'data' => $this->data,
            'actions' => $this->actions,
            'sections' => array_map(
                fn ($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp,
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
