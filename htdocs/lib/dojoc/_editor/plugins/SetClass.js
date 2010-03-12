dojo.provide("dojoc._editor.plugins.SetClass");
dojo.require("dijit._editor.plugins.FontChoice");

dojo.declare("dojoc._editor.plugins._SetClassDropdown",
	[dijit._Widget, dijit._Templated],
	{
		labelId: "", 
		widget: null,
		widgetsInTemplate: true,
		command: "SetClass",
	
		templateString:
			"<span style='white-space: nowrap' class='dijit dijitReset dijitInline'>" +
				"<label class='dijitLeft dijitInline' for='${selectId}'>${label}</label>" +
				"<input dojoType='dijit.form.FilteringSelect' required=false labelType=html labelAttr=label searchAttr=name " +
						"tabIndex='-1' id='${selectId}' dojoAttachPoint='select' value=''/>" +
			"</span>",
			
		//values: ['red','green','blue','gray'],
		label: 'Välj färg',
		
		getLabel: function(value, name){
			return "<div class='"+value+"'>" + name + "</div>";
		},

		postMixInProperties: function(){
			console.info('postMixInProperties SetClass');
			//console.log('Done creating menu',this);
			this.inherited(arguments);
			//console.log('Inherited:',this.inherited);
			//console.log('arguments:',arguments);
			//this.strings = dojo.i18n.getLocalization("dijit._editor", "FontChoice");
	
			// Set some substitution variables used in the template
			this.label = this.label || this.command;
			this.id = dijit.getUniqueId(this.declaredClass.replace(/\./g,"_"));		
			this.selectId = this.id + "_select";
			//this.inherited(arguments);
			console.log('here',this);
		},

		postCreate: function(){
			// Initialize the list of items in the drop down by creating data store with items like:
			// {value: 1, name: "xx-small", label: "<font size=1>xx-small</font-size>" }
			var	items = dojo.map(this.values, function(value){
					var name = value;
					return {
						label: this.getLabel(value, name),
						name: name,
						value: value
					};
				}, this);
			//items.push({label: "", name:"", value:""}); // FilteringSelect doesn't like unmatched blank strings
	
			this.select.store = new dojo.data.ItemFileReadStore({
				data: {
					identifier: "value",
					items: items
				}
			});
	
			this.select.attr("value", "");
			//this.select.attr("disabled") = false;
			this.disabled =  this.select.attr("disabled");
		},

	_setValueAttr: function(value){
		//if the value is not a permitted value, just set empty string to prevent showing the warning icon
		this.select.attr('value', dojo.indexOf(this.values,value) < 0 ? "" : value);
	},

	focus: function(){
		this.select.focus();
	},

	_setDisabledAttr: function(value){
		// summary:
		//		Over-ride for the button's 'disabled' attribute so that it can be 
		//		disabled programmatically.
		
		//Save off ths disabled state so the get retrieves it correctly
		//without needing to have a function proxy it.
		this.disabled = value;
		this.select.attr("disabled", value);
	}
	}
);

dojo.declare("dojoc._editor.plugins.SetClass",
	dijit._editor._Plugin,
	{
		//buttonClass: dijit.form.ToggleButton,
		useDefaultCommand: false,
		iconClassPrefix: "dojocEditorIcon",

		_initButton: function(){
			//this.command = "justifyRight";
			console.log('Init class set button',this);
			var params = this.params;
			this.button = new dojoc._editor.plugins._SetClassDropdown(params);
			var className = this.iconClassPrefix+" "+this.iconClassPrefix + this.command.charAt(0).toUpperCase() + this.command.substr(1);
			//this.editor.commands[this.command] = ("Toggle class " + this.setClassName);
			//this.iconClassPrefix = "dijitEditorIcon";
			delete this.command; // kludge so setEditor doesn't make the button invisible
			this.connect(this.button.select, "onChange", function(choice){
				if(this.updating){ return; }
				if(dojo.isIE || !this._focusHandle){
					this.editor.focus();
				}else{
					dijit.focus(this._focusHandle);
				}
				this._setClass(choice);
				//if(this.command == "fontName" && choice.indexOf(" ") != -1){ choice = "'" + choice + "'"; }
				//this.editor.execCommand(this.editor._normalizeCommand(this.command), choice);
			});	
			//console.log(this.button.domNode.style);
		},
/*
		updateState: function(){
			// Overrides _Plugin.updateState().
			// Set value of drop down in toolbar to reflect font/font size/format block
			// of text at current caret position.

			var _e = this.editor;
			var _c = this.command;
			if(!_e || !_e.isLoaded || !_c.length){ return; }
			if(this.button){
				var value;
				try{
					value = _e.queryCommandValue(_c) || "";
				}catch(e){
					//Firefox may throw error above if the editor is just loaded, ignore it
					value = "";
				}
				// strip off single quotes, if any
				var quoted = dojo.isString(value) && value.match(/'([^']*)'/);
				if(quoted){ value = quoted[1]; }

				this.updating = true;
				this.button.attr('value', value);
				delete this.updating;
			}

			if(this.editor.iframe){
				this._focusHandle = dijit.getFocus(this.editor.iframe);
			}
		},
		*/
		_setClass: function(choice){
			console.log(this.params);
			//var node=this.editor._sCall('getParentOfType',[this.editor._sCall('getSelectedElement'),this.validNode]);
			var node=this.editor._sCall('getAncestorElement',[this.validNode]);
			//var node=this.editor._sCall('getSelectedElement') || this.editor._sCall('getParentElement');
			//if(!this.validNode.length || this.editor._sCall('isTag',[node,this.validNode])){
			//console.log('Attempting to set',choice);
			//console.log('Node: ',this.editor._sCall('getSelectedElement'));
			if (node) {
				//dojo.toggleClass(node, this.toggleClassName);
				if(this.params.values.length) dojo.forEach(this.params.values,function(item){dojo.removeClass(node,item)})
				//dojo.forEach(this.params.values,function(item){dojo.removeClass(node,item)})
				dojo.addClass(node,choice);
			}
			//}
		}
	}
);

/* the following code registers my plugin */
dojo.subscribe(dijit._scopeName + ".Editor.getPlugin",null,function(o){
	if(o.plugin){ return; }
	if(o.args.name == "SetClass"){
		o.plugin = new dojoc._editor.plugins.SetClass({command: o.args.name});
	}
});