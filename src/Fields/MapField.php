<?php

namespace Litepie\Form\Fields;

use Litepie\Form\Field;

/**
 * Map Location Picker Field
 */
class MapField extends Field
{
    protected float $latitude = 0.0;
    protected float $longitude = 0.0;
    protected int $zoom = 10;
    protected string $provider = 'google';
    protected string $apiKey = '';

    protected function getFieldType(): string
    {
        return 'map';
    }

    public function coordinates(float $lat, float $lng): self
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        return $this;
    }

    public function zoom(int $zoom): self
    {
        $this->zoom = $zoom;
        return $this;
    }

    public function provider(string $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    public function apiKey(string $key): self
    {
        $this->apiKey = $key;
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="map-field-wrapper">';
        
        if ($this->label) {
            $html .= '<label class="form-label">' . htmlspecialchars($this->label);
            if ($this->required) {
                $html .= ' <span class="text-danger">*</span>';
            }
            $html .= '</label>';
        }

        $html .= '<div class="row">';
        $html .= '<div class="col-md-6">';
        $html .= '<input type="number" name="' . htmlspecialchars($this->name) . '[latitude]" ';
        $html .= 'class="form-control latitude-input" placeholder="Latitude" ';
        $html .= 'value="' . $this->latitude . '" step="any">';
        $html .= '</div>';
        $html .= '<div class="col-md-6">';
        $html .= '<input type="number" name="' . htmlspecialchars($this->name) . '[longitude]" ';
        $html .= 'class="form-control longitude-input" placeholder="Longitude" ';
        $html .= 'value="' . $this->longitude . '" step="any">';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="map-container mt-3" style="height: 400px;" ';
        $html .= 'data-lat="' . $this->latitude . '" ';
        $html .= 'data-lng="' . $this->longitude . '" ';
        $html .= 'data-zoom="' . $this->zoom . '" ';
        $html .= 'data-provider="' . $this->provider . '" ';
        $html .= 'data-api-key="' . $this->apiKey . '">';
        $html .= '</div>';
        
        if ($this->help) {
            $html .= '<div class="form-text">' . htmlspecialchars($this->help) . '</div>';
        }
        
        $html .= '</div>';

        return $html;
    }
}
