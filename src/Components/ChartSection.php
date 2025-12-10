<?php

namespace Litepie\Layout\Components;

class ChartSection extends BaseComponent
{
    protected string $chartType = 'line'; // line, bar, pie, doughnut, area, radar, scatter, bubble
    protected array $series = []; // Series configurations
    protected array $chartOptions = [];
    protected ?int $height = null;
    protected bool $responsive = true;
    protected bool $animated = true;
    protected ?string $library = null; // chart.js, apexcharts, recharts, etc.

    public function __construct(string $name)
    {
        parent::__construct($name, 'chart');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function chartType(string $type): self
    {
        $this->chartType = $type;
        return $this;
    }

    public function line(): self
    {
        return $this->chartType('line');
    }

    public function bar(): self
    {
        return $this->chartType('bar');
    }

    public function pie(): self
    {
        return $this->chartType('pie');
    }

    public function doughnut(): self
    {
        return $this->chartType('doughnut');
    }

    public function area(): self
    {
        return $this->chartType('area');
    }

    /**
     * Add series configuration
     */
    public function addSeries(string $key, string $label, array $options = []): self
    {
        $this->series[] = [
            'key' => $key,
            'label' => $label,
            'color' => $options['color'] ?? null,
            'type' => $options['type'] ?? $this->chartType,
        ];
        return $this;
    }

    public function chartOptions(array $options): self
    {
        $this->chartOptions = array_merge($this->chartOptions, $options);
        return $this;
    }

    public function height(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    public function responsive(bool $responsive = true): self
    {
        $this->responsive = $responsive;
        return $this;
    }

    public function animated(bool $animated = true): self
    {
        $this->animated = $animated;
        return $this;
    }

    public function library(string $library): self
    {
        $this->library = $library;
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
            'chart_type' => $this->chartType,
            'series' => $this->series,
            'chart_options' => $this->chartOptions,
            'height' => $this->height,
            'responsive' => $this->responsive,
            'animated' => $this->animated,
            'library' => $this->library,
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
