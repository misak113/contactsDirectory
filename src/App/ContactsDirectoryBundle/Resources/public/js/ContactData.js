/**
 * Created with JetBrains PhpStorm.
 * User: misak113
 * Date: 29.11.13
 * Time: 22:33
 * To change this template use File | Settings | File Templates.
 */

angular.module('contactsDirectory').factory('ContactData', function ($http) {

    /**
     *
     * @constructor
     */
    var ContactData = function () {
        this.saveOrderUrl = '/';
        this.addUrl = '/';
        this.deleteUrl = '/';
    };

    /**
     * @param {Array} contacts
     * @param {function} callback
     */
    ContactData.prototype.saveOrder = function (contacts, callback) {
        var data = {
            contacts: contacts
        };
        $http({
            url: this.saveOrderUrl,
            data: data,
            method: 'post'
        })
            .success(function (resp) {
                callback(null, resp.contacts);
            })
            .error(function (resp) {
                callback(resp.error);
            });
    };

    /**
     * @param {Array} contact
     * @param {function} callback
     */
    ContactData.prototype.add = function (contact, callback) {
        var data = {
            contact: contact
        };
        $http({
            url: this.addUrl,
            data: data,
            method: 'post'
        })
            .success(function (resp) {
                callback(null, resp.contact);
            })
            .error(function (resp) {
                callback(resp.error);
            });
    };

    /**
     * @param {Array} contact
     * @param {function} callback
     */
    ContactData.prototype.delete = function (contact, callback) {
        var data = {
            contact: contact
        };
        $http({
            url: this.deleteUrl,
            data: data,
            method: 'post'
        })
            .success(function (resp) {
                callback(null, resp.contact);
            })
            .error(function (resp) {
                callback(resp.error);
            });
    };

    /**
     * @param {string} url
     */
    ContactData.prototype.setSaveOrderUrl = function (url) {
        this.saveOrderUrl = url;
    };
    /**
     * @param {string} url
     */
    ContactData.prototype.setAddUrl = function (url) {
        this.addUrl = url;
    };
    /**
     * @param {string} url
     */
    ContactData.prototype.setDeleteUrl = function (url) {
        this.deleteUrl = url;
    };

    /**
     * factory of service
     */
    return new ContactData();
});
