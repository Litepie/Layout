<?php

namespace Litepie\Layout\Components;

class BadgeSection extends BaseComponent
{
    protected string $variant = 'default'; // default, primary, secondary, success, warning, error, info
    protected string $size = 'md'; // xs, sm, md, lg
    protected bool $pill = false; // Rounded pill style
    protected bool $outlined = false;
    protected bool $removable = false;
    protected array $badges = []; // Badge configurations

    public function __construct(string $name)
    {
        parent::__construct($name, 'badge');
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

    public function primary(): self
    {
        return $this->variant('primary');
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

    public function size(string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function small(): self
    {
        return $this->size('sm');
    }

    public function large(): self
    {
        return $this->size('lg');
    }

    public function pill(bool $pill = true): self
    {
        $this->pill = $pill;
        return $this;
    }

    public function outlined(bool $outlined = true): self
    {
        $this->outlined = $outlined;
        return $this;
    }

    public function removable(bool $removable = true): self
    {
        $this->removable = $removable;
        return $this;
    }

    /**
     * Add badge configuration
     */
    public function addBadge(string $key, array $options = []): self
    {
        $this->badges[] = [
            'key' => $key,
            'icon' => $options['icon'] ?? null,
            'color' => $options['color'] ?? null,
            'variant' => $options['variant'] ?? $this->variant,
            'removable' => $options['removable'] ?? $this->removable,
        ];
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
            'size' => $this->size,
            'pill' => $this->pill,
            'outlined' => $this->outlined,
            'removable' => $this->removable,
            'badges' => $this->badges,
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
