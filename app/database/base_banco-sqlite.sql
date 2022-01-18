PRAGMA foreign_keys=OFF; 

CREATE TABLE advertencia( 
      id  INTEGER    NOT NULL  , 
      motivo varchar  (200)   NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      dt_advertencia date   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(colaborador_id) REFERENCES colaborador(id)) ; 

CREATE TABLE aso( 
      id  INTEGER    NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      dt_realizado date   , 
      tipo_aso varchar  (30)   NOT NULL  , 
      status varchar  (30)   NOT NULL    DEFAULT 'a realizar', 
      vencimento date   , 
      link_scan_aso varchar  (200)   , 
 PRIMARY KEY (id),
FOREIGN KEY(colaborador_id) REFERENCES colaborador(id)) ; 

CREATE TABLE atestado( 
      id  INTEGER    NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      dt_atestado date   NOT NULL  , 
      dt_retorno date   , 
      dias int   NOT NULL  , 
      motivo varchar  (150)   NOT NULL  , 
      link_scan_atestado varchar  (200)   , 
 PRIMARY KEY (id),
FOREIGN KEY(colaborador_id) REFERENCES colaborador(id)) ; 

CREATE TABLE banco( 
      id  INTEGER    NOT NULL  , 
      cod int   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cargo( 
      id  INTEGER    NOT NULL  , 
      cargo varchar  (50)   NOT NULL  , 
      salario int   , 
      descricao varchar  (100)   , 
      escala int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(salario) REFERENCES salario(id),
FOREIGN KEY(escala) REFERENCES escala(id)) ; 

CREATE TABLE categoria_produto( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
      id_externo int   , 
      ncm_padrao int   NOT NULL  , 
      iconeCategoria varchar  (400)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Cest( 
      id  INTEGER    NOT NULL  , 
      n_cest varchar  (20)   NOT NULL  , 
      descricao varchar  (1000)   NOT NULL  , 
      id_woo_cst int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ChatAPi( 
      id  INTEGER    NOT NULL  , 
      bot_nome varchar  (30)   , 
      bot_token varchar  (50)   NOT NULL  , 
      chat_id varchar  (50)   NOT NULL  , 
      loja int   , 
      grupo_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
      estado_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(estado_id) REFERENCES estado(id)) ; 

CREATE TABLE colaborador( 
      id  INTEGER    NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      rg varchar  (30)   , 
      ctps varchar  (40)   , 
      cnh varchar  (30)   , 
      dt_registro date   NOT NULL  , 
      dt_desligamento date   , 
      status_colaborador varchar  (20)   NOT NULL    DEFAULT 'ativo', 
      dt_nascimento date   NOT NULL  , 
      contrato1 date   , 
      contrato2 date   , 
      salario_familia text   NOT NULL    DEFAULT 'F', 
      salario_familia_qtd int     DEFAULT 0, 
      bonificacao text     DEFAULT 'T', 
      status_ferias varchar  (30)   NOT NULL    DEFAULT 'inapto', 
      loja_registro int   NOT NULL  , 
      loja_atual int   NOT NULL  , 
      cargo int   NOT NULL  , 
      salario int   NOT NULL  , 
      carga_horaria int   NOT NULL  , 
      escala int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(loja_registro) REFERENCES loja(id),
FOREIGN KEY(cargo) REFERENCES cargo(id),
FOREIGN KEY(salario) REFERENCES salario(id),
FOREIGN KEY(carga_horaria) REFERENCES escala(id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(escala) REFERENCES escala(id)) ; 

CREATE TABLE compra_funcionario( 
      id  INTEGER    NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      venda_id int   NOT NULL  , 
      dt_venda date   NOT NULL  , 
      valor_venda double   NOT NULL  , 
      loja_venda int   NOT NULL  , 
      quitado text   NOT NULL    DEFAULT 'F', 
 PRIMARY KEY (id),
FOREIGN KEY(colaborador_id) REFERENCES colaborador(id)) ; 

CREATE TABLE conf_etiqu_produto( 
      id  INTEGER    NOT NULL  , 
      leftMargin int   NOT NULL  , 
      topMargin int   NOT NULL  , 
      labelWidth int   NOT NULL  , 
      labelHeight int   NOT NULL  , 
      spaceBetween int   NOT NULL  , 
      rowsPerPage int   NOT NULL  , 
      colsPerPage int   NOT NULL  , 
      fontSize int   NOT NULL  , 
      barcodeHeight int   NOT NULL  , 
      imageMargin int   NOT NULL  , 
      barcodeMethod varchar  (20)   NOT NULL  , 
      nome varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conta( 
      id  INTEGER    NOT NULL  , 
      tipo_conta_id int   NOT NULL  , 
      loja int   NOT NULL  , 
      natureza_id int   NOT NULL  , 
      fornecedor int   , 
      forma_pagamento varchar  (50)   , 
      dt_emissao date   NOT NULL  , 
      dt_vencimento date   NOT NULL  , 
      valor double   NOT NULL  , 
      desconto double   , 
      juros double   , 
      multa double   , 
      obs varchar  (200)   , 
      quitada char  (1)   NOT NULL    DEFAULT 'f', 
      parcelas int   , 
 PRIMARY KEY (id),
FOREIGN KEY(natureza_id) REFERENCES natureza(id),
FOREIGN KEY(tipo_conta_id) REFERENCES tipo_conta(id),
FOREIGN KEY(loja) REFERENCES loja(id)) ; 

CREATE TABLE conta_bancaria( 
      id  INTEGER    NOT NULL  , 
      loja int   , 
      fornecedor int   , 
      colaborador int   , 
      cliente int   , 
      parceiro int   , 
      id_referencia_tipo int   NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
      agencia int   , 
      numero_conta int   , 
      banco int  (50)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(id_referencia_tipo) REFERENCES tipo_conta_bancaria(id),
FOREIGN KEY(loja) REFERENCES loja(id),
FOREIGN KEY(fornecedor) REFERENCES fornecedor(id),
FOREIGN KEY(colaborador) REFERENCES colaborador(id),
FOREIGN KEY(banco) REFERENCES banco(id)) ; 

CREATE TABLE contato( 
      id  INTEGER    NOT NULL  , 
      pessoa_id int   , 
      email varchar  (30)   , 
      nome varchar  (30)   , 
      telefone varchar  (20)   , 
      obs varchar  (50)   , 
      fornecedor_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(fornecedor_id) REFERENCES fornecedor(id)) ; 

CREATE TABLE deposito( 
      id  INTEGER    NOT NULL  , 
      nome_deposito varchar  (50)   NOT NULL  , 
      loja int   , 
      prod_estoque int   , 
 PRIMARY KEY (id),
FOREIGN KEY(loja) REFERENCES loja(id)) ; 

CREATE TABLE documento( 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (50)   NOT NULL  , 
      dt_registro date   NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      tipo_documento int   NOT NULL    DEFAULT 1, 
      link_scan_documento varchar  (200)   , 
 PRIMARY KEY (id),
FOREIGN KEY(colaborador_id) REFERENCES colaborador(id),
FOREIGN KEY(tipo_documento) REFERENCES tipo_documento(id)) ; 

CREATE TABLE escala( 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (50)   , 
      carga_horaria_diaria text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (30)   , 
      uf varchar  (2)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_negociacao( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (30)   NOT NULL  , 
      cor varchar  (50)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_orcamento( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_venda( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ferias( 
      id  INTEGER    NOT NULL  , 
      dt_inicio date   , 
      dt_fim date   , 
      periodo int   NOT NULL    DEFAULT 30, 
      status_ferias varchar  (30)   NOT NULL    DEFAULT 'inapto', 
      colaborador_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(colaborador_id) REFERENCES colaborador(id)) ; 

CREATE TABLE fornecedor( 
      id  INTEGER    NOT NULL  , 
      razao_social varchar  (100)   NOT NULL  , 
      nome_fantasia varchar  (50)   , 
      cnpj varchar  (20)   NOT NULL  , 
      observacao varchar  (200)   , 
      fone varchar  (20)   , 
      email varchar  (30)   , 
      cidade int   NOT NULL  , 
      rua varchar  (100)   , 
      numero varchar  (30)   , 
      bairro varchar  (100)   , 
      complemento varchar  (100)   , 
      dt_ativacao date   NOT NULL  , 
      inscr_estadual varchar  (30)   , 
      possui_ie int   NOT NULL  , 
      icms varchar  (20)   NOT NULL  , 
      inscr_municipal varchar  (30)   , 
      regime_tributario varchar  (100)   NOT NULL  , 
      contato int   , 
      marca int   , 
      vazio1 varchar  (200)   , 
      vazio2 varchar  (200)   , 
      vazio3 varchar  (200)   , 
 PRIMARY KEY (id),
FOREIGN KEY(cidade) REFERENCES cidade(id)) ; 

CREATE TABLE grupo( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo_lojas( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_negociacao( 
      id  INTEGER    NOT NULL  , 
      negociacao_id int   NOT NULL  , 
      tipo_contato_id int   NOT NULL  , 
      dt_contato datetime   NOT NULL  , 
      descricao varchar  (200)   , 
 PRIMARY KEY (id),
FOREIGN KEY(tipo_contato_id) REFERENCES tipo_contato(id),
FOREIGN KEY(negociacao_id) REFERENCES negociacao(id)) ; 

CREATE TABLE Import( 
      id  INTEGER    NOT NULL  , 
      link_sheet varchar  (200)   , 
      nome_tabela varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE loja( 
      id  INTEGER    NOT NULL  , 
      razao_social varchar  (50)   NOT NULL  , 
      abreviacao varchar  (5)   , 
      nome_fantasia varchar  (100)   , 
      cnpj varchar  (20)   NOT NULL  , 
      grupo int   NOT NULL  , 
      observacao varchar  (200)   , 
      fone varchar  (30)   , 
      email varchar  (100)   , 
      cidade int   NOT NULL  , 
      cep varchar  (50)   , 
      rua varchar  (100)   , 
      numero varchar  (30)   , 
      bairro varchar  (50)   , 
      complemento varchar  (100)   , 
      dt_ativacao date   , 
      inscr_estadual varchar  (30)   , 
      icms varchar  (30)   , 
      inscr_municipal varchar  (30)   , 
      regime_tribut varchar  (5)   , 
      deposito int   , 
      tipo_emissao int   , 
      lat double   , 
      lon double   , 
      unidade int   , 
      idEmpresa varchar  (50)   , 
      csc_producao varchar  (50)   , 
      id_csc_producao varchar  (50)   , 
      serie_nf_producao int   , 
      seq_nf_producao int   , 
      csc_homologacao varchar  (50)   , 
      id_csc_homologacao varchar  (50)   , 
      serie_nf_homologacao int   , 
      seq_nf_homologacao int   , 
      senha_certificado varchar  (50)   , 
      funcionario int   , 
 PRIMARY KEY (id),
FOREIGN KEY(cidade) REFERENCES cidade(id),
FOREIGN KEY(grupo) REFERENCES grupo_lojas(id)) ; 

CREATE TABLE marca( 
      id  INTEGER    NOT NULL  , 
      marca varchar  (100)   NOT NULL  , 
      fornecedor_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(fornecedor_id) REFERENCES fornecedor(id)) ; 

CREATE TABLE mes( 
      id  INTEGER    NOT NULL  , 
      loja int   , 
      descricao varchar  (100)   , 
      mes int   NOT NULL  , 
      ano int   NOT NULL  , 
      qtd_dias_uteis int   NOT NULL  , 
      cidade int   , 
      valor_passagem double   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mestre_variavel( 
      id  INTEGER    NOT NULL  , 
      tipo varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE motivo_transferencia_colaborador( 
      id  INTEGER    NOT NULL  , 
      motivo varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE natureza( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Ncm( 
      id  INTEGER    NOT NULL  , 
      n_ncm varchar  (50)   , 
      cest int   , 
      id_woo_ncm int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao( 
      id  INTEGER    NOT NULL  , 
      cliente_id int   NOT NULL  , 
      vendedor_id int   NOT NULL  , 
      origem_negociacao_id int   NOT NULL  , 
      tipo_negociacao_id int   NOT NULL  , 
      estado_negociacao_id int   NOT NULL  , 
      system_unit_id int   NOT NULL  , 
      dt_inicio_negociacao datetime   NOT NULL  , 
      dt_fim_negociacao datetime   , 
      descricao varchar  (200)   NOT NULL  , 
      obs varchar  (200)   , 
 PRIMARY KEY (id),
FOREIGN KEY(tipo_negociacao_id) REFERENCES tipo_negociacao(id),
FOREIGN KEY(origem_negociacao_id) REFERENCES origem_negociacao(id),
FOREIGN KEY(vendedor_id) REFERENCES pessoa(id),
FOREIGN KEY(cliente_id) REFERENCES pessoa(id),
FOREIGN KEY(estado_negociacao_id) REFERENCES estado_negociacao(id)) ; 

CREATE TABLE negociacao_produto( 
      id  INTEGER    NOT NULL  , 
      produto_id int   NOT NULL  , 
      negociacao_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(negociacao_id) REFERENCES negociacao(id),
FOREIGN KEY(produto_id) REFERENCES produto(id)) ; 

CREATE TABLE nfce_request_alt( 
      id  INTEGER    NOT NULL  , 
      ambienteEmissao varchar  (30)   , 
      informacoesAdicionais varchar  (200)   , 
      presencaConsumidor varchar  (20)   , 
      numVenda varchar  (30)   NOT NULL  , 
      status varchar  (20)   , 
      n_nfce int   , 
      link_cupom varchar  (1000)   , 
      id_loja int   , 
      retorno_nfce int   , 
      venda_id int   , 
      dt_nfce date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento( 
      id  INTEGER    NOT NULL  , 
      cliente_id int   NOT NULL  , 
      vendedor_id int   NOT NULL  , 
      estado_orcamento_id int   NOT NULL  , 
      system_unit_id int   NOT NULL  , 
      dt_orcamento datetime   NOT NULL  , 
      obs varchar  (200)   , 
      frete double   , 
      valor_total double   , 
 PRIMARY KEY (id),
FOREIGN KEY(estado_orcamento_id) REFERENCES estado_orcamento(id),
FOREIGN KEY(cliente_id) REFERENCES pessoa(id),
FOREIGN KEY(vendedor_id) REFERENCES pessoa(id)) ; 

CREATE TABLE orcamento_item( 
      id  INTEGER    NOT NULL  , 
      orcamento_id int   NOT NULL  , 
      produto_id int   NOT NULL  , 
      quantidade double   NOT NULL  , 
      valor double   NOT NULL  , 
      desconto double   , 
 PRIMARY KEY (id),
FOREIGN KEY(produto_id) REFERENCES produto(id),
FOREIGN KEY(orcamento_id) REFERENCES orcamento(id)) ; 

CREATE TABLE origem_negociacao( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE parcelas_conta( 
      id  INTEGER    NOT NULL  , 
      conta_origem int   NOT NULL  , 
      loja_id int   NOT NULL  , 
      fornecedor_id int   , 
      valor double   NOT NULL  , 
      forma_pagamento varchar  (100)   NOT NULL  , 
      conta_bancaria_loja int   , 
      conta_bancaria_fornecedor int   , 
      vencimento date   NOT NULL  , 
      quitada char  (1)   NOT NULL    DEFAULT 'f', 
      id_parcela_mestre int   , 
      tipo_parcela varchar  (30)   NOT NULL    DEFAULT 'parcela', 
      link_comprovante varchar  (200)   , 
      obs varchar  (200)   , 
      vazio1 varchar  (50)   , 
      vazio2 varchar  (50)   , 
      vazio3 varchar  (50)   , 
 PRIMARY KEY (id),
FOREIGN KEY(loja_id) REFERENCES loja(id),
FOREIGN KEY(conta_bancaria_loja) REFERENCES conta_bancaria(id),
FOREIGN KEY(conta_origem) REFERENCES conta(id),
FOREIGN KEY(fornecedor_id) REFERENCES fornecedor(id),
FOREIGN KEY(conta_bancaria_fornecedor) REFERENCES conta_bancaria(id)) ; 

CREATE TABLE pessoa( 
      id  INTEGER    NOT NULL  , 
      system_user_id int   , 
      tipo_pessoa int   NOT NULL    DEFAULT 1, 
      nome varchar  (50)   NOT NULL  , 
      documento varchar  (100)   , 
      obs varchar  (200)   , 
      fone varchar  (20)   , 
      email varchar  (50)   , 
      cidade_id int   NOT NULL  , 
      endereco varchar  (400)     DEFAULT 'null', 
      estado_id int   , 
      cep varchar  (20)   , 
      dt_ativacao date   NOT NULL  , 
      dt_desativacao date   , 
      id_cliente_pdv2 int   , 
      id_cliente_pdv1 int   , 
      colaborador_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(cidade_id) REFERENCES cidade(id),
FOREIGN KEY(colaborador_id) REFERENCES colaborador(id),
FOREIGN KEY(tipo_pessoa) REFERENCES grupo(id)) ; 

CREATE TABLE pessoa_grupo( 
      id  INTEGER    NOT NULL  , 
      grupo_id int   NOT NULL  , 
      pessoa_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(grupo_id) REFERENCES grupo(id)) ; 

CREATE TABLE Preco( 
      id  INTEGER    NOT NULL  , 
      preco_venda double   NOT NULL  , 
      preco_custo double   NOT NULL  , 
      id_produto int   NOT NULL  , 
      id_tabela int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(id_tabela) REFERENCES tabela_preco(id),
FOREIGN KEY(id_produto) REFERENCES produto(id)) ; 

CREATE TABLE prod_estoque( 
      id  INTEGER    NOT NULL  , 
      quantidade int   NOT NULL  , 
      qtd_min int   , 
      qtd_max int   , 
      id_deposito int   NOT NULL  , 
      id_produto int   NOT NULL  , 
      produto_marca int   , 
      produto_referencia varchar  (30)   , 
      produto_sku varchar  (100)   , 
      produto_nome varchar  (200)   , 
      produto_nome_variacao varchar  (50)   , 
      produto_fornecedor int   , 
      produto_categoria int   , 
      produto_cod_barras varchar  (13)   , 
      curva varchar  (1)   , 
 PRIMARY KEY (id),
FOREIGN KEY(id_deposito) REFERENCES deposito(id),
FOREIGN KEY(produto_marca) REFERENCES marca(id),
FOREIGN KEY(produto_fornecedor) REFERENCES fornecedor(id),
FOREIGN KEY(produto_categoria) REFERENCES categoria_produto(id),
FOREIGN KEY(id_produto) REFERENCES produto(id)) ; 

CREATE TABLE produto( 
      id  INTEGER    NOT NULL  , 
      unidade_id int   , 
      categoria_produto_id int   NOT NULL  , 
      fornecedor_id int   , 
      descricao varchar  (60)   , 
      desc_variacao varchar  (50)   , 
      dt_cadastro date   NOT NULL  , 
      SKU varchar  (20)   NOT NULL  , 
      cod_barras varchar  (20)   , 
      obs varchar  (20)   , 
      id_externo int   , 
      estoque int   , 
      tabela_preco int   , 
      preco int   , 
      qtd_max int   , 
      marca int   , 
      situacao_prod int   , 
      referencia varchar  (30)   , 
      tipo_cadastro int   , 
      deposito int   , 
      id_familia int   , 
      mestre_variavel int   NOT NULL  , 
      qtd_min int   , 
      valor_custo double   , 
      valor_venda double   , 
      sit_tribut varchar  (20)   , 
      qtd int   , 
      ncm varchar  (20)   , 
      cest varchar  (30)   , 
      id_externo_promocao int   , 
      origem varchar  (100)   , 
      link_site varchar  (100)   , 
      status varchar  (50)   NOT NULL    DEFAULT 'Correto', 
      vazio1 varchar  (200)   , 
      vazio2 varchar  (200)   , 
      vazio3 varchar  (200)   , 
 PRIMARY KEY (id),
FOREIGN KEY(categoria_produto_id) REFERENCES categoria_produto(id),
FOREIGN KEY(unidade_id) REFERENCES unidade(id),
FOREIGN KEY(tipo_cadastro) REFERENCES tipo_cadastro_prod(id),
FOREIGN KEY(situacao_prod) REFERENCES situacao_prod(id),
FOREIGN KEY(marca) REFERENCES marca(id),
FOREIGN KEY(fornecedor_id) REFERENCES fornecedor(id),
FOREIGN KEY(mestre_variavel) REFERENCES mestre_variavel(id)) ; 

CREATE TABLE prospeccao( 
      id  INTEGER    NOT NULL  , 
      vendedor_id int   NOT NULL  , 
      cliente_id int   NOT NULL  , 
      system_unit_id int   NOT NULL  , 
      horario_inicial datetime   NOT NULL  , 
      horario_final datetime   NOT NULL  , 
      titulo varchar  (30)   , 
      cor varchar  (30)   , 
      observacao varchar  (200)   , 
 PRIMARY KEY (id),
FOREIGN KEY(vendedor_id) REFERENCES pessoa(id),
FOREIGN KEY(cliente_id) REFERENCES pessoa(id)) ; 

CREATE TABLE salario( 
      id  INTEGER    NOT NULL  , 
      valor double   NOT NULL  , 
      decimo_terceiro double   , 
      bonificacao_valor double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE situacao_prod( 
      id  INTEGER    NOT NULL  , 
      situacao_prod varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE subparcelas_parcela( 
      id  INTEGER    NOT NULL  , 
      conta_origem int   NOT NULL  , 
      loja_id int   NOT NULL  , 
      fornecedor_id int   , 
      valor double   NOT NULL  , 
      forma_pagamento varchar  (100)   NOT NULL  , 
      conta_bancaria_loja int   , 
      conta_bancaria_fornecedor int   , 
      vencimento date   NOT NULL  , 
      quitada char  (1)   NOT NULL  , 
      id_parcela_mestre int   , 
      tipo_parcela varchar  (30)   NOT NULL  , 
      link_comprovante varchar  (200)   , 
      obs varchar  (200)   , 
      vazio1 varchar  (50)   , 
      vazio2 varchar  (50)   , 
      vazio3 varchar  (50)   , 
 PRIMARY KEY (id),
FOREIGN KEY(conta_origem) REFERENCES conta(id),
FOREIGN KEY(loja_id) REFERENCES loja(id),
FOREIGN KEY(fornecedor_id) REFERENCES fornecedor(id),
FOREIGN KEY(conta_bancaria_loja) REFERENCES conta_bancaria(id),
FOREIGN KEY(id_parcela_mestre) REFERENCES parcelas_conta(id),
FOREIGN KEY(conta_bancaria_fornecedor) REFERENCES conta_bancaria(id)) ; 

CREATE TABLE tabela_preco( 
      id  INTEGER    NOT NULL  , 
      id_preco int   , 
      nome_tabela_preco varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_cadastro_prod( 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (200)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (200)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta_bancaria( 
      id  INTEGER    NOT NULL  , 
      conta_bancaria varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_contato( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_documento( 
      id  INTEGER    NOT NULL  , 
      tipo varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_negociacao( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE transferencia_colaborador( 
      id  INTEGER    NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      loja_origem int   NOT NULL  , 
      loja_destino int   NOT NULL  , 
      dt_transferencia date   NOT NULL  , 
      motivo_transferencia int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(colaborador_id) REFERENCES colaborador(id),
FOREIGN KEY(motivo_transferencia) REFERENCES motivo_transferencia_colaborador(id),
FOREIGN KEY(loja_origem) REFERENCES loja(id),
FOREIGN KEY(loja_destino) REFERENCES loja(id)) ; 

CREATE TABLE transferencia_etq( 
      id  INTEGER    NOT NULL  , 
      deposito_rec int   NOT NULL  , 
      deposito_env int   NOT NULL  , 
      estoque_id int   NOT NULL  , 
      quantidade int   NOT NULL  , 
      dt_registro date   NOT NULL  , 
      usuario int   , 
      id_transferencia int   , 
      id_produto int   , 
      tipo_transferencia varchar  (20)   NOT NULL    DEFAULT 'transferencia', 
      saldo_posterior int   , 
 PRIMARY KEY (id),
FOREIGN KEY(deposito_env) REFERENCES deposito(id),
FOREIGN KEY(estoque_id) REFERENCES prod_estoque(id),
FOREIGN KEY(id_produto) REFERENCES produto(id)) ; 

CREATE TABLE unidade( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (4)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vale_transporte( 
      id  INTEGER    NOT NULL  , 
      valor double   , 
      quantidade int   , 
      colaborador_id int   NOT NULL  , 
      dias_uteis int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(colaborador_id) REFERENCES colaborador(id),
FOREIGN KEY(dias_uteis) REFERENCES mes(id)) ; 

CREATE TABLE venda_alt( 
      id  INTEGER    NOT NULL  , 
      n_venda varchar  (30)   NOT NULL  , 
      id_interno varchar  (20)   NOT NULL  , 
      cliente_id int   , 
      status varchar  (20)   , 
      vendedor_id int   , 
      estado_venda_id int   , 
      system_unit_id int   , 
      dt_venda datetime   NOT NULL  , 
      obs varchar  (400)   , 
      valor_total double   , 
      total_desconto double   , 
      loja int   NOT NULL  , 
      id_venda int   , 
      variavel_duplicidade varchar  (600)   , 
      forma_pagamento varchar  (30)   , 
      caixa varchar  (30)   , 
      func_caixa varchar  (50)   , 
      fiscal varchar  (1)   , 
      total_produtos double   , 
      total_pagamentos double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_item_alt( 
      id  INTEGER    NOT NULL  , 
      produto_id int   NOT NULL  , 
      deposito int   , 
      name varchar  (200)   , 
      venda_id int   NOT NULL  , 
      quantidade int   NOT NULL  , 
      valor_unitario double   NOT NULL  , 
      valor_desconto double   , 
      valor_total double   , 
      SKU varchar  (50)   , 
      loja_id int   , 
      dt_venda date   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(venda_id) REFERENCES venda_alt(id),
FOREIGN KEY(produto_id) REFERENCES produto(id)) ; 

 
 CREATE UNIQUE INDEX unique_idx_loja_abreviacao ON loja(abreviacao);
 CREATE UNIQUE INDEX unique_idx_loja_nome_fantasia ON loja(nome_fantasia);
 CREATE UNIQUE INDEX unique_idx_loja_cnpj ON loja(cnpj);
 CREATE UNIQUE INDEX unique_idx_loja_inscr_estadual ON loja(inscr_estadual);
 CREATE UNIQUE INDEX unique_idx_pessoa_email ON pessoa(email);
 CREATE UNIQUE INDEX unique_idx_produto_SKU ON produto(SKU);
 CREATE UNIQUE INDEX unique_idx_venda_alt_id_interno ON venda_alt(id_interno);
 
  
