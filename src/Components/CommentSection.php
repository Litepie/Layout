<?php

namespace Litepie\Layout\Components;

class CommentSection extends BaseComponent
{
    protected bool $threaded = true; // Support nested replies
    protected int $maxDepth = 3; // Maximum nesting level
    protected bool $voting = true; // Upvote/downvote
    protected bool $editing = true;
    protected bool $deleting = true;
    protected string $sortOrder = 'newest'; // newest, oldest, popular, controversial
    protected bool $mentioning = true; // @username mentions
    protected bool $markdown = false;

    public function __construct(string $name)
    {
        parent::__construct($name, 'comment');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function threaded(bool $threaded = true): self
    {
        $this->threaded = $threaded;
        return $this;
    }

    public function maxDepth(int $depth): self
    {
        $this->maxDepth = $depth;
        return $this;
    }

    public function voting(bool $voting = true): self
    {
        $this->voting = $voting;
        return $this;
    }

    public function editing(bool $editing = true): self
    {
        $this->editing = $editing;
        return $this;
    }

    public function deleting(bool $deleting = true): self
    {
        $this->deleting = $deleting;
        return $this;
    }

    public function sortOrder(string $order): self
    {
        $this->sortOrder = $order;
        return $this;
    }

    public function mentioning(bool $mentioning = true): self
    {
        $this->mentioning = $mentioning;
        return $this;
    }

    public function markdown(bool $markdown = true): self
    {
        $this->markdown = $markdown;
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
            'threaded' => $this->threaded,
            'max_depth' => $this->maxDepth,
            'voting' => $this->voting,
            'editing' => $this->editing,
            'deleting' => $this->deleting,
            'sort_order' => $this->sortOrder,
            'mentioning' => $this->mentioning,
            'markdown' => $this->markdown,
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
