{{-- Bootstrap 5 Map Field (with coordinates) --}}
<div class="mb-3 {{ $field->getWrapperClass() }}" {!! $field->getWrapperAttributes() !!}>
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="form-label">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @php
        $value = old($field->getName(), $field->getValue());
        $coordinates = $value ? json_decode($value, true) : null;
        $lat = $coordinates['lat'] ?? $field->getAttribute('defaultLat', 40.7128);
        $lng = $coordinates['lng'] ?? $field->getAttribute('defaultLng', -74.0060);
        $zoom = $field->getAttribute('zoom', 13);
        $height = $field->getAttribute('height', '400px');
        $searchEnabled = $field->getAttribute('searchEnabled', true);
        $markerDraggable = $field->getAttribute('markerDraggable', true);
    @endphp

    <div class="map-container">
        @if($searchEnabled)
            <div class="map-search mb-2">
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="{{ $field->getId() }}_search"
                           placeholder="Search for a location..."
                           onkeypress="handleSearchKeypress(event, '{{ $field->getId() }}')">
                    <button class="btn btn-outline-secondary" 
                            type="button" 
                            onclick="searchLocation('{{ $field->getId() }}')"
                            title="Search">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-outline-secondary" 
                            type="button" 
                            onclick="getCurrentLocation('{{ $field->getId() }}')"
                            title="Use current location">
                        <i class="fas fa-location-arrow"></i>
                    </button>
                </div>
            </div>
        @endif

        <div class="map-display border rounded {{ $field->hasErrors() ? 'border-danger' : '' }}" 
             id="{{ $field->getId() }}_map" 
             style="height: {{ $height }}; width: 100%;">
            {{-- Map will be rendered here --}}
        </div>

        <div class="map-coordinates mt-2">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small">Latitude:</label>
                    <input type="number" 
                           step="any" 
                           class="form-control form-control-sm" 
                           id="{{ $field->getId() }}_lat"
                           value="{{ $lat }}"
                           onchange="updateMapFromCoordinates('{{ $field->getId() }}')"
                           placeholder="Latitude">
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Longitude:</label>
                    <input type="number" 
                           step="any" 
                           class="form-control form-control-sm" 
                           id="{{ $field->getId() }}_lng"
                           value="{{ $lng }}"
                           onchange="updateMapFromCoordinates('{{ $field->getId() }}')"
                           placeholder="Longitude">
                </div>
            </div>
        </div>

        {{-- Hidden Input --}}
        <input type="hidden" 
               name="{{ $field->getName() }}" 
               id="{{ $field->getId() }}"
               value="{{ $value }}"
               @if($field->isRequired()) required @endif>

        @if($field->hasErrors())
            <div class="text-danger mt-1">
                {{ $field->getFirstError() }}
            </div>
        @endif

        <div class="map-actions mt-2">
            <div class="btn-group-sm">
                <button type="button" 
                        class="btn btn-outline-secondary btn-sm"
                        onclick="resetMap('{{ $field->getId() }}')"
                        title="Reset to default location">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="button" 
                        class="btn btn-outline-secondary btn-sm"
                        onclick="clearLocation('{{ $field->getId() }}')"
                        title="Clear location">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    </div>

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif
</div>

{{-- Simple Map Implementation (you can replace with Google Maps, Leaflet, etc.) --}}
<script>
let mapInstances = {};

function initializeMap(fieldId) {
    const mapDiv = document.getElementById(fieldId + '_map');
    const latInput = document.getElementById(fieldId + '_lat');
    const lngInput = document.getElementById(fieldId + '_lng');
    
    const lat = parseFloat(latInput.value) || {{ $lat }};
    const lng = parseFloat(lngInput.value) || {{ $lng }};
    
    // Simple map placeholder (replace with actual map library)
    mapDiv.innerHTML = `
        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
            <div class="text-center">
                <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                <h5>Map Location</h5>
                <p class="mb-2">Latitude: <span id="${fieldId}_display_lat">${lat}</span></p>
                <p class="mb-2">Longitude: <span id="${fieldId}_display_lng">${lng}</span></p>
                <small class="text-muted">Click coordinates above to change location</small>
            </div>
        </div>
    `;
    
    updateHiddenField(fieldId);
}

function updateMapFromCoordinates(fieldId) {
    const latInput = document.getElementById(fieldId + '_lat');
    const lngInput = document.getElementById(fieldId + '_lng');
    const displayLat = document.getElementById(fieldId + '_display_lat');
    const displayLng = document.getElementById(fieldId + '_display_lng');
    
    const lat = parseFloat(latInput.value);
    const lng = parseFloat(lngInput.value);
    
    if (!isNaN(lat) && !isNaN(lng)) {
        if (displayLat) displayLat.textContent = lat;
        if (displayLng) displayLng.textContent = lng;
        updateHiddenField(fieldId);
    }
}

function updateHiddenField(fieldId) {
    const latInput = document.getElementById(fieldId + '_lat');
    const lngInput = document.getElementById(fieldId + '_lng');
    const hiddenInput = document.getElementById(fieldId);
    
    const lat = parseFloat(latInput.value);
    const lng = parseFloat(lngInput.value);
    
    if (!isNaN(lat) && !isNaN(lng)) {
        hiddenInput.value = JSON.stringify({
            lat: lat,
            lng: lng,
            address: ''
        });
    } else {
        hiddenInput.value = '';
    }
}

function searchLocation(fieldId) {
    const searchInput = document.getElementById(fieldId + '_search');
    const query = searchInput.value.trim();
    
    if (!query) return;
    
    // Simple geocoding simulation (replace with actual geocoding service)
    alert('Geocoding feature requires integration with a mapping service like Google Maps API, Leaflet with OpenStreetMap, or similar service.');
}

function handleSearchKeypress(event, fieldId) {
    if (event.key === 'Enter') {
        event.preventDefault();
        searchLocation(fieldId);
    }
}

function getCurrentLocation(fieldId) {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by this browser.');
        return;
    }
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            document.getElementById(fieldId + '_lat').value = lat;
            document.getElementById(fieldId + '_lng').value = lng;
            
            updateMapFromCoordinates(fieldId);
        },
        function(error) {
            alert('Error getting location: ' + error.message);
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000
        }
    );
}

function resetMap(fieldId) {
    const defaultLat = {{ $field->getAttribute('defaultLat', 40.7128) }};
    const defaultLng = {{ $field->getAttribute('defaultLng', -74.0060) }};
    
    document.getElementById(fieldId + '_lat').value = defaultLat;
    document.getElementById(fieldId + '_lng').value = defaultLng;
    
    updateMapFromCoordinates(fieldId);
}

function clearLocation(fieldId) {
    document.getElementById(fieldId + '_lat').value = '';
    document.getElementById(fieldId + '_lng').value = '';
    document.getElementById(fieldId).value = '';
    
    const mapDiv = document.getElementById(fieldId + '_map');
    mapDiv.innerHTML = `
        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
            <div class="text-center text-muted">
                <i class="fas fa-map fa-3x mb-3"></i>
                <h5>No Location Selected</h5>
                <p>Search for a location or click "Use current location"</p>
            </div>
        </div>
    `;
}

// Initialize map on page load
document.addEventListener('DOMContentLoaded', function() {
    const mapFields = document.querySelectorAll('[id$="_map"]');
    mapFields.forEach(mapDiv => {
        const fieldId = mapDiv.id.replace('_map', '');
        initializeMap(fieldId);
    });
});
</script>

<style>
.map-display {
    position: relative;
    overflow: hidden;
}

.map-coordinates .form-label {
    margin-bottom: 0.25rem;
}

.map-search .input-group {
    position: relative;
    z-index: 10;
}
</style>

{{-- 
Note: This is a basic map implementation. For production use, integrate with:
- Google Maps JavaScript API
- Leaflet with OpenStreetMap
- Mapbox GL JS
- Or other mapping services

Example integration points:
1. Replace initializeMap() with actual map library initialization
2. Implement real geocoding in searchLocation()
3. Add marker dragging functionality
4. Add map click handlers for location selection
--}}
