<?php

namespace Litepie\Layout\Components;

class FormSection extends BaseComponent
{
    protected array $formFields = [];

    protected ?string $label = null;

    protected ?string $description = null;

    protected int $columns = 1;

    protected string $gap = 'md';

    protected bool $collapsible = false;

    protected bool $collapsed = false;

    public function __construct(string $name)
    {
        parent::__construct($name, 'form');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function columns(int $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function gap(string $gap): self
    {
        $this->gap = $gap;

        return $this;
    }

    public function collapsible(bool $collapsible = true): self
    {
        $this->collapsible = $collapsible;

        return $this;
    }

    public function collapsed(bool $collapsed = true): self
    {
        $this->collapsed = $collapsed;

        return $this;
    }

    public function addFormField($field): self
    {
        if (method_exists($field, 'getName')) {
            $this->formFields[$field->getName()] = $field;
        } else {
            $this->formFields[] = $field;
        }

        return $this;
    }

    public function addFormFields(array $fields): self
    {
        foreach ($fields as $field) {
            $this->addFormField($field);
        }

        return $this;
    }

    public function getFormFields(): array
    {
        return $this->formFields;
    }

    public function getFormField(string $name)
    {
        return $this->formFields[$name] ?? null;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'label' => $this->label,
            'description' => $this->description,
            'fields' => array_map(fn ($field) => method_exists($field, 'toArray') ? $field->toArray() : (array) $field, $this->formFields),
            'columns' => $this->columns,
            'gap' => $this->gap,
            'collapsible' => $this->collapsible,
            'collapsed' => $this->collapsed,
            'actions' => $this->actions,
            'sections' => array_map(
                fn ($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp,
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
