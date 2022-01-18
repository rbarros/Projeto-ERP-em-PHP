<?php

class EnviarXml
{
    private static $database = 'vendas_base';
    //função com intuito de calcular os valores das vendas e enviar um emaisl zipado com o xml do respectivo mês
    // calcular o valor da primeira loja do array lojas
    // montar o array desses valores da loja junto ao array já existente;
    // zipar o mês anterior
    // enviar e-mail com o array dos calculos e  
    public static function ObterValores($objects=null){
        try{
            if($objects === null){
                return self::calcularMesAnterior();
            }else{
                return self::calcularPorPesquisa($objects);
            }
        }
         catch(Exception $e){
            return $e->getMessage();
        }
    } 
    public static function calcularPorPesquisa($objects){  
        try{
            //uma consulta para obter a chave valor de idempresa => nome fantasia
            TTransaction::open('base_banco');
            $lojas_obj                      = Loja::getObjects();
            TTransaction::close();
            $lojas                          = array();
            foreach($lojas_obj as $loja){
                $lojas[$loja->idEmpresa]    = $loja->nome_fantasia;
            }
            //arquivo compactando
            $arquivo_zip            = date("Y-m-d").'.zip';
            $diretorio              = "tmp/$arquivo_zip";
            $zip                    = new ZipArchive();
            if(file_exists($arquivo_zip)) unlink(realpath($arquivo_zip)); 
            if( $zip->open($arquivo_zip, \ZipArchive::CREATE) ){
                foreach($objects as $object){
                    $nome_loja          = $lojas[$object->empresaId];
                    $mes                = date( 'm', strtotime($object->dataEmissao));
                    $ano                = date( 'Y', strtotime($object->dataEmissao));
                    $arquivo_nome       = "$object->numero-$object->id_externo.xml";
                    $arquivo            = "xml/$ano/$mes/$nome_loja/$arquivo_nome";
                    $zip->addFile($arquivo, $arquivo_nome);
                }
            }
            $zip->close();
            TPage::openFile($arquivo_zip);
                
            
            
         }catch(Exception $e){
            return $e->getMessage();
         }
    }
    
    public static function compactarXml($referencia){
         try{
             $mes           = $referencia['mes'];
             $ano           = $referencia['ano'];
             $arquivo       = "xml/$mes-$ano.zip";
             if(file_exists($arquivo)) unlink(realpath($arquivo)); 
             $diretorio     = "xml/$ano/$mes/"; // aqui estou compactando a pasta raiz do sistema.
             $rootPath      = realpath($diretorio);
             $zip = new ZipArchive();
             $zip->open($arquivo, ZipArchive::CREATE | ZipArchive::OVERWRITE);   
             $files = new RecursiveIteratorIterator(
                      new RecursiveDirectoryIterator($rootPath),
                      RecursiveIteratorIterator::LEAVES_ONLY
                      ); 
             foreach ($files as $name => $file)
                {
                    if (!$file->isDir())
                    {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($rootPath) + 1);
                        // Adiciona os arquivos no pacote Zip.
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            $zip->close();
            TPage::openFile($arquivo);
         }catch(Exception $e){
            return $e->getMessage();
         }
        
    }
    
    
    public static function calcularMesAnterior(){    
        try{
            $resumo                 = [];
            TTransaction::open('base_banco');
            $lojas                  = Loja::getObjects();
            TTransaction::close();
            TTransaction::open(self::$database);
            $mes_atual              = date('m');
            $mes;
            $ano;
            if($mes_atual == "01"){
                $mes                    = "12";
                $ano                    = date('Y',strtotime("-1 year"));
            }else{
                $mes                    = date('m',strtotime("-1 month"));
                $ano                    = date('Y');
            }
            $este_mes                   = $ano.'-'.$mes;
            $referencia                 = array();
            $referencia['mes']          = $mes;
            $referencia['ano']          = $ano;
           
            foreach($lojas as $loja){
               $arrayLoja               = [];
               $vendas = Venda::where("loja","=",$loja->id)
                              ->where("dt_venda","like",$este_mes.'%')
                              ->load();
                              
               $dinheiroMensal          =0;
               $dinheiroMensalFiscal    =0;
               $creditoMensal           =0;
               $debitoMensal            =0;
               $mistoMensal             =0;
               
               if($vendas){
               foreach($vendas as $venda){
                       switch($venda->forma_pagamento){
                           case 'Dinheiro':
                               if($venda->fiscal == "T"){
                                   $dinheiroMensalFiscal    +=$venda->valor_total;
                                   $dinheiroMensal          +=$venda->valor_total;
                               }else{
                                   $dinheiroMensal          +=$venda->valor_total;
                               }
                               break;
                           case 'Pagamento misto':
                               $mistoMensal     +=$venda->valor_total;
                               break;
                           case 'Cartão Credito à Vista':
                           case 'Cartão Credito parcelado':   
                               $creditoMensal   +=$venda->valor_total;
                               break;
                           case 'Cartão Débito':
                               $debitoMensal    +=$venda->valor_total;
                               break;
                           default:
                               
                        }
                    }
                }
                $arrayLoja['Loja']                  = $loja->nome_fantasia;
                $arrayLoja['dinheiroMensalFiscal']  = round(($dinheiroMensalFiscal),2,PHP_ROUND_HALF_UP);
                $arrayLoja['dinheiroMensal']        = round(($dinheiroMensal),2,PHP_ROUND_HALF_UP);
                $arrayLoja['creditoMensal']         = round(($creditoMensal),2,PHP_ROUND_HALF_UP);
                $arrayLoja['debitoMensal']          = round(($debitoMensal),2,PHP_ROUND_HALF_UP);
                $arrayLoja['mistoMensal']           = round(($mistoMensal),2,PHP_ROUND_HALF_UP);
                $resumo[$loja->nome_fantasia]       = $arrayLoja;
            }
            $file = 'xml/Resumo Vendas.csv';
            $handle = fopen($file, 'w');
            $csvColumns =[];
            $csvColumns = ['Loja','Dinheiro Mensal Fiscal','Dinheiro Mensal','Credito Mensal','Debito Mensal','Misto Mensal'];
            fputcsv($handle, $csvColumns, ';');
            foreach($resumo as $loja){
                $csvColumns   =[];
                $csvColumns []=$loja['Loja'];
                $csvColumns []=$loja['dinheiroMensalFiscal'];
                $csvColumns []=$loja['dinheiroMensal'];
                $csvColumns []=$loja['creditoMensal'];
                $csvColumns []=$loja['debitoMensal'];
                $csvColumns []=$loja['mistoMensal'];
                fputcsv($handle, $csvColumns, ';');
            }
            fclose($handle);
            TPage::openFile($file);
            TTransaction::close();
            $referencia['lojas'] = $lojas;
            self::compactarXml($referencia);
        }
        catch(Exception $e){
                return $e->getMessage();
             }
        }
    
    
    public static function listarValores($date=null){//função recebe como parâmetro a data inicial e data final selecionado la no formulário, caso null retornar total mês atual
        if($date != null){
            $data_inicio = strtotime($date['dt_emissao']);
        }   
    }
    
    
    public static function listarDiretorio($param=null){
        try{
            $mes       = date('m',strtotime('-1 month'));
            $ano       = date('Y');
            $diretorio = "xml/$ano/$mes/";
            $rootPath  = realpath($diretorio);
            $message   = '';
            $resumo    = [];
            $files     = new RecursiveIteratorIterator(
                         new RecursiveDirectoryIterator($rootPath),
                         RecursiveIteratorIterator::LEAVES_ONLY
                         ); 
            $valor          = null;
            $venda          = null;
            foreach ($files as $name => $file)
            {
                if (!$file->isDir())
                {
                    $filePath       = $file->getRealPath();
                    $relativePath   = substr($filePath, strlen($rootPath) + 1);
                    $xml            = simplexml_load_file($filePath);
                    foreach($a_xml->infNFe->pag->detPag as $valor){
                        echo "<pre>";
                        var_dump ($valor);
                        echo "</pre>";
                    }
                    //$valor          = doubleval($a_xml->infNFe->pag->detPag->vPag);
                    echo "<pre>";
                    echo $xml;
                    var_dump($filePath);
                    echo "</pre>";
                   /* foreach($xml as $a_xml){
                        if($a_xml->infNFe->emit->xFant != null){
                            $to_remove2 = array('PR','1','2','3','4','5','6','7','8','9','0');
                            $venda  = substr($a_xml->infNFe->infAdic->infCpl,166);
                            $valor  = doubleval($a_xml->infNFe->pag->detPag->vPag);
                            $valor  = str_replace('.',',',$valor);
                                                //$resumo[]       = $filePath.'/'.$venda.'/'.$valor;
                        }
                    }*/
                    
                }
                $resumo[]       = $filePath.'/'.$valor;
            }
            
            $file = 'tmp/Resumo Vendas.csv';
            $handle = fopen($file, 'w');
            $csvColumns = [];
            $csvColumns = ['ANO','MÊS','LOJA','numero nfce','venda','valor'];
            $to_remove  = [];
            $to_remove  = ['xml','var','www','html','.','"'];
            fputcsv($handle, $csvColumns, ';');
            foreach($resumo as $nfce){
                    $csvColumns    =[];
                    $nfce          = str_replace($to_remove,'',$nfce); 
                    $nfce          = str_replace('/',';',$nfce);
                    $nfce          = substr($nfce, 5);
                    $csvColumns[]  = $nfce;
                    fputcsv($handle, $csvColumns, ';');
                }
                 fclose($handle);
            TPage::openFile($file);
        }catch(Exeption $e){
            new TMessage('error',$e->getMessage());
        }
    }
}