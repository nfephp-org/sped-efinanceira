function trilha(){
	var urlPagina = location.pathname.toLowerCase();
	var rastro = "<span class='titulo'>Voc\u00ea est\u00e1 aqui:</span><span class='itens'>";
	var urls = new Array();
	urls = urlPagina.split('/');
	urls.pop();	
//	alert(urls);
	
	if(!urlPagina.match(".htm") && !urlPagina.match(".asp")){
		urlPagina = urlPagina + "default.htm";
	}
	
	$.ajax({
		type: "GET",
		url: "/xml/breadcrumb.xml",
		dataType: "xml",
		success: function(xml) {
			//alert($(xml).text());			
			var nome = $(xml).find('breadcrumbs').attr('nome');
			var url = $(xml).find('breadcrumbs').attr('url');
			var id;
			var encontrou = false;			

			if(url.toLowerCase() == urlPagina)
			{
				rastro = rastro + "<span class='itensold'>" +nome + "</span>";	
			}
			else
			{
				rastro = rastro + "<a class='itens' href='"+url+"'>"+nome+"</a>";
									
				$(xml).find('topico').each(function(){
					nome = $(this).attr('nome');
					url = $(this).attr('url');
					id = $(this).attr('id');
					
					if((url.toLowerCase() == urlPagina || urlPagina == ("/"+id+"/")) && !encontrou)
					{
						nome = $(this).attr('nome');
						rastro = rastro + " >> " + "<span class='itensold'>" +nome + "</span>";

						encontrou = true;
					}
					
					if(id == urls[1] && !encontrou)
					{
						rastro = rastro + " >> " + "<a class='itens' href='"+url+"'>" +nome + "</a>";
						$(this).find('item').each(function(){					
							url = $(this).attr('url');
							if(url.toLowerCase() == urlPagina&& !encontrou)
							{
								nome = $(this).attr('nome');
								rastro = rastro + " >> " + "<span class='itensold'>" +nome + "</span>";

								encontrou = true;
							}					
						});
						if(!encontrou){
							$(this).find('item').each(function(){
								var nomeItem = $(this).attr('nome');
								var urlItem = $(this).attr('url');
								
								if(url.toLowerCase() == urlPagina && !encontrou)
								{
									nome = $(this).attr('nome');
									rastro = rastro + " >> " + "<span class='itensold'>" +nome + "</span>";
									encontrou = true;
								}
								
								if(!encontrou){

									$(this).find('subitem').each(function(){
										var nomeSubItem = $(this).attr('nome');							
										var urlSubItem = $(this).attr('url');
										if(urlSubItem.toLowerCase() == urlPagina && !encontrou)
										{
											rastro = rastro + " >> " + "<a class='itens' href='"+urlItem+"'>" +nomeItem + "</a>";
											
											nome = $(this).attr('nome');
											
											rastro = rastro + " >> " + "<span class='itensold'>" +nome + "</span>";								
											encontrou = true;
										}											
									
										if(!encontrou){
											
											
											$(this).find('item-interno').each(function(){							
													url = $(this).attr('url');
													if(url.toLowerCase() == urlPagina && !encontrou)
													{	
														rastro = rastro + " >> " + "<a class='itens' href='"+urlItem+"'>" +nomeItem + "</a>";
														rastro = rastro + " >> " + "<a class='itens' href='"+urlSubItem+"'>" +nomeSubItem + "</a>";
														
														nome = $(this).attr('nome');
														rastro = rastro + " >> " + "<span class='itensold'>" +nome + "</span>";								
														encontrou = true;
													}
													
											});
										}
									});
								}								
							});	
						}
					}
				});
			}//end-else
			
			//alert(rastro);
			rastro = rastro + '</span>';
			$("#breadcrumbs").html(rastro); 
		}
	});		
}

jQuery(document).ready( function(){
	trilha();
} );