<?php

namespace Litepie\Layout\Components;

class CardComponent extends BaseComponent
{
    protected ?string $image = null;

    protected string $variant = 'default'; // default, outlined, elevated

    protected array $fields = [];

    public function __construct(string $name)
    {
        parent::__construct($name, 'card');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function image(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function variant(string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * Add a field to display in the card
     * Usage: addField('email', 'Email Address', 'john@example.com')
     * Or: addField('email', 'Email Address') - value will come from dataSource
     */
    public function addField(string $name, string $label, mixed $value = null): self
    {
        $this->fields[] = [
            'name' => $name,
            'label' => $label,
            'value' => $value,
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
            'description' => $this->description,
            'image' => $this->image,
            'variant' => $this->variant,
            'fields' => $this->fields,
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
