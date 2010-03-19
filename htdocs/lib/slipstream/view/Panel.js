dojo.provide("slipstream.view.Panel");
dojo.provide("slipstream.view.PanelTree");
dojo.require("dijit.layout.ContentPane");
dojo.require("dojox.form.BusyButton");
dojo.require('slipstream.layout._TemplatedLayoutWidget');
dojo.require("dojox.grid.EnhancedGrid");
dojo.require("dojox.grid.enhanced.plugins.DnD");
dojo.require("dojox.grid.enhanced.plugins.Menu");
dojo.require("dojox.grid.enhanced.plugins.NestedSorting");
dojo.require("dojox.grid.enhanced.plugins.IndirectSelection");
//dojo.require("dojox.grid.cells.dijit");

dojo.declare("slipstream.view.Panel", [slipstream.layout._TemplatedLayoutWidget], {
	mytype: 'panel',
	store: null,
	item: null,
	language: 'sv',
	isDebug: true,
	isContainer: true,
	isLayoutContainer: true,
	widgetsInTemplate: true,
	doLayout: true,
	baseClass: "dijitContentPane",
	language: 'sv',
	prefix: '',
	suffix: '',
	
	constructor: function(args){
		this.log('Constructing Panel for '+args.title,args,this);
		this.cnx={};
		this.cnx.slugsource=new dojo.NodeList();
		this.cnx.forms=new dojo.NodeList();
		this.forms=new dojo.NodeList();
		this.imageUpload=new dojo.NodeList();
		this.lang=new dojo.NodeList();
		this.slugsource=new dojo.NodeList();
		this.postLayout=new dojo.NodeList();
		this.buttons={};
		this.languageOptions=undefined;
		this.langMenu=undefined;
		this.setupDataHandling();
	},
	postMixInProperties: function(){
		this.log('slipstream.view.Panel::postMixInProperties for '+this.title);
		//this.inherited(arguments);
	},
	
	buildRendering: function(){
		this.log('slipstream.view.Panel::buildRendering for '+this.title);
		console.info(this.selector,'Selector');
		this.inherited(arguments);
		console.info(this.selector,'Selector');
	},
	
	postCreate: function(){
		this.log('slipstream.view.Panel::postCreate for '+this.title);
		this.inherited(arguments);
		this.slugsource.attr('intermediateChanges',true);
		this._setupButtons();
		if(this.selector!=undefined) this._connectSelector();
		//this._connectStore();
	},
	startup: function(){
		this.log('slipstream.view.Panel::startup for '+this.title);
		this.inherited(arguments);
	},
	destroy: function(){
		this.log('slipstream.view.Panel::destroy for '+this.title);
		this.inherited(arguments);
	},
	layout: function(){
		this.log('slipstream.view.Panel::layout for '+this.title);
		var cb=this._contentBox;
		if(this.getChildren().length==1){
			this.getChildren()[0].resize({h:cb.h,w:cb.w});
		} else {
			console.error('Can only contain one child, have '+this.getChildren().length,this);
		}
		this.postLayout.forEach(dojo.hitch(this,function(obj){obj()}))
	},
	setupDataHandling: function(){
		
	},
	_setupButtons: function(){
		var _self=this;
		if (this.buttonBar && this.selector) {
			if (!this.autoSave) {
				this.buttons.save = new dojox.form.BusyButton({
					label: "Spara",
					style: "float: left",
					disabled: true,
					onClick: dojo.hitch(this, function(e){
						//this._forceValuesToStore();
						if (this.store.isDirty()) {
							this.store.save({
								alwaysPostNewItems: true,
								revertOnError: false,
								onComplete: function(){
									_self.buttons.save.cancel();
								},
								onError: dojo.hitch(this,function(item){
									this._saveError(item);
								})
							})
						};
											//console.debug(this.item);
					})
				})
				this.buttonBar.domNode.appendChild(this.buttons.save.domNode);
			}

			this.buttons.revert = new dijit.form.Button({
				label: "Återställ",
				disabled: true,
				onClick: dojo.hitch(this,'_revert')
			});
			this.buttonBar.domNode.appendChild(this.buttons.revert.domNode);
			this.buttons.create = new dijit.form.Button({
				label: "Skapa ny",
				disabled: !this.createElement,
				onClick: dojo.hitch(this,function(e){
					this._clearStore();
					var newitem=this.store.newItem(this.skeleton?dojo.clone(this.skeleton):{});
					//this._selectItem(newitem);
					this.log('New Item:',newitem);
					//this._setItem(newitem);
					this._setItem(newitem);
					this.selector.selection.select(this.selector.getItemIndex(newitem));
				})
			});
			this.buttonBar.domNode.appendChild(this.buttons.create.domNode);
			
			if(this.languageOptions!=undefined && this.selector!=undefined){
				this.langMenu=new dojox.form.DropDownSelect({
					//disabled: true,
					onChange: dojo.hitch(this,function(lang){
						console.log('onSelect',lang);
						if(lang) this.setLang(lang);
					})
				},dojo.create('select'));
				this.langMenu.addOption(this.languageOptions);
				console.log(this.langMenu,'Created Language menu');
				this.buttonBar.domNode.appendChild(this.langMenu.domNode);
			};
		}
		/*
		if (this.imageButton) {
			console.log('Setup image upload button');
			this.postLayout.push(dojo.hitch(this,function(){
				console.log('Execute image upload button');
				this.imageUpload=new dojox.form.FileUploader({
					uploadUrl: this.uploaddir+'ss_upload.php',
					selectMultipleFiles: false,
					isDebug: true,
					devMode: false,
					
					force: 'html',
					onChange: dojo.hitch(this,function(files){
						console.log('Selected files',files);
						var fileName=this.prefix+this.store.getValue(this.item,this.store.getIdentityAttributes()[0])+this.suffix+(files[0].type!=''?files[0].type:files[0].name.substr(files[0].name.lastIndexOf('.'),4));
						console.log('fileName',fileName);
						//files[0].fileName=fileName;
						//this.imageUpload.upload();
						this.imageUpload.upload({name:(fileName)});
						console.log('postdata',this.imageUpload.postData);
						dojo.connect(this.imageUpload,'onComplete',this,function(){
							this.image.src=(this.uploaddir+fileName+'?'+(new Date().toString()));
							console.log('Setting filename: ',fileName);
							if(this.store.hasAttribute(this.item,'img')) this.store.setValue(this.item,'img',fileName);
						});
					})
				},this.imageButton);
			}))
		}
		*/
		this.imageUploadButtons=new dojo.NodeList();
		this.imageUpload.forEach(dojo.hitch(this,function(obj){
			var _buttonName=dojo.attr(obj,'name')
			//this.postLayout.push(dojo.hitch(this,function(){
			console.log('Display after:',dojo.attr(obj,'displayAfter'));
			dojo.connect(dijit.byId(dojo.attr(obj,'displayAfter')),'onShow',this,function(){
				var _name=dojo.attr(obj,'name');
				var _uploadUrl=dojo.attr(obj,'uploadUrl');
				var _targetNode=dojo.byId(_name+'node');
				console.log('Execute image upload button');
				this.imageUploadButtons.push(new dojox.form.FileUploader({
					uploadUrl: _uploadUrl,
					selectMultipleFiles: false,
					isDebug: true,
					devMode: false,
					force: 'html',
					onChange: function(files){
						console.log('Selected files',files);
						//var fileName=this.prefix+this.store.getValue(this.item,this.store.getIdentityAttributes()[0])+this.suffix+(files[0].type!=''?files[0].type:files[0].name.substr(files[0].name.lastIndexOf('.'),4));
						//console.log('fileName',fileName);
						//files[0].fileName=fileName;
						//this.imageUpload.upload();
						//this.imageUpload.upload({name:(fileName)});
						//console.log('postdata',this.postData);
						var _uploader=this;
						dojo.connect(this,'onError',_self,function(evt){
							console.log('Error:',evt);
						});
						dojo.connect(this,'onComplete',_self,function(data){
							console.log('Data',data);
							_targetNode.innerHTML=this.getImageHtml(data[0].path);
							//_targetNode.innerHTML='<img src="'+_location+files[0].name+'?'+(new Date().toString())+'"/>';
							this.store.setValue(this.item,_name,data[0].path);
							this.store.save({
								alwaysPostNewItems: true,
								revertOnError: false,
								onComplete: function(){
									_self.buttons.save.cancel();
								},
								onError: dojo.hitch(this,function(item){
									this._saveError(item);
								})
							});
						});
						this.upload();
						
					}
				},obj));
				if(_clear=dijit.byId(_name+'clear')){
					console.log('initClearButton');
					dojo.connect(_clear,'onClick',_self,function(evt){
						this.store.unsetAttribute(this.item,_name);
						_targetNode.innerHTML='';
						this.store.save({
							alwaysPostNewItems: true,
							revertOnError: false,
							onComplete: function(){
								_self.buttons.save.cancel();
							},
							onError: dojo.hitch(this,function(item){
								this._saveError(item);
							})
						});
					})
				}
			})
		}));
		/*if(this.imageButton){
			this.buttons.image=this.imageButton;
			//console.debug(this.buttons.image);
			this.postLayout.push(dojo.hitch(this,function(){
				this.imageUpload=new dojox.form.FileUploader({
					uploadUrl: this.uploaddir+'UploadFile.php',
					selectMultipleFiles: false,
					button: this.buttons.image,
					isDebug: true,
					onChange: dojo.hitch(this,function(files){
						//files[0].name='staff_'+this.store.getValue(this.item,'idstaff')+files[0].type;
						//this.log('Selected files',files);
						//this.log('postdata',this.imageUpload.postData);
						var fileName=this.prefix+this.store.getValue(this.item,this.store.getIdentityAttributes()[0])+this.suffix+files[0].type;
						
							//console.log('Uploading: ',fileName)
						this.imageUpload.upload({name:(fileName)});
						//dojo.connect(this.imageUpload,'onComplete',this,'_reloadImage');
						dojo.connect(this.imageUpload,'onComplete',this,function(){
							//set name here
							this.image.src=(this.uploaddir+fileName+'?'+(new Date().toString()));
							//console.log('Setting filename: ',fileName);
							if(this.store.hasAttribute(this.item,'img')) this.store.setValue(this.item,'img',fileName);
						});
						//setTimeout(dojo.hitch(this, "_reloadImage"), 200);
						//this.image.src=(this.image.src+'?'+Math.ceil(Math.random()*10000));
						//this.image.src='/images/staff_'+this.store.getValue(this.item,'idstaff')+files[0].type;
					})
				});
			}))
		}*/
		
		
	},
	_connectSelector: function(){
		if(this.selector!=undefined) dojo.connect(this.selector, 'onClick', this, function(e){
			if(e.rowIndex!=undefined){
				this.selector.selection.deselectAll();
				this.selector.selection.addToSelection(e.rowIndex);
				this._selectItem(e.grid.getItem(e.rowIndex));
			}
		});
		this.log('Connecting grid selector');
	},
	_saveSelection: function(){
		if(this.selector!=undefined) this.selection=this.selector.selection.getSelected();
	},
	_restoreSelection: function(){
		if(this.selection && this.selector!=undefined){
			this.selector.selection.setSelected(this.selection);
		}
	},
	_selectItem: function(item){
		var _self=this;
		if(this.store.isDirty()){
			this._clearStore(item);
		} else {
			this._setItem(item);
		}
	},
	_clearStore:function(item){
		if (this.store.isDirty()) {
			if (confirm('Vill du spara dina ändringar?')) {
				this.buttons.save.makeBusy();
				this.store.save({
					alwaysPostNewItems: true,
					revertOnError: false,
					onComplete: function(){
						if (item) 
							_self._setItem(item)
					},
					onError: dojo.hitch(this, function(item){
						this._saveError(_self.item);
					})
				});
			}
			else {
				var _id = this.store.getIdentity(this.item);
				this._revert(item);
				//if (item) this._setItem(item);
			}
		}
	},
	_saveError: function(item){
		//this.store.changing(this.item);
		console.error('Error during save',item);
		//alert('Error during save');
		this.buttons.save.cancel();
		this.buttons.save.attr('disabled',false);
		alert('An errror occurred during save. Please check your data and try again or revert to the old data.');
	},
	_revertError:function(item){
		this.log(item);
	},
	_setItem: function(item){
		this.log('SetiItem',item);
		if(this.detailSwitch) this.detailSwitch.selectChild(this.edit);
		if(this.item!=null) this._disconnectDataFields();
		this.isDirty=false;
		this.item=item;
		if(this.item['Translation']!=undefined && this.item.Translation[this.language]==undefined) {
			var _item=this.store.fetchItemByIdentity({
					identity: this.store.getIdentity(this.item) + '#Translation'
				});
				this.store.setValue(_item,this.language,{});
		}
		//if(item['Translation']!=undefined && item.Translation[this.language]==undefined) this.item.Translation[this.language]={};
		this._connectDataFields();
		this.buttons.save.cancel();
		this.buttons.save.attr('disabled',true);
		this.buttons.revert.attr('disabled',true);
		//this.onSetItem();
	},
	_revert: function(item){
		var _id=this.store.getIdentity(this.item);
		console.log('Reverting',this.item,this.store.revert({global:true}));
		//this.store.revert();
		//this._setItem(this.item);
		if(item){
			this._setItem(item);
		} else {
			this._postRevert(_id);
		}
		return;
		this.log('Item id:',_id);
	},
	_postRevert:function(id){
		this.log('PostRevert:',id);
		this.isDirty=false;
		this.buttons.save.attr('disabled',true);
		this.buttons.revert.attr('disabled',true);
		//this._setItem(item);
		this.store.fetchItemByIdentity({identity: id, onItem: dojo.hitch(this,'_setItem')});
	},
	_connectDataFields: function(){
		var self=this;
		//if(this.image) this.image.src=this.getImageSrc();
		this.imageUpload.forEach(dojo.hitch(this,function(obj){
			var _name=dojo.attr(obj,'name');
			var _targetNode=dojo.byId(_name+'node');
			if(this.store.hasAttribute(this.item,_name)){
				_targetNode.innerHTML=this.getImageHtml(this.store.getValue(this.item,_name))
				//_targetNode.innerHTML='<img src="'+dojo.attr(obj,'location')+this.store.getValue(this.item,_name)+'?'+(new Date().toString())+'"/>';
			} else {
				_targetNode.innerHTML='';
			}
		}));
		this.forms.forEach(dojo.hitch(this,function(obj){
			var _name=obj.name;
			var _item=this.item;
			dojo.removeClass(obj.domNode,'fieldChanged');
			if(this.store.hasAttribute(_item,_name)){
				if ((_value = this.store.getValue(_item, _name)) != undefined) {
					this.log('Setting '+obj.name+' to:',_value,_name);
					obj.attr('value',_value);
				} else {
					this.log('Setting '+obj.name+' to null!');
					obj.attr('value',null);
				}
			}
			this.cnx.forms.push(dojo.connect(obj,"onChange",this,function(value){
				this._setItemData(_item,_name,value,obj);
			}));
		}));
		if ((this.slugsource.length>0) && this.slug) {
			if ((this.slug.attr('value') == '') || (this.slug.attr('value') == convertToSlug(this.slugsource.map(function(obj){return obj.attr('value')}).join(' ')))) {
				this.slugsource.forEach(dojo.hitch(this,function(obj){
					this.cnx.slugsource.push(dojo.connect(obj, 'onChange', this, function(e){
						this.slug.attr('value', convertToSlug(this.slugsource.map(function(obj){return obj.attr('value')}).join(' ')));
					}));
				}));
			}
			this.cnx.forms.push(dojo.connect(this.slug, "onChange", this, function(){
				if (this.slug.attr('value') == '') {
					this.slug.attr('value', convertToSlug(this.slugsource.map(function(obj){return obj.attr('value')}).join(' ')));
					if (this.cnx.slugsource.length==0) {
						this.log(this.slugsource);
						this.slugsource.forEach(dojo.hitch(this,function(obj){
							this.cnx.slugsource.push(dojo.connect(obj,'onChange',this,function(e){
								this.slug.attr('value', convertToSlug(this.slugsource.map(function(obj){return obj.attr('value')}).join(' ')));
							}))
						}));
					}
				}
				else 
					if (this.slug.attr('value') != convertToSlug(this.slugsource.map(function(obj){return obj.attr('value')}).join(' '))) {
						dojo.forEach(this.slugConnection,function(obj){dojo.disconnect(obj)});
					}
			}))
		}
	},
	resetLang:function(){
		this.store.lang=this.language;
		if(this.langMenu) this.langMenu.attr('value',this.language);
	},
	
	setLang: function(lang){
		if(this.store.lang != lang && this.selector!=undefined){
			this.store.lang=lang;
			this._setItem(this.item);
			//var _sel=this.selector.selection.getSelected();
			this._saveSelection();
			this.selector._refresh();
			this._restoreSelection();
			//this.selector.selection.setSelected(_sel);
		}
	},
	_disconnectDataFields: function (){
		this.cnx.slugsource.forEach(function(obj){dojo.disconnect(obj)});
		this.cnx.slugsource.length=0;
		this.cnx.forms.forEach(function(obj){dojo.disconnect(obj)});
		this.cnx.forms.length=0;
		//this.forms.attr('value','');
		this.forms.forEach(function(obj){obj.reset?obj.reset():obj.attr('value','')});
	},
	_setItemData: function(item,name,val,field){
		//this.log('Fiering for '+name,val,_store=this.store.getValue(item, name),this.compare(_store, val));
		if (!this.compare(_store=this.store.getValue(item, name), val)) {
			dojo.addClass(field.domNode,'fieldChanged');
			this.store.setValue(item,name,val);
			this.isDirty=true;
			this.buttons.save.attr('disabled',false);
			this.buttons.revert.attr('disabled',false);
			//this.log('Changed ' + name + ':', _store, val);
		}
	},
	onSetItem: function(item){
	},
	reset: function(){
		this._disconnectDataFields();
	},
	log: function(){
		if (this.isDebug) {
			console.log.apply(console, arguments);
		}
	},
	compare: function(it1,it2){
		if(it1 instanceof Date){
			if ((!(it2 instanceof Date)) || (it2.getTime() != it1.getTime())) return false;
		} else if(dojo.isArray(it1) && dojo.isArray(dobj=it2)){
			if(it1.length!=it2.length) return false;
			if(!dojo.every(it1,function(i,idx){return this.compare(i,it2[idx])/*i===it2[idx]*/},this)){
				return false;
			}
		} else if(((typeof(it1)==='string') && (it1.length==0)) && (it2===undefined||it2===null)){
		} else if (it1 != it2) {
			return false;
		};
		return true;
	},
	rtest: function(){
		var _id=this.store.getIdentity(this.item);
		this.log('Pre revert:',this.item.Translation.sv.headline);
		this.store.revert();
		this.log('Post revert:',this.item.Translation.sv.headline);
		this._setItem(this.item);
		/*_item=this.store.fetchItemByIdentity({identity: _id, onItem: function(item){this.log('onItem',item.Translation.sv.headline)}});
		this.log('Direct item:',_item.Translation.sv.headline);*/
	},
	getImageHtml: function(name,alt){
		return '<img style="width: 100%" src="'+name+'?'+(new Date().toString())+'"'+(alt?' lt="'+alt+'"':'')+'/>';
	}
})


dojo.declare("slipstream.view.PanelTree", [slipstream.view.Panel], {
	_connectSelector: function(){
		dojo.connect(this.selector, 'onClick', this, function(item,node){this._selectItem(item)});
		this.log('Connecting tree selector');
	},
	_saveSelection: function(){
		this.selection=this.selector.attr('selected');
	},
	_restoreSelection: function(){
		if(this.selection){
			this.selector.attr('selected',this.selection);
		}
	},
})