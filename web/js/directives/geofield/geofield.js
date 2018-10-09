app.directive('agroGeofield', function() {
    return {        
        restrict: 'EA',
        scope: {
            //geodata : '=',
            model: '=ngModel'
        },

        mapUrl : 'https://maps.api.2gis.ru/2.0/loader.js?pkg=full',

        //'https://maps.googleapis.com/maps/api/js?key=AIzaSyAdc4D22iXnYoIbDE7lpXos0RFWpVA7WXY',
    
        templateUrl: tmplUrl,

        centerMap : {
            lat : 42.869460,
            lng : 74.611213,
            zoom : 12
        },
        
        link: function(scope, element, attrs, geofieldCtrl) {
            var s = document.createElement('script');

            var self = this;


            s.onload = function() {

                var _map = document.getElementById('map');
                //var height = document.documentElement.clientHeight;
                //_map.style.width = $('#modal-container').width() - 15 + "px";
                //_map.style.height = height - 25 + "px";
                var map;
                DG.then(function () {
                    map = DG.map('map', {
                        center: [self.centerMap.lat, self.centerMap.lng],
                        zoom: self.centerMap.zoom,
                        maxBounds: [
                        ],
                        minZoom : 2
                    });
                    //var marker = DG.marker([42.869465, 74.611210]).addTo(map).bindPopup('Вы кликнули по мне!');
                    var latlngs = [
                        [42.869480, 74.611213],
                        [42.869500, 74.611213],
                        [42.869520, 74.611213],
                        [42.869540, 74.611213],
                        [42.869560, 74.611213],
                        [42.869580, 74.611113]
                    ];
                    //var polyline = DG.polyline(latlngs, {color: 'red'}).addTo(map);
                    //map.fitBounds(polyline.getBounds());
                });
                
                //$( "#modal-container" ).dialog({autoOpen: false});
                
                geofieldCtrl.showMapButton();
            };
            
            s.src = this.mapUrl;     
            
            document.body.appendChild(s); 
            
            scope.openMapDialog = function(){
                //$( "#modal-container" ).dialog("open");
                $('#modal-container').height(document.documentElement.clientHeight-100);
                UIkit.modal($('#modal-container')).show();
                var resizeEvent = new Event('resize');
                window.dispatchEvent(resizeEvent);
            }; 

            scope.geoCallback = function(data){ 
                var r = confirm("Вы выбрали точку с координатами : " + data);
                if (r == true) {
                    geofieldCtrl.setGeoValue(data);
                    $(element.children()[1]).foundation('reveal','close');
                } 
            }
        },

        controller: ['$scope', function($scope) {
            this.setGeoValue = function(data) {
                $scope.$apply(function() {
                    $scope.geodata = data;
                });
            };

            this.showMapButton = function(){
                $scope.$apply(function() {
                    $scope.map_button = true;
                    $('#map').height(document.documentElement.clientHeight-100);
                    
                });
            }
        }],

        bindToController: true 
    }
});
