<?php

namespace Litepie\Layout\Components;

/**
 * AvatarComponent
 *
 * Display user avatars with various styles, sizes, and features.
 * Supports images, text initials, icons, status indicators, badges, and grouped avatars.
 */
class AvatarComponent extends BaseComponent
{
    // Avatar source
    protected ?string $src = null; // Image URL

    protected ?string $alt = null; // Alt text for image

    protected ?string $text = null; // Text initials (e.g., "JD" for John Doe)

    protected ?string $avatarIcon = null; // Icon name if using icon instead of image

    // Size options
    protected string $size = 'md'; // xs, sm, md, lg, xl, 2xl

    protected ?string $customSize = null; // Custom size (e.g., "64px", "4rem")

    // Shape options
    protected string $shape = 'circle'; // circle, rounded, square

    protected ?string $radius = null; // Custom border radius

    // Style variants
    protected string $variant = 'default'; // default, outlined, elevated, bordered

    protected ?string $bgColor = null; // Background color for text/icon avatars

    protected ?string $textColor = null; // Text color

    // Border and ring
    protected ?string $borderColor = null;

    protected ?int $borderWidth = null;

    protected bool $ring = false; // Add ring/glow effect

    protected ?string $ringColor = null;

    // Status indicator
    protected bool $showStatus = false;

    protected string $status = 'offline'; // online, offline, away, busy, dnd

    protected string $statusPosition = 'bottom-right'; // bottom-right, bottom-left, top-right, top-left

    protected ?string $statusColor = null; // Custom status color

    // Badge/notification
    protected bool $showBadge = false;

    protected ?string $badgeContent = null; // Number or text

    protected string $badgePosition = 'top-right'; // top-right, top-left, bottom-right, bottom-left

    protected string $badgeVariant = 'primary'; // primary, success, warning, error, info

    // Clickable
    protected bool $clickable = false;

    protected ?string $href = null;

    // Fallback
    protected bool $showFallback = true;

    protected string $fallbackType = 'initials'; // initials, icon, placeholder

    protected ?string $fallbackIcon = 'user';

    protected ?string $fallbackBgColor = null;

    // Tooltip
    protected ?string $tooltip = null;

    protected string $tooltipPosition = 'top';

    // Group/Stack support (for avatar groups)
    protected bool $isGroup = false;

    protected array $avatars = []; // Array of avatar configurations for groups

    protected int $maxVisible = 3; // Max avatars to show in group

    protected bool $showCount = true; // Show "+N" for remaining avatars

    protected string $stackDirection = 'horizontal'; // horizontal, vertical

    protected bool $reversed = false; // Reverse stacking order

    public function __construct(string $name)
    {
        parent::__construct($name, 'avatar');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    // ========================================================================
    // Avatar Source Methods
    // ========================================================================

    /**
     * Set the avatar image URL
     */
    public function src(string $src): self
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Set the alt text for the image
     */
    public function alt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Set text initials (e.g., "JD" for John Doe)
     */
    public function text(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Set icon name for icon-based avatar
     */
    public function avatarIcon(string $icon): self
    {
        $this->avatarIcon = $icon;

        return $this;
    }

    /**
     * Generate initials from full name
     */
    public function initials(string $name): self
    {
        $parts = explode(' ', trim($name));
        $initials = '';

        if (count($parts) >= 2) {
            $initials = strtoupper(substr($parts[0], 0, 1).substr($parts[count($parts) - 1], 0, 1));
        } elseif (count($parts) === 1) {
            $initials = strtoupper(substr($parts[0], 0, 2));
        }

        $this->text = $initials;

        return $this;
    }

    // ========================================================================
    // Size Methods
    // ========================================================================

    /**
     * Set avatar size
     */
    public function size(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Set custom size
     */
    public function customSize(string $size): self
    {
        $this->customSize = $size;

        return $this;
    }

    public function xs(): self
    {
        return $this->size('xs');
    }

    public function sm(): self
    {
        return $this->size('sm');
    }

    public function md(): self
    {
        return $this->size('md');
    }

    public function lg(): self
    {
        return $this->size('lg');
    }

    public function xl(): self
    {
        return $this->size('xl');
    }

    public function xxl(): self
    {
        return $this->size('2xl');
    }

    // ========================================================================
    // Shape Methods
    // ========================================================================

    /**
     * Set avatar shape
     */
    public function shape(string $shape): self
    {
        $this->shape = $shape;

        return $this;
    }

    public function circle(): self
    {
        return $this->shape('circle');
    }

    public function rounded(): self
    {
        return $this->shape('rounded');
    }

    public function square(): self
    {
        return $this->shape('square');
    }

    /**
     * Set custom border radius
     */
    public function radius(string $radius): self
    {
        $this->radius = $radius;

        return $this;
    }

    // ========================================================================
    // Style Methods
    // ========================================================================

    /**
     * Set variant
     */
    public function variant(string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function outlined(): self
    {
        return $this->variant('outlined');
    }

    public function elevated(): self
    {
        return $this->variant('elevated');
    }

    public function bordered(): self
    {
        return $this->variant('bordered');
    }

    /**
     * Set background color
     */
    public function bgColor(string $color): self
    {
        $this->bgColor = $color;

        return $this;
    }

    /**
     * Set text color
     */
    public function textColor(string $color): self
    {
        $this->textColor = $color;

        return $this;
    }

    /**
     * Set border color
     */
    public function borderColor(string $color): self
    {
        $this->borderColor = $color;

        return $this;
    }

    /**
     * Set border width
     */
    public function borderWidth(int $width): self
    {
        $this->borderWidth = $width;

        return $this;
    }

    /**
     * Add ring/glow effect
     */
    public function ring(bool $ring = true, ?string $color = null): self
    {
        $this->ring = $ring;
        if ($color !== null) {
            $this->ringColor = $color;
        }

        return $this;
    }

    // ========================================================================
    // Status Indicator Methods
    // ========================================================================

    /**
     * Show status indicator
     */
    public function showStatus(bool $show = true): self
    {
        $this->showStatus = $show;

        return $this;
    }

    /**
     * Set status
     */
    public function status(string $status): self
    {
        $this->status = $status;
        $this->showStatus = true;

        return $this;
    }

    public function online(): self
    {
        return $this->status('online');
    }

    public function offline(): self
    {
        return $this->status('offline');
    }

    public function away(): self
    {
        return $this->status('away');
    }

    public function busy(): self
    {
        return $this->status('busy');
    }

    public function dnd(): self
    {
        return $this->status('dnd');
    }

    /**
     * Set status position
     */
    public function statusPosition(string $position): self
    {
        $this->statusPosition = $position;

        return $this;
    }

    /**
     * Set custom status color
     */
    public function statusColor(string $color): self
    {
        $this->statusColor = $color;

        return $this;
    }

    // ========================================================================
    // Badge Methods
    // ========================================================================

    /**
     * Show badge
     */
    public function showBadge(bool $show = true): self
    {
        $this->showBadge = $show;

        return $this;
    }

    /**
     * Set badge content
     */
    public function badge(string|int $content, ?string $variant = null): self
    {
        $this->badgeContent = (string) $content;
        $this->showBadge = true;

        if ($variant !== null) {
            $this->badgeVariant = $variant;
        }

        return $this;
    }

    /**
     * Set badge position
     */
    public function badgePosition(string $position): self
    {
        $this->badgePosition = $position;

        return $this;
    }

    /**
     * Set badge variant
     */
    public function badgeVariant(string $variant): self
    {
        $this->badgeVariant = $variant;

        return $this;
    }

    // ========================================================================
    // Clickable Methods
    // ========================================================================

    /**
     * Make avatar clickable
     */
    public function clickable(bool $clickable = true): self
    {
        $this->clickable = $clickable;

        return $this;
    }

    /**
     * Set href for clickable avatar
     */
    public function href(string $href): self
    {
        $this->href = $href;
        $this->clickable = true;

        return $this;
    }

    // ========================================================================
    // Fallback Methods
    // ========================================================================

    /**
     * Show fallback when image fails to load
     */
    public function showFallback(bool $show = true): self
    {
        $this->showFallback = $show;

        return $this;
    }

    /**
     * Set fallback type
     */
    public function fallbackType(string $type): self
    {
        $this->fallbackType = $type;

        return $this;
    }

    /**
     * Set fallback icon
     */
    public function fallbackIcon(string $icon): self
    {
        $this->fallbackIcon = $icon;

        return $this;
    }

    /**
     * Set fallback background color
     */
    public function fallbackBgColor(string $color): self
    {
        $this->fallbackBgColor = $color;

        return $this;
    }

    // ========================================================================
    // Tooltip Methods
    // ========================================================================

    /**
     * Set tooltip text
     */
    public function tooltip(string $tooltip, ?string $position = null): self
    {
        $this->tooltip = $tooltip;

        if ($position !== null) {
            $this->tooltipPosition = $position;
        }

        return $this;
    }

    // ========================================================================
    // Group/Stack Methods
    // ========================================================================

    /**
     * Create avatar group
     */
    public function group(bool $isGroup = true): self
    {
        $this->isGroup = $isGroup;

        return $this;
    }

    /**
     * Add avatar to group
     */
    public function addAvatar(array $config): self
    {
        $this->avatars[] = $config;
        $this->isGroup = true;

        return $this;
    }

    /**
     * Set maximum visible avatars in group
     */
    public function maxVisible(int $max): self
    {
        $this->maxVisible = $max;

        return $this;
    }

    /**
     * Show count of remaining avatars
     */
    public function showCount(bool $show = true): self
    {
        $this->showCount = $show;

        return $this;
    }

    /**
     * Set stack direction
     */
    public function stackDirection(string $direction): self
    {
        $this->stackDirection = $direction;

        return $this;
    }

    /**
     * Reverse stacking order
     */
    public function reversed(bool $reversed = true): self
    {
        $this->reversed = $reversed;

        return $this;
    }

    // ========================================================================
    // Serialization
    // ========================================================================

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'description' => $this->description,

            // Source
            'src' => $this->src,
            'alt' => $this->alt,
            'text' => $this->text,
            'avatar_icon' => $this->avatarIcon,

            // Size
            'size' => $this->size,
            'custom_size' => $this->customSize,

            // Shape
            'shape' => $this->shape,
            'radius' => $this->radius,

            // Style
            'variant' => $this->variant,
            'bg_color' => $this->bgColor,
            'text_color' => $this->textColor,
            'border_color' => $this->borderColor,
            'border_width' => $this->borderWidth,
            'ring' => $this->ring,
            'ring_color' => $this->ringColor,

            // Status
            'show_status' => $this->showStatus,
            'status' => $this->status,
            'status_position' => $this->statusPosition,
            'status_color' => $this->statusColor,

            // Badge
            'show_badge' => $this->showBadge,
            'badge_content' => $this->badgeContent,
            'badge_position' => $this->badgePosition,
            'badge_variant' => $this->badgeVariant,

            // Clickable
            'clickable' => $this->clickable,
            'href' => $this->href,

            // Fallback
            'show_fallback' => $this->showFallback,
            'fallback_type' => $this->fallbackType,
            'fallback_icon' => $this->fallbackIcon,
            'fallback_bg_color' => $this->fallbackBgColor,

            // Tooltip
            'tooltip' => $this->tooltip,
            'tooltip_position' => $this->tooltipPosition,

            // Group
            'is_group' => $this->isGroup,
            'avatars' => $this->avatars,
            'max_visible' => $this->maxVisible,
            'show_count' => $this->showCount,
            'stack_direction' => $this->stackDirection,
            'reversed' => $this->reversed,

            // Base properties
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
