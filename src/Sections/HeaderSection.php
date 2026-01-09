<?php

namespace Litepie\Layout\Sections;

/**
 * HeaderSection
 *
 * A section representing a page or section header.
 * Common use case: Navigation headers, page titles, action bars
 *
 * No predefined slots - all content added via sections/components.
 */
class HeaderSection extends BaseSection
{
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
