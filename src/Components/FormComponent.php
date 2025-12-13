<?php

namespace Litepie\Layout\Components;

class FormComponent extends BaseComponent
{
    protected array $formFields = [];

    protected array $buttons = [];

    protected ?string $label = null;

    protected ?string $action = null;

    protected string $method = 'POST';

    protected ?string $enctype = null;

    protected array $validation = [];

    protected int $formColumns = 1;

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

    public function action(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function method(string $method): self
    {
        $this->method = strtoupper($method);

        return $this;
    }

    public function enctype(string $enctype): self
    {
        $this->enctype = $enctype;

        return $this;
    }

    public function validationRules(array $rules): self
    {
        $this->validation = array_merge($this->validation, $rules);

        return $this;
    }

    public function columns(int $columns): self
    {
        $this->formColumns = $columns;

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

    /**
     * Add a field to the form
     * Usage: addField('email', 'email', 'Email Address', ['required' => true])
     */
    public function addField(string $name, string $type, string $label, array $options = []): self
    {
        $field = array_merge([
            'name' => $name,
            'type' => $type,
            'label' => $label,
        ], $options);

        $this->formFields[$name] = $field;

        return $this;
    }

    /**
     * Add a button to the form
     * Usage: addButton('submit', 'Submit', 'submit')
     */
    public function addButton(string $name, string $label, string $type = 'button', array $options = []): self
    {
        $button = array_merge([
            'name' => $name,
            'label' => $label,
            'type' => $type,
        ], $options);

        $this->buttons[] = $button;

        return $this;
    }

    public function addFormField($field): self
    {
        if (is_object($field) && method_exists($field, 'getName')) {
            $this->formFields[$field->getName()] = $field;
        } elseif (is_array($field) && isset($field['name'])) {
            $this->formFields[$field['name']] = $field;
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
            'action' => $this->action,
            'method' => $this->method,
            'enctype' => $this->enctype,
            'fields' => array_map(fn ($field) => (is_object($field) && method_exists($field, 'toArray')) ? $field->toArray() : (array) $field, $this->formFields),
            'buttons' => $this->buttons,
            'validation' => $this->validation,
            'columns' => $this->formColumns,
            'gap' => $this->gap,
            'collapsible' => $this->collapsible,
            'collapsed' => $this->collapsed,
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
