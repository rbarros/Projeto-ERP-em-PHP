<?php

use Google\client;
use Google\service;

class LinksImportForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Import';
    private static $primaryKey = 'id';
    private static $formName = 'form_Import';

    use Adianti\Base\AdiantiFileSaveTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Import Master Online");


        $Estado = new TEntry('Estado');
        $button_importar_estados = new TButton('button_importar_estados');
        $button_exportar_estados = new TButton('button_exportar_estados');
        $Cidade = new TEntry('Cidade');
        $button_importar_cidades = new TButton('button_importar_cidades');
        $button_exportar_cidades = new TButton('button_exportar_cidades');
        $Fornecedor = new TEntry('Fornecedor');
        $button_importar_fornecedores = new TButton('button_importar_fornecedores');
        $button_exportar_fornecedores = new TButton('button_exportar_fornecedores');
        $Marca = new TEntry('Marca');
        $button_importar_marcas = new TButton('button_importar_marcas');
        $button_exportar_marcas = new TButton('button_exportar_marcas');
        $CategoriaProduto = new TEntry('CategoriaProduto');
        $button_importar_categorias = new TButton('button_importar_categorias');
        $button_exportar_categorias = new TButton('button_exportar_categorias');
        $SituacaoProd = new TEntry('SituacaoProd');
        $button_importar_situacao_produto = new TButton('button_importar_situacao_produto');
        $button_exportar_situacao_do_produto = new TButton('button_exportar_situacao_do_produto');
        $TipoCadastroProd = new TEntry('TipoCadastroProd');
        $button_importar_tipo_produto = new TButton('button_importar_tipo_produto');
        $button_exportar_tipo_produto = new TButton('button_exportar_tipo_produto');
        $Cest = new TEntry('Cest');
        $button_importar_cest = new TButton('button_importar_cest');
        $button_exportar_cest = new TButton('button_exportar_cest');
        $Ncm = new TEntry('Ncm');
        $button_importar_ncm = new TButton('button_importar_ncm');
        $button_exportar_ncm = new TButton('button_exportar_ncm');
        $Unidade = new TEntry('Unidade');
        $button_importar_unidade_de_medida = new TButton('button_importar_unidade_de_medida');
        $button_exportar_unidade_de_medida = new TButton('button_exportar_unidade_de_medida');
        $SystemUnit = new TEntry('SystemUnit');
        $button_importar_unidade_do_sistema = new TButton('button_importar_unidade_do_sistema');
        $button_exportar_unidade_do_sistema = new TButton('button_exportar_unidade_do_sistema');
        $GrupoLojas = new TEntry('GrupoLojas');
        $button_importar_grupo_lojas = new TButton('button_importar_grupo_lojas');
        $button_exportar_grupo_lojas = new TButton('button_exportar_grupo_lojas');
        $Loja = new TEntry('Loja');
        $button_importar_lojas = new TButton('button_importar_lojas');
        $button_exportar_lojas = new TButton('button_exportar_lojas');
        $Deposito = new TEntry('Deposito');
        $button_importar_deposito = new TButton('button_importar_deposito');
        $button_exportar_deposito = new TButton('button_exportar_deposito');
        $SystemUsers = new TEntry('SystemUsers');
        $button_importar_usuarios = new TButton('button_importar_usuarios');
        $button_exportar_usuarios = new TButton('button_exportar_usuarios');
        $SystemUserGroup = new TEntry('SystemUserGroup');
        $button_importar_grupo_usuario = new TButton('button_importar_grupo_usuario');
        $button_exportar_grupo_usuario = new TButton('button_exportar_grupo_usuario');
        $SystemUserUnit = new TEntry('SystemUserUnit');
        $button_importar_unidade_usuario = new TButton('button_importar_unidade_usuario');
        $button_exportar_unidade_usuario = new TButton('button_exportar_unidade_usuario');
        $Chatapi = new TEntry('Chatapi');
        $button_importar_chat_api = new TButton('button_importar_chat_api');
        $button_exportar_chat_api = new TButton('button_exportar_chat_api');
        $ConfEtiquProduto = new TEntry('ConfEtiquProduto');
        $button_importar_confi_etiquetas = new TButton('button_importar_confi_etiquetas');
        $button_exportar_confi_etiquetas = new TButton('button_exportar_confi_etiquetas');
        $Produto = new TEntry('Produto');
        $button_importar_produto = new TButton('button_importar_produto');
        $button_exportar_produto = new TButton('button_exportar_produto');
        $TabelaPreco = new TEntry('TabelaPreco');
        $button_importar_tabela_preco = new TButton('button_importar_tabela_preco');
        $button_exportar_tabela_preco = new TButton('button_exportar_tabela_preco');
        $Preco = new TEntry('Preco');
        $button_importar_preco = new TButton('button_importar_preco');
        $button_exportar_preco = new TButton('button_exportar_preco');
        $ProdEstoque = new TEntry('ProdEstoque');
        $button_importar_estoque = new TButton('button_importar_estoque');
        $button_exportar_estoque = new TButton('button_exportar_estoque');
        $TransferenciaEtq = new TEntry('TransferenciaEtq');
        $button_importar_transferencias = new TButton('button_importar_transferencias');
        $button_exportar_transferencias = new TButton('button_exportar_transferencias');
        $Venda = new TFile('Venda');
        $button_importar_venda = new TButton('button_importar_venda');
        $button_exportar_csv_vendas = new TButton('button_exportar_csv_vendas');
        $VendaItem = new TFile('VendaItem');
        $button_importar_itens_da_venda = new TButton('button_importar_itens_da_venda');
        $button_exportar_csv_itens_da_venda = new TButton('button_exportar_csv_itens_da_venda');
        $PagamentosVenda = new TFile('PagamentosVenda');
        $button_importar_pagamentos_da_venda = new TButton('button_importar_pagamentos_da_venda');
        $button_exportar_csv_pagamentos_da_venda = new TButton('button_exportar_csv_pagamentos_da_venda');
        $NfceRequest = new TFile('NfceRequest');
        $button_importar_nfce_e = new TButton('button_importar_nfce_e');
        $button_exportar_csv_nfce_e = new TButton('button_exportar_csv_nfce_e');
        $ItensNfce = new TFile('ItensNfce');
        $button_importar_item_da_nfc_e = new TButton('button_importar_item_da_nfc_e');
        $button_exportar_csv_item_da_nfc_e = new TButton('button_exportar_csv_item_da_nfc_e');
        $PagamentoNfce = new TFile('PagamentoNfce');
        $button_importar_pagamento_da_nfc_e = new TButton('button_importar_pagamento_da_nfc_e');
        $button_exportar_csv_pagamento_da_nfc_e = new TButton('button_exportar_csv_pagamento_da_nfc_e');
        $RetornoNfce = new TFile('RetornoNfce');
        $button_importar_retorno_da_nfc_e = new TButton('button_importar_retorno_da_nfc_e');
        $button_exportar_csv_da_nfc_e = new TButton('button_exportar_csv_da_nfc_e');
        $Banco = new TEntry('Banco');
        $button_importar_bancos = new TButton('button_importar_bancos');
        $ContaBancaria = new TEntry('ContaBancaria');
        $button_importar_conta_bancaria = new TButton('button_importar_conta_bancaria');
        $button_exportar_conta_bancaria = new TButton('button_exportar_conta_bancaria');
        $Natureza = new TEntry('Natureza');
        $button_importar_natureza = new TButton('button_importar_natureza');
        $button_exportar_natureza = new TButton('button_exportar_natureza');
        $Conta = new TEntry('Conta');
        $button_importar_conta = new TButton('button_importar_conta');
        $button_exportar_conta = new TButton('button_exportar_conta');

        $Venda->enableFileHandling();
        $VendaItem->enableFileHandling();
        $ItensNfce->enableFileHandling();
        $NfceRequest->enableFileHandling();
        $RetornoNfce->enableFileHandling();
        $PagamentoNfce->enableFileHandling();
        $PagamentosVenda->enableFileHandling();

        $Ncm->setSize('100%');
        $Loja->setSize('100%');
        $Cest->setSize('100%');
        $Conta->setSize('100%');
        $Banco->setSize('100%');
        $Marca->setSize('100%');
        $Venda->setSize('100%');
        $Preco->setSize('100%');
        $Cidade->setSize('100%');
        $Estado->setSize('100%');
        $Chatapi->setSize('100%');
        $Produto->setSize('100%');
        $Unidade->setSize('100%');
        $Deposito->setSize('100%');
        $Natureza->setSize('100%');
        $ItensNfce->setSize('100%');
        $VendaItem->setSize('100%');
        $Fornecedor->setSize('100%');
        $GrupoLojas->setSize('100%');
        $SystemUnit->setSize('100%');
        $TabelaPreco->setSize('100%');
        $NfceRequest->setSize('100%');
        $RetornoNfce->setSize('100%');
        $SystemUsers->setSize('100%');
        $ProdEstoque->setSize('100%');
        $SituacaoProd->setSize('100%');
        $PagamentoNfce->setSize('100%');
        $ContaBancaria->setSize('100%');
        $SystemUserUnit->setSize('100%');
        $PagamentosVenda->setSize('100%');
        $SystemUserGroup->setSize('100%');
        $ConfEtiquProduto->setSize('100%');
        $TransferenciaEtq->setSize('100%');
        $CategoriaProduto->setSize('100%');
        $TipoCadastroProd->setSize('100%');

        $button_exportar_ncm->setAction(new TAction([$this, 'onExportNcm']), "exportar ncm");
        $button_importar_ncm->setAction(new TAction([$this, 'onImportNcm']), "importar ncm");
        $button_exportar_cest->setAction(new TAction([$this, 'onExportCest']), "exportar cest");
        $button_importar_cest->setAction(new TAction([$this, 'onImportCest']), "importar cest");
        $button_importar_lojas->setAction(new TAction([$this, 'onImportLoja']), "importar lojas");
        $button_importar_nfce_e->setAction(new TAction([$this, 'importNfce']), "importar nfce-e");
        $button_exportar_conta->setAction(new TAction([$this, 'onExportConta']), "exportar conta");
        $button_exportar_lojas->setAction(new TAction([$this, 'onExportLojas']), "exportar lojas");
        $button_importar_venda->setAction(new TAction([$this, 'onImportVenda']), "importar venda");
        $button_importar_conta->setAction(new TAction([$this, 'onImportConta']), "importar conta");
        $button_importar_preco->setAction(new TAction([$this, 'onImportPreco']), "importar preço");
        $button_exportar_preco->setAction(new TAction([$this, 'onExportPrecos']), "exportar preço");
        $button_importar_bancos->setAction(new TAction([$this, 'onImportBanco']), "Importar bancos");
        $button_exportar_marcas->setAction(new TAction([$this, 'onExportMarcas']), "exportar marcas");
        $button_importar_marcas->setAction(new TAction([$this, 'onImportMarcas']), "importar marcas");
        $button_exportar_cidades->setAction(new TAction([$this, 'onExportCidade']), "exportar cidades");
        $button_exportar_estoque->setAction(new TAction([$this, 'onExportEstoque']), "exportar estoque");
        $button_exportar_produto->setAction(new TAction([$this, 'onExportProduto']), "exportar produto");
        $button_importar_produto->setAction(new TAction([$this, 'onImportProduto']), "importar produto");
        $button_exportar_estados->setAction(new TAction([$this, 'onExportEstados']), "exportar estados");
        $button_importar_estoque->setAction(new TAction([$this, 'onImportEstoque']), "importar estoque");
        $button_importar_estados->setAction(new TAction([$this, 'onImportEstados']), "Importar Estados");
        $button_importar_cidades->setAction(new TAction([$this, 'onImportCidades']), "importar cidades");
        $button_importar_chat_api->setAction(new TAction([$this, 'onImportChatApi']), "importar chat api");
        $button_exportar_chat_api->setAction(new TAction([$this, 'onExportChatApi']), "exportar chat api");
        $button_exportar_natureza->setAction(new TAction([$this, 'onExportNatureza']), "exportar natureza");
        $button_importar_deposito->setAction(new TAction([$this, 'onimportDeposito']), "Importar deposito");
        $button_exportar_deposito->setAction(new TAction([$this, 'onExportDeposito']), "exportar deposito");
        $button_importar_usuarios->setAction(new TAction([$this, 'onImportUsuario']), "Importar usuários");
        $button_exportar_usuarios->setAction(new TAction([$this, 'onExportUsuario']), "exportar usuários");
        $button_importar_natureza->setAction(new TAction([$this, 'onImportNatureza']), "importar natureza");
        $button_exportar_csv_nfce_e->setAction(new TAction([$this, 'onExportNfce']), "exportar CSV nfce-e");
        $button_exportar_csv_vendas->setAction(new TAction([$this, 'onExportVendas']), "exportar CSV vendas");
        $button_importar_categorias->setAction(new TAction([$this, 'onImportCategoria']), "importar categorias");
        $button_exportar_categorias->setAction(new TAction([$this, 'onExportCategorias']), "exportar categorias");
        $button_exportar_csv_da_nfc_e->setAction(new TAction([$this, 'onExportRetorno']), "exportar CSV da nfc-e");
        $button_importar_grupo_lojas->setAction(new TAction([$this, 'onImportGrupoLojas']), "importar grupo lojas");
        $button_exportar_grupo_lojas->setAction(new TAction([$this, 'onExportGrupoLojas']), "exportar grupo lojas");
        $button_importar_item_da_nfc_e->setAction(new TAction([$this, 'importItensNfce']), "importar item da nfc-e");
        $button_exportar_fornecedores->setAction(new TAction([$this, 'onExportFornecedor']), "exportar fornecedores");
        $button_importar_tipo_produto->setAction(new TAction([$this, 'onImportTipoProduto']), "importar tipo produto");
        $button_exportar_tipo_produto->setAction(new TAction([$this, 'onExportTipoProduto']), "exportar tipo produto");
        $button_importar_fornecedores->setAction(new TAction([$this, 'onImportFornecedores']), "importar fornecedores");
        $button_importar_tabela_preco->setAction(new TAction([$this, 'onImportTabelaPreco']), "importar tabela preço");
        $button_exportar_tabela_preco->setAction(new TAction([$this, 'onExportTabelaPreco']), "exportar tabela preço");
        $button_importar_itens_da_venda->setAction(new TAction([$this, 'onImportItensVenda']), "importar itens da venda");
        $button_exportar_grupo_usuario->setAction(new TAction([$this, 'onExportGrupoUsuario']), "exportar grupo usuario");
        $button_importar_grupo_usuario->setAction(new TAction([$this, 'onImportGrupoUsuario']), "importar grupo usuario");
        $button_importar_unidade_de_medida->setAction(new TAction([$this, 'onImportMedida']), "importar unidade de medida");
        $button_importar_situacao_produto->setAction(new TAction([$this, 'onImportSitProd']), "importar situação produto");
        $button_importar_retorno_da_nfc_e->setAction(new TAction([$this, 'importRetornoNfce']), "importar retorno da nfc-e");
        $button_exportar_conta_bancaria->setAction(new TAction([$this, 'onExportContaBancaria']), "Exportar conta bancaria");
        $button_importar_conta_bancaria->setAction(new TAction([$this, 'onImportContaBancaria']), "importar conta bancaria");
        $button_exportar_transferencias->setAction(new TAction([$this, 'onExportTransferencia']), "exportar transferências");
        $button_exportar_csv_item_da_nfc_e->setAction(new TAction([$this, 'onExportItemNfce']), "exportar CSV  item da nfc-e");
        $button_exportar_unidade_de_medida->setAction(new TAction([$this, 'onExportGupoLojas']), "exportar unidade de medida");
        $button_importar_confi_etiquetas->setAction(new TAction([$this, 'onImportConfEtiqueta']), "importar confi. etiquetas");
        $button_importar_transferencias->setAction(new TAction([$this, 'onImportTransferencias']), "importar transferências");
        $button_importar_unidade_usuario->setAction(new TAction([$this, 'onImportUnidadeUsuario']), "importar unidade usuario");
        $button_exportar_unidade_usuario->setAction(new TAction([$this, 'onExportUnidadeUsuario']), "exportar unidade usuario");
        $button_exportar_confi_etiquetas->setAction(new TAction([$this, 'onExportConfEtiquetas']), "exportar confi. etiquetas");
        $button_exportar_csv_itens_da_venda->setAction(new TAction([$this, 'onExportItemVenda']), "exportar CSV itens da venda");
        $button_exportar_unidade_do_sistema->setAction(new TAction([$this, 'onExportUnidSistema']), "exportar unidade do sistema");
        $button_importar_pagamento_da_nfc_e->setAction(new TAction([$this, 'importPagamentosNfce']), "importar pagamento da nfc-e");
        $button_exportar_situacao_do_produto->setAction(new TAction([$this, 'onExportSitProduto']), "exportar situação do produto");
        $button_importar_pagamentos_da_venda->setAction(new TAction([$this, 'importPagamentosVenda']), "importar pagamentos da venda");
        $button_importar_unidade_do_sistema->setAction(new TAction([$this, 'importImportUnidadeSistema']), "importar unidade do sistema");
        $button_exportar_csv_pagamento_da_nfc_e->setAction(new TAction([$this, 'onExportPagamentoNfce']), "exportar CSV pagamento da nfc-e");
        $button_exportar_csv_pagamentos_da_venda->setAction(new TAction([$this, 'onExportPagamentoVenda']), "exportar CSV pagamentos da venda");

        $button_exportar_ncm->addStyleClass('btn-default');
        $button_importar_ncm->addStyleClass('btn-default');
        $button_exportar_cest->addStyleClass('btn-default');
        $button_importar_cest->addStyleClass('btn-default');
        $button_exportar_conta->addStyleClass('btn-default');
        $button_importar_lojas->addStyleClass('btn-default');
        $button_importar_preco->addStyleClass('btn-default');
        $button_exportar_preco->addStyleClass('btn-default');
        $button_importar_venda->addStyleClass('btn-default');
        $button_importar_conta->addStyleClass('btn-default');
        $button_exportar_lojas->addStyleClass('btn-default');
        $button_exportar_marcas->addStyleClass('btn-default');
        $button_importar_marcas->addStyleClass('btn-default');
        $button_importar_nfce_e->addStyleClass('btn-default');
        $button_importar_bancos->addStyleClass('btn-default');
        $button_exportar_estoque->addStyleClass('btn-default');
        $button_importar_estoque->addStyleClass('btn-default');
        $button_exportar_produto->addStyleClass('btn-default');
        $button_importar_produto->addStyleClass('btn-default');
        $button_exportar_estados->addStyleClass('btn-default');
        $button_importar_estados->addStyleClass('btn-default');
        $button_importar_cidades->addStyleClass('btn-default');
        $button_exportar_cidades->addStyleClass('btn-default');
        $button_importar_chat_api->addStyleClass('btn-default');
        $button_exportar_chat_api->addStyleClass('btn-default');
        $button_exportar_natureza->addStyleClass('btn-default');
        $button_importar_natureza->addStyleClass('btn-default');
        $button_importar_deposito->addStyleClass('btn-default');
        $button_exportar_deposito->addStyleClass('btn-default');
        $button_importar_usuarios->addStyleClass('btn-default');
        $button_exportar_usuarios->addStyleClass('btn-default');
        $button_exportar_csv_nfce_e->addStyleClass('btn-default');
        $button_exportar_csv_vendas->addStyleClass('btn-default');
        $button_exportar_categorias->addStyleClass('btn-default');
        $button_importar_categorias->addStyleClass('btn-default');
        $button_importar_grupo_lojas->addStyleClass('btn-default');
        $button_exportar_grupo_lojas->addStyleClass('btn-default');
        $button_exportar_csv_da_nfc_e->addStyleClass('btn-default');
        $button_exportar_tabela_preco->addStyleClass('btn-default');
        $button_importar_fornecedores->addStyleClass('btn-default');
        $button_exportar_fornecedores->addStyleClass('btn-default');
        $button_importar_tabela_preco->addStyleClass('btn-default');
        $button_exportar_tipo_produto->addStyleClass('btn-default');
        $button_importar_tipo_produto->addStyleClass('btn-default');
        $button_importar_grupo_usuario->addStyleClass('btn-default');
        $button_importar_item_da_nfc_e->addStyleClass('btn-default');
        $button_exportar_grupo_usuario->addStyleClass('btn-default');
        $button_importar_itens_da_venda->addStyleClass('btn-default');
        $button_importar_conta_bancaria->addStyleClass('btn-default');
        $button_exportar_conta_bancaria->addStyleClass('btn-default');
        $button_exportar_transferencias->addStyleClass('btn-default');
        $button_importar_transferencias->addStyleClass('btn-default');
        $button_importar_confi_etiquetas->addStyleClass('btn-default');
        $button_exportar_confi_etiquetas->addStyleClass('btn-default');
        $button_exportar_unidade_usuario->addStyleClass('btn-default');
        $button_importar_unidade_usuario->addStyleClass('btn-default');
        $button_importar_retorno_da_nfc_e->addStyleClass('btn-default');
        $button_importar_situacao_produto->addStyleClass('btn-default');
        $button_importar_unidade_de_medida->addStyleClass('btn-default');
        $button_exportar_csv_item_da_nfc_e->addStyleClass('btn-default');
        $button_exportar_unidade_de_medida->addStyleClass('btn-default');
        $button_importar_unidade_do_sistema->addStyleClass('btn-default');
        $button_exportar_unidade_do_sistema->addStyleClass('btn-default');
        $button_importar_pagamento_da_nfc_e->addStyleClass('btn-default');
        $button_exportar_csv_itens_da_venda->addStyleClass('btn-default');
        $button_exportar_situacao_do_produto->addStyleClass('btn-default');
        $button_importar_pagamentos_da_venda->addStyleClass('btn-default');
        $button_exportar_csv_pagamento_da_nfc_e->addStyleClass('btn-default');
        $button_exportar_csv_pagamentos_da_venda->addStyleClass('btn-default');

        $button_exportar_ncm->setImage('fas:angle-up #000000');
        $button_exportar_cest->setImage('fas:angle-up #000000');
        $button_exportar_conta->setImage('fas:angle-up #000000');
        $button_exportar_lojas->setImage('fas:angle-up #000000');
        $button_importar_ncm->setImage('fas:angle-down #000000');
        $button_exportar_preco->setImage('fas:angle-up #000000');
        $button_importar_cest->setImage('fas:angle-down #000000');
        $button_exportar_marcas->setImage('fas:angle-up #000000');
        $button_importar_lojas->setImage('fas:angle-down #000000');
        $button_importar_venda->setImage('fas:angle-down #000000');
        $button_exportar_estoque->setImage('fas:angle-up #000000');
        $button_exportar_estados->setImage('fas:angle-up #000000');
        $button_importar_preco->setImage('fas:angle-down #000000');
        $button_exportar_cidades->setImage('fas:angle-up #000000');
        $button_importar_conta->setImage('fas:angle-down #000000');
        $button_exportar_produto->setImage('fas:angle-up #000000');
        $button_exportar_chat_api->setImage('fas:angle-up #000000');
        $button_exportar_deposito->setImage('fas:angle-up #000000');
        $button_exportar_usuarios->setImage('fas:angle-up #000000');
        $button_importar_marcas->setImage('fas:angle-down #000000');
        $button_importar_bancos->setImage('fas:angle-down #000000');
        $button_importar_nfce_e->setImage('fas:angle-down #000000');
        $button_exportar_natureza->setImage('fas:angle-up #000000');
        $button_importar_produto->setImage('fas:angle-down #000000');
        $button_importar_estoque->setImage('fas:angle-down #000000');
        $button_importar_estados->setImage('fas:angle-down #000000');
        $button_importar_cidades->setImage('fas:angle-down #000000');
        $button_importar_deposito->setImage('fas:angle-down #000000');
        $button_exportar_categorias->setImage('fas:angle-up #000000');
        $button_importar_natureza->setImage('fas:angle-down #000000');
        $button_exportar_csv_nfce_e->setImage('fas:angle-up #000000');
        $button_exportar_csv_vendas->setImage('fas:angle-up #000000');
        $button_importar_chat_api->setImage('fas:angle-down #000000');
        $button_importar_usuarios->setImage('fas:angle-down #000000');
        $button_exportar_grupo_lojas->setImage('fas:angle-up #000000');
        $button_exportar_fornecedores->setImage('fas:angle-up #000000');
        $button_exportar_tabela_preco->setImage('fas:angle-up #000000');
        $button_exportar_tipo_produto->setImage('fas:angle-up #000000');
        $button_exportar_csv_da_nfc_e->setImage('fas:angle-up #000000');
        $button_importar_categorias->setImage('fas:angle-down #000000');
        $button_exportar_grupo_usuario->setImage('fas:angle-up #000000');
        $button_importar_grupo_lojas->setImage('fas:angle-down #000000');
        $button_exportar_transferencias->setImage('fas:angle-up #000000');
        $button_importar_fornecedores->setImage('fas:angle-down #000000');
        $button_exportar_conta_bancaria->setImage('fas:angle-up #000000');
        $button_importar_tabela_preco->setImage('fas:angle-down #000000');
        $button_importar_tipo_produto->setImage('fas:angle-down #000000');
        $button_importar_item_da_nfc_e->setImage('fas:angle-down #000000');
        $button_importar_grupo_usuario->setImage('fas:angle-down #000000');
        $button_exportar_unidade_usuario->setImage('fas:angle-up #000000');
        $button_exportar_confi_etiquetas->setImage('fas:angle-up #000000');
        $button_importar_itens_da_venda->setImage('fas:angle-down #000000');
        $button_importar_transferencias->setImage('fas:angle-down #000000');
        $button_importar_conta_bancaria->setImage('fas:angle-down #000000');
        $button_importar_confi_etiquetas->setImage('fas:angle-down #000000');
        $button_importar_unidade_usuario->setImage('fas:angle-down #000000');
        $button_exportar_unidade_de_medida->setImage('fas:angle-up #000000');
        $button_exportar_csv_item_da_nfc_e->setImage('fas:angle-up #000000');
        $button_importar_retorno_da_nfc_e->setImage('fas:angle-down #000000');
        $button_exportar_csv_itens_da_venda->setImage('fas:angle-up #000000');
        $button_exportar_unidade_do_sistema->setImage('fas:angle-up #000000');
        $button_importar_situacao_produto->setImage('fas:angle-down #000000');
        $button_exportar_situacao_do_produto->setImage('fas:angle-up #000000');
        $button_importar_unidade_de_medida->setImage('fas:angle-down #000000');
        $button_importar_pagamento_da_nfc_e->setImage('fas:angle-down #000000');
        $button_importar_unidade_do_sistema->setImage('fas:angle-down #000000');
        $button_importar_pagamentos_da_venda->setImage('fas:angle-down #000000');
        $button_exportar_csv_pagamento_da_nfc_e->setImage('fas:angle-up #000000');
        $button_exportar_csv_pagamentos_da_venda->setImage('fas:angle-up #000000');

        $row1 = $this->form->addContent([new TFormSeparator("IMPORTADOR / EXPORTADOR", '#000000', '18', '#ff0091')]);
        $row2 = $this->form->addFields([new TLabel("Estado:", null, '14px', null)],[$Estado],[$button_importar_estados],[$button_exportar_estados]);
        $row2->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Cidades:", null, '14px', null)],[$Cidade],[$button_importar_cidades],[$button_exportar_cidades]);
        $row3->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row4 = $this->form->addFields([new TLabel("Fornecedores:", null, '14px', null)],[$Fornecedor],[$button_importar_fornecedores],[$button_exportar_fornecedores]);
        $row4->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row5 = $this->form->addFields([new TLabel("Marcas:", null, '14px', null)],[$Marca],[$button_importar_marcas],[$button_exportar_marcas]);
        $row5->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row6 = $this->form->addFields([new TLabel("Categoria do produto:", null, '14px', null)],[$CategoriaProduto],[$button_importar_categorias],[$button_exportar_categorias]);
        $row6->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row7 = $this->form->addFields([new TLabel("Situação produto:", null, '14px', null)],[$SituacaoProd],[$button_importar_situacao_produto],[$button_exportar_situacao_do_produto]);
        $row7->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row8 = $this->form->addFields([new TLabel("Tipo produto:", null, '14px', null)],[$TipoCadastroProd],[$button_importar_tipo_produto],[$button_exportar_tipo_produto]);
        $row8->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row9 = $this->form->addFields([new TLabel("cest:", null, '14px', null)],[$Cest],[$button_importar_cest],[$button_exportar_cest]);
        $row9->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row10 = $this->form->addFields([new TLabel("ncm:", null, '14px', null)],[$Ncm],[$button_importar_ncm],[$button_exportar_ncm]);
        $row10->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row11 = $this->form->addFields([new TLabel("Unidade medida:", null, '14px', null)],[$Unidade],[$button_importar_unidade_de_medida],[$button_exportar_unidade_de_medida]);
        $row11->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row12 = $this->form->addFields([new TLabel("Unidade do sistema:", null, '14px', null)],[$SystemUnit],[$button_importar_unidade_do_sistema],[$button_exportar_unidade_do_sistema]);
        $row12->layout = [' col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row13 = $this->form->addFields([new TLabel("Grupo de lojas:", null, '14px', null)],[$GrupoLojas],[$button_importar_grupo_lojas],[$button_exportar_grupo_lojas]);
        $row13->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row14 = $this->form->addFields([new TLabel("Lojas:", null, '14px', null)],[$Loja],[$button_importar_lojas],[$button_exportar_lojas]);
        $row14->layout = [' col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row15 = $this->form->addFields([new TLabel("Depósitos:", null, '14px', null)],[$Deposito],[$button_importar_deposito],[$button_exportar_deposito]);
        $row15->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row16 = $this->form->addFields([new TLabel("Usuários:", null, '14px', null)],[$SystemUsers],[$button_importar_usuarios],[$button_exportar_usuarios]);
        $row16->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row17 = $this->form->addFields([new TLabel("Grupo de Usuários:", null, '14px', null)],[$SystemUserGroup],[$button_importar_grupo_usuario],[$button_exportar_grupo_usuario]);
        $row17->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row18 = $this->form->addFields([new TLabel("unidades do usuario", null, '14px', null)],[$SystemUserUnit],[$button_importar_unidade_usuario],[$button_exportar_unidade_usuario]);
        $row18->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row19 = $this->form->addFields([new TLabel("Chat Api:", null, '14px', null)],[$Chatapi],[$button_importar_chat_api],[$button_exportar_chat_api]);
        $row19->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row20 = $this->form->addFields([new TLabel("Configurações da etiqueta:", null, '14px', null)],[$ConfEtiquProduto],[$button_importar_confi_etiquetas],[$button_exportar_confi_etiquetas]);
        $row20->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row21 = $this->form->addFields([new TLabel("Produto:", null, '14px', null)],[$Produto],[$button_importar_produto],[$button_exportar_produto]);
        $row21->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row22 = $this->form->addFields([new TLabel("Tabela preço:", null, '14px', null)],[$TabelaPreco],[$button_importar_tabela_preco],[$button_exportar_tabela_preco]);
        $row22->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row23 = $this->form->addFields([new TLabel("Preco:", null, '14px', null)],[$Preco],[$button_importar_preco],[$button_exportar_preco]);
        $row23->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row24 = $this->form->addFields([new TLabel("Estoque:", null, '14px', null)],[$ProdEstoque],[$button_importar_estoque],[$button_exportar_estoque]);
        $row24->layout = ['col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row25 = $this->form->addFields([new TLabel("Transferência:", null, '14px', null)],[$TransferenciaEtq],[$button_importar_transferencias],[$button_exportar_transferencias]);
        $row25->layout = [' col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row26 = $this->form->addFields([new TLabel("Venda", null, '14px', 'B', '100%')],[$Venda],[$button_importar_venda],[$button_exportar_csv_vendas]);
        $row26->layout = [' col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row27 = $this->form->addFields([new TLabel("Itens da venda:", null, '14px', 'B', '100%')],[$VendaItem],[$button_importar_itens_da_venda],[$button_exportar_csv_itens_da_venda]);
        $row27->layout = [' col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row28 = $this->form->addFields([new TLabel("Pagamentos da venda:", null, '14px', 'B', '100%')],[$PagamentosVenda],[$button_importar_pagamentos_da_venda],[$button_exportar_csv_pagamentos_da_venda]);
        $row28->layout = [' col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row29 = $this->form->addFields([new TLabel("NFC-e:", null, '14px', 'B')],[$NfceRequest],[$button_importar_nfce_e],[$button_exportar_csv_nfce_e]);
        $row29->layout = [' col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row30 = $this->form->addFields([new TLabel("NFC-e Item:", null, '14px', 'B')],[$ItensNfce],[$button_importar_item_da_nfc_e],[$button_exportar_csv_item_da_nfc_e]);
        $row30->layout = [' col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row31 = $this->form->addFields([new TLabel("NFC-e  pagamento:", null, '14px', 'B')],[$PagamentoNfce],[$button_importar_pagamento_da_nfc_e],[$button_exportar_csv_pagamento_da_nfc_e]);
        $row31->layout = [' col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row32 = $this->form->addFields([new TLabel("Retorno Nfce:", null, '14px', 'B')],[$RetornoNfce],[$button_importar_retorno_da_nfc_e],[$button_exportar_csv_da_nfc_e]);
        $row32->layout = [' col-sm-3',' col-sm-5','col-sm-2','col-sm-2'];

        $row33 = $this->form->addFields([new TLabel("Banco", null, '14px', null)],[$Banco],[$button_importar_bancos],[]);
        $row33->layout = ['col-sm-3',' col-sm-3 col-lg-5',' col-sm-6 col-lg-2','col-sm-2'];

        $row34 = $this->form->addFields([new TLabel("Conta bancária", null, '14px', null)],[$ContaBancaria],[$button_importar_conta_bancaria],[$button_exportar_conta_bancaria]);
        $row34->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row35 = $this->form->addFields([new TLabel("Natureza:", null, '14px', null)],[$Natureza],[$button_importar_natureza],[$button_exportar_natureza]);
        $row35->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        $row36 = $this->form->addFields([new TLabel("Conta:", null, '14px', null)],[$Conta],[$button_importar_conta],[$button_exportar_conta]);
        $row36->layout = ['col-sm-3',' col-sm-5',' col-sm-2','col-sm-2'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar Links", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Desenvolvedor","Importador Principal"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public  function onImportEstados($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
        // --------------------------------ESTADO
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Estado;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object = new Estado();//---------------
                    $object->id     = $value[0];//---------------
                    $object->nome   = strtoupper($value[1]);//---------------
                    $object->uf     = $value[2];
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportEstados($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Estado;
            $objects                = Estado::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage()); 
        }
    }

    public  function onImportCidades($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Cidade;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object = new Cidade();//---------------
                    $object->id             = $value[0];//---------------
                    $object->nome           = strtoupper($value[1]);//---------------
                    $object->estado_id      = $value[2];//---------------
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportCidade($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Cidade;
            $objects                = Cidade::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());
        }
    }

    public  function onImportFornecedores($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Fornecedor;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object = new Fornecedor();
                        if(isset($value[0])) $object->id                         = $value[0];
                        if(isset($value[1])) $object->razao_social               = strtoupper($value[1]);
                        if(isset($value[2])) $object->nome_fantasia              = strtoupper($value[2]);
                        if(isset($value[3])) $object->cnpj                       = $value[3];
                        if(isset($value[4])) $object->observacao                 = $value[4];
                        if(isset($value[5])) $object->fone                       = $value[5];
                        if(isset($value[6])) $object->email                      = $value[6];
                        if(isset($value[7])) $object->cidade                     = $value[7];
                        if(isset($value[8])) $object->rua                        = $value[8];
                        if(isset($value[9])) $object->numero                     = $value[9];
                        if(isset($value[10]))$object->bairro                     = $value[10];
                        if(isset($value[11]))$object->complemento                = $value[11];
                        if(isset($value[12]))$object->dt_ativacao                = $value[12];
                        if(isset($value[13]))$object->inscr_estadual             = $value[13];
                        if(isset($value[14]))$object->possui_ie                  = $value[14];
                        if(isset($value[15]))$object->icms                       = $value[15];
                        if(isset($value[16]))$object->inscr_municipal            = $value[16];
                        if(isset($value[17]))$object->regime_tributario          = $value[17];
                        if(isset($value[18]))$object->contato                    = $value[18];
                        if(isset($value[19]))$object->marca                      = $value[19];
                        if(isset($value[20]))$object->vazio1                     = $value[20];
                        if(isset($value[21]))$object->vazio2                     = $value[21];
                        if(isset($value[22]))$object->vazio3                     = $value[22];
                        $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportFornecedor($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Fornecedor;
            $objects                = Fornecedor::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImportMarcas($param = null) 
    {
        try 
        {
             $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Marca;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object = new Marca();//---------------
                    $object->id             = $value[0];//---------------
                    $object->marca          = strtoupper($value[1]);//---------------
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportMarcas($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Marca;
            $objects                = Marca::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());  

        }
    }

    public  function onImportCategoria($param = null) 
    {
        try 
        {
             $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->CategoriaProduto;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                 = new CategoriaProduto();//---------------
                    $object->id             = $value[0];//---------------
                    $object->nome           = $value[1];//---------------
                    $object->id_externo     = $value[2];
                    $object->ncm_padrao     = $value[3];

                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportCategorias($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->CategoriaProduto;
            $objects                = CategoriaProduto::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage()); 
        }
    }

    public  function onImportSitProd($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->SituacaoProd;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                 = new SituacaoProd();//---------------
                    $object->id             = $value[0];//---------------
                    $object->situacao_prod  = strtoupper($value[1]);//---------------

                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportSitProduto($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->SituacaoProd;
            $objects                = SituacaoProd::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());
        }
    }

    public  function onImportTipoProduto($param = null) 
    {
        try 
        {
           $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->TipoCadastroProd;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                 = new TipoCadastroProd();//---------------
                    $object->id             = $value[0];//---------------
                    $object->descricao      = strtoupper($value[1]);//---------------

                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportTipoProduto($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->TipoCadastroProd;
            $objects                = TipoCadastroProd::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());  
        }
    }

    public  function onImportCest($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Cest;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            $toremove = array(' ',' ','.',',');
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                 = new Cest();//---------------
                    $object->id             = $value[0];//---------------
                    $object->n_cest         = str_replace($toremove,'',$value[1]);
                    $object->descricao      = strtoupper($value[2]);//---------------
                    $object->id_woo_cst     = isset($value[3])?$value[3]:null;

                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportCest($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Cest;
            $objects                = Cest::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage()); 
        }
    }

    public  function onImportNcm($param = null) 
    {
        try 
        {
             $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Ncm;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            $toremove = array(' ',' ','.',',');
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                 = new Ncm();//---------------
                    $object->id             = $value[0];//---------------
                    $object->n_ncm          = str_replace($toremove,'',$value[1]);
                    $object->cest           = $value[2];//---------------
                    $object->id_woo_ncm     = isset($value[3])?$value[3]:null;

                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportNcm($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Ncm;
            $objects                = Ncm::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
            $this->form->setData($this->form->getData());
        }
    }

    public  function onImportMedida($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Unidade;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                 = new Unidade();//---------------
                    $object->id             = $value[0];//---------------
                    $object->nome           = strtoupper($value[1]);//---------------
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportGupoLojas($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->GrupoLojas;
            $objects                = GrupoLojas::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function importImportUnidadeSistema($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->SystemUnit;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open('permission');
                    $object                     = new SystemUnit();//---------------
                    $object->id                 = isset($value[0])?$value[0]:null;
                    $object->name               = isset($value[1])?$value[1]:null;
                    $object->connection_name    = isset($value[2])?$value[2]:null;
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportUnidSistema($param = null) 
    {
        try 
        {
 TTransaction::open('permission');
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->SystemUnit;
            $objects                = SystemUnit::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());  
            $this->form->setData($this->form->getData());
        }
    }

    public  function onImportGrupoLojas($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->GrupoLojas;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new GrupoLojas();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->nome               = $value[1];//---------------
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportGrupoLojas($param = null) 
    {
        try 
        {
             TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->GrupoLojas;
            $objects                = GrupoLojas::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());  
        }
    }

    public  function onImportLoja($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Loja;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new Loja();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->razao_social       = $value[1];
                    $object->abreviacao         = $value[2];
                    $object->nome_fantasia      = $value[3];
                    $object->cnpj               = $value[4];
                    $object->grupo              = $value[5];
                    $object->observacao         = $value[6];
                    $object->fone               = $value[7];
                    $object->email              = $value[8];
                    $object->cidade             = $value[9];
                    $object->cep                = $value[10];
                    $object->rua                = $value[11];
                    $object->numero             = $value[12];
                    $object->bairro             = $value[13];
                    $object->complemento        = $value[14];
                    $object->dt_ativacao        = $value[15];
                    $object->inscr_estadual     = $value[16];
                    $object->icms               = $value[17];
                    $object->inscr_municipal    = $value[18];
                    $object->regime_tribut      = $value[19];
                    $object->deposito           = $value[20];
                    $object->tipo_emissao       = $value[21];
                    $object->lat                = $value[22];
                    $object->lon                = $value[23];
                    $object->unidade            = $value[24];
                    $object->idEmpresa          = isset($value[25])?$value[25]:null;
                    $object->csc_producao       = isset($value[26])?$value[26]:null;
                    $object->id_csc_producao    = isset($value[27])?$value[27]:null;
                    $object->serie_nf_producao  = isset($value[28])?$value[28]:null;
                    $object->seq_nf_producao    = isset($value[29])?$value[29]:null;
                    $object->csc_homologacao    = isset($value[30])?$value[30]:null;
                    $object->id_csc_homologacao = isset($value[31])?$value[31]:null;
                    $object->serie_nf_homologacao= isset($value[32])?$value[32]:null;
                    $object->seq_nf_homologacao = isset($value[33])?$value[33]:null;
                    $object->senha_certificado  = isset($value[34])?$value[34]:null;
                    $object->funcionario        = isset($value[35])?$value[35]:null;
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportLojas($param = null) 
    {
        try 
        {
             TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Loja;
            $objects                = Loja::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());  
        }
    }

    public  function onimportDeposito($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Deposito;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new Deposito();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->nome_deposito      = isset($value[1])?$value[1]:null;
                    $object->nomeLoja           = isset($value[2])?$value[2]:null;
                    $object->prod_estoque       = isset($value[3])?$value[3]:null;
                    $object->store();

                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportDeposito($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Deposito;
            $objects                = Deposito::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
            $this->form->setData($this->form->getData());
        }
    }

    public  function onImportUsuario($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->SystemUsers;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open('permission');
                    $object                     = new SystemUsers();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->name               = $value[1];
                    $object->login              = $value[2];
                    $object->password           = $value[3];
                    $object->email              = $value[4];
                    $object->frontpage          = $value[5];
                    $object->system_unit_id     = $value[6];
                    $object->active             = $value[7];
                    $object->store();

                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportUsuario($param = null) 
    {
        try 
        {
            TTransaction::open('permission');
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->SystemUsers;
            $objects                = SystemUsers::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());  
            $this->form->setData($this->form->getData());
        }
    }

    public  function onImportGrupoUsuario($param = null) 
    {
        try 
        {
           $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->SystemUserGroup;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open('permission');
                    $object                     = new SystemUserGroup();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->system_user_id     = $value[1];
                    $object->system_group_id    = $value[2];
                    $object->store();

                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportGrupoUsuario($param = null) 
    {
        try 
        {
             TTransaction::open('permission');
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->SystemUserGroup;
            $objects                = SystemUserGroup::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());   
            $this->form->setData($this->form->getData());
        }
    }

    public  function onImportUnidadeUsuario($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->SystemUserUnit;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open('permission');
                    $object                     = new SystemUserUnit();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->system_user_id     = $value[1];
                    $object->system_unit_id     = $value[2];
                    $object->store();

                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportUnidadeUsuario($param = null) 
    {
        try 
        {
             TTransaction::open('permission');
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->SystemUserUnit;
            $objects                = SystemUserUnit::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage()); 
            $this->form->setData($this->form->getData());
        }
    }

    public  function onImportChatApi($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Chatapi;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new Chatapi();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->bot_nome           = isset($value[1])?$value[1]:null;
                    $object->bot_token          = isset($value[2])?$value[2]:null;
                    $object->chat_id            = isset($value[3])?$value[3]:null;
                    $object->grupo_nome         = isset($value[4])?$value[4]:null;
                    $object->grupo_id           = isset($value[5])?$value[5]:null;
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportChatApi($param = null) 
    {
        try 
        {
             TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Chatapi;
            $objects                = Chatapi::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
            $this->form->setData($this->form->getData());
        }
    }

    public  function onImportConfEtiqueta($param = null) 
    {
        try 
        {
             $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->ConfEtiquProduto;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new ConfEtiquProduto();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->leftMargin         = $value[1];
                    $object->topMargin          = $value[2];
                    $object->labelWidth         = $value[3];
                    $object->labelHeight        = $value[4];
                    $object->spaceBetween       = $value[5];
                    $object->rowsPerPage        = $value[6];
                    $object->colsPerPage        = $value[7];
                    $object->fontSize           = $value[8];
                    $object->barcodeHeight      = $value[9];
                    $object->imageMargin        = $value[10];
                    $object->barcodeMethod      = $value[11];
                    $object->nome               = isset($value[13])?$value[13]:null;
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportConfEtiquetas($param = null) 
    {
        try 
        {
             TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->ConfEtiquProduto;
            $objects                = ConfEtiquProduto::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());  
            $this->form->setData($this->form->getData());
        }
    }

    public  function onImportProduto($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Produto;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                         = new Produto();//---------------
                    $object->id                     = $value[0];//---------------

                    $object->unidade_id             = $value[1];
                    $object->categoria_produto_id   = $value[2];
                    $object->fornecedor_id          = $value[3];
                    $object->descricao              = $value[4];
                    $object->desc_variacao          = $value[5];
                    $object->dt_cadastro            = $value[6];
                    $object->SKU                    = $value[7];
                    $object->cod_barras             = $value[8];
                    $object->obs                    = $value[9];
                    $object->id_externo             = $value[10];
                    $object->estoque                = $value[11];
                    $object->tabela_preco           = $value[12];
                    $object->preco                  = isset($value[13])?$value[13]:null;
                    $object->qtd_max                = $value[14];
                    $object->marca                  = $value[15];
                    $object->situacao_prod          = $value[16];
                    $object->referencia             = $value[17];
                    $object->tipo_cadastro          = $value[18];
                    $object->deposito               = $value[19];
                    $object->id_familia             = $value[20];
                    $object->mestre_variavel        = $value[21]=="mestre"?1:2;
                    $object->qtd_min                = $value[22];
                    $object->valor_custo            = $value[23];
                    $object->valor_venda            = $value[24];
                    $object->sit_tribut             = $value[25];
                    $object->qtd                    = $value[26];
                    $object->ncm                    = $value[27];
                    $object->cest                   = $value[28];
                    $object->id_externo_promocao    = $value[29];
                    $object->origem                 = $value[30];
                    if(isset($value[31]))
                        $object->link_site          = $value[31];
                    if(isset($value[32]))
                        $object->vazio1             = $value[32];
                    if(isset($value[33]))
                        $object->vazio2             = $value[33];
                    if(isset($value[34]))
                        $object->vazio3             = $value[34];

                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportProduto($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Produto;
            $objects                = Produto::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImportTabelaPreco($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->TabelaPreco;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new TabelaPreco();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->id_preco           = $value[1];//---------------
                    $object->nome_tabela_preco  = strtoupper($value[2]);//---------------
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportTabelaPreco($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->TabelaPreco;
            $objects                = TabelaPreco::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage()); 
            $this->form->setData($this->form->getData());
        }
    }

    public  function onImportPreco($param = null) 
    {
        try 
        {
           $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Preco;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new Preco();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->preco_venda        = $value[1];//---------------
                    $object->preco_custo        = $value[2];//---------------
                    $object->id_produto         = $value[3];//---------------
                    $object->id_tabela          = $value[4];//---------------
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportPrecos($param = null) 
    {
        try 
        {
             TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Preco;
            $objects                = Preco::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());   
        }
    }

    public  function onImportEstoque($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->ProdEstoque;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                         = new ProdEstoque();//---------------
                    $object->id                     = $value[0];//---------------
                    $object->quantidade             = $value[1];
                    $object->qtd_min                = $value[2];
                    $object->qtd_max                = $value[3];
                    $object->id_deposito            = $value[4];
                    $object->id_produto             = $value[5];
                    $object->produto_marca          = $value[6];
                    $object->produto_referencia     = $value[7];
                    $object->produto_sku            = $value[8];
                    $object->produto_nome           = $value[9];
                    $object->produto_nome_variacao  = $value[10];
                    $object->produto_fornecedor     = $value[11];
                    $object->produto_categoria      = $value[12];
                    $object->produto_cod_barras     = $value[13];
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportEstoque($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->ProdEstoque;
            $objects                = ProdEstoque::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImportTransferencias($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->TransferenciaEtq;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new TransferenciaEtq();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->deposito_rec       = $value[1];
                    $object->deposito_env       = $value[2];
                    $object->estoque_id         = $value[3];
                    $object->quantidade         = $value[4];
                    $object->dt_registro        = $value[5];
                    $object->usuario            = $value[6];
                    $object->id_transferencia   = $value[7];
                    if(isset($value[8])&&$value[8] != null){
                        $object->id_produto     = $value[8];
                    }else{
                        $estoque                = new ProdEstoque($object->estoque_id);
                        $object->id_produto     = $estoque->id_produto;
                    }

                    $object->saldo_anterior     = isset($value[9])?$value[9]:null;
                    $object->saldo_posterior    = isset($value[10])?$value[10]:null;
                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportTransferencia($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->TransferenciaEtq;
            $objects                = TransferenciaEtq::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImportVenda($param = null) 
    {
        try 
        {
            $Tfile              = 'Venda';
            $fileName           = json_decode(urldecode($param[$Tfile]))->fileName;
            $handle             = fopen($fileName, "r");
            $count              = 0;
            $separador          = ';';
            $limite_da_linha    = 0;
            $to_remove          = array("\n","   ","<br>","\r","/\s/");

            ini_set('max_execution_time', 0);
            while (($value = fgetcsv($handle, $limite_da_linha, $separador)) !== FALSE)
            {
                TTransaction::open('vendas_base');
                    $object                     = new Venda();//---------------
                    $object->id                 = $value[0];
                    $object->n_venda            = $value[1];
                    $object->id_interno         = $value[2];
                    $object->cliente_id         = $value[3];
                    $object->status             = $value[4];
                    $object->vendedor_id        = $value[5];
                    $object->estado_venda_id    = $value[6];
                    $object->system_unit_id     = $value[7];
                    $object->dt_venda           = $value[8];
                    $object->obs                = utf8_encode(str_replace($to_remove,"",$value[9]));
                    $object->valor_total        = $value[10];
                    $object->total_desconto     = $value[11];
                    $object->loja               = $value[12];
                    $object->id_venda           = $value[13];
                    $object->variavel_duplicidade= utf8_encode($value[14]);
                    $object->forma_pagamento    = utf8_encode($value[15]);
                    $object->caixa              = $value[16];
                    $object->func_caixa         = utf8_encode($value[17]);
                    $object->fiscal             = $value[18];
                    $object->total_produtos     = $value[19];
                    $object->total_pagamentos   = $value[20];
                    /*echo'<pre>';
                    var_dump($object);
                    echo'</pre>';*/
                    $object->store();
                    $count++;
                TTransaction::close();
            }

            fclose($handle);
            $this->form->setData($this->form->getData());
            new TMessage('info', "{$count} {$Tfile} foram importados!");

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportVendas($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $objects                = Venda::getObjects();
            $class                  = "Vendas";
            self::exportarCsv($objects,$class);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImportItensVenda($param = null) 
    {
        try 
        {
            $Tfile              = 'VendaItem';
            $fileName           = json_decode(urldecode($param[$Tfile]))->fileName;
            $handle             = fopen($fileName, "r");
            $count              = 0;
            $separador          = ';';
            $limite_da_linha    = 0;
            ini_set('max_execution_time', 0);
            while (($value = fgetcsv($handle, $limite_da_linha, $separador)) !== FALSE)
            {
                TTransaction::open('vendas_base');
                    $object                     = new VendaItem();//---------------
                    $object->id                 = $value [0];
                    $object->produto_id         = $value [1];
                    $object->deposito           = $value [2];
                    $object->name               = utf8_encode($value [3]);
                    $object->venda_id           = $value [4];
                    $object->quantidade         = $value [5];
                    $object->valor_unitario     = $value [6];
                    $object->valor_desconto     = $value [7];
                    $object->valor_total        = $value [8];
                    $object->SKU                = $value [9];
                    $object->loja_id            = $value [10];
                    $object->dt_venda           = $value [11];
                    $object->cest               = $value [12];
                    $object->ncm                = $value [13];
                    $object->cfop               = $value [14];
                    $object->percentual         = $value [15];
                    $object->unidadeMedida      = $value [16];
                    $object->situacaoTributaria = $value [17];
                    $object->origem             = $value [18];
                    $object->categoria_produto  = $value [19];
                    $object->fornecedor         = $value [20];
                    $object->referencia         = utf8_encode($value [21]);
                    $object->marca              = $value [22];
                    $object->store();
                    $count++;
                TTransaction::close();
            }
            echo "$erros";
            fclose($handle);
            $this->form->setData($this->form->getData());
            new TMessage('info', "{$count} {$Tfile} foram importados!");

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', "$object->id - $object->id_venda - ".$e->getMessage());    
        }
    }

    public  function onExportItemVenda($param = null) 
    {
        try 
        {
           TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $objects                = VendaItem::getObjects();  
            $class                  = "VendaItem";
            self::exportarCsv($objects,$class);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function importPagamentosVenda($param = null) 
    {
        try 
        {
            $Tfile              = 'PagamentosVenda';
            $fileName           = json_decode(urldecode($param[$Tfile]))->fileName;
            $handle             = fopen($fileName, "r");
            $count              = 0;
            $separador          = ';';
            $limite_da_linha    = 0;
            ini_set('max_execution_time', 0);
            $limit = 10000;
            while (($value = fgetcsv($handle, $limite_da_linha, $separador)) !== FALSE)
            {
                TTransaction::open('vendas_base');
                    $object                     = new VendaPagamento();//---------------
                    $object->id                 = $value[0];

                    $object->metodo_pgto        = utf8_encode($value[1]);
                    $valor                      = str_replace(',','.',$value[2]);
                    if($valor < $limit || $valor > ($limit*-1)){
                        $object->valor_pgto     = $valor;
                    }else{
                        continue;
                    }
                    $object->venda_id           = $value[3];
                    $object->dt_venda           = $value[4];
                    $object->id_loja            = $value[5];

                    $object->store();
                    $count++;
                TTransaction::close();
            }
            fclose($handle);
            $this->form->setData($this->form->getData());
            new TMessage('info', "{$count} {$Tfile} foram importados!");

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportPagamentoVenda($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $objects                = Pagamentos::getObjects();  
            $class                  = "VendaPagamentos";
            self::exportarCsv($objects,$class);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function importNfce($param = null) 
    {
        try 
        {
            $Tfile              = 'NfceRequest';
            $fileName           = json_decode(urldecode($param[$Tfile]))->fileName;
            $handle             = fopen($fileName, "r");
            $count              = 0;
            $separador          = ';';
            $limite_da_linha    = 0;
            ini_set('max_execution_time', 0);
            $limit = 10000;
            while (($value = fgetcsv($handle, $limite_da_linha, $separador)) !== FALSE)
            {
                TTransaction::open('vendas_base');
                    $object                     = new Nfce();//---------------
                    $object->id                 = $value[0];

                    $object->ambienteEmissao    = $value[1];
                    $object->informacoesAdicionais = utf8_encode($value[2]);
                    $object->presencaConsumidor = $value[3];
                    $object->numVenda           = $value[4];
                    $object->status             = $value[5];
                    $object->n_nfce             = $value[6];
                    $object->link_cupom         = $value[7];
                    $object->id_loja            = $value[8];
                    $object->retorno_nfce       = $value[9];
                    $object->venda_id           = $value[10];
                    $object->dt_nfce            = $value[11];

                    $object->store();
                    $count++;
                TTransaction::close();
            }
            echo "$erros";
            fclose($handle);
            $this->form->setData($this->form->getData());
            new TMessage('info', "{$count} {$Tfile} foram importados!");

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportNfce($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $objects                = NfceRequest::getObjects();  
            $class                  = "Nfce";
            self::exportarCsv($objects,$class);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function importItensNfce($param = null) 
    {
        try 
        {
            //code here

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportItemNfce($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $objects                = Itens::getObjects();  
            $class                  = "ItensNFCE";
            self::exportarCsv($objects,$class);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function importPagamentosNfce($param = null) 
    {
        try 
        {
            //code here

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportPagamentoNfce($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $objects                = FormasPgto::getObjects();  
            $class                  = "PagamentosNFCE";
            self::exportarCsv($objects,$class);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function importRetornoNfce($param = null) 
    {
        try 
        {
           $Tfile              = 'RetornoNfce';
            $fileName           = json_decode(urldecode($param[$Tfile]))->fileName;
            $handle             = fopen($fileName, "r");
            $count              = 0;
            $separador          = ';';
            $limite_da_linha    = 0;
            ini_set('max_execution_time', 0);
            $limit = 10000;
            while (($value = fgetcsv($handle, $limite_da_linha, $separador)) !== FALSE)
            {
                TTransaction::open('webhook');
                    $object                     = new NfceRetorno();//---------------
                    $object->id                 = $value[0];

                    $object->id_externo         = $value[1];
                    $object->tipo               = $value[2];
                    $object->status             = $value[3];
                    $object->motivoStatus       = $value[4];
                    $object->ambienteEmissao    = $value[5];
                    $object->enviadaPorEmail    = $value[6];
                    $object->dataCriacao        = $value[7];
                    $object->dataUltimaAlteracao= $value[8];
                    $object->forcarEmissaoContingencia = $value[9];
                    $object->numero             = $value[10];
                    $object->serie              = $value[11];
                    $object->dataEmissao        = $value[12];
                    $object->chaveAcesso        = $value[13];
                    $object->dataAutorizacao    = $value[14];
                    $object->linkDanfe          = $value[15];
                    $object->linkDownloadXml    = $value[16];
                    $object->lnkConsultaPorChaveAcesso = $value[17];
                    $object->emitidaEmContingencia = $value[18];
                    $object->empresaId          = $value[19];
                    $object->numeroProtocolo    = $value[20];
                    $object->digestValue        = $value[21];
                    $object->valorTotal         = $value[22];
                    $object->informacoesAdicionais = $value[23];
                    $object->qrCode             = $value[24];

                    $object->store();
                    $count++;
                TTransaction::close();
            }
            echo "$erros";
            fclose($handle);
            $this->form->setData($this->form->getData());
            new TMessage('info', "{$count} {$Tfile} foram importados!");

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportRetorno($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->RetornoNfce;
            $objects                = RetornoNfce::getObjects();  
            $class                  = "RetornoNFCE";
            self::exportarCsv($objects,$class);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImportBanco($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Banco;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new Banco();//---------------

                    $object->cod                = $value[0];//--------------
                    $object->nome               = $value[1];

                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImportContaBancaria($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->ContaBancaria;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new ContaBancaria();//---------------
                    $object->id                 = $value[0];//---------------

                    $object->loja               = $value[1];
                    $object->id_referencia_tipo = $value[6];
                    $object->nome               = $value[7];    
                    $object->agencia            = $value[8];
                    $object->numero_conta       = $value[9];
                    $object->banco              = $value[10];

                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportContaBancaria($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Conta;
            $objects                = ContaBancaria::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImportNatureza($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Natureza;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new Natureza();//---------------
                    $object->id                 = $value[0];//---------------
                    $object->nome               = $value[1];//---------------

                    $object->store();
                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportNatureza($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Natureza;
            $objects                = Natureza::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImportConta($param = null) 
    {
        try 
        {
            $data =  $this->form->getData();
            $cliente = ApiManager::getGoogleClient();
            ini_set('max_execution_time', 0);
            //enviando o cliente para a classe do google
            $service = new Google_Service_Sheets($cliente);
            $spreadsheetId = $data->Conta;//---------------
            $range = "Página1";
            $response = $service->spreadsheets_values->get($spreadsheetId,$range);
            $values = $response->getValues();
            $indice = 1;
            if(empty($values)){
                throw new exception('dados não encontrados');
            }else{
                foreach($values as $value){//o foreach pula por linha e ->[N] as colunas
                    TTransaction::open(self::$database);
                    $object                     = new Conta();//---------------
                    $object->id                 = $value[0];//---------------

                    $object->tipo_conta_id      = 2;
                    $object->loja               = $value[4];///system_unit_id
                    $object->natureza_id        = $value[3];
                    $object->fornecedor         = isset($value[10])&&$value[10]!=null&&$value[10]!=''?intval($value[10]):null;//substr(".0","",$value[10]);
                    $object->dt_emissao         = $value[5];
                    $object->dt_vencimento      = $value[6];
                    $object->valor              = $value[7];
                    $object->quitada            = $value[12];
                    $object->desconto           = '';
                    $object->juros              = '';
                    $object->multa              = '';
                    $object->obs                = $value[11];
                    $object->parcelas           = 1;
                    //inicio salvar parcelas
                    $object->store();
                    $parcela                    = new ParcelasConta();
                    $parcela->conta_origem     = $object->id;
                    $parcela->loja_id          = $object->loja;
                    $parcela->fornecedor_id    = $object->fornecedor;
                    $parcela->forma_pagamento  = isset($value[9])&&$value[9]!=null&&$value[9]!=""?$value[9]:'boleto';
                    //pesquisa conta bancária
                    if(isset($value[8]) && $value[8]!= null && $value[8]!= ""){
                        $conta_bancarias = ContaBancaria::where('nome','=',$value[8])->load();
                        if($conta_bancarias){
                            $conta_bancaria             = $conta_bancarias[0];
                            $parcela->conta_bancaria   = $conta_bancaria->id;
                        }
                    }
                    $parcela->vencimento       = $value[6];
                    $parcela->quitada          = $object->quitada;
                    $parcela->id_parcela_mestre= '';
                    $parcela->tipo_parcela     = "Parcela";
                    $parcela->link_comprovante = '';
                    $parcela->obs              = $object->obs;
                    $parcela->valor            = $object->valor;
                    $parcela->vazio1           = '';
                    $parcela->vazio2           = '';
                    $parcela->vazio3           = '';

                    $parcela->store();

                    TTransaction::close();
                }
            }
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onExportConta($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $link                   = $data->Conta;
            $objects                = Conta::getObjects();  
            self::enviarGsheet($objects,$link);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction  
            $data = $this->form->getData(); // get form data as array
            $messageAction = null;
            $campos  = array ('Estado','Cidade','Fornecedor','Marca','CategoriaProduto','SituacaoProd','TipoCadastroProd',
                              'Cest','Ncm','Unidade','TabelaPreco','Preco','SystemUnit','GrupoLojas','Loja','Deposito',
                              'SystemUsers','SystemUserGroup','SystemUserUnit','Chatapi','ConfEtiquProduto','Produto',
                              'ProdEstoque','TransferenciaEtq','Venda','VendaItem','PagamentosVenda','NfceRequest','ItensNfce',
                              'PagamentoNfce','RetornoNfce','Natureza','Conta','Parcelas');   
            $this->form->validate(); 
/*
            $object = new Import(); // create an empty object 
*/
            //APAGAR CASO FAZER DEPLOY PARA PRODUÇÃO
            foreach($data as $key => $campo){
                $object;
                $objects = Import::where('nome_tabela','=',$key)->load();
                if($objects){
                    $object = $objects[0];
                }else{
                    $object = new Import();
                }
                $object->nome_tabela = $key;
                $object->link_sheet = $campo;  
                $object->store();
            }

/*

            $Venda_dir = '/tmp';
            $VendaItem_dir = '/tmp';
            $PagamentosVenda_dir = '/tmp';
            $NfceRequest_dir = '/tmp';
            $ItensNfce_dir = '/tmp';
            $PagamentoNfce_dir = '/tmp';
            $RetornoNfce_dir = '/tmp';  

            $object->store(); // save the object 

            $this->saveFile($object, $data, 'Venda', $Venda_dir);
            $this->saveFile($object, $data, 'VendaItem', $VendaItem_dir);
            $this->saveFile($object, $data, 'PagamentosVenda', $PagamentosVenda_dir);
            $this->saveFile($object, $data, 'NfceRequest', $NfceRequest_dir);
            $this->saveFile($object, $data, 'ItensNfce', $ItensNfce_dir);
            $this->saveFile($object, $data, 'PagamentoNfce', $PagamentoNfce_dir);
            $this->saveFile($object, $data, 'RetornoNfce', $RetornoNfce_dir); 

            $data->id = $object->id; 
*/
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', "Registro salvo", $messageAction); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = 1;  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction
/*
                $object = new Import($key); // instantiates the Active Record 
*/

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

                TTransaction::open(self::$database); 
                $data = $this->form->getData();
                $object =new stdClass();
                foreach($data as $key=>$value){
                    $imports = Import::where('nome_tabela','=',$key)->load();
                    if($imports){
                        $import         = $imports[0];
                        $object->$key   = $import->link_sheet;
                    }
                }
                TForm::sendData(self::$formName, $object);
                $this->form->setData($object);
                TTransaction::close();

    } 

    public function enviarGsheet($objects,$link){
        try{
            TTransaction::open(self::$database);
            $data = $this->form->getData();
            //alterar aqui as variaveis de acordo com a classe
            $spreadsheetId          = $link;
            $objectsJson            = array();
            $indice_letra           = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                                       'AA','AB','AC','AC','AD','AE','AF','AG','AH','AI','Aj','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT',
                                       'AU','AY','AW','AX','AY','AZ'];
            $indice                 = 0;
            foreach($objects as $object){
                $arrayTemp  = [];  
                $indice     = 0;  
                foreach($object as $atributo){
                    echo "$tipo \n";
                    $arrayTemp[] = strval($atributo);
                    $indice ++;
                }

                $objectsJson[] =$arrayTemp;
            }

            $cliente                = ApiManager::getGoogleClient();
            $limite                 = $indice_letra[$indice];
            $range                  = "Página1!A:$limite";
            $service                = new Google_Service_Sheets($cliente);

            //limpa o Sheets
            $requestBody = new Google_Service_Sheets_ClearValuesRequest();
            $response = $service->spreadsheets_values->clear($spreadsheetId, $range, $requestBody);  
            //salva o array no sheets
            $body = new Google_Service_Sheets_ValueRange(['values' => $objectsJson]);
            $params = ['valueInputOption' => 'RAW'];
            $insert = ["insertDataOption"=>"INSERT_ROWS"];
            $result = $service -> spreadsheets_values->append(
                $spreadsheetId,
                $range,
                $body,
                $params,
                $insert
                );
            $this->form->setData($data);
            TTransaction::close();
        }catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
            $this->form->setData($this->form->getData());
        }
    }

    public function exportarCsv($objects,$class){
        try{
            set_time_limit(0);
            $class      = isset($class)?$class:"";
            $file       = 'tmp/'.$class.uniqid().'.csv';
            $to_remove  = array("\n","   ","<br>","\r","/\s/");
            $handle = fopen($file, 'w');
            foreach($objects as $record){
                $csvColumns =[];
                foreach($record as $atributo){
                    $csvColumns[] = str_replace($to_remove,"",$atributo);
                }
                fputcsv($handle, $csvColumns, ';');
            }
             fclose($handle);
             TPage::openFile($file);
             $this->form->setData($this->form->getData());
        }catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
            $this->form->setData($this->form->getData());
        }
    }

}

