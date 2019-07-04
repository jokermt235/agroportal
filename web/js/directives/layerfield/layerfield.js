app.directive('agroLayer', function() {
    return {        
        restrict: 'EA',
    
        templateUrl: layerUrl,

        link: function(scope, element, attrs, agroLayerCtrl) {
            scope.openLayerDialog = function(){
                UIkit.modal('#layer-modal',{container: false}).show();
            };
        },
        controller: ['$scope', function($scope) {
            $scope.ngOkClicked = function(){
                if($scope.js_layer){
                    for(var i=0;i < $scope.js_layer.length;i++){
                        if($scope.js_layer[i].id == $scope.checked_layer){
                            $scope.choose_layer = $scope.js_layer[i].name;
                        }
                    }
                }
                UIkit.modal('#layer-modal').hide();

            },
            $scope.ngCancelClicked = function(){
                $scope.checked_layer = '';
                $scope.choose_layer = 'Выбрать слой';
                UIkit.modal('#layer-modal').hide();
            }
        }],

        bindToController: true
    }
});
