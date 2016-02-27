/**
 * -- CKEDITOR Link selection injection
 * @author q23/geckse
 **/	

setInterval('cke_inject_ckeditor()', 60);

function cke_inject_ckeditor(){
	
	var uibutton = document.getElementsByClassName('cke_dialog_ui_button');
	
	if(uibutton.length == 0){
		return false;
	}

	Array.prototype.forEach.call(uibutton, function(el) {
	    if(el.tagName == "SPAN"){
		    if(el.innerHTML.indexOf("Server durchsuchen") != -1 
		    || el.innerHTML.indexOf("Browse Server") != -1 
		    || el.innerHTML.indexOf("Ver Servidor") != -1
		    || el.innerHTML.indexOf("Explorer le serveur") != -1){
				
				var article_selection_title = "Choose article";
				if(el.innerHTML.indexOf("Server durchsuchen") != -1) article_selection_title = "Artikel auswählen";
				if(el.innerHTML.indexOf("Ver Servidor") != -1) article_selection_title = "Seleccione un artículo";
				if(el.innerHTML.indexOf("Explorer le serveur") != -1) article_selection_title = "Choisir un article";
				
				
				if(el.parentElement.getAttribute('data-injected') != '1'){
					el.parentElement.setAttribute('data-injected','1');
								
					var holder = el.parentElement.parentElement.parentElement.parentElement;
			
					var active = "";
					
					
					for (var i = 0, childNode; i <= holder.childNodes.length; i ++) {
					    childNode = holder.childNodes[i];
					     if(el.innerHTML.indexOf("URL") == 0){
						 	active = document.getElementById(el.getAttribute('for')).value;
						}
					}
					
					var articles_selected = cke_articles;
					if(active != "") articles_selected = articles_selected.replace('value="'+active+'"', 'value="'+active+'" selected="selected"');				
					el.parentElement.parentElement.innerHTML += '<br/><br/>'+article_selection_title+':<br><select class="cke_article_selection" onDblClick="cke_transfer_url(this)" style="border: 1px solid #cccccc; width: 100%; height: 120px;" multiple="multiple">'+articles_selected+'</select>';

				}
			}				
	    }
	});		
}


function cke_transfer_url(select){
	
	var holder = select.parentElement.parentElement.parentElement;
	var aid = select.value;
	
	var urlfield = 0;
	var urlfield_id = holder.querySelector('.cke_required').getAttribute('for');
	
	if(urlfield_id){
		urlfield = document.getElementById(urlfield_id);
		urlfield.value = "[LINKHREF "+aid+"]";
	}
	
	var type = holder.querySelector('.cke_dialog_ui_input_select > select');
	if(type){
		type.value = "";
	}
}
