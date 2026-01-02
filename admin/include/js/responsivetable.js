jQuery(document).ready(function(){
	
	// responsive tables
	var headertext = [];
	headers = document.querySelectorAll("#js-table th");
	tablerows = document.querySelectorAll("#js-table th");
	tablebody = document.querySelector("#js-table tbody");

	for(var i = 0; i < headers.length; i++) {
	  var current = headers[i];
	  headertext.push(current.textContent.replace(/\r?\n|\r/,""));
	} 
	if(tablebody != null){
		for (var i = 0; row = tablebody.rows[i]; i++) {
		  for (var j = 0; col = row.cells[j]; j++) {
		    col.setAttribute("data-th", headertext[j]);
		  } 
		}
	}
});