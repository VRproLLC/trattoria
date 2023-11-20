function geoFindMe() {
    function success(position) {
        const latitude  = position.coords.latitude;
        const longitude = position.coords.longitude;

        $.ajax({
            type: 'POST',
            url: '/set_geo',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                latitude: latitude,
                longitude: longitude,
            },
            success: function (data) {
                location.reload();
            },
            error: function (data) {
                console.log(data)
            }
        });
    }
    function error() {
        console.log('Невозможно получить ваше местоположение');
    }

    if (!navigator.geolocation) {
        console.log('Geolocation не поддерживается вашим браузером')
    } else navigator.geolocation.getCurrentPosition(success, error);
}
