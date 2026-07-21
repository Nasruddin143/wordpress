function ktInitMap() {

    const mapEl = document.getElementById('kt-map');
    if (!mapEl) return;

    const location = {
        lat: 20.095000631377545, // 19.9483, // Ozar example
        lng: 73.92822219435617 // 73.9276
    };

    const map = new google.maps.Map(mapEl, {
        zoom: 15,
        mapId: 'b9ca4adb4ea9e72f54e338ab',
        center: location,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false
    });

    new google.maps.marker.AdvancedMarkerElement({
        position: location,
        map: map,
        title: 'King Tailors'
    });
}
