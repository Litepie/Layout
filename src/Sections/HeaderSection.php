<?php

namespace Litepie\Layout\Sections;

/**
 * HeaderComponent
 *
 * A component representing a page or section header with predefined section slots.
 * Common use case: Navigation headers, page titles, action bars
 *
 * Predefined sections:
 * - left: Left-aligned content (logo, brand)
 * - center: Center-aligned content (navigation, title)
 * - right: Right-aligned content (actions, user menu, search)
 */
class HeaderSection extends BaseSection
{
    protected array $allowedSections = ['left', 'center', 'right'];

    protected string $variant = 'default'; // default, sticky, transparent, bordered

    protected ?string $background = null;

    protected ?int $height = null;

    public function __construct(string $name)
    {
        parent::__construct($name, 'header');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * Set header variant
     */
    public function variant(string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * Make header sticky
     */
    public function sticky(): self
    {
        return $this->variant('sticky');
    }

    /**
     * Make header transparent
     */
    public function transparent(): self
    {
        return $this->variant('transparent');
    }

    /**
     * Add border to header
     */
    public function bordered(): self
    {
        return $this->variant('bordered');
    }

    /**
     * Set background color or gradient
     */
    public function background(string $background): self
    {
        $this->background = $background;

        return $this;
    }

    /**
     * Set header height
     */
    public function height(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge($this->getCommonProperties(), [
            'variant' => $this->variant,
            'background' => $this->background,
            'height' => $this->height,
        ]);
    }
}
