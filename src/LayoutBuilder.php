<?php

namespace Litepie\Layout;

use Litepie\Layout\Traits\HandlesComputedFields;

class LayoutBuilder
{
    use HandlesComputedFields;

    protected string $module;

    protected string $context;

    protected array $sections = [];

    protected ?Section $currentSection = null;

    public function __construct(string $module, string $context)
    {
        $this->module = $module;
        $this->context = $context;
    }

    public static function create(string $module, string $context): self
    {
        return new static($module, $context);
    }

    public function section(string $name): Section
    {
        $section = new Section($name, $this);
        $this->sections[$name] = $section;
        $this->currentSection = $section;

        return $section;
    }

    public function addSection(Section $section): self
    {
        $this->sections[$section->getName()] = $section;

        return $this;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    public function getSection(string $name): ?Section
    {
        return $this->sections[$name] ?? null;
    }

    public function build(): Layout
    {
        return new Layout($this->module, $this->context, $this->sections);
    }

    public function toArray(): array
    {
        return [
            'module' => $this->module,
            'context' => $this->context,
            'sections' => array_map(fn ($section) => $section->toArray(), $this->sections),
        ];
    }
}
