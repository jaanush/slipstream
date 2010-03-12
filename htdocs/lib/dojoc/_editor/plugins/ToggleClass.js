dojo.provide("dojoc._editor.plugins.ToggleClass");
dojo.require("dijit._editor._Plugin");

dojo.declare("dojoc._editor.plugins.ToggleClass",
	dijit._editor._Plugin,
	{
		//buttonClass: dijit.form.ToggleButton,
		useDefaultCommand: false,
		iconClassPrefix: "dojocEditorIcon",

		_initButton: function(){
			//this.command = "justifyRight";
			console.log('Init class toggle button');
			this.editor.commands[this.command] = ("Toggle class " + this.toggleClassName);
			//this.iconClassPrefix = "dijitEditorIcon";
			this.inherited(arguments);
			delete this.command; // kludge so setEditor doesn't make the button invisible
			this.connect(this.button, "onClick", this._toggleClass);	
		},

		destroy: function(f){
			this.inherited(arguments);
			if(this.sourceArea){ dojo.destroy(this.sourceArea); }
		},
		_toggleClass: function(){
			var node=this.editor._sCall('getParentOfType',[this.editor._sCall('getSelectedElement'),this.validNode]);
			//var node=this.editor._sCall('getSelectedElement') || this.editor._sCall('getParentElement');
			//if(!this.validNode.length || this.editor._sCall('isTag',[node,this.validNode])){
			console.log('Attempting to togle',node);
			if (node) {
				dojo.toggleClass(node, this.toggleClassName);
				if(this.classConflict.length) dojo.removeClass(node, this.classConflict);
			}
			//}
		}
	}
);

/* the following code registers my plugin */
dojo.subscribe(dijit._scopeName + ".Editor.getPlugin",null,function(o){
	if(o.plugin){ return; }
	if(o.args.name == "ToggleClass"){
		o.plugin = new dojoc._editor.plugins.ToggleClass({command: o.args.name});
	}
});