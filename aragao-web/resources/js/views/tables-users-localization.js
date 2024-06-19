Alpine.data('tablesUsersLocalization', () => ({
    closeModal($wire) {
        latitude = null;
        longitude = null;
        $wire().modalAdd = false;
    },
    showMap(latitude, longitude, $wire) {
        latitude = latitude;
        longitude = longitude;
        let url = `https://maps.google.com/maps?f=d&output=embed&daddr=${latitude},${longitude}`;
        document.querySelector('#map').src = url;
        $wire().modalAdd = true;
    },
}));