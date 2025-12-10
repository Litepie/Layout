<?php

namespace Litepie\Layout\Examples;

use Litepie\Layout\Facades\Layout;

class ProductFormLayout
{
    /**
     * Register the product form layout
     */
    public static function register(): void
    {
        Layout::register('product', 'form', function ($builder) {
            $builder
                ->section('basic_info')
                    ->label('Basic Information')
                    ->description('Essential product details')
                    ->subsection('details')
                        ->label('Product Details')
                        ->field('name')
                            ->type('text')
                            ->label('Product Name')
                            ->required()
                            ->maxLength(100)
                        ->end()
                        ->field('sku')
                            ->type('text')
                            ->label('SKU')
                            ->required()
                            ->maxLength(50)
                        ->end()
                        ->field('description')
                            ->type('textarea')
                            ->label('Description')
                            ->placeholder('Enter product description')
                            ->maxLength(1000)
                        ->end()
                        ->field('category')
                            ->type('select')
                            ->label('Category')
                            ->options([
                                'electronics' => 'Electronics',
                                'clothing' => 'Clothing',
                                'books' => 'Books',
                                'home' => 'Home & Garden',
                            ])
                            ->required()
                        ->end()
                    ->endSubsection()
                ->endSection()
                ->section('pricing')
                    ->label('Pricing & Inventory')
                    ->subsection('price_details')
                        ->label('Price Information')
                        ->field('price')
                            ->type('number')
                            ->label('Price')
                            ->placeholder('0.00')
                            ->required()
                            ->min(0)
                        ->end()
                        ->field('sale_price')
                            ->type('number')
                            ->label('Sale Price')
                            ->placeholder('0.00')
                            ->min(0)
                        ->end()
                        ->field('cost')
                            ->type('number')
                            ->label('Cost')
                            ->placeholder('0.00')
                            ->min(0)
                        ->end()
                    ->endSubsection()
                    ->subsection('inventory')
                        ->label('Inventory')
                        ->field('stock_quantity')
                            ->type('number')
                            ->label('Stock Quantity')
                            ->default(0)
                            ->min(0)
                        ->end()
                        ->field('track_inventory')
                            ->type('checkbox')
                            ->label('Track Inventory')
                            ->default(true)
                        ->end()
                        ->field('allow_backorders')
                            ->type('checkbox')
                            ->label('Allow Backorders')
                            ->default(false)
                        ->end()
                    ->endSubsection()
                ->endSection()
                ->section('media')
                    ->label('Media')
                    ->subsection('images')
                        ->label('Product Images')
                        ->field('featured_image')
                            ->type('file')
                            ->label('Featured Image')
                            ->accept('image/*')
                            ->maxSize(2048) // 2MB
                        ->end()
                        ->field('gallery_images')
                            ->type('file')
                            ->label('Gallery Images')
                            ->accept('image/*')
                            ->multiple()
                            ->maxSize(2048)
                        ->end()
                    ->endSubsection()
                ->endSection()
                ->section('seo')
                    ->label('SEO & Marketing')
                    ->subsection('seo_fields')
                        ->label('SEO Information')
                        ->field('meta_title')
                            ->type('text')
                            ->label('Meta Title')
                            ->maxLength(60)
                        ->end()
                        ->field('meta_description')
                            ->type('textarea')
                            ->label('Meta Description')
                            ->maxLength(160)
                        ->end()
                        ->field('tags')
                            ->type('text')
                            ->label('Tags')
                            ->placeholder('Comma-separated tags')
                        ->end()
                    ->endSubsection()
                ->endSection();
        });
    }

    /**
     * Get the layout
     */
    public static function get(?int $userId = null): ?\Litepie\Layout\Layout
    {
        return Layout::get('product', 'form', $userId);
    }
}
