<?php

namespace Litepie\Layout\Components;

use Litepie\Layout\Contracts\Component;
use Litepie\Layout\Contracts\Renderable;
use Litepie\Layout\Traits\Debuggable;
use Litepie\Layout\Traits\HasConditionalLogic;
use Litepie\Layout\Traits\HasEvents;
use Litepie\Layout\Traits\Responsive;
use Litepie\Layout\Traits\Translatable;
use Litepie\Layout\Traits\Validatable;

/**
 * BaseComponent
 *
 * Base class for simple content components without section slots.
 * Use this for components that render actual content like forms, cards, tables, lists, alerts, etc.
 *
 * Components are leaf nodes - they cannot contain other sections or components.
 * Examples: FormComponent, CardComponent, TableComponent, ListComponent, AlertComponent
 *
 * For container components that have named section slots, use BaseSection instead.
 */
abstract class BaseComponent implements Component, Renderable
{
    use Debuggable,
        HasConditionalLogic,
        HasEvents,
        Responsive,
        Translatable,
        Validatable;

    protected string $name;

    protected string $type;

    protected ?int $order = null;

    protected bool $visible = true;

    protected array $meta = [];

    // Section header properties
    protected ?string $title = null;

    protected ?string $subtitle = null;

    protected ?string $description = null;

    protected ?string $icon = null;

    protected array $actions = [];

    // Components are leaf nodes and shouldn't contain sections
    // This property exists for backward compatibility
    protected array $sections = [];

    // Data source configuration for frontend loading
    protected ?string $dataSource = null;

    protected ?\Closure $dataSourceCallback = null;

    protected ?string $dataUrl = null;

    protected array $dataParams = [];

    protected ?string $dataTransform = null;

    protected bool $loadOnMount = true;

    protected bool $reloadOnChange = false;

    protected bool $useSharedData = false;

    protected ?string $dataKey = null;

    // Reference to parent builder for endSection() support
    public $parentBuilder = null;

    // Authorization
    protected array $permissions = [];

    protected array $roles = [];

    protected ?\Closure $canSeeCallback = null;

    protected bool $authorizedToSee = true;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function order(int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function visible(bool $visible = true): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function hidden(): self
    {
        return $this->visible(false);
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function meta(array $meta): self
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function subtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function addAction(string $label, string $url, array $options = []): self
    {
        $this->actions[] = array_merge([
            'label' => $label,
            'url' => $url,
        ], $options);

        return $this;
    }

    public function actions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * Set data source endpoint for frontend loading or callback for server-side data
     * Accepts either a string URL or a Closure for server-side data generation
     */
    public function dataSource(string|\Closure $source): self
    {
        if ($source instanceof \Closure) {
            $this->dataSourceCallback = $source;
            // Execute the callback and store the result
            $this->dataSource = 'callback';
        } else {
            $this->dataSource = $source;
        }

        return $this;
    }

    /**
     * Get the data from the data source callback
     */
    public function getDataSourceData(): mixed
    {
        if ($this->dataSourceCallback) {
            return ($this->dataSourceCallback)();
        }

        return null;
    }

    /**
     * Set full data URL for frontend loading
     */
    public function dataUrl(string $url): self
    {
        $this->dataUrl = $url;

        return $this;
    }

    /**
     * Set data parameters for API call
     */
    public function dataParams(array $params): self
    {
        $this->dataParams = array_merge($this->dataParams, $params);

        return $this;
    }

    /**
     * Set data transform function name
     */
    public function dataTransform(string $transform): self
    {
        $this->dataTransform = $transform;

        return $this;
    }

    /**
     * Set whether to load data on component mount
     */
    public function loadOnMount(bool $load = true): self
    {
        $this->loadOnMount = $load;

        return $this;
    }

    /**
     * Set whether to reload when parent context changes
     */
    public function reloadOnChange(bool $reload = true): self
    {
        $this->reloadOnChange = $reload;

        return $this;
    }

    /**
     * Use data from shared/parent data source instead of separate API call
     */
    public function useSharedData(bool $shared = true, ?string $key = null): self
    {
        $this->useSharedData = $shared;
        if ($key !== null) {
            $this->dataKey = $key;
        }

        return $this;
    }

    /**
     * Set the key to extract data from shared data source
     */
    public function dataKey(string $key): self
    {
        $this->dataKey = $key;

        return $this;
    }

    public function getDataSource(): ?string
    {
        return $this->dataSource;
    }

    public function getDataUrl(): ?string
    {
        return $this->dataUrl;
    }

    public function getDataParams(): array
    {
        return $this->dataParams;
    }

    public function permissions(array|string $permissions): self
    {
        $this->permissions = is_array($permissions) ? $permissions : [$permissions];

        return $this;
    }

    public function roles(array|string $roles): self
    {
        $this->roles = is_array($roles) ? $roles : [$roles];

        return $this;
    }

    public function canSee(\Closure $callback): self
    {
        $this->canSeeCallback = $callback;

        return $this;
    }

    public function resolveAuthorization($user = null): self
    {
        if ($this->canSeeCallback !== null) {
            $this->authorizedToSee = call_user_func($this->canSeeCallback, $user);
        }

        if (! empty($this->permissions) && $user !== null) {
            $this->authorizedToSee = $this->checkPermissions($user, $this->permissions);
        }

        if (! empty($this->roles) && $user !== null) {
            $this->authorizedToSee = $this->checkRoles($user, $this->roles);
        }

        return $this;
    }

    protected function checkPermissions($user, array $permissions): bool
    {
        if (method_exists($user, 'hasAnyPermission')) {
            return $user->hasAnyPermission($permissions);
        }
        if (method_exists($user, 'can')) {
            foreach ($permissions as $permission) {
                if ($user->can($permission)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function checkRoles($user, array $roles): bool
    {
        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole($roles);
        }
        if (method_exists($user, 'hasRole')) {
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    return true;
                }
            }
        }
        if (isset($user->role)) {
            return in_array($user->role, $roles);
        }

        return false;
    }

    public function isAuthorizedToSee(): bool
    {
        return $this->authorizedToSee;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * End section and return to parent builder
     * Allows chaining: ->section('body')->chart()->endSection()->section('footer')
     *
     * If parentBuilder is a SectionContainer, returns its parent (the section/layout)
     * Otherwise returns parentBuilder directly
     */
    public function endSection()
    {
        if ($this->parentBuilder instanceof \Litepie\Layout\SectionContainer) {
            return $this->parentBuilder->getParent();
        }

        return $this->parentBuilder;
    }

    /**
     * Helper method to get common properties for toArray()
     */
    protected function getCommonProperties(): array
    {
        $properties = [
            'type' => $this->type,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'icon' => $this->icon,
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

        // If dataSource callback exists, execute it and include the data
        if ($this->dataSourceCallback) {
            $properties['data'] = $this->getDataSourceData();
        }

        return $properties;
    }

    abstract public function toArray(): array;

    public function render(): array
    {
        return $this->toArray();
    }
}
