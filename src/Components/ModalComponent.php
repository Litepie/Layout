<?php

namespace Litepie\Layout\Components;

class ModalComponent extends BaseComponent
{
    protected string $size = 'md'; // xs, sm, md, lg, xl, full

    protected bool $closable = true;

    protected bool $closeOnBackdrop = true;

    protected bool $closeOnEscape = true;

    protected ?string $trigger = null; // Element ID or selector that opens modal

    protected array $footer = [];

    public function __construct(string $name)
    {
        parent::__construct($name, 'modal');
    }

    public static function make(string $name): self
    {
        return new static($name);
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

    public function fullscreen(): self
    {
        return $this->size('full');
    }

    public function closable(bool $closable = true): self
    {
        $this->closable = $closable;

        return $this;
    }

    public function closeOnBackdrop(bool $close = true): self
    {
        $this->closeOnBackdrop = $close;

        return $this;
    }

    public function closeOnEscape(bool $close = true): self
    {
        $this->closeOnEscape = $close;

        return $this;
    }

    public function trigger(string $trigger): self
    {
        $this->trigger = $trigger;

        return $this;
    }

    public function addFooterButton(string $label, string $action, array $options = []): self
    {
        $this->footer[] = array_merge([
            'label' => $label,
            'action' => $action,
        ], $options);

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
            'size' => $this->size,
            'closable' => $this->closable,
            'close_on_backdrop' => $this->closeOnBackdrop,
            'close_on_escape' => $this->closeOnEscape,
            'trigger' => $this->trigger,
            'footer' => $this->footer,
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
