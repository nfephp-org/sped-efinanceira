
function criarMenu(){
	var html;
	var novoGrupo = false;
		
	$.ajax({
		type: "GET",
		url: "/xml/breadcrumb.xml",
		dataType: "xml",
		success: function(xml) {
			//alert($(xml).text());			
			var nome = $(xml).find('breadcrumbs').attr('nome');
			var url = $(xml).find('breadcrumbs').attr('url');
			html = "<ul id='navmenu-h'><li class='inicio'><a id='inicio' href='"+url+"' title='"+nome+"'></a></li>";			
			$(xml).find('topico').each(function(){

				visivelMenu = $(this).attr('visivel-menu');
				
				if(visivelMenu != "nao"){
				
					nome = $(this).attr('nome');
					url = $(this).attr('url');
					id = $(this).attr('id');
					html = html + "<li><a href='"+url+"'>"+nome+"</a>";
					novoGrupo = true;				
					
					$(this).find('item').each(function(){
						if(novoGrupo){
							html = html + "<ul>";
							novoGrupo = false;						
						}
						url = $(this).attr('url');
						
						nome = $(this).attr('nome');
						html = html + "<li><a href='"+url+"'>"+nome+"</a>";
											
						if($(this).find('subitem').length == 0){
							html = html + "</li>";
						}
						else if(!novoGrupo){
							html = html + "<ul>";						
							novoGrupo = true;
						}				
						
						$(this).find('subitem').each(function(){
						
							url = $(this).attr('url');
							nome = $(this).attr('nome');
						
							html = html + "<li><a href='"+url+"'>"+nome+"</a></li>";
							
							//fecha o grupo do subitem
							if($(this).next().length == 0) {
								html = html + "</ul></li>";
								novoGrupo = false;
							}
						});
						
						//fecha o grupo item
						if($(this).next().length == 0) {
							html = html + "</ul></li>";
							novoGrupo = false;
						}
					});
				}
			});						
			html = html + "</ul>"			
			$("#menu-principal").html(html);
		}
	});		
}

function tabelas() {
    jQuery('#conteudo-pagina table').each( function () {
            var classe = '';
            jQuery(this).children('tbody').children('tr').each( function () { classe = classe == 'impar' ? 'par' : 'impar'; jQuery(this).addClass(classe); } );
    } );
}

jQuery(document).ready( function(){
	criarMenu();
	tabelas();
} );