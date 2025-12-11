<?php

namespace Litepie\Layout;

use Litepie\Layout\Components\AccordionSection;
use Litepie\Layout\Components\AlertSection;
use Litepie\Layout\Components\BadgeSection;
use Litepie\Layout\Components\CardSection;
use Litepie\Layout\Components\ChartSection;
use Litepie\Layout\Components\CommentSection;
use Litepie\Layout\Components\CustomSection;
use Litepie\Layout\Components\FormSection;
use Litepie\Layout\Components\GridSection;
use Litepie\Layout\Components\ListSection;
use Litepie\Layout\Components\MediaSection;
use Litepie\Layout\Components\ModalSection;
use Litepie\Layout\Components\ScrollSpySection;
use Litepie\Layout\Components\StatsSection;
use Litepie\Layout\Components\TableSection;
use Litepie\Layout\Components\TabsSection;
use Litepie\Layout\Components\TextSection;
use Litepie\Layout\Components\TimelineSection;
use Litepie\Layout\Components\WizardSection;
use Litepie\Layout\Contracts\Component;
use Litepie\Layout\Traits\Cacheable;
use Litepie\Layout\Traits\Debuggable;
use Litepie\Layout\Traits\Exportable;
use Litepie\Layout\Traits\HandlesComputedFields;
use Litepie\Layout\Traits\Testable;

class LayoutBuilder
{
    use Cacheable, Debuggable, Exportable, HandlesComputedFields, Testable;

    protected string $name;

    protected string $mode;

    protected array $sections = [];

    protected ?string $sharedDataUrl = null; // Single API endpoint for all components

    protected array $sharedDataParams = [];

    public function __construct(string $name, string $mode)
    {
        $this->name = $name;
        $this->mode = $mode;
    }

    /**
     * Set a shared data URL for all components to use
     */
    public function sharedDataUrl(string $url): self
    {
        $this->sharedDataUrl = $url;

        return $this;
    }

    /**
     * Set shared data parameters
     */
    public function sharedDataParams(array $params): self
    {
        $this->sharedDataParams = array_merge($this->sharedDataParams, $params);

        return $this;
    }

    public static function create(string $module, string $context): self
    {
        return new static($module, $context);
    }

    /**
     * Add a component to the layout
     */
    public function addComponent(Component $component): self
    {
        if (method_exists($component, 'getName')) {
            $this->sections[$component->getName()] = $component;
        } else {
            $this->sections[] = $component;
        }

        return $this;
    }

    /**
     * Create and add a FormSection
     */
    public function formSection(string $name): FormSection
    {
        $section = FormSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a TextSection
     */
    public function textSection(string $name): TextSection
    {
        $section = TextSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a CardSection
     */
    public function cardSection(string $name): CardSection
    {
        $section = CardSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a TableSection
     */
    public function tableSection(string $name): TableSection
    {
        $section = TableSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a GridSection
     */
    public function gridSection(string $name): GridSection
    {
        $section = GridSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a StatsSection
     */
    public function statsSection(string $name): StatsSection
    {
        $section = StatsSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a TabsSection
     */
    public function tabsSection(string $name): TabsSection
    {
        $section = TabsSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add an AccordionSection
     */
    public function accordionSection(string $name): AccordionSection
    {
        $section = AccordionSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a ScrollSpySection
     */
    public function scrollSpySection(string $name): ScrollSpySection
    {
        $section = ScrollSpySection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a CustomSection
     */
    public function customSection(string $name, string $type = 'custom'): CustomSection
    {
        $section = CustomSection::make($name, $type);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a ListSection
     */
    public function listSection(string $name): ListSection
    {
        $section = ListSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a TimelineSection
     */
    public function timelineSection(string $name): TimelineSection
    {
        $section = TimelineSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add an AlertSection
     */
    public function alertSection(string $name): AlertSection
    {
        $section = AlertSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a ModalSection
     */
    public function modalSection(string $name): ModalSection
    {
        $section = ModalSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a WizardSection
     */
    public function wizardSection(string $name): WizardSection
    {
        $section = WizardSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a ChartSection
     */
    public function chartSection(string $name): ChartSection
    {
        $section = ChartSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a MediaSection
     */
    public function mediaSection(string $name): MediaSection
    {
        $section = MediaSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a CommentSection
     */
    public function commentSection(string $name): CommentSection
    {
        $section = CommentSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Create and add a BadgeSection
     */
    public function badgeSection(string $name): BadgeSection
    {
        $section = BadgeSection::make($name);
        $this->addComponent($section);

        return $section;
    }

    /**
     * Legacy support: Create a Section (old structure)
     *
     * @deprecated Use formSection() or other specific section types
     */
    public function section(string $name): Section
    {
        $section = new Section($name, $this);
        $this->sections[$name] = $section;

        return $section;
    }

    /**
     * Legacy support: Add a Section
     *
     * @deprecated Use addComponent() instead
     */
    public function addSection(Component $section): self
    {
        $this->sections[$section->getName()] = $section;

        return $this;
    }

    public function getModule(): string
    {
        return $this->name;
    }

    public function getContext(): string
    {
        return $this->mode;
    }

    /**
     * Get all components
     */
    public function getComponents(): array
    {
        return $this->sections;
    }

    /**
     * Legacy support: Get sections
     *
     * @deprecated Use getComponents() instead
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * Get component by name
     */
    public function getComponent(string $name): ?Component
    {
        return $this->sections[$name] ?? null;
    }

    /**
     * Legacy support: Get section
     *
     * @deprecated Use getComponent() instead
     */
    public function getSection(string $name): mixed
    {
        return $this->getComponent($name);
    }

    public function build(): Layout
    {
        return new Layout($this->name, $this->mode, $this->sections);
    }

    public function toArray(): array
    {
        return [
            'module' => $this->name,
            'context' => $this->mode,
            'shared_data_url' => $this->sharedDataUrl,
            'shared_data_params' => $this->sharedDataParams,
            'components' => array_map(
                fn ($component) => method_exists($component, 'toArray') ? $component->toArray() : (array) $component,
                $this->components
            ),
        ];
    }
}
