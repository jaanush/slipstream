<?php
$djpath='/lib/dojo-svn';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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

            .dojoxGrid table { margin: 0; } html, body { width: 100%; height: 100%;
            margin: 0; }
			fieldset	{ border: 1px solid #CCCCCC; margin: 4px; padding: 4px}
</style>
<script type="text/javascript" src="<?php print($djpath) ?>/dojo/dojo.js" djConfig="isDebug: true, parseOnLoad: true, usePlainJson: true"></script>
<script type="text/javascript">
	dojo.registerModulePath("slipstream", "/lib/slipstream");
	dojo.registerModulePath("site", "/lib/site");

//dojo.require('dojox.data.ClientFilter');
dojo.require('dojox.data.JsonRestStore');
dojo.require('dojox.grid.DataGrid');

var store=new dojox.data.JsonRestStore({target: '/ssrest/user',idAttribute: 'id',labelAttribute: 'name'});
//store.fetch({query:{id:'*'},sort:[{attribute: 'order', descending: false}],queryOptions:{cache:true}});
var layout=[{
				defaultCell: { width: 8, editable: false, type: dojox.grid.cells._Widget },
				rows:
				[
					{
						name: 'Order',
						field: 'order',
						//hidden: true,
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
//sortFields="[{attribute: 'order', descending: false}]"


</script>
</head>
<body class="tundra">
			<div id="grid1" jsId="grid1" sortAttrs="[{attribute: 'order', descending: false}]" structure="layout" query="{id:'*'}" store="store" rowSelector="20px" selectionMode="single" dojoType="dojox.grid.DataGrid" style="height: 100%">
			</div>
</body>
</html>