
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
    $scope.setAddUrl = function (url) {
        ContactData.setAddUrl(url);
    };

    $scope.add = function () {
        $scope.loading = true;
        ContactData.add({
            firstname: $scope.firstname,
            lastname: $scope.lastname,
            degree: $scope.degree,
            telephone: $scope.telephone,
            email: $scope.email
        }, function (e, contact) {
            if (e === null) {
                $scope.firstname = '';
                $scope.lastname = '';
                $scope.degree = '';
                $scope.telephone = '';
                $scope.email = '';
                $scope.contacts.push(contact);
            }
            $scope.loading = false;
        });
    };

}