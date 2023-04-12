<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
<script>
    //  Яндекс мапа
    var myMap;

    // Дочекаємося завантаження API і готовності DOM.
    ymaps.ready(init);

    function init () {
        myMap = new ymaps.Map('map', {
            center: [55.99803,92.898377],
            zoom: 17,
            controls: ['zoomControl', 'typeSelector',  'fullscreenControl']
        });
        var myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
            balloonContentBody: [
                '<address>',
                '<strong>ТзОВ "Софтньюс Медіа Групп"</strong>',
                '<br/>',
                'Адреса: 660093 м. Красноярськ, вул. Капітанська, буд. 12, офіс 43',
                '</address>'
            ].join('')
        }, {
            preset: 'islands#darkGreenDotIcon'
        });
        myMap.geoObjects.add(myPlacemark);
        myMap.behaviors.disable('scrollZoom');
    } 
</script>
<div class="map_resp">
    <div id="map"></div>
</div>