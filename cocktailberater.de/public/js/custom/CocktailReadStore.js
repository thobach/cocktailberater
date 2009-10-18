dojo.require("dojox.data.QueryReadStore");

dojo.provide("custom.CocktailReadStore");
dojo.declare("custom.CocktailReadStore", dojox.data.QueryReadStore, {
    fetch:function (request) {
        request.serverQuery = { search:request.query.name, search_type: "name" };
        return this.inherited("fetch", arguments);
    }
});