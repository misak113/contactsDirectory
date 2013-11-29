
function ListCtrl($scope, ContactData) {
    $scope.contacts = [];
    $scope.loading = false;

    $scope.sortingLog = [];
    $scope.sortableOptions = {
        // called after a node is dropped
        stop: function(e, ui) {
            $scope.loading = true;
            ContactData.saveOrder($scope.contacts, function (e, contacts) {
                $scope.loading = false;
            });
        }
    };

    $scope.setSaveOrderUrl = function (url) {
        ContactData.setSaveOrderUrl(url);
    };

}