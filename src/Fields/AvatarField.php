<?php

namespace Litepie\Form\Fields;

/**
 * Avatar Field
 * 
 * Specialized image field for avatar/profile picture uploads
 * with cropping, preview, and size constraints
 */
class AvatarField extends ImageField
{
    protected int $size = 150; // Default avatar size
    protected string $shape = 'circle'; // circle or square
    protected bool $initials = true; // Show initials as placeholder
    protected ?string $initialsFrom = null; // Field name to generate initials from
    protected string $defaultAvatar = ''; // Default avatar URL/path
    
    /**
     * Get the field type.
     */
    protected function getFieldType(): string
    {
        return 'avatar';
    }

    /**
     * Constructor - Set sensible defaults for avatars.
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        
        // Avatar-specific defaults
        $this->accept('image/jpeg,image/png,image/gif,image/webp');
        $this->maxSize(2); // 2MB max
        $this->crop(true);
        $this->aspectRatio('1:1'); // Square crop
        $this->minDimensions(200, 200);
    }

    /**
     * Set avatar display size (in pixels).
     */
    public function size(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Get avatar size.
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Set avatar shape.
     */
    public function shape(string $shape): self
    {
        $this->shape = in_array($shape, ['circle', 'square']) ? $shape : 'circle';
        return $this;
    }

    /**
     * Get avatar shape.
     */
    public function getShape(): string
    {
        return $this->shape;
    }

    /**
     * Enable/disable initials placeholder.
     */
    public function initials(bool $show = true): self
    {
        $this->initials = $show;
        return $this;
    }

    /**
     * Get initials setting.
     */
    public function hasInitials(): bool
    {
        return $this->initials;
    }

    /**
     * Set field name to generate initials from (e.g., 'name' or 'first_name').
     */
    public function initialsFrom(string $fieldName): self
    {
        $this->initialsFrom = $fieldName;
        return $this;
    }

    /**
     * Get initials from field name.
     */
    public function getInitialsFrom(): ?string
    {
        return $this->initialsFrom;
    }

    /**
     * Set default avatar URL/path.
     */
    public function defaultAvatar(string $url): self
    {
        $this->defaultAvatar = $url;
        return $this;
    }

    /**
     * Get default avatar.
     */
    public function getDefaultAvatar(): string
    {
        return $this->defaultAvatar;
    }

    /**
     * Set to circle shape (shorthand).
     */
    public function circle(): self
    {
        return $this->shape('circle');
    }

    /**
     * Set to square shape (shorthand).
     */
    public function square(): self
    {
        return $this->shape('square');
    }

    /**
     * Render the field.
     */
    public function render(): string
    {
        $attributes = $this->buildAttributes();
        $shapeClass = $this->shape === 'circle' ? 'rounded-circle' : 'rounded';
        $currentValue = $this->value ?? $this->defaultAvatar;
        
        $html = '<div class="avatar-field-wrapper">';
        
        if ($this->label) {
            $html .= '<label class="form-label">' . htmlspecialchars($this->label);
            if ($this->required) {
                $html .= ' <span class="text-danger">*</span>';
            }
            $html .= '</label>';
        }

        // Avatar preview
        $html .= '<div class="avatar-preview mb-3">';
        
        if ($currentValue) {
            $html .= sprintf(
                '<img src="%s" class="avatar-image %s" style="width: %dpx; height: %dpx; object-fit: cover;" alt="Avatar">',
                htmlspecialchars($currentValue),
                $shapeClass,
                $this->size,
                $this->size
            );
        } elseif ($this->initials && $this->initialsFrom) {
            $html .= sprintf(
                '<div class="avatar-initials %s d-flex align-items-center justify-content-center bg-primary text-white" style="width: %dpx; height: %dpx; font-size: %dpx;" data-initials-from="%s"></div>',
                $shapeClass,
                $this->size,
                $this->size,
                intval($this->size / 2.5),
                htmlspecialchars($this->initialsFrom)
            );
        } else {
            $html .= sprintf(
                '<div class="avatar-placeholder %s bg-light d-flex align-items-center justify-content-center" style="width: %dpx; height: %dpx;"><i class="bi bi-person-circle" style="font-size: %dpx;"></i></div>',
                $shapeClass,
                $this->size,
                $this->size,
                intval($this->size * 0.8)
            );
        }
        
        $html .= '</div>';

        // File input
        $html .= '<input type="file" name="' . htmlspecialchars($this->name) . '" ';
        $html .= 'id="' . $this->getId() . '" ';
        $html .= 'class="form-control avatar-input" ';
        $html .= 'data-avatar-size="' . $this->size . '" ';
        $html .= 'data-avatar-shape="' . $this->shape . '" ';
        $html .= $attributes . '>';

        // Upload hints
        $html .= '<div class="form-text">';
        $html .= 'Recommended: ' . $this->size . 'x' . $this->size . 'px. ';
        $html .= 'Max size: ' . $this->maxSize . 'MB. ';
        $html .= 'Formats: JPG, PNG, GIF, WebP';
        $html .= '</div>';

        if ($this->help) {
            $html .= '<div class="form-text">' . htmlspecialchars($this->help) . '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'size' => $this->size,
            'shape' => $this->shape,
            'initials' => $this->initials,
            'initialsFrom' => $this->initialsFrom,
            'defaultAvatar' => $this->defaultAvatar,
        ]);
    }
}
