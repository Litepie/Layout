<?php

namespace Litepie\Layout\Components;

class BreadcrumbComponent extends BaseComponent
{
    protected array $items = [];

    protected string $separator = '/';

    protected bool $showHome = true;

    protected ?string $homeUrl = null;

    protected ?string $homeLabel = 'Home';

    protected ?string $homeIcon = null;

    public function __construct(string $name)
    {
        parent::__construct($name, 'breadcrumb');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * Add a breadcrumb item
     */
    public function addItem(string $label, ?string $url = null, array $options = []): self
    {
        $this->items[] = [
            'label' => $label,
            'url' => $url,
            'icon' => $options['icon'] ?? null,
            'active' => $options['active'] ?? false,
            'disabled' => $options['disabled'] ?? false,
        ];

        return $this;
    }

    /**
     * Set the separator between breadcrumb items
     */
    public function separator(string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Set whether to show home link
     */
    public function showHome(bool $show = true): self
    {
        $this->showHome = $show;

        return $this;
    }

    /**
     * Set home URL
     */
    public function homeUrl(string $url): self
    {
        $this->homeUrl = $url;

        return $this;
    }

    /**
     * Set home label
     */
    public function homeLabel(string $label): self
    {
        $this->homeLabel = $label;

        return $this;
    }

    /**
     * Set home icon
     */
    public function homeIcon(string $icon): self
    {
        $this->homeIcon = $icon;

        return $this;
    }

    /**
     * Use chevron separator (>)
     */
    public function chevron(): self
    {
        return $this->separator('>');
    }

    /**
     * Use arrow separator (→)
     */
    public function arrow(): self
    {
        return $this->separator('→');
    }

    /**
     * Use slash separator (/)
     */
    public function slash(): self
    {
        return $this->separator('/');
    }

    /**
     * Use dot separator (•)
     */
    public function dot(): self
    {
        return $this->separator('•');
    }

    /**
     * Get all breadcrumb items
     */
    public function getItems(): array
    {
        return $this->items;
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
            'separator' => $this->separator,
            'show_home' => $this->showHome,
            'home_url' => $this->homeUrl,
            'home_label' => $this->homeLabel,
            'home_icon' => $this->homeIcon,
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
