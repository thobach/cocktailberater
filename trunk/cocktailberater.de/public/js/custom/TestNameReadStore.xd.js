dojo.require("dojox.data.QueryReadStore");

dojo.provide("custom.TestNameReadStore");
dojo.declare("custom.TestNameReadStore", dojox.data.QueryReadStore, {
    fetch:function (request) {
        request.serverQuery = { search:request.query.name, search_type: dojo.byId('search_type_value').value };
        return this.inherited("fetch", arguments);
    }
});