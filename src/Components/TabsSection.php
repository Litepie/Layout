<?php

namespace Litepie\Layout\Components;

class TabsSection extends BaseComponent
{
    protected array $tabs = [];

    protected ?string $activeTab = null;

    protected string $position = 'top'; // top, left, right, bottom

    protected bool $lazy = false;

    public function __construct(string $name)
    {
        parent::__construct($name, 'tabs');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * Add a tab with components
     */
    public function addTab(string $id, string $label, array $components = [], array $options = []): self
    {
        $this->tabs[$id] = [
            'id' => $id,
            'label' => $label,
            'components' => $components,
            'icon' => $options['icon'] ?? null,
            'badge' => $options['badge'] ?? null,
            'disabled' => $options['disabled'] ?? false,
            'visible' => $options['visible'] ?? true,
            'permissions' => $options['permissions'] ?? [],
            'roles' => $options['roles'] ?? [],
        ];

        // Set first tab as active if none set
        if ($this->activeTab === null) {
            $this->activeTab = $id;
        }

        return $this;
    }

    /**
     * Set the active tab
     */
    public function activeTab(string $tabId): self
    {
        $this->activeTab = $tabId;

        return $this;
    }

    /**
     * Set tab position
     */
    public function position(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Enable lazy loading for tabs
     */
    public function lazy(bool $lazy = true): self
    {
        $this->lazy = $lazy;

        return $this;
    }

    /**
     * Get all tabs
     */
    public function getTabs(): array
    {
        return $this->tabs;
    }

    /**
     * Get a specific tab
     */
    public function getTab(string $id): ?array
    {
        return $this->tabs[$id] ?? null;
    }

    /**
     * Resolve authorization for tabs and their components
     */
    public function resolveAuthorization($user = null): self
    {
        parent::resolveAuthorization($user);

        foreach ($this->tabs as &$tab) {
            // Check tab-level permissions
            if (! empty($tab['permissions'])) {
                $tab['authorized'] = $this->checkPermissions($user, $tab['permissions']);
            } elseif (! empty($tab['roles'])) {
                $tab['authorized'] = $this->checkRoles($user, $tab['roles']);
            } else {
                $tab['authorized'] = true;
            }

            // Resolve authorization for components in the tab
            if (! empty($tab['components'])) {
                foreach ($tab['components'] as $component) {
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
        $tabs = [];
        foreach ($this->tabs as $tab) {
            $tabs[] = [
                'id' => $tab['id'],
                'label' => $tab['label'],
                'icon' => $tab['icon'],
                'badge' => $tab['badge'],
                'disabled' => $tab['disabled'],
                'visible' => $tab['visible'],
                'authorized' => $tab['authorized'] ?? true,
                'components' => array_map(
                    fn ($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp,
                    $tab['components']
                ),
                'permissions' => $tab['permissions'],
                'roles' => $tab['roles'],
            ];
        }

        return [
            'type' => $this->type,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'tabs' => $tabs,
            'active_tab' => $this->activeTab,
            'position' => $this->position,
            'lazy' => $this->lazy,
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
