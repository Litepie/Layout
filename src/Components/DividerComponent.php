<?php

namespace Litepie\Layout\Components;

/**
 * DividerComponent
 *
 * Visual separator component for organizing content sections.
 * Supports horizontal and vertical dividers, text labels, icons, and various styles.
 */
class DividerComponent extends BaseComponent
{
    // Orientation
    protected string $orientation = 'horizontal'; // horizontal, vertical

    // Style variants
    protected string $variant = 'solid'; // solid, dashed, dotted, double, gradient

    // Thickness/size
    protected string $thickness = 'thin'; // thin, medium, thick

    protected ?string $customThickness = null; // Custom thickness (e.g., "2px")

    // Color
    protected ?string $color = null; // Custom color

    protected string $colorVariant = 'default'; // default, primary, secondary, muted, accent

    // Spacing
    protected string $spacing = 'md'; // xs, sm, md, lg, xl

    protected ?string $marginTop = null;

    protected ?string $marginBottom = null;

    protected ?string $marginLeft = null;

    protected ?string $marginRight = null;

    // Label/text on divider
    protected ?string $label = null;

    protected string $labelPosition = 'center'; // left, center, right (for horizontal) / top, center, bottom (for vertical)

    protected ?string $labelIcon = null;

    protected bool $iconOnly = false;

    // Gradient options
    protected ?string $gradientFrom = null;

    protected ?string $gradientTo = null;

    protected string $gradientDirection = 'to-r'; // to-r, to-l, to-t, to-b

    // Decorative elements
    protected bool $withDots = false; // Add decorative dots

    protected bool $withCircle = false; // Add circle in center

    protected ?string $ornament = null; // Custom ornament/decoration

    // Shadow/glow
    protected bool $withShadow = false;

    protected bool $withGlow = false;

    protected ?string $glowColor = null;

    // Length
    protected ?string $width = null; // For horizontal dividers

    protected ?string $height = null; // For vertical dividers

    protected bool $fullWidth = true; // Full width for horizontal

    protected bool $fullHeight = false; // Full height for vertical

    // Inset divider (indented from edges)
    protected bool $inset = false;

    protected ?string $insetSize = null;

    public function __construct(string $name)
    {
        parent::__construct($name, 'divider');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    // ========================================================================
    // Orientation Methods
    // ========================================================================

    /**
     * Set divider orientation
     */
    public function orientation(string $orientation): self
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function horizontal(): self
    {
        return $this->orientation('horizontal');
    }

    public function vertical(): self
    {
        return $this->orientation('vertical');
    }

    // ========================================================================
    // Style Variant Methods
    // ========================================================================

    /**
     * Set style variant
     */
    public function variant(string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function solid(): self
    {
        return $this->variant('solid');
    }

    public function dashed(): self
    {
        return $this->variant('dashed');
    }

    public function dotted(): self
    {
        return $this->variant('dotted');
    }

    public function double(): self
    {
        return $this->variant('double');
    }

    public function gradient(): self
    {
        return $this->variant('gradient');
    }

    // ========================================================================
    // Thickness Methods
    // ========================================================================

    /**
     * Set thickness
     */
    public function thickness(string $thickness): self
    {
        $this->thickness = $thickness;

        return $this;
    }

    public function thin(): self
    {
        return $this->thickness('thin');
    }

    public function medium(): self
    {
        return $this->thickness('medium');
    }

    public function thick(): self
    {
        return $this->thickness('thick');
    }

    /**
     * Set custom thickness
     */
    public function customThickness(string $thickness): self
    {
        $this->customThickness = $thickness;

        return $this;
    }

    // ========================================================================
    // Color Methods
    // ========================================================================

    /**
     * Set custom color
     */
    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set color variant
     */
    public function colorVariant(string $variant): self
    {
        $this->colorVariant = $variant;

        return $this;
    }

    public function primary(): self
    {
        return $this->colorVariant('primary');
    }

    public function secondary(): self
    {
        return $this->colorVariant('secondary');
    }

    public function muted(): self
    {
        return $this->colorVariant('muted');
    }

    public function accent(): self
    {
        return $this->colorVariant('accent');
    }

    // ========================================================================
    // Spacing Methods
    // ========================================================================

    /**
     * Set spacing (margin top and bottom for horizontal, left and right for vertical)
     */
    public function spacing(string $spacing): self
    {
        $this->spacing = $spacing;

        return $this;
    }

    /**
     * Set custom margins
     */
    public function marginTop(string $margin): self
    {
        $this->marginTop = $margin;

        return $this;
    }

    public function marginBottom(string $margin): self
    {
        $this->marginBottom = $margin;

        return $this;
    }

    public function marginLeft(string $margin): self
    {
        $this->marginLeft = $margin;

        return $this;
    }

    public function marginRight(string $margin): self
    {
        $this->marginRight = $margin;

        return $this;
    }

    /**
     * Set vertical margins (top and bottom)
     */
    public function marginY(string $margin): self
    {
        $this->marginTop = $margin;
        $this->marginBottom = $margin;

        return $this;
    }

    /**
     * Set horizontal margins (left and right)
     */
    public function marginX(string $margin): self
    {
        $this->marginLeft = $margin;
        $this->marginRight = $margin;

        return $this;
    }

    // ========================================================================
    // Label Methods
    // ========================================================================

    /**
     * Set label text
     */
    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set label position
     */
    public function labelPosition(string $position): self
    {
        $this->labelPosition = $position;

        return $this;
    }

    public function labelLeft(): self
    {
        return $this->labelPosition('left');
    }

    public function labelCenter(): self
    {
        return $this->labelPosition('center');
    }

    public function labelRight(): self
    {
        return $this->labelPosition('right');
    }

    /**
     * Set label icon
     */
    public function labelIcon(string $icon): self
    {
        $this->labelIcon = $icon;

        return $this;
    }

    /**
     * Use icon only (no text)
     */
    public function iconOnly(bool $iconOnly = true): self
    {
        $this->iconOnly = $iconOnly;

        return $this;
    }

    // ========================================================================
    // Gradient Methods
    // ========================================================================

    /**
     * Set gradient colors
     */
    public function gradientColors(string $from, string $to, ?string $direction = null): self
    {
        $this->gradientFrom = $from;
        $this->gradientTo = $to;
        $this->variant = 'gradient';

        if ($direction !== null) {
            $this->gradientDirection = $direction;
        }

        return $this;
    }

    /**
     * Set gradient direction
     */
    public function gradientDirection(string $direction): self
    {
        $this->gradientDirection = $direction;

        return $this;
    }

    // ========================================================================
    // Decorative Methods
    // ========================================================================

    /**
     * Add decorative dots
     */
    public function withDots(bool $withDots = true): self
    {
        $this->withDots = $withDots;

        return $this;
    }

    /**
     * Add circle in center
     */
    public function withCircle(bool $withCircle = true): self
    {
        $this->withCircle = $withCircle;

        return $this;
    }

    /**
     * Set custom ornament
     */
    public function ornament(string $ornament): self
    {
        $this->ornament = $ornament;

        return $this;
    }

    // ========================================================================
    // Shadow/Glow Methods
    // ========================================================================

    /**
     * Add shadow effect
     */
    public function withShadow(bool $withShadow = true): self
    {
        $this->withShadow = $withShadow;

        return $this;
    }

    /**
     * Add glow effect
     */
    public function withGlow(bool $withGlow = true, ?string $color = null): self
    {
        $this->withGlow = $withGlow;

        if ($color !== null) {
            $this->glowColor = $color;
        }

        return $this;
    }

    // ========================================================================
    // Size/Length Methods
    // ========================================================================

    /**
     * Set width (for horizontal dividers)
     */
    public function width(string $width): self
    {
        $this->width = $width;
        $this->fullWidth = false;

        return $this;
    }

    /**
     * Set height (for vertical dividers)
     */
    public function height(string $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Set to full width
     */
    public function fullWidth(bool $fullWidth = true): self
    {
        $this->fullWidth = $fullWidth;

        return $this;
    }

    /**
     * Set to full height
     */
    public function fullHeight(bool $fullHeight = true): self
    {
        $this->fullHeight = $fullHeight;

        return $this;
    }

    // ========================================================================
    // Inset Methods
    // ========================================================================

    /**
     * Make divider inset (indented from edges)
     */
    public function inset(bool $inset = true, ?string $size = null): self
    {
        $this->inset = $inset;

        if ($size !== null) {
            $this->insetSize = $size;
        }

        return $this;
    }

    /**
     * Set inset size
     */
    public function insetSize(string $size): self
    {
        $this->insetSize = $size;
        $this->inset = true;

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

            // Orientation
            'orientation' => $this->orientation,

            // Style
            'variant' => $this->variant,
            'thickness' => $this->thickness,
            'custom_thickness' => $this->customThickness,

            // Color
            'color' => $this->color,
            'color_variant' => $this->colorVariant,

            // Spacing
            'spacing' => $this->spacing,
            'margin_top' => $this->marginTop,
            'margin_bottom' => $this->marginBottom,
            'margin_left' => $this->marginLeft,
            'margin_right' => $this->marginRight,

            // Label
            'label' => $this->label,
            'label_position' => $this->labelPosition,
            'label_icon' => $this->labelIcon,
            'icon_only' => $this->iconOnly,

            // Gradient
            'gradient_from' => $this->gradientFrom,
            'gradient_to' => $this->gradientTo,
            'gradient_direction' => $this->gradientDirection,

            // Decorative
            'with_dots' => $this->withDots,
            'with_circle' => $this->withCircle,
            'ornament' => $this->ornament,

            // Effects
            'with_shadow' => $this->withShadow,
            'with_glow' => $this->withGlow,
            'glow_color' => $this->glowColor,

            // Size
            'width' => $this->width,
            'height' => $this->height,
            'full_width' => $this->fullWidth,
            'full_height' => $this->fullHeight,

            // Inset
            'inset' => $this->inset,
            'inset_size' => $this->insetSize,

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
