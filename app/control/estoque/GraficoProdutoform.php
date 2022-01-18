<?php

class GraficoProdutoform extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_GraficoProdutoform';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("Gráfico de produtos");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Gráfico de produtos");


        $produto_id = new TDBCombo('produto_id', 'base_banco', 'Produto', 'id', '{SKU}  {cod_barras}  {descricao}  {desc_variacao}  {referencia} ','descricao asc'  );

        $produto_id->setSize('100%');
        $produto_id->setEditable(false);

        $row1 = $this->form->addFields([new TLabel("Produto:", null, '14px', null, '100%'),$produto_id]);
        $row1->layout = [' col-sm-3 col-lg-12'];

        //ALGORITIMO BÁSICO
        $dia                = 1;
        $mes                = intval(date('m'));
        $ano                = intval(date('Y'));
        if($mes==1){
            $ultimo_mes     = 12;
            $ano            = $ano-1;
        }else{
            $ultimo_mes     = ($mes-1)<10?'0'.($mes-1):($mes-1);
        }
        if($ultimo_mes==1){
            $penultimo_mes  = 12;
            $ano            = $ano-1;
        }else{
            $penultimo_mes  = ($ultimo_mes-1)<10?'0'.($ultimo_mes-1):($ultimo_mes-1);
        }
        $mes                = ($mes)<10?'0'.($mes):($mes);
        $mes1               = "$ano-$mes";//mes atual
        $mes2               = "$ano-$ultimo_mes";//ultimo mes
        $mes3               = "$ano-$penultimo_mes";//penultimo mes

        $id                 = $param['id_produto'];
        $apartir            = "$ano-$penultimo_mes-01";
        TTransaction::open('vendas_base');
        $itens_vendidos = VendaItem::where('produto_id','=',$id)
                                   ->where('dt_venda','>=',$apartir)
                                   ->load();
       TTransaction::close();
       $contador1           = 0;
       $contador2           = 0;
       $contador3           = 0;
       $SKU                 = '';

        if($itens_vendidos){
            $SKU            = $itens_vendidos[0]->SKU;
            foreach($itens_vendidos as $item_vendido){
                $data_item  = date_create($item_vendido->dt_venda);
                $mes_item   = $data_item->format('m');
                $ano_item   = $data_item->format('Y');
                $data_item  = "$ano_item-$mes_item";
                echo $item_vendido->quantidade;
                switch($data_item){
                    case $mes1:
                        echo'mes1';
                        $contador1 += $item_vendido->quantidade;
                        break;
                    case $mes2:
                        echo'mes2';
                        $contador2 += $item_vendido->quantidade;
                        break;
                    case $mes3:
                        echo'mes3';
                        $contador3 += $item_vendido->quantidade;
                        break;
                    default:
                        break;
                }
            }
        }

        //cabeçalho

        $html               = new THtmlRenderer('app/resources/google_line_chart.html');
        $data               = array();
        $data[] = [ 'Mes','quantidade'];
        $data[] = [ "$mes3",$contador3];
        $data[] = [ "$mes2",$contador2];
        $data[] = [ "$mes1",$contador1];

        $html->enableSection('main', array('data'   => json_encode($data),
                                           'width'  => '100%',
                                           'height'  => '300px',
                                           'title'  => 'Itens vendidos nos ultimos 3 meses',
                                           'ytitle' => 'Qtd.', 
                                           'xtitle' => 'Mês',
                                           'uniqid' => uniqid()));

        $container = new TVBox;
        $container->style = 'width: 100%';
        /*if ($show_breadcrumb)
        {
            $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }*/
        $container->add($html);
        parent::add($container);
        //ALGORITMO PARA TER FILTRO D EPERIODO E DEPOSITO

        /*
        echo'<pre> -- PARAMETRO -- <br>';
        var_dump($param);
        echo'</pre>';
       if(isset($param['id_produto'])){
        $produto            = $param['id_produto'];
        $periodo            = isset($param['periodo'])?$param['periodo']:2;
        $deposito_p         = isset($param['deposito'])?$param['deposito']:null;
        $depositos          = array();
        $itens_vendidos     = null;
        TTransaction::open('base_banco');
        if($deposito_p==null){
            $depositos      = Deposito::getObjects();
        }else{
            $depositos[]    = new Deposito($deposito); 
        }
        TTransaction::close();
        $dia                = 1;
        $mes                = intval(date('m'));
        $ano                = intval(date('Y'));
        $meses              = array();
        foreach($depositos as $deposito){
            $deposito_[$deposito->id] = ['nome'=>str_replace('DEPOSITO','',$deposito->nome_deposito),'quantidade'=>0];
        }
        $meses[]            = ["mes"=>$mes,"ano"=>$ano,'depositos'=>$deposito_];
        $apartir            = null;
        if($mes-$periodo<1){
            $novo_mes       = (12+($mes-$periodo))<10?'0'.(12+($mes-$periodo)):12+($mes-$periodo);
            $novo_ano       = $ano-1;
            $apartir        = "$novo_ano-$novo_mes-01";
        }else{
            $novo_mes       = ($mes-$periodo)<10?'0'.($mes-$periodo):$mes-$periodo;
            $apartir        = "$ano-$novo_mes-01";
        }
        for($i=1;$i<=$periodo;$i++){
            if($meses[$i-1]['mes']==1){
                $ano_       = $ano-1;
                $deposito_  = array();
                foreach($depositos as $deposito){
                    $deposito_[$deposito->id] = ['nome'=>str_replace('DEPOSITO','',$deposito->nome_deposito),'quantidade'=>0];
                }
                $meses[]    = ['mes'=>12,'ano'=>$ano_,'depositos'=>$deposito_];
            }else{
                $mes_       = $meses[$i-1]['mes']-1;
                $ano_       = $meses[$i-1]['ano'];
                $deposito_  = array();
                //incluir contadores de acordo
                foreach($depositos as $deposito){
                    $deposito_[$deposito->id] = ['nome'=>str_replace('DEPOSITO','',$deposito->nome_deposito),'quantidade'=>0];
                }
                $meses[]    = ['mes'=>$mes_,'ano'=>$ano_,'depositos'=>$deposito_];
            } 
        }

        foreach($meses as $mes){
            echo'<pre> -- ITENS VENDIDOS -- <br>';

            $meses[4]['depositos'][11]['quantidade'] = 1;
            var_dump($meses[4]['depositos'][11]['quantidade']);
            echo'</pre>';
            $data[]     = $subdata;
        }
        echo'<pre> -- MESES -- <br>';
            var_dump($meses);
            echo'</pre>';

            foreach($meses as $mes){
                    $mes_   = $mes['mes'];
                    $ano_   = $ano['ano'];
                    $data_mes = "$ano_-$mes_";
                    if($data_mes == $data_item){
                            echo'<pre> -- meses dentro vendas -- <br>';
                            var_dump($mes);
                            echo'</pre>';
                    }else{
                       continue;
                    }
        TTransaction::open('vendas_base');
        $itens_vendidos = VendaItem::where('produto_id','=',intval($produto))
                                   ->where('dt_venda','>=',$apartir)
                                   ->load();
       TTransaction::close();
        echo'<pre> -- ITENS VENDIDOS -- <br>';
        var_dump($itens_vendidos);
        echo'</pre>';
       if($itens_vendidos){
           echo 'sim';
           $predata = new StdClass();
            foreach($itens_vendidos as $iten_vendido){
                $data_item  = date_create($item_vendido->dt_venda);
                $mes_item   = $data_item->format('m');
                $ano_item   = $data_item->format('Y');
                $data_item  = "$ano_item-$mes_item".$item_vendido->deposito;
                $predata->$data_item += $item_vendido->quantidade;
                }
            echo'<pre> -- meses -- <br>';
            var_dump($predata);
            echo'</pre>';
        }
       }//fim se tiver parametro produto
           $html               = new THtmlRenderer('app/resources/google_line_chart.html');
        $data               = array();

        echo'<pre> -- meses -- <br>';
        var_dump($meses);
        echo'</pre>';

        $mes                = ($mes)<10?'0'.($mes):($mes);
        $mes1               = "$ano-$mes";//mes atual
        $mes2               = "$ano-$ultimo_mes";//ultimo mes
        $mes3               = "$ano-$penultimo_mes";//penultimo mes

        $apartir            = "$ano-$penultimo_mes-01";

        //inicio tratamento grafico -- obtendo dados
        $html               = new THtmlRenderer('app/resources/google_line_chart.html');
        $data               = array();
        TTransaction::open('vendas_base');
        $itens_vendidos = VendaItem::where('produto_id','=',$id)
                                   ->where('dt_venda','>=',$apartir)
                                   ->load();
       TTransaction::close();
       $contador1           = array();
       $contador2           = array();
       $contador3           = array();
       $SKU                 = '';

        if($itens_vendidos){
            $SKU            = $itens_vendidos[0]->SKU;
            foreach($itens_vendidos as $item_vendido){
                $data_item  = date_create($item_vendido->dt_venda);
                $mes_item   = $data_item->format('m');
                $ano_item   = $data_item->format('Y');
                $data_item  = "$ano_item-$mes_item";
                switch($data_item){
                    case $mes1:

                        $contador1[$item_vendido->deposito] += $item_vendido->quantidade;
                        break;
                    case $mes2:

                        $contador2[$item_vendido->deposito] += $item_vendido->quantidade;
                        break;
                    case $mes3:

                        $contador3[$item_vendido->deposito] += $item_vendido->quantidade;
                        break;
                    default:
                        break;

                }
            }
        }

        TTransaction::open('base_dados');
        $depositos = Deposito::getObjects();
        TTransaction::close();
        foreach($depositos as $deposito){
            $data[0] = [ 'Mes','quantidade'];
        }

        //cabeçalho
        echo'<pre>';
        var_dump($contador1);
        var_dump($contador2);
        var_dump($contador3);
        echo'</pre>';

        $data[] = [ "$mes3",$contador3];
        $data[] = [ "$mes2",$contador2];
        $data[] = [ "$mes1",$contador1];

        $html->enableSection('main', array('data'   => json_encode($data),
                                           'width'  => '100%',
                                           'height'  => '300px',
                                           'title'  => 'Itens vendidos nos ultimos 3 meses',
                                           'ytitle' => 'Qtd.', 
                                           'xtitle' => 'Mês',
                                           'uniqid' => uniqid()));

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($html);
        parent::add($container);
       }*/

        // create the form actions


        parent::add($this->form);

    }

    public function onShow($param = null)
    {               

        $data = $this->form->getData();
        $data->produto_id = $param['id_produto'];
        $this->form->setData($data);
    } 

}

