<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Date Range Field
 */
class DateRangeField extends Field
{
    protected ?string $startDate = null;
    protected ?string $endDate = null;
    protected string $format = 'Y-m-d';
    protected string $separator = ' to ';

    protected function getFieldType(): string
    {
        return 'daterange';
    }

    public function format(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function separator(string $separator): self
    {
        $this->separator = $separator;
        return $this;
    }

    public function render(): string
    {
        $attributes = $this->buildAttributes();
        
        $html = '<div class="date-range-wrapper">';
        
        if ($this->label) {
            $html .= '<label class="form-label">' . htmlspecialchars($this->label);
            if ($this->required) {
                $html .= ' <span class="text-danger">*</span>';
            }
            $html .= '</label>';
        }

        $html .= '<div class="input-group">';
        $html .= '<input type="date" name="' . htmlspecialchars($this->name) . '[start]" ';
        $html .= 'class="form-control" placeholder="Start Date" ' . $attributes . '>';
        $html .= '<span class="input-group-text">' . htmlspecialchars($this->separator) . '</span>';
        $html .= '<input type="date" name="' . htmlspecialchars($this->name) . '[end]" ';
        $html .= 'class="form-control" placeholder="End Date" ' . $attributes . '>';
        $html .= '</div>';
        
        if ($this->help) {
            $html .= '<div class="form-text">' . htmlspecialchars($this->help) . '</div>';
        }
        
        $html .= '</div>';

        return $html;
    }
}
