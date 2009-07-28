dojo.require("dojox.data.QueryReadStore");

dojo.provide("custom.TestNameReadStore");
dojo.declare("custom.TestNameReadStore", dojox.data.QueryReadStore, {
    fetch:function (request) {
        request.serverQuery = { test:request.query.name };
        return this.inherited("fetch", arguments);
    }
});