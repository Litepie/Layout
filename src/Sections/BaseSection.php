<?php

namespace Litepie\Layout\Sections;

use Litepie\Layout\Contracts\Component;
use Litepie\Layout\Contracts\Renderable;
use Litepie\Layout\Traits\Debuggable;
use Litepie\Layout\Traits\HasConditionalLogic;
use Litepie\Layout\Traits\HasEvents;
use Litepie\Layout\Traits\Responsive;
use Litepie\Layout\Traits\Translatable;
use Litepie\Layout\Traits\Validatable;

/**
 * BaseSection
 * 
 * Base class for container/layout sections that have named section slots.
 * Use this for components that define AREAS where other components can be placed.
 * 
 * These are containers that hold other components in named slots.
 * Examples: HeaderSection, LayoutSection, GridSection, TabsSection, AccordionSection
 * 
 * For simple content components without section slots, use BaseComponent instead.
 */
abstract class BaseSection implements Component, Renderable
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

    // Data source configuration for frontend loading
    protected ?string $dataSource = null;

    protected ?string $dataUrl = null;

    protected array $dataParams = [];

    protected ?string $dataTransform = null;

    protected bool $loadOnMount = true;

    protected bool $reloadOnChange = false;

    protected bool $useSharedData = false;

    protected ?string $dataKey = null;

    // Named section slots (for container sections like Header, Layout, Grid, Tabs)
    protected array $sectionSlots = [];

    // Allowed section names for this component (empty = allow all)
    protected array $allowedSections = [];

    // Nested sections for infinite nesting (legacy support)
    protected array $sections = [];

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

    // ========================================================================
    // Named Section Slots (for container sections)
    // ========================================================================

    /**
     * Create or get a named section slot
     * This is the primary method for container sections where they
     * have named areas that hold multiple components
     *
     * Example: $header->section('left')->add($logo)->add($menu)
     */
    public function section(string $name): \Litepie\Layout\SectionContainer
    {
        // Validate section name if allowed sections are defined
        if (!empty($this->allowedSections) && !in_array($name, $this->allowedSections)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Section '%s' is not allowed in %s. Allowed sections: %s",
                    $name,
                    get_class($this),
                    implode(', ', $this->allowedSections)
                )
            );
        }

        // Create section container if it doesn't exist
        if (!isset($this->sectionSlots[$name])) {
            $this->sectionSlots[$name] = new \Litepie\Layout\SectionContainer($name, $this);
        }

        return $this->sectionSlots[$name];
    }

    /**
     * Get all section slots
     */
    public function getSectionSlots(): array
    {
        return $this->sectionSlots;
    }

    /**
     * Check if section has a specific section slot
     */
    public function hasSection(string $name): bool
    {
        return isset($this->sectionSlots[$name]);
    }

    /**
     * Get allowed section names for this section
     */
    public function getAllowedSections(): array
    {
        return $this->allowedSections;
    }

    /**
     * Check if this section uses named section slots
     */
    public function hasNamedSections(): bool
    {
        return !empty($this->sectionSlots);
    }

    // ========================================================================
    // Legacy Section Support (Deprecated)
    // ========================================================================

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

        if (property_exists($section, 'parentBuilder')) {
            $section->parentBuilder = $this;
        }

        return $this;
    }

    /**
     * End current section and return to parent builder
     */
    public function endSection()
    {
        return $this->parentBuilder;
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

    /**
     * Helper method to serialize legacy sections for toArray()
     */
    protected function serializeLegacySections(): array
    {
        return array_map(
            fn ($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp,
            $this->sections
        );
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

        // Add section slots if using named sections
        if ($this->hasNamedSections()) {
            $properties['section_slots'] = $this->serializeSectionSlots();
        }

        // Add legacy sections for backward compatibility
        if (!empty($this->sections)) {
            $properties['sections'] = $this->serializeLegacySections();
        }

        return $properties;
    }

    /**
     * Helper method to serialize section slots for toArray()
     */
    protected function serializeSectionSlots(): array
    {
        $serialized = [];
        
        foreach ($this->sectionSlots as $name => $container) {
            $serialized[$name] = $container->toArray();
        }
        
        return $serialized;
    }

    abstract public function toArray(): array;

    public function render(): array
    {
        return $this->toArray();
    }
}
