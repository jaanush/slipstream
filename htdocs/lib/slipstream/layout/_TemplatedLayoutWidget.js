dojo.provide("slipstream.layout._TemplatedLayoutWidget");

dojo.require("dijit.layout._LayoutWidget");
dojo.require("dijit._Templated");

dojo.declare("slipstream.layout._TemplatedLayoutWidget",
	[dijit.layout._LayoutWidget, dijit._Templated],
	{
		buildRendering: function(){
			dijit._Templated.prototype.buildRendering.apply(this, arguments);
			this.containerNode = this.containerNode || this.domNode;
		},
		destroyDescendants: function(){
			if(this.containerNode == this.domNode){
				dijit._Widget.prototype.destroyDescendants.apply(this, arguments);
			}else{
				dijit.layout._LayoutWidget.prototype.destroyDescendants.apply(this, arguments);
			}
		}
	}
);