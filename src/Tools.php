<?php


namespace NFePHP\eFinanc;

class Tools
{
    const CADASTRO = 'consultaInformacoesCadastrais';
    const INTERMEDIARIO  = 'consultaInformacoesIntermediario';
    const PATROCINADO = 'consultaInformacoesPatrocinado';
    const MOVIMENTO = 'consultaInformacoesMovimento';
    const LISTA = 'consultaListaEFinanceira';
    
    private $available;
    
    public function __construct()
    {
        $this->available = get_class_methods($this);
        var_dump($this->available);
    }
    
    /**
     *
     * @param string $type
     * @param stdClass $std
     */
    public function consulta($type, $std):string
    {
        if (!in_array($type, $this->available)) {
            //esta consulta não foi localizada
            throw EventsException::wrongArgument(1000, $type);
        }
        return $this->$type($std);
    }
    
    /**
     *
     * @param array $events
     */
    public function envia($events):string
    {
    }
    
    /**
     * @param stdClass $std
     */
    protected function consultaInformacoesCadastrais($std):string
    {
    }
    
    protected function consultaInformacoesIntermediario($std):string
    {
    }
    
    protected function consultaInformacoesPatrocinado($std):string
    {
    }
    
    protected function consultaInformacoesMovimento($std):string
    {
    }
            
    protected function consultaListaEFinanceira($std):string
    {
    }
}
