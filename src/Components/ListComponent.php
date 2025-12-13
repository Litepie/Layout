<?php

namespace Litepie\Layout\Components;

class ListComponent extends BaseComponent
{
    protected string $listType = 'bullet'; // bullet, numbered, definition, checklist

    protected array $items = []; // Item configurations (structure, not data)

    protected bool $ordered = false;

    protected bool $nested = false;

    protected ?string $marker = null; // Custom bullet marker

    public function __construct(string $name)
    {
        parent::__construct($name, 'list');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function listType(string $type): self
    {
        $this->listType = $type;
        $this->ordered = in_array($type, ['numbered', 'checklist']);

        return $this;
    }

    public function bullet(): self
    {
        return $this->listType('bullet');
    }

    public function numbered(): self
    {
        return $this->listType('numbered');
    }

    public function definition(): self
    {
        return $this->listType('definition');
    }

    public function checklist(): self
    {
        return $this->listType('checklist');
    }

    public function nested(bool $nested = true): self
    {
        $this->nested = $nested;

        return $this;
    }

    public function marker(string $marker): self
    {
        $this->marker = $marker;

        return $this;
    }

    /**
     * Add item configuration (structure only)
     * Supports two patterns:
     * 1. addItem($key, $label, $options) - individual parameters
     * 2. addItem($item) - array with all properties
     */
    public function addItem(string|array $keyOrItem, ?string $label = null, array $options = []): self
    {
        // Pattern 2: Array with all properties
        if (is_array($keyOrItem)) {
            $item = $keyOrItem;
            $this->items[] = [
                'key' => $item['key'] ?? null,
                'label' => $item['label'] ?? null,
                'url' => $item['url'] ?? null,
                'icon' => $item['icon'] ?? null,
                'color' => $item['color'] ?? null,
                'checked' => $item['checked'] ?? null, // For checklist
                'target' => $item['target'] ?? null,
            ];
            
            return $this;
        }
        
        // Pattern 1: Individual parameters
        $this->items[] = [
            'key' => $keyOrItem,
            'label' => $label,
            'url' => $options['url'] ?? null,
            'icon' => $options['icon'] ?? null,
            'color' => $options['color'] ?? null,
            'checked' => $options['checked'] ?? null, // For checklist
            'target' => $options['target'] ?? null,
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
            'list_type' => $this->listType,
            'ordered' => $this->ordered,
            'nested' => $this->nested,
            'marker' => $this->marker,
            'items' => $this->items,
            'data_source' => $this->dataSource,
            'data_url' => $this->dataUrl,
            'data_params' => $this->dataParams,
            'data_transform' => $this->dataTransform,
            'load_on_mount' => $this->loadOnMount,
            'reload_on_change' => $this->reloadOnChange,
            'use_shared_data' => $this->useSharedData,
            'data_key' => $this->dataKey,
            'actions' => $this->actions,
            'order' => $this->order,
            'visible' => $this->visible,
            'permissions' => $this->permissions,
            'roles' => $this->roles,
            'authorized_to_see' => $this->authorizedToSee,
            'meta' => $this->meta,
        ];
    }
}
