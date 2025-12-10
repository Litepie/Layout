<?php

namespace Litepie\Layout\Components;

class TimelineSection extends BaseComponent
{
    protected string $orientation = 'vertical'; // vertical, horizontal

    protected string $position = 'left'; // left, right, center, alternate

    protected bool $showDates = true;

    protected bool $showIcons = true;

    protected string $dateFormat = 'relative'; // relative, absolute, custom

    protected array $events = []; // Event configurations

    public function __construct(string $name)
    {
        parent::__construct($name, 'timeline');
    }

    public static function make(string $name): self
    {
        return new static($name);
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

    public function horizontal(): self
    {
        return $this->orientation('horizontal');
    }

    public function position(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function alternate(): self
    {
        return $this->position('alternate');
    }

    public function showDates(bool $show = true): self
    {
        $this->showDates = $show;

        return $this;
    }

    public function showIcons(bool $show = true): self
    {
        $this->showIcons = $show;

        return $this;
    }

    public function dateFormat(string $format): self
    {
        $this->dateFormat = $format;

        return $this;
    }

    /**
     * Add event configuration (structure only)
     */
    public function addEvent(string $key, array $options = []): self
    {
        $this->events[] = [
            'key' => $key,
            'icon' => $options['icon'] ?? null,
            'color' => $options['color'] ?? null,
            'variant' => $options['variant'] ?? 'default', // default, success, warning, error
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
            'orientation' => $this->orientation,
            'position' => $this->position,
            'show_dates' => $this->showDates,
            'show_icons' => $this->showIcons,
            'date_format' => $this->dateFormat,
            'events' => $this->events,
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
