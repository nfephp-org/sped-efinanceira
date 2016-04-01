$(document).ready(function() {
    $("#reduzir").click(function() {   
    	var tamanhoFonte = descobreTamanhoAtual('#conteudo-pagina');
    	if(tamanhoFonte > 9)
    	{
	        $('#conteudo-pagina').css('font-size', descobreTamanhoAtual('#conteudo-pagina') - 1);
	        
	        $('#conteudo-pagina h1').css('font-size', descobreTamanhoAtual('#conteudo-pagina h1') - 1);
	        $('#conteudo-pagina h2').css('font-size', descobreTamanhoAtual('#conteudo-pagina h2') - 1);
	        
	        $('#conteudo-pagina a').css('font-size', descobreTamanhoAtual('#conteudo-pagina a') - 1);
	    }
    });
    
    $("#ampliar").click(function() {
    	var tamanhoFonte = descobreTamanhoAtual('#conteudo-pagina');
    	if(tamanhoFonte < 16)
    	{
	        $('#conteudo-pagina').css('font-size', descobreTamanhoAtual('#conteudo-pagina') + 1);
	        
	        $('#conteudo-pagina h1').css('font-size', descobreTamanhoAtual('#conteudo-pagina h1') + 1);
	        $('#conteudo-pagina h2').css('font-size', descobreTamanhoAtual('#conteudo-pagina h2') + 1);
	        
	        $('#conteudo-pagina a').css('font-size', descobreTamanhoAtual('#conteudo-pagina a') + 1);
	    }
    });
});

function descobreTamanhoAtual (seletor)
{
    var tamanhoAtual = $(seletor).css('font-size');
    return parseFloat(tamanhoAtual, 10);
}