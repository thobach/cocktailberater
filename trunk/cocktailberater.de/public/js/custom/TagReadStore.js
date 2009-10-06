dojo.require("dojox.data.QueryReadStore");

dojo.provide("custom.TagReadStore");
dojo.declare("custom.TagReadStore", dojox.data.QueryReadStore, {
    fetch:function (request) {
        request.serverQuery = { search:request.query.name, search_type: "tag" };
        return this.inherited("fetch", arguments);
    }
});