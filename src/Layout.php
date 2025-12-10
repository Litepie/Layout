<?php

namespace Litepie\Layout;

use Litepie\Layout\Contracts\Renderable;

class Layout implements Renderable
{
    protected string $module;

    protected string $context;

    protected array $sections;

    protected array $meta = [];

    public function __construct(string $module, string $context, array $sections = [])
    {
        $this->module = $module;
        $this->context = $context;
        $this->sections = $sections;
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
     * Get all Litepie/Form fields from all subsections
     */
    public function getAllFormFields(): array
    {
        $fields = [];
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
     * Resolve authorization for all sections, subsections, and fields
     */
    public function resolveAuthorization($user = null): self
    {
        foreach ($this->sections as $section) {
            if (method_exists($section, 'resolveAuthorization')) {
                $section->resolveAuthorization($user);
            }
        }

        return $this;
    }

    /**
     * Get layout for a specific user with authorization resolved
     */
    public function forUser($user): self
    {
        return $this->resolveAuthorization($user);
    }

    /**
     * Get only authorized sections
     */
    public function getAuthorizedSections(): array
    {
        return array_filter(
            $this->sections,
            fn ($section) => ! method_exists($section, 'isAuthorizedToSee') || $section->isAuthorizedToSee()
        );
    }

    public function toArray(): array
    {
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

    public function render(): array
    {
        return $this->toArray();
    }
}
