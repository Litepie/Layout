<?php

namespace Litepie\Layout\Components;

class TableComponent extends BaseComponent
{
    protected array $tableColumns = [];

    protected bool $searchable = false;

    protected bool $sortable = false;

    protected bool $paginated = false;

    protected ?int $perPage = null;

    protected ?string $sortColumn = null;

    protected string $sortDirection = 'asc';

    public function __construct(string $name)
    {
        parent::__construct($name, 'table');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function columns(array $columns): self
    {
        $this->tableColumns = $columns;

        return $this;
    }

    public function searchable(bool $searchable = true): self
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function sortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    public function paginated(bool $paginated = true): self
    {
        $this->paginated = $paginated;

        return $this;
    }

    public function perPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function defaultSort(string $column, string $direction = 'asc'): self
    {
        $this->sortColumn = $column;
        $this->sortDirection = $direction;

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
            'columns' => $this->tableColumns,
            'searchable' => $this->searchable,
            'sortable' => $this->sortable,
            'paginated' => $this->paginated,
            'per_page' => $this->perPage,
            'sort_column' => $this->sortColumn,
            'sort_direction' => $this->sortDirection,
            'data_source' => $this->dataSource,
            'data_url' => $this->dataUrl,
            'data_params' => $this->dataParams,
            'data_transform' => $this->dataTransform,
            'load_on_mount' => $this->loadOnMount,
            'reload_on_change' => $this->reloadOnChange,
            'use_shared_data' => $this->useSharedData,
            'data_key' => $this->dataKey,
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
