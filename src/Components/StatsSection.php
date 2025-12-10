<?php

namespace Litepie\Layout\Components;

class StatsSection extends BaseComponent
{
    protected array $metrics = []; // Array of metric configurations
    protected string $layout = 'grid'; // grid, list, inline
    protected int $columns = 4;
    protected string $size = 'md'; // sm, md, lg
    protected bool $showTrend = true;
    protected bool $showChange = true;

    public function __construct(string $name)
    {
        parent::__construct($name, 'stats');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * Add a metric configuration (structure only, no data)
     */
    public function addMetric(string $key, string $label, array $options = []): self
    {
        $this->metrics[] = [
            'key' => $key,
            'label' => $label,
            'icon' => $options['icon'] ?? null,
            'color' => $options['color'] ?? null,
            'format' => $options['format'] ?? 'number', // number, currency, percentage
            'prefix' => $options['prefix'] ?? null,
            'suffix' => $options['suffix'] ?? null,
            'show_trend' => $options['show_trend'] ?? $this->showTrend,
            'show_change' => $options['show_change'] ?? $this->showChange,
        ];
        return $this;
    }

    public function layout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    public function columns(int $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function size(string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function showTrend(bool $show = true): self
    {
        $this->showTrend = $show;
        return $this;
    }

    public function showChange(bool $show = true): self
    {
        $this->showChange = $show;
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
            'metrics' => $this->metrics,
            'layout' => $this->layout,
            'columns' => $this->columns,
            'size' => $this->size,
            'show_trend' => $this->showTrend,
            'show_change' => $this->showChange,
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
