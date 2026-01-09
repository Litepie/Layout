<?php

namespace Litepie\Layout\Components;

/**
 * DrawerComponent
 *
 * Drawer component provides side panels that slide in from edges of the screen.
 * Supports multiple anchor positions (left, right, top, bottom) and variants (temporary, persistent, mini, permanent).
 */
class DrawerComponent extends BaseComponent
{
    // Core properties
    protected string $anchor = 'left'; // left, right, top, bottom
    protected string $variant = 'temporary'; // temporary, persistent, mini, permanent
    protected bool $open = false;
    protected ?string $width = null;
    protected ?string $height = null;
    protected ?string $miniWidth = null;

    // Backdrop properties
    protected bool $backdrop = true;
    protected ?string $backdropColor = null;
    protected bool $closeOnBackdrop = true;
    protected bool $closeOnEscape = true;

    // UI elements
    protected bool $closeButton = true;
    protected bool $defaultOpen = false;

    // Mini variant properties
    protected bool $expandOnHover = false;

    // Content sections
    protected ?array $header = null;
    protected ?array $content = null;
    protected ?array $footer = null;
    protected ?array $trigger = null;

    // Layout configuration (separate from content)
    protected ?string $layout = null;
    protected ?array $layoutConfig = null;

    // Styling
    protected ?string $backgroundColor = null;
    protected ?string $textColor = null;
    protected ?int $elevation = null;
    protected ?string $borderRadius = null;
    protected ?string $transition = null;
    protected ?int $transitionDuration = null;

    // Advanced
    protected ?string $parent = null; // For nested drawers
    protected ?string $maxWidth = null;
    protected ?string $maxHeight = null;

    public function __construct(string $name)
    {
        parent::__construct($name, 'drawer');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    // ========================================================================
    // Core Methods
    // ========================================================================

    /**
     * Set anchor position (left, right, top, bottom)
     */
    public function anchor(string $anchor): self
    {
        $this->anchor = $anchor;
        return $this;
    }

    /**
     * Set width (for left/right anchors)
     */
    public function width(string $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * Set height (for top/bottom anchors)
     */
    public function height(string $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Set drawer variant
     */
    public function variant(string $variant): self
    {
        $this->variant = $variant;
        return $this;
    }

    /**
     * Set open state
     */
    public function open(bool $open = true): self
    {
        $this->open = $open;
        return $this;
    }

    // ========================================================================
    // Backdrop Methods
    // ========================================================================

    /**
     * Show/hide backdrop
     */
    public function backdrop(bool $show = true): self
    {
        $this->backdrop = $show;
        return $this;
    }

    /**
     * Set backdrop color
     */
    public function backdropColor(string $color): self
    {
        $this->backdropColor = $color;
        return $this;
    }

    /**
     * Close drawer when clicking backdrop
     */
    public function closeOnBackdrop(bool $close = true): self
    {
        $this->closeOnBackdrop = $close;
        return $this;
    }

    /**
     * Close drawer on ESC key
     */
    public function closeOnEscape(bool $close = true): self
    {
        $this->closeOnEscape = $close;
        return $this;
    }

    // ========================================================================
    // UI Elements
    // ========================================================================

    /**
     * Show/hide close button
     */
    public function closeButton(bool $show = true): self
    {
        $this->closeButton = $show;
        return $this;
    }

    /**
     * Set drawer to open by default
     */
    public function defaultOpen(bool $open = true): self
    {
        $this->defaultOpen = $open;
        return $this;
    }

    // ========================================================================
    // Mini Variant Methods
    // ========================================================================

    /**
     * Set mini width (collapsed width for mini variant)
     */
    public function miniWidth(string $width): self
    {
        $this->miniWidth = $width;
        return $this;
    }

    /**
     * Expand mini drawer on hover
     */
    public function expandOnHover(bool $expand = true): self
    {
        $this->expandOnHover = $expand;
        return $this;
    }

    // ========================================================================
    // Content Methods
    // ========================================================================

    /**
     * Set drawer header
     */
    public function header(array $config): self
    {
        $this->header = $config;
        return $this;
    }

    /**
     * Set drawer main content
     */
    public function content(array $config): self
    {
        $this->content = $config;
        return $this;
    }

    /**
     * Set drawer footer
     */
    public function footer(array $config): self
    {
        $this->footer = $config;
        return $this;
    }

    /**
     * Set trigger button/element
     */
    public function trigger(array $config): self
    {
        $this->trigger = $config;
        return $this;
    }

    /**
     * Set content layout structure with grid configuration.
     * This method merges layout configuration into the content.
     *
     * @param string $layoutType The layout type (e.g., 'grid', 'flex', 'stack')
     * @param array $layoutConfig The layout configuration array
     * @return self
     */
    public function contentLayout(string $layoutType, array $layoutConfig): self
    {
        $this->layout = $layoutType;
        $this->layoutConfig = $layoutConfig;

        return $this;
    }

    /**
     * Set layout type separately.
     *
     * @param string $layoutType The layout type (e.g., 'grid', 'flex', 'stack')
     * @return self
     */
    public function layout(string $layoutType): self
    {
        $this->layout = $layoutType;
        return $this;
    }

    /**
     * Set layout configuration separately.
     *
     * @param array $layoutConfig The layout configuration array
     * @return self
     */
    public function layoutConfig(array $layoutConfig): self
    {
        $this->layoutConfig = $layoutConfig;
        return $this;
    }

    /**
     * Set content with component and optional layout configuration.
     *
     * @param mixed $component The component data
     * @param string|null $layoutType Optional layout type
     * @param array|null $layoutConfig Optional layout configuration
     * @return self
     */
    public function contentWithLayout($component, ?string $layoutType = null, ?array $layoutConfig = null): self
    {
        $this->content = ['component' => $component];

        if ($layoutType) {
            $this->layout = $layoutType;
        }

        if ($layoutConfig) {
            $this->layoutConfig = $layoutConfig;
        }

        return $this;
    }

    // ========================================================================
    // Styling Methods
    // ========================================================================

    /**
     * Set background color
     */
    public function backgroundColor(string $color): self
    {
        $this->backgroundColor = $color;
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
     * Set shadow elevation (0-24)
     */
    public function elevation(int $level): self
    {
        $this->elevation = $level;
        return $this;
    }

    /**
     * Set border radius
     */
    public function borderRadius(string $radius): self
    {
        $this->borderRadius = $radius;
        return $this;
    }

    /**
     * Set transition animation type
     */
    public function transition(string $type): self
    {
        $this->transition = $type;
        return $this;
    }

    /**
     * Set transition duration in milliseconds
     */
    public function transitionDuration(int $ms): self
    {
        $this->transitionDuration = $ms;
        return $this;
    }

    // ========================================================================
    // Advanced Methods
    // ========================================================================

    /**
     * Set parent drawer for nested drawers
     */
    public function parent(string $drawerName): self
    {
        $this->parent = $drawerName;
        return $this;
    }

    /**
     * Set maximum width
     */
    public function maxWidth(string $width): self
    {
        $this->maxWidth = $width;
        return $this;
    }

    /**
     * Set maximum height
     */
    public function maxHeight(string $height): self
    {
        $this->maxHeight = $height;
        return $this;
    }

    // ========================================================================
    // Preset Methods
    // ========================================================================

    /**
     * Create a basic navigation drawer
     */
    public function navigationMenu(): self
    {
        return $this
            ->anchor('left')
            ->width('280px')
            ->variant('temporary')
            ->backdrop(true)
            ->closeOnBackdrop(true)
            ->closeOnEscape(true);
    }

    /**
     * Create a filter panel drawer
     */
    public function filterPanel(): self
    {
        return $this
            ->anchor('right')
            ->width('360px')
            ->variant('temporary')
            ->backdrop(true)
            ->closeOnBackdrop(true);
    }

    /**
     * Create a mini app navigation drawer
     */
    public function miniNav(): self
    {
        return $this
            ->anchor('left')
            ->variant('mini')
            ->width('280px')
            ->miniWidth('72px')
            ->expandOnHover(true)
            ->defaultOpen(true)
            ->backdrop(false);
    }

    /**
     * Create a persistent sidebar
     */
    public function persistentSidebar(): self
    {
        return $this
            ->anchor('left')
            ->width('280px')
            ->variant('persistent')
            ->defaultOpen(true)
            ->backdrop(false);
    }

    /**
     * Create a mobile bottom sheet
     */
    public function bottomSheet(): self
    {
        return $this
            ->anchor('bottom')
            ->height('50vh')
            ->variant('temporary')
            ->backdrop(true)
            ->closeOnBackdrop(true);
    }

    // ========================================================================
    // Getters
    // ========================================================================

    public function getAnchor(): string
    {
        return $this->anchor;
    }

    public function getVariant(): string
    {
        return $this->variant;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function getMiniWidth(): ?string
    {
        return $this->miniWidth;
    }

    public function isOpen(): bool
    {
        return $this->open;
    }

    public function hasBackdrop(): bool
    {
        return $this->backdrop;
    }

    public function getBackdropColor(): ?string
    {
        return $this->backdropColor;
    }

    public function shouldCloseOnBackdrop(): bool
    {
        return $this->closeOnBackdrop;
    }

    public function shouldCloseOnEscape(): bool
    {
        return $this->closeOnEscape;
    }

    public function hasCloseButton(): bool
    {
        return $this->closeButton;
    }

    public function isDefaultOpen(): bool
    {
        return $this->defaultOpen;
    }

    public function shouldExpandOnHover(): bool
    {
        return $this->expandOnHover;
    }

    public function getHeader(): ?array
    {
        return $this->header;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function getFooter(): ?array
    {
        return $this->footer;
    }

    public function getTrigger(): ?array
    {
        return $this->trigger;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function getLayoutConfig(): ?array
    {
        return $this->layoutConfig;
    }

    // ========================================================================
    // Array Conversion
    // ========================================================================

    public function toArray(): array
    {
        return array_merge($this->getCommonProperties(), $this->filterNullValues([
            'anchor' => $this->anchor,
            'variant' => $this->variant,
            'open' => $this->open ? true : null,
            'defaultOpen' => $this->defaultOpen ? true : null,
            'width' => $this->width,
            'height' => $this->height,
            'miniWidth' => $this->miniWidth,
            'backdrop' => $this->backdrop ? true : null,
            'backdropColor' => $this->backdropColor,
            'closeOnBackdrop' => $this->closeOnBackdrop ? true : null,
            'closeOnEscape' => $this->closeOnEscape ? true : null,
            'closeButton' => $this->closeButton ? true : null,
            'expandOnHover' => $this->expandOnHover ? true : null,
            'header' => $this->header,
            'content' => $this->content,
            'footer' => $this->footer,
            'trigger' => $this->trigger,
            'layout' => $this->layout,
            'layoutConfig' => $this->layoutConfig,
            'backgroundColor' => $this->backgroundColor,
            'textColor' => $this->textColor,
            'elevation' => $this->elevation,
            'borderRadius' => $this->borderRadius,
            'transition' => $this->transition,
            'transitionDuration' => $this->transitionDuration,
            'parent' => $this->parent,
            'maxWidth' => $this->maxWidth,
            'maxHeight' => $this->maxHeight,
        ]));
    }
}
