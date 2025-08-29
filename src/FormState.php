<?php

namespace Litepie\Form;

/**
 * Simple Form State Tracker
 * 
 * Lightweight state management for forms without full state machine complexity
 */
class FormState
{
    protected array $states = [
        'draft' => ['validating', 'reset'],
        'validating' => ['validated', 'invalid', 'draft'],
        'validated' => ['submitting', 'draft'],
        'invalid' => ['validating', 'draft'],
        'submitting' => ['submitted', 'failed'],
        'submitted' => ['completed', 'processing'],
        'failed' => ['draft', 'validating'],
        'processing' => ['completed', 'failed'],
        'completed' => ['archived'],
        'archived' => [],
    ];

    protected string $currentState = 'draft';
    protected array $history = [];

    public function getCurrentState(): string
    {
        return $this->currentState;
    }

    public function canTransitionTo(string $state): bool
    {
        return in_array($state, $this->states[$this->currentState] ?? []);
    }

    public function transitionTo(string $newState): bool
    {
        if (!$this->canTransitionTo($newState)) {
            return false;
        }

        $this->history[] = [
            'from' => $this->currentState,
            'to' => $newState,
            'timestamp' => time(),
        ];

        $this->currentState = $newState;
        return true;
    }

    public function getHistory(): array
    {
        return $this->history;
    }

    public function getAllowedTransitions(): array
    {
        return $this->states[$this->currentState] ?? [];
    }

    public function reset(): void
    {
        $this->currentState = 'draft';
        $this->history = [];
    }
}
