<?php

namespace Litepie\Form\Concerns;

/**
 * Handles field visibility, permissions, and role-based access
 */
trait HasVisibility
{
    /**
     * Whether field is visible.
     */
    protected bool $visible = true;

    /**
     * Visibility condition callback.
     */
    protected ?\Closure $visibilityCondition = null;

    /**
     * Declarative visibility conditions (field, operator, value).
     */
    protected array $visibilityConditions = [];

    /**
     * Permission/ability required to view this field.
     */
    protected ?string $permission = null;

    /**
     * Roles allowed to view this field.
     */
    protected array $roles = [];

    /**
     * Hide the field.
     */
    public function hide(): self
    {
        $this->visible = false;
        return $this;
    }

    /**
     * Show the field.
     */
    public function show(): self
    {
        $this->visible = true;
        return $this;
    }

    /**
     * Set field visibility.
     */
    public function visible(bool $visible = true): self
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * Set visibility condition (callback or declarative).
     * 
     * Usage:
     *   ->visibleWhen(fn($data) => $data['type'] === 'premium')
     *   ->visibleWhen('type', '=', 'premium')
     *   ->visibleWhen('age', '>', 18)
     */
    public function visibleWhen(...$args): self
    {
        if (count($args) === 1 && $args[0] instanceof \Closure) {
            // Closure-based condition
            $this->visibilityCondition = $args[0];
        } elseif (count($args) === 3) {
            // Declarative condition: field, operator, value
            [$field, $operator, $value] = $args;
            $this->visibilityConditions[] = [
                'field' => $field,
                'operator' => $operator,
                'value' => $value,
            ];
        }
        return $this;
    }

    /**
     * Get visibility conditions.
     */
    public function getVisibilityConditions(): array
    {
        return $this->visibilityConditions;
    }

    /**
     * Check if field meets visibility conditions.
     */
    public function meetsVisibilityConditions(array $data): bool
    {
        // Check closure-based condition
        if ($this->visibilityCondition !== null) {
            return call_user_func($this->visibilityCondition, $data);
        }

        // Check declarative conditions (all must be true)
        if (empty($this->visibilityConditions)) {
            return true;
        }

        foreach ($this->visibilityConditions as $condition) {
            // Support dot notation for nested data
            $fieldValue = $data[$condition['field']] ?? null;
            if (str_contains($condition['field'], '.')) {
                // Handle nested fields like 'address.city'
                $keys = explode('.', $condition['field']);
                $fieldValue = $data;
                foreach ($keys as $key) {
                    if (is_array($fieldValue) && isset($fieldValue[$key])) {
                        $fieldValue = $fieldValue[$key];
                    } else {
                        $fieldValue = null;
                        break;
                    }
                }
            }
            
            switch ($condition['operator']) {
                case '=':
                case '==':
                    if ($fieldValue != $condition['value']) return false;
                    break;
                case '===':
                    if ($fieldValue !== $condition['value']) return false;
                    break;
                case '!=':
                    if ($fieldValue == $condition['value']) return false;
                    break;
                case '!==':
                    if ($fieldValue === $condition['value']) return false;
                    break;
                case '>':
                    if ($fieldValue <= $condition['value']) return false;
                    break;
                case '>=':
                    if ($fieldValue < $condition['value']) return false;
                    break;
                case '<':
                    if ($fieldValue >= $condition['value']) return false;
                    break;
                case '<=':
                    if ($fieldValue > $condition['value']) return false;
                    break;
                case 'in':
                    if (!in_array($fieldValue, (array)$condition['value'])) return false;
                    break;
                case 'not_in':
                    if (in_array($fieldValue, (array)$condition['value'])) return false;
                    break;
                case 'contains':
                    if (strpos($fieldValue, $condition['value']) === false) return false;
                    break;
                case 'starts_with':
                    if (!str_starts_with($fieldValue, $condition['value'])) return false;
                    break;
                case 'ends_with':
                    if (!str_ends_with($fieldValue, $condition['value'])) return false;
                    break;
                default:
                    return false;
            }
        }

        return true;
    }

    /**
     * Set visibility based on user permission.
     */
    public function can(string $permission): self
    {
        $this->permission = $permission;
        return $this;
    }

    /**
     * Set visibility based on user roles.
     */
    public function roles(array|string $roles): self
    {
        $this->roles = is_array($roles) ? $roles : [$roles];
        return $this;
    }

    /**
     * Check if field is visible.
     */
    public function isVisible(?object $user = null): bool
    {
        // Check basic visibility flag
        if (!$this->visible) {
            return false;
        }

        // Check permission
        if ($this->permission && $user) {
            if (method_exists($user, 'can') && !$user->can($this->permission)) {
                return false;
            }
        }

        // Check roles
        if (!empty($this->roles) && $user) {
            if (method_exists($user, 'hasAnyRole') && !$user->hasAnyRole($this->roles)) {
                return false;
            } elseif (method_exists($user, 'hasRole')) {
                $hasRole = false;
                foreach ($this->roles as $role) {
                    if ($user->hasRole($role)) {
                        $hasRole = true;
                        break;
                    }
                }
                if (!$hasRole) {
                    return false;
                }
            }
        }

        // Check visibility condition
        if ($this->visibilityCondition) {
            return call_user_func($this->visibilityCondition, $user);
        }

        return true;
    }
}
