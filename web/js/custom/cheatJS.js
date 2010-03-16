/*
 * JS for html cheat to make the html div height synchronized 
 * with the panel container
 */
function panelHtmlCheat(div){	
	var ref = document.getElementById("center_panel");
	var ref = ref.childNodes[1];
	ref = ref.firstChild;
	ref = ref.firstChild;
	ref = ref.firstChild;
	if(ref.childNodes[1]){
		ref = ref.childNodes[1];
	}else{
		ref = ref.firstChild;
	}
	div.style.height = parseInt(ref.style.height) - 65 + "px";
	
}