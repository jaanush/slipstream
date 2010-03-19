<?php
$djpath='/lib/dojo-1.4.2';
//$djpath='/lib/dojo-svn';
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Brandboxer admin</title>
<style type="text/css">
	@import "<?php print($djpath) ?>/dojo/resources/dojo.css";
	@import "<?php print($djpath) ?>/dijit/themes/dijit.css";
	@import "<?php print($djpath) ?>/dijit/themes/tundra/tundra.css";
	@import "<?php print($djpath) ?>/dojox/grid/resources/Grid.css";
    @import "<?php print($djpath) ?>/dojox/grid/resources/tundraGrid.css";
    @import "<?php print($djpath) ?>/dojox/layout/resources/ExpandoPane.css";
    @import "<?php print($djpath) ?>/dojox/editor/plugins/resources/editorPlugins.css";
    @import "<?php print($djpath) ?>/dojox/editor/plugins/resources/css/ShowBlockNodes.css";
    @import "<?php print($djpath) ?>/dojox/editor/plugins/resources/css/Breadcrumb.css";
    @import "/lib/dojoc/_editor/plugins/plugins.css";
    @import "<?php print($djpath) ?>/dojox/form/resources/CheckedMultiSelect.css";
    @import "<?php print($djpath) ?>/dojox/form/resources/BusyButton.css";
    @import "<?php print($djpath) ?>/dijit/themes/tundra/form/Button.css";
    @import "<?php print($djpath) ?>/dojox/form/resources/FileUploader.css";
	@import "<?php print($djpath) ?>/dojox/grid/enhanced/resources/tundraEnhancedGrid.css";
	@import "<?php print($djpath) ?>/dojox/grid/enhanced/resources/EnhancedGrid_rtl.css";

            .dojoxGrid table { margin: 0; } html, body { width: 100%; height: 100%;
            margin: 0; }
			/*fieldset	{ border: 1px solid #CCCCCC; margin: 4px; padding: 4px}*/
</style>
<script type="text/javascript" src="<?php print($djpath) ?>/dojo/dojo.js" djConfig="isDebug: true, parseOnLoad: true, usePlainJson: true"></script>
<script type="text/javascript">
	dojo.registerModulePath("slipstream", "/lib/slipstream");
	dojo.registerModulePath("site", "/lib/site");

/*
dojo.require('dojox.data.PersevereStore');
store=dojox.data.PersevereStore.getStores("/ssrest/",true);
*/

//dojo.require('dojox.data.ClientFilter');
dojo.require('dojox.data.JsonRestStore');
//dojo.require('dojox.rpc.Service');
//dojo.require('dojox.rpc.JsonRPC');
dojo.require('dojox.grid.DataGrid');

dojo.require('dijit.layout.BorderContainer');
dojo.require('dijit.layout.ContentPane');
dojo.require('dijit.layout.TabContainer');
dojo.require('slipstream.view.Panel');
dojo.require('dojox.layout.ExpandoPane');
dojo.require('dijit.layout.AccordionContainer');
dojo.require('dijit.form.CheckBox');
dojo.require('dijit.form.TextBox');
dojo.require('dojox.form.DropDownSelect');
dojo.require('dijit.form.Textarea');

dojo.require('dijit.Editor');
dojo.require("dijit._editor.plugins.FontChoice");
dojo.require("dijit._editor.plugins.LinkDialog");
dojo.require("dijit._editor.plugins.ViewSource");
dojo.require("dojox.editor.plugins.UploadImage");
dojo.require("dojox.editor.plugins.ShowBlockNodes");
dojo.require("dojox.editor.plugins.Breadcrumb");
dojo.require("dojox.form.FileUploader");
dojo.require('dojox.grid.DataGrid');
dojo.require("dojox.grid.EnhancedGrid");
dojo.require("dojox.grid.enhanced.plugins.DnD");
dojo.require("dojox.grid.enhanced.plugins.Menu");
dojo.require("dojox.grid.enhanced.plugins.NestedSorting");
dojo.require("dojox.grid.enhanced.plugins.IndirectSelection");

dojo.extend(dojox.grid.enhanced.plugins.NestedSorting,{
_getDsSortAttrs: function(){
	// summary:
	//		Get the sorting attributes for Data Store
	// return: Object
	//		Sorting attributes used by Data Store e.g. {attribute: 'xxx', descending: true|false}
	var si = null;
	if(this.sortFields.length>0){
		var dsSortAttrs=this.sortFields;
		this.sortFields=[];
	} else {
		var dsSortAttrs = [];
		dojo.forEach(this.sortAttrs, function(attr){
			if(attr && (attr["asc"] == 1 || attr["asc"] == -1)){
				dsSortAttrs.push({attribute:attr["attr"], descending: (attr["asc"] == -1)});
			}
		});
	}
	return dsSortAttrs.length > 0 ? dsSortAttrs : null;
}
});

dojox.embed.Flash.prototype.init= function(/*dojox.embed.__flashArgs*/ kwArgs, /*DOMNode?*/ node){
	console.log("embed.Flash.movie.init")
	//	summary
	//		Initialize (i.e. place and load) the movie based on kwArgs.
	this.destroy();		//	ensure we are clean first.
	node = dojo.byId(node || this.domNode);
	if(!node){ throw new Error("dojox.embed.Flash: no domNode reference has been passed."); }

	// vars to help determine load status
	var p = 0, testLoaded=false;
	this._poller = null; this._pollCount = 0; this._pollMax = 15; this.pollTime = 100;

	if(dojox.embed.Flash.initialized){

		this.id = dojox.embed.Flash.place(kwArgs, node);
		this.domNode = node;

		setTimeout(dojo.hitch(this, function(){
			this.movie = this.byId(this.id, kwArgs.doc);
			this.onReady(this.movie);

			this._poller = setInterval(dojo.hitch(this, function(){

				// catch errors if not quite ready.
				try{
					p = this.movie.PercentLoaded();
				}catch(e){
					/* squelch */
					//console.warn("this.movie.PercentLoaded() failed");
				}

				if(p == 100){
					// if percent = 100, movie is fully loaded and we're communicating
					this._onload();

				}else if(p==0 && this._pollCount++ > this._pollMax){
					// after several attempts, we're not past zero.
					// FIXME: What if we get stuck on 33% or something?
					this._onload();
					//clearInterval(this._poller);
					//throw new Error("Building SWF failed.");
				}
			}), this.pollTime);
		}), 1);
	}
}
/*
var _service=new dojox.rpc.Service('/smd.php?domain=ppl');
var sserv=_service.get;
sserv.put=_service.put;
sserv.post=_service.post;
sserv.delete=_service.delete;
store=new dojox.data.JsonRestStore({service: sserv,idAttribute: 'id',labelAttribute: 'name'});
*/
entryStore=new dojox.data.JsonRestStore({target: '/ssrest/user',idAttribute: 'id',labelAttribute: 'name'});
featureStore=new dojox.data.JsonRestStore({target: '/ssrest/feature',idAttribute: 'id',labelAttribute: 'name'});
//entryStore.fetch({query:{id:'*'},sort:[{attribute: 'order', descending: false}],queryOptions:{cache:true}});
//featureStore.fetch({query:{id:'*'},sort:[{attribute: 'order', descending: false}],queryOptions:{cache:true}});
//store.fetch({query:{}});
function init(){
		entryStructure=[{
			defaultCell: { width: 8, editable: false, type: dojox.grid.cells._Widget },
			rows:
			[
				{
					name: 'Order',
					field: 'order',
					hidden: true,
					width: '50px'
				},
				{
					name: 'Longname',
					field: 'name',
					width: '150px'
				},
				{
					name: 'Active',
					field: 'active',
					width: '50px'
				}
		]}];

		featureStructure=[{
			defaultCell: { width: 8, editable: false, type: dojox.grid.cells._Widget },
			rows:
			[
				{
					name: 'Order',
					field: 'order',
					hidden: true
				},
				{
					name: 'Name',
					field: 'name',
					width: '150px'
				},
				{
					name: 'Active',
					field: 'active',
					width: '50px'
				}
		]}];
		var outerFrame=new dijit.layout.BorderContainer({
			style: 'width: 100%; height: 100%; border: 0; overflow: hidden'
		});
		var header=new dijit.layout.ContentPane({
			title:"Header",
			region: 'top',
			style: 'height: 20px; border: 0; margin: 0',
			content: '<h1>Slipstream</h1>'
		});
		outerFrame.addChild(header);
		var tabContainer=new dijit.layout.TabContainer({
			title:"Middle",
			region: 'center',
			style: 'overflow: hidden'
		});
		outerFrame.addChild(tabContainer);
		var footer=new dijit.layout.ContentPane({
			title:"Footer",
			region: 'bottom',
			content: 'footer'
		});
		outerFrame.addChild(footer);
		entryView=new slipstream.view.Panel({
			title: 'Inlägg',
			//style: 'overflow: hidden',
			store: entryStore,
			createElement: true,
			helpUrl: '/lib/site/view/help/sv/Entry.html',
			templatePath: dojo.moduleUrl("site.view", "templates/Entry.html")
		});
		tabContainer.addChild(entryView);
		featureView=new slipstream.view.Panel({
			title: 'Bildspel',
			store: featureStore,
			createElement: true,
			helpUrl: '/lib/site/view/help/sv/Feature.html',
			templatePath: dojo.moduleUrl("site.view", "templates/Feature.html")
		});
		tabContainer.addChild(featureView);
		dojo.body().appendChild(outerFrame.domNode);
		outerFrame.startup();
		//dojo.publish('ss/renderDone');
		dojo.subscribe(dijit.byId('grid1').rowMovedTopic, function(r){
			console.log(r);
			var _grid=r.grid;
			var _store=_grid.store;
			var _order=_grid._by_idx[0].item.order||0;
			dojo.forEach(_grid._by_idx,function(_row,_idx){
				if(_row.item['order']!=_order+_idx){
					console.log('Setting order on',_row.item,'to',_order+_idx);
					_store.setValue(_row.item,'order',_order+_idx);
				}
				/*if(_store.hasAttribute(_item,'order')){

				} else {
					_store.setValue(_item,'order',_order+_idx);
				}*/
			});
			_store.save();
		});
}
dojo.addOnLoad(init);


</script>
</head>
<body class="tundra">
</body>
</html>