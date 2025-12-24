<?php

namespace Litepie\Form\Concerns;

/**
 * Handles field dependencies, computed values, and conditional logic
 */
trait HasDependencies
{
    /**
     * Field this field depends on.
     */
    protected ?string $dependsOn = null;

    /**
     * Computed field callback.
     */
    protected ?\Closure $computedCallback = null;

    /**
     * Loading text for async operations.
     */
    protected ?string $loadingText = null;

    /**
     * Confirmation message for changes.
     */
    protected ?string $confirmMessage = null;

    /**
     * Whether to track changes.
     */
    protected bool $trackChanges = false;

    /**
     * Set field dependency.
     */
    public function dependsOn(string $fieldName): self
    {
        $this->dependsOn = $fieldName;
        return $this;
    }

    /**
     * Get field dependency.
     */
    public function getDependsOn(): ?string
    {
        return $this->dependsOn;
    }

    /**
     * Set computed field callback.
     */
    public function computed(\Closure $callback): self
    {
        $this->computedCallback = $callback;
        return $this;
    }

    /**
     * Check if field is computed.
     */
    public function isComputed(): bool
    {
        return $this->computedCallback !== null;
    }

    /**
     * Compute field value.
     */
    public function computeValue(array $data): mixed
    {
        if ($this->computedCallback) {
            return call_user_func($this->computedCallback, $data);
        }
        
        return $this->value;
    }

    /**
     * Set loading text.
     */
    public function loadingText(string $text): self
    {
        $this->loadingText = $text;
        return $this;
    }

    /**
     * Get loading text.
     */
    public function getLoadingText(): ?string
    {
        return $this->loadingText;
    }

    /**
     * Set confirmation message.
     */
    public function confirmChange(string $message): self
    {
        $this->confirmMessage = $message;
        return $this;
    }

    /**
     * Get confirmation message.
     */
    public function getConfirmMessage(): ?string
    {
        return $this->confirmMessage;
    }

    /**
     * Enable change tracking.
     */
    public function trackChanges(bool $track = true): self
    {
        $this->trackChanges = $track;
        return $this;
    }

    /**
     * Check if changes are tracked.
     */
    public function isTrackingChanges(): bool
    {
        return $this->trackChanges;
    }
}
