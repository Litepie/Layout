<?php

namespace Litepie\Layout\Components;

class MediaSection extends BaseComponent
{
    protected string $mediaType = 'image'; // image, video, gallery, audio
    protected string $layout = 'grid'; // grid, masonry, carousel
    protected int $mediaColumns = 3;
    protected string $aspectRatio = '16:9';
    protected bool $lightbox = true;
    protected bool $captions = true;
    protected array $items = []; // Item configurations

    public function __construct(string $name)
    {
        parent::__construct($name, 'media');
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function mediaType(string $type): self
    {
        $this->mediaType = $type;
        return $this;
    }

    public function image(): self
    {
        return $this->mediaType('image');
    }

    public function video(): self
    {
        return $this->mediaType('video');
    }

    public function gallery(): self
    {
        return $this->mediaType('gallery');
    }

    public function audio(): self
    {
        return $this->mediaType('audio');
    }

    public function layout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    public function grid(): self
    {
        return $this->layout('grid');
    }

    public function masonry(): self
    {
        return $this->layout('masonry');
    }

    public function carousel(): self
    {
        return $this->layout('carousel');
    }

    public function columns(int $columns): self
    {
        $this->mediaColumns = $columns;
        return $this;
    }

    public function aspectRatio(string $ratio): self
    {
        $this->aspectRatio = $ratio;
        return $this;
    }

    public function lightbox(bool $lightbox = true): self
    {
        $this->lightbox = $lightbox;
        return $this;
    }

    public function captions(bool $captions = true): self
    {
        $this->captions = $captions;
        return $this;
    }

    /**
     * Add media item configuration
     */
    public function addItem(string $key, array $options = []): self
    {
        $this->items[] = [
            'key' => $key,
            'alt' => $options['alt'] ?? null,
            'caption' => $options['caption'] ?? null,
        ];
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
            'media_type' => $this->mediaType,
            'layout' => $this->layout,
            'columns' => $this->mediaColumns,
            'aspect_ratio' => $this->aspectRatio,
            'lightbox' => $this->lightbox,
            'captions' => $this->captions,
            'items' => $this->items,
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
