<?php

namespace Litepie\Layout\Components;

class TableComponent extends BaseComponent
{
    protected array $tableColumns = [];

    protected array $tableData = [];

    protected bool $searchable = false;

    protected bool $sortable = false;

    protected bool $filterable = false;

    protected bool $selectable = false;

    protected bool $paginated = false;

    protected ?int $perPage = null;

    protected ?string $sortColumn = null;

    protected string $sortDirection = 'asc';

    protected bool $hoverable = false;

    protected bool $striped = false;

    public function __construct(string $name)
    {
        parent::__construct($name, 'table');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * Add a column to the table
     */
    public function addColumn(string $key, string $label, array $options = []): self
    {
        $this->tableColumns[] = array_merge([
            'key' => $key,
            'label' => $label,
        ], $options);

        return $this;
    }

    public function columns(array $columns): self
    {
        $this->tableColumns = $columns;

        return $this;
    }

    public function data(array $data): self
    {
        $this->tableData = $data;

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

    public function filterable(bool $filterable = true): self
    {
        $this->filterable = $filterable;

        return $this;
    }

    public function selectable(bool $selectable = true): self
    {
        $this->selectable = $selectable;

        return $this;
    }

    public function hoverable(bool $hoverable = true): self
    {
        $this->hoverable = $hoverable;

        return $this;
    }

    public function striped(bool $striped = true): self
    {
        $this->striped = $striped;

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

    /**
     * Alias for perPage() - enables pagination and sets per page count
     */
    public function paginate(int $perPage): self
    {
        $this->paginated = true;
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Alias for paginate() - enables pagination and sets per page count
     */
    public function pagination(int $perPage): self
    {
        return $this->paginate($perPage);
    }

    public function defaultSort(string $column, string $direction = 'asc'): self
    {
        $this->sortColumn = $column;
        $this->sortDirection = $direction;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge($this->getCommonProperties(), $this->filterNullValues([
            'columns' => $this->tableColumns,
            'data' => $this->tableData,
            'searchable' => $this->searchable,
            'sortable' => $this->sortable,
            'filterable' => $this->filterable,
            'selectable' => $this->selectable,
            'hoverable' => $this->hoverable,
            'striped' => $this->striped,
            'paginated' => $this->paginated,
            'per_page' => $this->perPage,
            'sort_column' => $this->sortColumn,
            'sort_direction' => $this->sortDirection,
        ]));
    }
}
