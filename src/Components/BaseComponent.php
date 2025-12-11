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

    protected ?string $icon = null;

    protected array $actions = [];

    // Data source configuration for frontend loading
    protected ?string $dataSource = null; // API endpoint or data source identifier

    protected ?string $dataUrl = null; // Full API URL

    protected array $dataParams = []; // Query parameters for data fetching

    protected ?string $dataTransform = null; // Optional transform function name

    protected bool $loadOnMount = true; // Auto-load data when component mounts

    protected bool $reloadOnChange = false; // Reload when parent context changes

    protected bool $useSharedData = false; // Use data from parent/shared data source

    protected ?string $dataKey = null; // Key to extract from shared data (supports dot notation: 'user.profile.header')

    // Nested sections for infinite nesting
    protected array $sections = [];

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
     * Set data source endpoint for frontend loading
     */
    public function dataSource(string $source): self
    {
        $this->dataSource = $source;

        return $this;
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
     * Supports dot notation for nested data: 'user.profile.header'
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

    /**
     * Add a nested section (enables infinite nesting)
     */
    public function addSection(Component $section): self
    {
        if (method_exists($section, 'getName')) {
            $this->sections[$section->getName()] = $section;
        } else {
            $this->sections[] = $section;
        }

        return $this;
    }

    /**
     * Add multiple nested sections
     */
    public function addSections(array $sections): self
    {
        foreach ($sections as $section) {
            $this->addSection($section);
        }

        return $this;
    }

    /**
     * Get all nested sections
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * Get a specific nested section by name
     */
    public function getSection(string $name): ?Component
    {
        return $this->sections[$name] ?? null;
    }

    /**
     * Check if component has nested sections
     */
    public function hasSections(): bool
    {
        return ! empty($this->sections);
    }

    /**
     * @deprecated Use addSection() instead
     */
    public function addComponent(Component $component): self
    {
        return $this->addSection($component);
    }

    /**
     * @deprecated Use addSections() instead
     */
    public function addComponents(array $components): self
    {
        return $this->addSections($components);
    }

    /**
     * @deprecated Use getSections() instead
     */
    public function getComponents(): array
    {
        return $this->getSections();
    }

    /**
     * @deprecated Use getSection() instead
     */
    public function getComponent(string $name): ?Component
    {
        return $this->getSection($name);
    }

    /**
     * @deprecated Use hasSections() instead
     */
    public function hasComponents(): bool
    {
        return $this->hasSections();
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

        // Resolve authorization for nested sections
        foreach ($this->sections as $section) {
            if (method_exists($section, 'resolveAuthorization')) {
                $section->resolveAuthorization($user);
            }
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

    abstract public function toArray(): array;

    public function render(): array
    {
        return $this->toArray();
    }
}
