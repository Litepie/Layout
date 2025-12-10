<?php

namespace Litepie\Layout;

use Litepie\Layout\Contracts\Renderable;
use Litepie\Layout\Contracts\Component;
use Litepie\Layout\Components\FormSection;
use Litepie\Layout\Components\GridSection;
use Litepie\Layout\Components\TabsSection;
use Litepie\Layout\Components\AccordionSection;
use Litepie\Layout\Components\ScrollSpySection;
use Litepie\Layout\Traits\Cacheable;
use Litepie\Layout\Traits\Testable;
use Litepie\Layout\Traits\Exportable;
use Litepie\Layout\Traits\Debuggable;

class Layout implements Renderable
{
    use Cacheable, Testable, Exportable, Debuggable;
    protected string $module;

    protected string $context;

    protected array $sections;
    protected array $components = [];
    protected array $meta = [];

    public function __construct(string $module, string $context, array $sectionsOrComponents = [])
    {
        $this->module = $module;
        $this->context = $context;
        
        // Support both old sections and new components
        if (!empty($sectionsOrComponents) && reset($sectionsOrComponents) instanceof Component) {
            $this->components = $sectionsOrComponents;
            $this->sections = []; // Empty for component-based layouts
        } else {
            $this->sections = $sectionsOrComponents;
        }
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    public function getSection(string $name): ?Section
    {
        return $this->sections[$name] ?? null;
    }

    /**
     * Get all components (v3.0+)
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * Get component by name (v3.0+)
     */
    public function getComponent(string $name): ?Component
    {
        return $this->components[$name] ?? null;
    }

    /**
     * Add a component to the layout (v3.0+)
     */
    public function addComponent(Component $component): self
    {
        if (method_exists($component, 'getName')) {
            $this->components[$component->getName()] = $component;
        } else {
            $this->components[] = $component;
        }
        return $this;
    }

    public function getSubsection(string $sectionName, string $subsectionName): ?Subsection
    {
        $section = $this->getSection($sectionName);

        return $section?->getSubsection($subsectionName);
    }

    /**
     * Get a specific form field from a subsection
     *
     * @return mixed|null
     */
    public function getFormField(string $sectionName, string $subsectionName, string $fieldName)
    {
        $subsection = $this->getSubsection($sectionName, $subsectionName);

        return $subsection?->getFormField($fieldName);
    }

    /**
     * Get all Litepie/Form fields from all subsections or components
     *
     * @return array
     */
    public function getAllFormFields(): array
    {
        $fields = [];
        
        // Component-based layouts (v3.0+)
        if (!empty($this->components)) {
            $this->collectFormFieldsRecursive($this->components, $fields);
            return $fields;
        }
        
        // Legacy Section/Subsection structure
        foreach ($this->sections as $section) {
            foreach ($section->getSubsections() as $subsection) {
                foreach ($subsection->getFormFields() as $field) {
                    $fields[] = $field;
                }
            }
        }

        return $fields;
    }

    /**
     * Helper to recursively collect form fields from components (v3.0+)
     */
    protected function collectFormFieldsRecursive(array $components, array &$fields): void
    {
        foreach ($components as $component) {
            // If it's a FormSection, get its form fields
            if ($component instanceof FormSection) {
                foreach ($component->getFormFields() as $field) {
                    $fields[] = $field;
                }
            }
            
            // Recurse into any component's nested sections (infinite nesting support)
            if (method_exists($component, 'getSections') && method_exists($component, 'hasSections')) {
                if ($component->hasSections()) {
                    $this->collectFormFieldsRecursive($component->getSections(), $fields);
                }
            }
            
            // If it's a TabsSection, recurse into each tab's components
            if ($component instanceof TabsSection && method_exists($component, 'getTabs')) {
                foreach ($component->getTabs() as $tab) {
                    if (!empty($tab['components'])) {
                        $this->collectFormFieldsRecursive($tab['components'], $fields);
                    }
                }
            }
            
            // If it's an AccordionSection, recurse into each item's components
            if ($component instanceof AccordionSection && method_exists($component, 'getItems')) {
                foreach ($component->getItems() as $item) {
                    if (!empty($item['components'])) {
                        $this->collectFormFieldsRecursive($item['components'], $fields);
                    }
                }
            }
            
            // If it's a ScrollSpySection, recurse into each section's components
            if ($component instanceof ScrollSpySection && method_exists($component, 'getSpySections')) {
                foreach ($component->getSpySections() as $section) {
                    if (!empty($section['components'])) {
                        $this->collectFormFieldsRecursive($section['components'], $fields);
                    }
                }
            }
            
            // Support legacy Section/Subsection structure
            if ($component instanceof Section) {
                foreach ($component->getSubsections() as $subsection) {
                    foreach ($subsection->getFormFields() as $field) {
                        $fields[] = $field;
                    }
                }
            }
        }
    }

    /**
     * Get a Litepie/Form field by name from anywhere in the layout
     *
     * @return mixed|null
     */
    public function getFormFieldByName(string $name)
    {
        foreach ($this->getAllFormFields() as $field) {
            if (method_exists($field, 'getName') && $field->getName() === $name) {
                return $field;
            }
        }

        return null;
    }

    public function meta(array $meta): self
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * Resolve authorization for all sections, subsections, fields, and components
     */
    public function resolveAuthorization($user = null): self
    {
        // Component-based layouts (v3.0+)
        if (!empty($this->components)) {
            $this->resolveComponentAuthorization($this->components, $user);
            return $this;
        }
        
        // Legacy Section/Subsection structure
        foreach ($this->sections as $section) {
            if (method_exists($section, 'resolveAuthorization')) {
                $section->resolveAuthorization($user);
            }
        }

        return $this;
    }

    /**
     * Helper to recursively resolve authorization for components (v3.0+)
     */
    protected function resolveComponentAuthorization(array $components, $user): void
    {
        foreach ($components as $component) {
            if (method_exists($component, 'resolveAuthorization')) {
                $component->resolveAuthorization($user);
            }
            
            // Recurse into nested components
            if ($component instanceof GridSection && method_exists($component, 'getComponents')) {
                $this->resolveComponentAuthorization($component->getComponents(), $user);
            }
            
            // Recurse into TabsSection tabs
            if ($component instanceof TabsSection && method_exists($component, 'getTabs')) {
                foreach ($component->getTabs() as $tab) {
                    if (!empty($tab['components'])) {
                        $this->resolveComponentAuthorization($tab['components'], $user);
                    }
                }
            }
            
            // Recurse into AccordionSection items
            if ($component instanceof AccordionSection && method_exists($component, 'getItems')) {
                foreach ($component->getItems() as $item) {
                    if (!empty($item['components'])) {
                        $this->resolveComponentAuthorization($item['components'], $user);
                    }
                }
            }
            
            // Recurse into ScrollSpySection sections
            if ($component instanceof ScrollSpySection && method_exists($component, 'getSpySections')) {
                foreach ($component->getSpySections() as $section) {
                    if (!empty($section['components'])) {
                        $this->resolveComponentAuthorization($section['components'], $user);
                    }
                }
            }
            
            // Support legacy Section/Subsection structure
            if ($component instanceof Section) {
                foreach ($component->getSubsections() as $subsection) {
                    if (method_exists($subsection, 'resolveAuthorization')) {
                        $subsection->resolveAuthorization($user);
                    }
                }
            }
        }
    }

    /**
     * Get layout for a specific user with authorization resolved
     */
    public function forUser($user): self
    {
        return $this->resolveAuthorization($user);
    }

    /**
     * Get only authorized sections or components
     */
    public function getAuthorizedSections(): array
    {
        return array_filter(
            $this->sections,
            fn ($section) => ! method_exists($section, 'isAuthorizedToSee') || $section->isAuthorizedToSee()
        );
    }

    /**
     * Get only authorized components (v3.0+)
     */
    public function getAuthorizedComponents(): array
    {
        return array_filter($this->components, fn($component) => 
            !method_exists($component, 'isAuthorizedToSee') || $component->isAuthorizedToSee()
        );
    }

    public function toArray(): array
    {
        // Component-based layouts (v3.0+)
        if (!empty($this->components)) {
            return [
                'module' => $this->module,
                'context' => $this->context,
                'components' => array_map(
                    fn($comp) => method_exists($comp, 'toArray') ? $comp->toArray() : (array) $comp,
                    $this->components
                ),
                'meta' => $this->meta,
            ];
        }
        
        // Legacy Section/Subsection structure
        return [
            'module' => $this->module,
            'context' => $this->context,
            'sections' => array_map(fn ($section) => $section->toArray(), $this->sections),
            'meta' => $this->meta,
        ];
    }

    /**
     * Convert to array with only authorized components
     */
    public function toAuthorizedArray(): array
    {
        // Component-based layouts (v3.0+)
        if (!empty($this->components)) {
            $components = [];
            foreach ($this->getAuthorizedComponents() as $key => $component) {
                if (method_exists($component, 'toArray')) {
                    $componentArray = $component->toArray();
                    $components[$key] = $this->filterAuthorizedRecursive($componentArray);
                } else {
                    $components[$key] = (array) $component;
                }
            }

            return [
                'module' => $this->module,
                'context' => $this->context,
                'components' => $components,
                'meta' => $this->meta,
            ];
        }
        
        // Legacy Section/Subsection structure
        $sections = [];
        foreach ($this->getAuthorizedSections() as $key => $section) {
            $sectionArray = $section->toArray();

            // Filter authorized subsections
            if (! empty($sectionArray['subsections'])) {
                $sectionArray['subsections'] = array_filter(
                    $sectionArray['subsections'],
                    fn ($sub) => $sub['authorized_to_see'] ?? true
                );

                // Filter authorized fields within subsections
                foreach ($sectionArray['subsections'] as &$subsection) {
                    if (! empty($subsection['fields'])) {
                        $subsection['fields'] = array_filter(
                            $subsection['fields'],
                            fn ($field) => $field['authorized_to_see'] ?? true
                        );
                    }
                }
            }

            $sections[$key] = $sectionArray;
        }

        return [
            'module' => $this->module,
            'context' => $this->context,
            'sections' => $sections,
            'meta' => $this->meta,
        ];
    }

    /**
     * Helper to recursively filter authorized items from component arrays (v3.0+)
     */
    protected function filterAuthorizedRecursive(array $data): array
    {
        // Filter tabs
        if (isset($data['tabs']) && is_array($data['tabs'])) {
            $data['tabs'] = array_filter($data['tabs'], fn($tab) => $tab['authorized'] ?? true);
            foreach ($data['tabs'] as &$tab) {
                if (isset($tab['components']) && is_array($tab['components'])) {
                    $tab['components'] = array_map(
                        fn($comp) => $this->filterAuthorizedRecursive($comp),
                        array_filter($tab['components'], fn($comp) => $comp['authorized_to_see'] ?? true)
                    );
                }
            }
        }
        
        // Filter accordion items
        if (isset($data['items']) && is_array($data['items'])) {
            $data['items'] = array_filter($data['items'], fn($item) => $item['authorized'] ?? true);
            foreach ($data['items'] as &$item) {
                if (isset($item['components']) && is_array($item['components'])) {
                    $item['components'] = array_map(
                        fn($comp) => $this->filterAuthorizedRecursive($comp),
                        array_filter($item['components'], fn($comp) => $comp['authorized_to_see'] ?? true)
                    );
                }
            }
        }
        
        // Filter scrollspy sections
        if (isset($data['sections']) && is_array($data['sections'])) {
            $data['sections'] = array_filter($data['sections'], fn($section) => $section['authorized'] ?? true);
            foreach ($data['sections'] as &$section) {
                if (isset($section['components']) && is_array($section['components'])) {
                    $section['components'] = array_map(
                        fn($comp) => $this->filterAuthorizedRecursive($comp),
                        array_filter($section['components'], fn($comp) => $comp['authorized_to_see'] ?? true)
                    );
                }
            }
        }
        
        // Filter nested components (Grid, etc)
        if (isset($data['components']) && is_array($data['components'])) {
            $data['components'] = array_map(
                fn($comp) => $this->filterAuthorizedRecursive($comp),
                array_filter($data['components'], fn($comp) => $comp['authorized_to_see'] ?? true)
            );
        }
        
        return $data;
    }

    public function render(): array
    {
        return $this->toArray();
    }
}
