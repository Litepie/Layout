<?php

namespace Litepie\Layout\Components;

class AlertSection extends BaseComponent
{
    protected string $variant = 'info'; // info, success, warning, error, default
    protected ?string $message = null;
    protected bool $dismissible = false;
    protected bool $bordered = false;
    protected bool $filled = false;

    public function __construct(string $name)
    {
        parent::__construct($name, 'alert');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function variant(string $variant): self
    {
        $this->variant = $variant;
        return $this;
    }

    public function info(): self
    {
        return $this->variant('info');
    }

    public function success(): self
    {
        return $this->variant('success');
    }

    public function warning(): self
    {
        return $this->variant('warning');
    }

    public function error(): self
    {
        return $this->variant('error');
    }

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function dismissible(bool $dismissible = true): self
    {
        $this->dismissible = $dismissible;
        return $this;
    }

    public function bordered(bool $bordered = true): self
    {
        $this->bordered = $bordered;
        return $this;
    }

    public function filled(bool $filled = true): self
    {
        $this->filled = $filled;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'variant' => $this->variant,
            'message' => $this->message,
            'dismissible' => $this->dismissible,
            'bordered' => $this->bordered,
            'filled' => $this->filled,
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
                fn($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp,
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
