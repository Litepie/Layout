<?php

namespace Litepie\Layout\Components;

class CardComponent extends BaseComponent
{
    protected ?string $image = null;

    protected string $variant = 'default'; // default, outlined, elevated

    protected array $fields = [];

    protected array $header = [];

    protected array $footer = [];

    public function __construct(string $name)
    {
        parent::__construct($name, 'card');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function image(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function variant(string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * Add a field to display in the card
     * Usage: addField('email', 'Email Address', 'john@example.com')
     * Or: addField('email', 'Email Address') - value will come from dataSource
     */
    public function addField(string $name, string $label, mixed $value = null): self
    {
        $this->fields[] = [
            'name' => $name,
            'label' => $label,
            'value' => $value,
        ];

        return $this;
    }

    /**
     * Add a custom header item
     * Usage: addHeader('badge', 'Status', ['variant' => 'success', 'text' => 'Active'])
     */
    public function addHeader(string $type, string $label, array $options = []): self
    {
        $this->header[] = array_merge([
            'type' => $type,
            'label' => $label,
        ], $options);

        return $this;
    }

    /**
     * Add an action button to the card header
     * Usage: addHeaderAction('Edit', '/edit', ['variant' => 'primary', 'icon' => 'edit'])
     */
    public function addHeaderAction(string $label, string $action, array $options = []): self
    {
        $this->header[] = array_merge([
            'type' => 'action',
            'label' => $label,
            'action' => $action,
        ], $options);

        return $this;
    }

    /**
     * Add a dropdown menu to the card header
     * Usage: addHeaderDropdown('Actions', [
     *     ['label' => 'Edit', 'action' => '/edit'],
     *     ['label' => 'Delete', 'action' => '/delete', 'variant' => 'danger']
     * ], ['icon' => 'more-vertical'])
     */
    public function addHeaderDropdown(string $label, array $items, array $options = []): self
    {
        $this->header[] = array_merge([
            'type' => 'dropdown',
            'label' => $label,
            'items' => $items,
        ], $options);

        return $this;
    }

    /**
     * Set the entire header array
     */
    public function header(array $header): self
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Add a footer button or element
     * Usage: addFooter('Edit', '/edit', ['variant' => 'primary', 'icon' => 'edit'])
     */
    public function addFooter(string $label, string $action, array $options = []): self
    {
        $this->footer[] = array_merge([
            'type' => 'action',
            'label' => $label,
            'action' => $action,
        ], $options);

        return $this;
    }

    /**
     * Add an action button to the card footer
     * Usage: addFooterAction('Save', '/save', ['variant' => 'primary', 'icon' => 'save'])
     */
    public function addFooterAction(string $label, string $action, array $options = []): self
    {
        return $this->addFooter($label, $action, $options);
    }

    /**
     * Add a dropdown menu to the card footer
     * Usage: addFooterDropdown('More Actions', [
     *     ['label' => 'Archive', 'action' => '/archive'],
     *     ['label' => 'Export', 'action' => '/export'],
     *     'divider',
     *     ['label' => 'Delete', 'action' => '/delete', 'variant' => 'danger']
     * ])
     */
    public function addFooterDropdown(string $label, array $items, array $options = []): self
    {
        $this->footer[] = array_merge([
            'type' => 'dropdown',
            'label' => $label,
            'items' => $items,
        ], $options);

        return $this;
    }

    /**
     * Set the entire footer array
     */
    public function footer(array $footer): self
    {
        $this->footer = $footer;

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
            'description' => $this->description,
            'image' => $this->image,
            'variant' => $this->variant,
            'fields' => $this->fields,
            'header' => $this->header,
            'footer' => $this->footer,
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
