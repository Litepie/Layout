<?php

namespace Litepie\Layout\Responsive;

class DeviceDetector
{
    protected string $userAgent;
    protected array $breakpoints;

    public function __construct(?string $userAgent = null)
    {
        $this->userAgent = $userAgent ?? ($_SERVER['HTTP_USER_AGENT'] ?? '');
        $this->breakpoints = config('litepie.layout.breakpoints', [
            'xs' => 0,
            'sm' => 640,
            'md' => 768,
            'lg' => 1024,
            'xl' => 1280,
            '2xl' => 1536,
        ]);
    }

    /**
     * Detect device type
     */
    public function getDeviceType(): string
    {
        if ($this->isMobile()) {
            return 'mobile';
        }

        if ($this->isTablet()) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Check if mobile device
     */
    public function isMobile(): bool
    {
        return preg_match(
            '/(android|webos|iphone|ipod|blackberry|iemobile|opera mini)/i',
            $this->userAgent
        ) === 1;
    }

    /**
     * Check if tablet device
     */
    public function isTablet(): bool
    {
        return preg_match('/(ipad|tablet|playbook|silk)|(android(?!.*mobile))/i', $this->userAgent) === 1;
    }

    /**
     * Check if desktop device
     */
    public function isDesktop(): bool
    {
        return !$this->isMobile() && !$this->isTablet();
    }

    /**
     * Get breakpoints
     */
    public function getBreakpoints(): array
    {
        return $this->breakpoints;
    }

    /**
     * Get breakpoint for device
     */
    public function getBreakpoint(): string
    {
        if ($this->isMobile()) {
            return 'xs';
        }

        if ($this->isTablet()) {
            return 'md';
        }

        return 'xl';
    }
}
