jQuery(document).ready(function () {
    gamp_autocomplete();
});

const gamp_autocomplete = () => {
    const api_key = gamp_settings.gamp_api_key;
    const zip_code_input_class = gamp_settings.gamp_zip_code;
    const location_input_class = gamp_settings.gamp_location;

    if (api_key === "") {
        console.warn("Please enter your Google API Key in the GAMP Plugin settings.");
        return -1;
    }

    const zip_code_inputs = jQuery('.' + zip_code_input_class);
    const location_inputs = jQuery('.' + location_input_class);

    if (zip_code_inputs.length === 0 || location_inputs.length === 0) {
        console.warn("Please enter the correct class for the Zip Code and Location fields in the GAMP Plugin settings.");
        return -1;
    }

    // Load Google Maps API script with a callback
    load_google_maps_script(api_key);
};

const load_google_maps_script = (api_key) => {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${api_key}&libraries=places&callback=initAutocomplete`;
    script.async = true;
    script.defer = true;

    document.head.appendChild(script);
};

// Define initAutocomplete as a global function
function initAutocomplete() {
    const zip_code_input_class = gamp_settings.gamp_zip_code;
    const location_input_class = gamp_settings.gamp_location;

    const zip_code_inputs = document.querySelectorAll('.' + zip_code_input_class);
    const location_inputs = document.querySelectorAll('.' + location_input_class);

    if (zip_code_inputs.length === 0 || location_inputs.length === 0) {
        console.error("Please enter the correct class for the Zip Code and Location fields.");
        return;
    }

    zip_code_inputs.forEach((zip_code_input, index) => {
        const location_input = location_inputs[index];

        if (!location_input) {
            console.error(`No matching location input for zip code input at index ${index}.`);
            return;
        }

        // Store initial placeholders
        const initialZipPlaceholder = zip_code_input.getAttribute('placeholder') ?? "";
        const initialLocationPlaceholder = location_input.getAttribute('placeholder') ?? "";

        const autocompleteLocation = new google.maps.places.Autocomplete(location_input, {
            types: ['(cities)']
        });

        const autocompleteZipCode = new google.maps.places.Autocomplete(zip_code_input, {
            types: ['(regions)']
        });

        // Restore initial placeholders after initialization
        location_input.setAttribute('placeholder', initialLocationPlaceholder);
        zip_code_input.setAttribute('placeholder', initialZipPlaceholder);

        autocompleteLocation.addListener('place_changed', function () {
            const place = autocompleteLocation.getPlace();
            const postalCodeComponent = place.address_components.find(component => component.types.includes("postal_code"));
            if (postalCodeComponent) {
                zip_code_input.value = postalCodeComponent.long_name;
            } else {
                console.warn("No postal code found in the selected place.");
            }
        });

        autocompleteZipCode.addListener('place_changed', function () {
            const place = autocompleteZipCode.getPlace();
            const postalCodeComponent = place.address_components.find(component => component.types.includes("postal_code"));
            const cityComponent = place.address_components.find(component => component.types.includes("locality"));

            if (postalCodeComponent) {
                zip_code_input.value = postalCodeComponent.long_name;
            } else {
                console.warn("No postal code found in the selected place.");
            }
			

            if (cityComponent) {
                location_input.value = cityComponent.long_name;
            } else {
                console.warn("No city found in the selected place.");
            }
			
			/*
			* Add custom event to the document
			*/
			
			const event = new CustomEvent('gamp_place_changed');
			document.dispatchEvent(event);
        });
    });
}
