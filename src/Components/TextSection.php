<?php

namespace Litepie\Layout\Components;

class TextSection extends BaseComponent
{
    protected ?string $content = null;
    protected string $size = 'md';
    protected string $align = 'left';

    public function __construct(string $name)
    {
        parent::__construct($name, 'text');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function content(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function size(string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function align(string $align): self
    {
        $this->align = $align;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'content' => $this->content,
            'size' => $this->size,
            'align' => $this->align,
            'actions' => $this->actions,
            'sections' => array_map(
                fn($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp,
                $this->sections
            ),
            'order' => $this->order,
            'visible' => $this->visible,
            'permissions' => $this->permissions,
            'roles' => $this->roles,
            'authorized_to_see' => $this->authorizedToSee,
            'meta' => $this->meta,
        ];
    }
}
