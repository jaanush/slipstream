dojo.provide("dojoc._editor.plugins.ViewSource");
dojo.require("dijit._editor._Plugin");

dojo.declare("dojoc._editor.plugins.ViewSource",
	dijit._editor._Plugin,
	{
		buttonClass: dijit.form.ToggleButton,
		useDefaultCommand: false,

		_initButton: function(){
			this.command = "htmlToggle";
			this.editor.commands[this.command] = "View HTML source"; // note: should be localized
			this.iconClassPrefix = "customIcon";
			this.inherited(arguments);
			delete this.command; // kludge so setEditor doesn't make the button invisible
			this.connect(this.button, "onClick", this._toggleSource);	
		},

		destroy: function(f){
			this.inherited(arguments);
			if(this.sourceArea){ dojo.destroy(this.sourceArea); }
		},

		_toggleSource: function(){
			this.source = !this.source;
			if(!this.sourceArea){
				this.sourceArea = dojo.doc.createElement('textarea');
				this.sourceArea.style.position = 'absolute';
				dojo.place(this.sourceArea, this.editor.domNode, "last");
			}
			if(this.source){
				this.sourceArea.value = this.editor.getValue();
				dojo.marginBox(this.sourceArea, dojo.marginBox(this.editor.editingArea));
			}else{
				this.editor.setValue(this.sourceArea.value);
				this.sourceArea.style.top = "-999px";
			}

			this.editor.attr('disabled', this.source); // conditionally disable toolbar -- FIXME. Doesn't work.
			this.button.attr('label', this.source ? "View WYSIWYG" : this.editor.commands[this.command]); // note: should be localized
		}
	}
);

/* the following code registers my plugin */
dojo.subscribe(dijit._scopeName + ".Editor.getPlugin",null,function(o){
	if(o.plugin){ return; }
	if(o.args.name == "ViewSource"){
		o.plugin = new dojoc._editor.plugins.ViewSource({command: o.args.name});
	}
});