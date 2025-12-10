<?php

namespace Litepie\Layout\Components;

class AccordionSection extends BaseComponent
{
    protected array $items = [];
    protected bool $multiple = false;
    protected bool $collapsible = true;
    protected ?string $expanded = null;

    public function __construct(string $name)
    {
        parent::__construct($name, 'accordion');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * Add an accordion item with components
     */
    public function addItem(string $id, string $label, array $components = [], array $options = []): self
    {
        $this->items[$id] = [
            'id' => $id,
            'label' => $label,
            'components' => $components,
            'icon' => $options['icon'] ?? null,
            'badge' => $options['badge'] ?? null,
            'disabled' => $options['disabled'] ?? false,
            'visible' => $options['visible'] ?? true,
            'permissions' => $options['permissions'] ?? [],
            'roles' => $options['roles'] ?? [],
            'description' => $options['description'] ?? null,
        ];
        
        // Set first item as expanded if none set
        if ($this->expanded === null) {
            $this->expanded = $id;
        }
        
        return $this;
    }

    /**
     * Allow multiple items to be expanded simultaneously
     */
    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;
        return $this;
    }

    /**
     * Set whether items can be collapsed
     */
    public function collapsible(bool $collapsible = true): self
    {
        $this->collapsible = $collapsible;
        return $this;
    }

    /**
     * Set the initially expanded item
     */
    public function expanded(string $itemId): self
    {
        $this->expanded = $itemId;
        return $this;
    }

    /**
     * Get all items
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Get a specific item
     */
    public function getItem(string $id): ?array
    {
        return $this->items[$id] ?? null;
    }

    /**
     * Resolve authorization for items and their components
     */
    public function resolveAuthorization($user = null): self
    {
        parent::resolveAuthorization($user);
        
        foreach ($this->items as &$item) {
            // Check item-level permissions
            if (!empty($item['permissions'])) {
                $item['authorized'] = $this->checkPermissions($user, $item['permissions']);
            } elseif (!empty($item['roles'])) {
                $item['authorized'] = $this->checkRoles($user, $item['roles']);
            } else {
                $item['authorized'] = true;
            }
            
            // Resolve authorization for components in the item
            if (!empty($item['components'])) {
                foreach ($item['components'] as $component) {
                    if (method_exists($component, 'resolveAuthorization')) {
                        $component->resolveAuthorization($user);
                    }
                }
            }
        }
        
        return $this;
    }

    public function toArray(): array
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = [
                'id' => $item['id'],
                'label' => $item['label'],
                'icon' => $item['icon'],
                'badge' => $item['badge'],
                'disabled' => $item['disabled'],
                'visible' => $item['visible'],
                'authorized' => $item['authorized'] ?? true,
                'description' => $item['description'],
                'components' => array_map(
                    fn($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp,
                    $item['components']
                ),
                'permissions' => $item['permissions'],
                'roles' => $item['roles'],
            ];
        }

        return [
            'type' => $this->type,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'items' => $items,
            'expanded' => $this->expanded,
            'multiple' => $this->multiple,
            'collapsible' => $this->collapsible,
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
