dojo.provide("dojoc._editor.plugins.UploadDoc");
dojo.require("dijit._editor._Plugin");
dojo.require("dojox.form.FileUploader");

dojo.declare("dojoc._editor.plugins.UploadDoc",
	dijit._editor._Plugin,
	{
		//summary: 
		// 	Adds an icon to the Editor toolbar that when clicked, opens a system dialog
		//	Although the toolbar icon is a tiny "image" the uploader could be used for 
		//	any file type
		
		tempImageUrl: "",
		iconClassPrefix: "editorIcon",
		useDefaultCommand: false,
		uploadUrl: "",
		button:null,
		label:"Upload",
		htmlTemplate: "<a href=\"${urlInput}\" _djrealurl=\"${urlInput}\">Dokument: ${urlInput}</a>",
		
		setToolbar: function(toolbar){
			this.button.destroy();
			this.createFileInput();
			toolbar.addChild(this.button);
		},
		
		_initButton: function(){
			this.command = "uploadDoc";
			this.editor.commands[this.command] = "Upload Document";
			this.inherited("_initButton", arguments);
			delete this.command;
		},
		
		createFileInput: function(){
			var node = dojo.create('span', {innerHTML:"."}, document.body)
			dojo.style(node, {
				width:"40px",
				height:"20px",
				paddingLeft:"8px",
				paddingRight:"8px"
			})
			this.button = new dojox.form.FileUploader({
				isDebug:true,
				//force:"html",
				uploadUrl:this.uploadUrl,
				uploadOnChange:true,
				selectMultipleFiles:false,
				baseClass:"dojoxEditorUploadNorm",
				hoverClass:"dojoxEditorUploadHover",
				activeClass:"dojoxEditorUploadActive",
				disabledClass:"dojoxEditorUploadDisabled"
			}, node);
			this.connect(this.button, "onComplete", "onComplete");
		},
		
		onComplete: function(data,ioArgs,widgetRef){
			console.log('Creating doc link');
			data = data[0];
			//this.currentDocId = "doc_"+(new Date().getTime());
			// Image is ready to insert
			//var args={};
			if(this.downloadPath){
				var args = {
					urlInput: this.downloadPath + data.name
				};
			}else{
				var args={
					urlInput: data.file
				}
			}
			this.editor.execCommand('inserthtml', dojo.string.substitute(this.htmlTemplate, args));
		}
		
	}
)

dojo.subscribe(dijit._scopeName + ".Editor.getPlugin",null,function(o){
	if(o.plugin){ return; }
	switch(o.args.name){
	case "uploadDoc":
		o.plugin = new dojoc._editor.plugins.UploadDoc({url: o.args.url});
	}
});