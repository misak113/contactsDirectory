
function ListCtrl($scope, ContactData) {
    // kontakty
    $scope.contacts = [];
    // pokud se načítá
    $scope.loading = false;

    // Data to create contact
    /** @type {string} */
    $scope.firstname = '';
    /** @type {string} */
    $scope.lastname = '';
    /** @type {string} */
    $scope.degree = '';
    /** @type {string} */
    $scope.telephone = '';
    /** @type {string} */
    $scope.email = '';

    $scope.sortableOptions = {
        // called after a node is dropped
        stop: function(e, ui) {
            // uloží pořadí
            $scope.error = '';
            $scope.loading = true;
            ContactData.saveOrder($scope.contacts, function (e, contacts) {
                $scope.loading = false;
            });
        }
    };

    /**
     * @param url
     */
    $scope.setSaveOrderUrl = function (url) {
        ContactData.setSaveOrderUrl(url);
    };
    /**
     * @param url
     */
    $scope.setAddUrl = function (url) {
        ContactData.setAddUrl(url);
    };
    /**
     * @param url
     */
    $scope.setDeleteUrl = function (url) {
        ContactData.setDeleteUrl(url);
    };

    /**
     * přidá kontakt
     */
    $scope.add = function () {
        $scope.error = '';
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
            } else {
                $scope.error = e;
            }
            $scope.loading = false;
        });
    };

    /**
     * Smaže kontakt
     * @param contact
     */
    $scope.delete = function (contact) {
        $scope.error = '';
        $scope.loading = true;
        ContactData.delete({
            id: contact.id
        }, function (e, contact) {
            if (e === null) {
                var index = 0;
                angular.forEach($scope.contacts, function (c, i) {
                    if (c.id == contact.id)
                        index = i;
                });
                $scope.contacts.splice(index, 1);
            }
            $scope.loading = false;
        });
    }

}