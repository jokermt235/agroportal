app.directive('agroGeofield',['$http', function($http) {
    return {        
        restrict: 'EA',
        scope: {
            model: '=ngModel'
        },

        mapUrl : 'https://maps.api.2gis.ru/2.0/loader.js?pkg=full',

    
        templateUrl: tmplUrl,

        centerMap : {
            lat : geoip_lat,
            lng : geoip_lng,
            zoom : 8
        },
        
        link: function(scope, element, attrs, geofieldCtrl) {
            var s = document.createElement('script');

            var self = this;


            s.onload = function() {

                var _map = document.getElementById('map');
                var map;
                DG.then(function () {
                    map = DG.map('map', {
                        center: [self.centerMap.lat, self.centerMap.lng],
                        zoom: self.centerMap.zoom,
                        maxBounds: [
                        ],
                        minZoom : 2
                    });
                    map.on('click', function(evt){
                        geofieldCtrl.setMarkerOnMap(DG, map, evt);
                    });
                });
                
                geofieldCtrl.showMapButton();
            };
            
            s.src = this.mapUrl;     
            
            document.body.appendChild(s); 
            
            scope.openMapDialog = function(){
                $('#modal-container').height(document.documentElement.clientHeight-100);
                UIkit.modal('#modal-container',{container: false}).show();
                var resizeEvent = new Event('resize');
                window.dispatchEvent(resizeEvent);
            }; 

            scope.geoCallback = function(data){ 
                var r = confirm("Вы выбрали точку с координатами : " + data);
                if (r == true) {
                    geofieldCtrl.setGeoValue(data);
                } 
            }
        },

        controller: ['$scope', function($scope) {        
            this.marker;
            $scope.ngOkClicked = function(){
                UIkit.modal('#modal-container').hide();

            },
            $scope.ngCancelClicked = function(){
                $scope.lat  = null;
                $scope.lng  = null;
                if(this.marker){
                    this.marker.remove();
                }
                this.marker = null;
                UIkit.modal('#modal-container').hide();
            }
            this.setGeoValue = function(data) {
                $scope.$apply(function() {
                    $scope.geodata = data;
                });
            };

            this.showMapButton = function(){
                $scope.$apply(function() {
                    $scope.map_button = true;
                    $('#map').height(document.documentElement.clientHeight-150);
                    
                });
            };
            this.setMarkerOnMap = function(DG, map, evt){
                var data = {
                    lat: evt.latlng.lat,
                    lng: evt.latlng.lng
                }
                $http.post(checkMarkerUrl,data).then(function(response) {
                    if(response.data.status){
                        var layer = $( "input[type=radio][name=layer_id]:checked" ).val();
                        if(layer){
                            if(this.marker){
                                this.marker.remove();
                                this.marker = null;
                                $scope.lat = null;
                                $scope.lng = null;
                            }
                            this.marker = DG.marker([evt.latlng.lat, evt.latlng.lng]).addTo(map);
                            $scope.lat = evt.latlng.lat;
                            $scope.lng = evt.latlng.lng;
                        }else{
                            if(this.marker){
                                this.marker.remove();
                                this.marker = null;
                                $scope.lat = null;
                                $scope.lng = null;
                            }   
                            alert("Сначала выберерите слой, а потом можно установить маркер!");
                        }
                    }
                });
            };
        }],

        bindToController: true 
    }
}]);
