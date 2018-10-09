app.directive('akGeofield', function() {
    return { 
        restrict: 'EA',
        scope: {
            geodata : '='
        },

        mapUrl : 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAdc4D22iXnYoIbDE7lpXos0RFWpVA7WXY',
        
        templateUrl: root + 'rest/tmpl/fields/geofield.html',

        centerMap : {
            lat : 42.869460,
            lng : 74.611213,
            zoom : 12
        },
        
        link: function(scope, element, attrs, geofieldCtrl) {
            var s = document.createElement('script');

            var self = this;

            s.onload = function() {
                var mapProp= {
                    center: new google.maps.LatLng(self.centerMap.lat, self.centerMap.lng),
                    zoom: self.centerMap.zoom,
                };
                
                var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

                google.maps.event.addListener(map, 'click', function(args) {  
                    scope.geoCallback(args.latLng.lat() + "," +  args.latLng.lng());
                });
            };
            
            s.src = this.mapUrl;     
            
            document.body.appendChild(s);
            
            scope.openMapDialog = function(){
                $(element.children()[1]).foundation('reveal','open');
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
        }],

        bindToController: true 
    }
});
