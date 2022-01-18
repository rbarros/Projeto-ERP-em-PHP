CREATE TABLE advertencia( 
      id  INT IDENTITY    NOT NULL  , 
      motivo varchar  (200)   NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      dt_advertencia date   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE aso( 
      id  INT IDENTITY    NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      dt_realizado date   , 
      tipo_aso varchar  (30)   NOT NULL  , 
      status varchar  (30)   NOT NULL    DEFAULT 'a realizar', 
      vencimento date   , 
      link_scan_aso varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE atestado( 
      id  INT IDENTITY    NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      dt_atestado date   NOT NULL  , 
      dt_retorno date   , 
      dias int   NOT NULL  , 
      motivo varchar  (150)   NOT NULL  , 
      link_scan_atestado varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE banco( 
      id  INT IDENTITY    NOT NULL  , 
      cod int   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cargo( 
      id  INT IDENTITY    NOT NULL  , 
      cargo varchar  (50)   NOT NULL  , 
      salario int   , 
      descricao varchar  (100)   , 
      escala int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria_produto( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
      id_externo int   , 
      ncm_padrao int   NOT NULL  , 
      iconeCategoria varchar  (400)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Cest( 
      id  INT IDENTITY    NOT NULL  , 
      n_cest varchar  (20)   NOT NULL  , 
      descricao varchar  (1000)   NOT NULL  , 
      id_woo_cst int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ChatAPi( 
      id  INT IDENTITY    NOT NULL  , 
      bot_nome varchar  (30)   , 
      bot_token varchar  (50)   NOT NULL  , 
      chat_id varchar  (50)   NOT NULL  , 
      loja int   , 
      grupo_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
      estado_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE colaborador( 
      id  INT IDENTITY    NOT NULL  , 
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
      salario_familia bit   NOT NULL    DEFAULT false, 
      salario_familia_qtd int     DEFAULT 0, 
      bonificacao bit     DEFAULT true, 
      status_ferias varchar  (30)   NOT NULL    DEFAULT 'inapto', 
      loja_registro int   NOT NULL  , 
      loja_atual int   NOT NULL  , 
      cargo int   NOT NULL  , 
      salario int   NOT NULL  , 
      carga_horaria int   NOT NULL  , 
      escala int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE compra_funcionario( 
      id  INT IDENTITY    NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      venda_id int   NOT NULL  , 
      dt_venda date   NOT NULL  , 
      valor_venda float   NOT NULL  , 
      loja_venda int   NOT NULL  , 
      quitado bit   NOT NULL    DEFAULT false, 
 PRIMARY KEY (id)) ; 

CREATE TABLE conf_etiqu_produto( 
      id  INT IDENTITY    NOT NULL  , 
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
      id  INT IDENTITY    NOT NULL  , 
      tipo_conta_id int   NOT NULL  , 
      loja int   NOT NULL  , 
      natureza_id int   NOT NULL  , 
      fornecedor int   , 
      forma_pagamento varchar  (50)   , 
      dt_emissao date   NOT NULL  , 
      dt_vencimento date   NOT NULL  , 
      valor float   NOT NULL  , 
      desconto float   , 
      juros float   , 
      multa float   , 
      obs varchar  (200)   , 
      quitada char  (1)   NOT NULL    DEFAULT 'f', 
      parcelas int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conta_bancaria( 
      id  INT IDENTITY    NOT NULL  , 
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
 PRIMARY KEY (id)) ; 

CREATE TABLE contato( 
      id  INT IDENTITY    NOT NULL  , 
      pessoa_id int   , 
      email varchar  (30)   , 
      nome varchar  (30)   , 
      telefone varchar  (20)   , 
      obs varchar  (50)   , 
      fornecedor_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE deposito( 
      id  INT IDENTITY    NOT NULL  , 
      nome_deposito varchar  (50)   NOT NULL  , 
      loja int   , 
      prod_estoque int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documento( 
      id  INT IDENTITY    NOT NULL  , 
      descricao varchar  (50)   NOT NULL  , 
      dt_registro date   NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      tipo_documento int   NOT NULL    DEFAULT 1, 
      link_scan_documento varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE escala( 
      id  INT IDENTITY    NOT NULL  , 
      descricao varchar  (50)   , 
      carga_horaria_diaria time   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (30)   , 
      uf varchar  (2)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_negociacao( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (30)   NOT NULL  , 
      cor varchar  (50)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_orcamento( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_venda( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ferias( 
      id  INT IDENTITY    NOT NULL  , 
      dt_inicio date   , 
      dt_fim date   , 
      periodo int   NOT NULL    DEFAULT 30, 
      status_ferias varchar  (30)   NOT NULL    DEFAULT 'inapto', 
      colaborador_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fornecedor( 
      id  INT IDENTITY    NOT NULL  , 
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
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo_lojas( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_negociacao( 
      id  INT IDENTITY    NOT NULL  , 
      negociacao_id int   NOT NULL  , 
      tipo_contato_id int   NOT NULL  , 
      dt_contato datetime2   NOT NULL  , 
      descricao varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Import( 
      id  INT IDENTITY    NOT NULL  , 
      link_sheet varchar  (200)   , 
      nome_tabela varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE loja( 
      id  INT IDENTITY    NOT NULL  , 
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
      lat float   , 
      lon float   , 
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
 PRIMARY KEY (id)) ; 

CREATE TABLE marca( 
      id  INT IDENTITY    NOT NULL  , 
      marca varchar  (100)   NOT NULL  , 
      fornecedor_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mes( 
      id  INT IDENTITY    NOT NULL  , 
      loja int   , 
      descricao varchar  (100)   , 
      mes int   NOT NULL  , 
      ano int   NOT NULL  , 
      qtd_dias_uteis int   NOT NULL  , 
      cidade int   , 
      valor_passagem float   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mestre_variavel( 
      id  INT IDENTITY    NOT NULL  , 
      tipo varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE motivo_transferencia_colaborador( 
      id  INT IDENTITY    NOT NULL  , 
      motivo varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE natureza( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Ncm( 
      id  INT IDENTITY    NOT NULL  , 
      n_ncm varchar  (50)   , 
      cest int   , 
      id_woo_ncm int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao( 
      id  INT IDENTITY    NOT NULL  , 
      cliente_id int   NOT NULL  , 
      vendedor_id int   NOT NULL  , 
      origem_negociacao_id int   NOT NULL  , 
      tipo_negociacao_id int   NOT NULL  , 
      estado_negociacao_id int   NOT NULL  , 
      system_unit_id int   NOT NULL  , 
      dt_inicio_negociacao datetime2   NOT NULL  , 
      dt_fim_negociacao datetime2   , 
      descricao varchar  (200)   NOT NULL  , 
      obs varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao_produto( 
      id  INT IDENTITY    NOT NULL  , 
      produto_id int   NOT NULL  , 
      negociacao_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nfce_request_alt( 
      id  INT IDENTITY    NOT NULL  , 
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
      id  INT IDENTITY    NOT NULL  , 
      cliente_id int   NOT NULL  , 
      vendedor_id int   NOT NULL  , 
      estado_orcamento_id int   NOT NULL  , 
      system_unit_id int   NOT NULL  , 
      dt_orcamento datetime2   NOT NULL  , 
      obs varchar  (200)   , 
      frete float   , 
      valor_total float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_item( 
      id  INT IDENTITY    NOT NULL  , 
      orcamento_id int   NOT NULL  , 
      produto_id int   NOT NULL  , 
      quantidade float   NOT NULL  , 
      valor float   NOT NULL  , 
      desconto float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE origem_negociacao( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE parcelas_conta( 
      id  INT IDENTITY    NOT NULL  , 
      conta_origem int   NOT NULL  , 
      loja_id int   NOT NULL  , 
      fornecedor_id int   , 
      valor float   NOT NULL  , 
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
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa( 
      id  INT IDENTITY    NOT NULL  , 
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
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_grupo( 
      id  INT IDENTITY    NOT NULL  , 
      grupo_id int   NOT NULL  , 
      pessoa_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Preco( 
      id  INT IDENTITY    NOT NULL  , 
      preco_venda float   NOT NULL  , 
      preco_custo float   NOT NULL  , 
      id_produto int   NOT NULL  , 
      id_tabela int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE prod_estoque( 
      id  INT IDENTITY    NOT NULL  , 
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
 PRIMARY KEY (id)) ; 

CREATE TABLE produto( 
      id  INT IDENTITY    NOT NULL  , 
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
      valor_custo float   , 
      valor_venda float   , 
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
 PRIMARY KEY (id)) ; 

CREATE TABLE prospeccao( 
      id  INT IDENTITY    NOT NULL  , 
      vendedor_id int   NOT NULL  , 
      cliente_id int   NOT NULL  , 
      system_unit_id int   NOT NULL  , 
      horario_inicial datetime2   NOT NULL  , 
      horario_final datetime2   NOT NULL  , 
      titulo varchar  (30)   , 
      cor varchar  (30)   , 
      observacao varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE salario( 
      id  INT IDENTITY    NOT NULL  , 
      valor float   NOT NULL  , 
      decimo_terceiro float   , 
      bonificacao_valor float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE situacao_prod( 
      id  INT IDENTITY    NOT NULL  , 
      situacao_prod varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE subparcelas_parcela( 
      id  INT IDENTITY    NOT NULL  , 
      conta_origem int   NOT NULL  , 
      loja_id int   NOT NULL  , 
      fornecedor_id int   , 
      valor float   NOT NULL  , 
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
 PRIMARY KEY (id)) ; 

CREATE TABLE tabela_preco( 
      id  INT IDENTITY    NOT NULL  , 
      id_preco int   , 
      nome_tabela_preco varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_cadastro_prod( 
      id  INT IDENTITY    NOT NULL  , 
      descricao varchar  (200)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (200)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta_bancaria( 
      id  INT IDENTITY    NOT NULL  , 
      conta_bancaria varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_contato( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_documento( 
      id  INT IDENTITY    NOT NULL  , 
      tipo varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_negociacao( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE transferencia_colaborador( 
      id  INT IDENTITY    NOT NULL  , 
      colaborador_id int   NOT NULL  , 
      loja_origem int   NOT NULL  , 
      loja_destino int   NOT NULL  , 
      dt_transferencia date   NOT NULL  , 
      motivo_transferencia int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE transferencia_etq( 
      id  INT IDENTITY    NOT NULL  , 
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
 PRIMARY KEY (id)) ; 

CREATE TABLE unidade( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (4)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vale_transporte( 
      id  INT IDENTITY    NOT NULL  , 
      valor float   , 
      quantidade int   , 
      colaborador_id int   NOT NULL  , 
      dias_uteis int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_alt( 
      id  INT IDENTITY    NOT NULL  , 
      n_venda varchar  (30)   NOT NULL  , 
      id_interno varchar  (20)   NOT NULL  , 
      cliente_id int   , 
      status varchar  (20)   , 
      vendedor_id int   , 
      estado_venda_id int   , 
      system_unit_id int   , 
      dt_venda datetime2   NOT NULL  , 
      obs varchar  (400)   , 
      valor_total float   , 
      total_desconto float   , 
      loja int   NOT NULL  , 
      id_venda int   , 
      variavel_duplicidade varchar  (600)   , 
      forma_pagamento varchar  (30)   , 
      caixa varchar  (30)   , 
      func_caixa varchar  (50)   , 
      fiscal varchar  (1)   , 
      total_produtos float   , 
      total_pagamentos float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_item_alt( 
      id  INT IDENTITY    NOT NULL  , 
      produto_id int   NOT NULL  , 
      deposito int   , 
      name varchar  (200)   , 
      venda_id int   NOT NULL  , 
      quantidade int   NOT NULL  , 
      valor_unitario float   NOT NULL  , 
      valor_desconto float   , 
      valor_total float   , 
      SKU varchar  (50)   , 
      loja_id int   , 
      dt_venda date   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
 ALTER TABLE loja ADD UNIQUE (abreviacao);
 ALTER TABLE loja ADD UNIQUE (nome_fantasia);
 ALTER TABLE loja ADD UNIQUE (cnpj);
 ALTER TABLE loja ADD UNIQUE (inscr_estadual);
 ALTER TABLE pessoa ADD UNIQUE (email);
 ALTER TABLE produto ADD UNIQUE (SKU);
 ALTER TABLE venda_alt ADD UNIQUE (id_interno);
  
 ALTER TABLE advertencia ADD CONSTRAINT fk_advertencia_colaborador FOREIGN KEY (colaborador_id) references colaborador(id); 
ALTER TABLE aso ADD CONSTRAINT fk_aso_colaborador FOREIGN KEY (colaborador_id) references colaborador(id); 
ALTER TABLE atestado ADD CONSTRAINT fk_atestado_colaborador FOREIGN KEY (colaborador_id) references colaborador(id); 
ALTER TABLE cargo ADD CONSTRAINT fk_cargo_salario FOREIGN KEY (salario) references salario(id); 
ALTER TABLE cargo ADD CONSTRAINT fk_cargo_escala FOREIGN KEY (escala) references escala(id); 
ALTER TABLE cidade ADD CONSTRAINT fk_cidade_1 FOREIGN KEY (estado_id) references estado(id); 
ALTER TABLE colaborador ADD CONSTRAINT fk_funcionario_func FOREIGN KEY (loja_registro) references loja(id); 
ALTER TABLE colaborador ADD CONSTRAINT fk_funcionario_cargo FOREIGN KEY (cargo) references cargo(id); 
ALTER TABLE colaborador ADD CONSTRAINT fk_colaborador_salario FOREIGN KEY (salario) references salario(id); 
ALTER TABLE colaborador ADD CONSTRAINT fk_colaborador_carga_horaria FOREIGN KEY (carga_horaria) references escala(id); 
ALTER TABLE colaborador ADD CONSTRAINT fk_colaborador_pessoa FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE colaborador ADD CONSTRAINT fk_colaborador_escala FOREIGN KEY (escala) references escala(id); 
ALTER TABLE compra_funcionario ADD CONSTRAINT fk_compra_funcionario_colaborador FOREIGN KEY (colaborador_id) references colaborador(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_natureza FOREIGN KEY (natureza_id) references natureza(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_tipo_conta FOREIGN KEY (tipo_conta_id) references tipo_conta(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_loja FOREIGN KEY (loja) references loja(id); 
ALTER TABLE conta_bancaria ADD CONSTRAINT fk_conta_bancaria_tipo FOREIGN KEY (id_referencia_tipo) references tipo_conta_bancaria(id); 
ALTER TABLE conta_bancaria ADD CONSTRAINT fk_conta_bancaria_loja FOREIGN KEY (loja) references loja(id); 
ALTER TABLE conta_bancaria ADD CONSTRAINT fk_conta_bancaria_fornecedor FOREIGN KEY (fornecedor) references fornecedor(id); 
ALTER TABLE conta_bancaria ADD CONSTRAINT fk_conta_bancaria_colaborador FOREIGN KEY (colaborador) references colaborador(id); 
ALTER TABLE conta_bancaria ADD CONSTRAINT fk_conta_bancaria_banco FOREIGN KEY (banco) references banco(id); 
ALTER TABLE contato ADD CONSTRAINT fk_contato_pessoa FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE contato ADD CONSTRAINT fk_contato_fornecedor FOREIGN KEY (fornecedor_id) references fornecedor(id); 
ALTER TABLE deposito ADD CONSTRAINT fk_deposito_nome FOREIGN KEY (loja) references loja(id); 
ALTER TABLE documento ADD CONSTRAINT fk_documento_colaborador FOREIGN KEY (colaborador_id) references colaborador(id); 
ALTER TABLE documento ADD CONSTRAINT fk_documento_tipo FOREIGN KEY (tipo_documento) references tipo_documento(id); 
ALTER TABLE ferias ADD CONSTRAINT fk_ferias_colaborador FOREIGN KEY (colaborador_id) references colaborador(id); 
ALTER TABLE fornecedor ADD CONSTRAINT fk_fornecedor_cidade FOREIGN KEY (cidade) references cidade(id); 
ALTER TABLE historico_negociacao ADD CONSTRAINT fk_historico_negociacao_2 FOREIGN KEY (tipo_contato_id) references tipo_contato(id); 
ALTER TABLE historico_negociacao ADD CONSTRAINT fk_historico_negociacao_1 FOREIGN KEY (negociacao_id) references negociacao(id); 
ALTER TABLE loja ADD CONSTRAINT fk_loja_cidade FOREIGN KEY (cidade) references cidade(id); 
ALTER TABLE loja ADD CONSTRAINT fk_loja_grupo FOREIGN KEY (grupo) references grupo_lojas(id); 
ALTER TABLE marca ADD CONSTRAINT fk_marca_fornecedor FOREIGN KEY (fornecedor_id) references fornecedor(id); 
ALTER TABLE negociacao ADD CONSTRAINT fk_negociacao_5 FOREIGN KEY (tipo_negociacao_id) references tipo_negociacao(id); 
ALTER TABLE negociacao ADD CONSTRAINT fk_negociacao_4 FOREIGN KEY (origem_negociacao_id) references origem_negociacao(id); 
ALTER TABLE negociacao ADD CONSTRAINT fk_negociacao_3 FOREIGN KEY (vendedor_id) references pessoa(id); 
ALTER TABLE negociacao ADD CONSTRAINT fk_negociacao_1 FOREIGN KEY (cliente_id) references pessoa(id); 
ALTER TABLE negociacao ADD CONSTRAINT fk_negociacao_6 FOREIGN KEY (estado_negociacao_id) references estado_negociacao(id); 
ALTER TABLE negociacao_produto ADD CONSTRAINT fk_negociacao_produto_2 FOREIGN KEY (negociacao_id) references negociacao(id); 
ALTER TABLE negociacao_produto ADD CONSTRAINT fk_negociacao_produto_1 FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_2 FOREIGN KEY (estado_orcamento_id) references estado_orcamento(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_1 FOREIGN KEY (cliente_id) references pessoa(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_3 FOREIGN KEY (vendedor_id) references pessoa(id); 
ALTER TABLE orcamento_item ADD CONSTRAINT fk_orcamento_item_2 FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE orcamento_item ADD CONSTRAINT fk_orcamento_item_1 FOREIGN KEY (orcamento_id) references orcamento(id); 
ALTER TABLE parcelas_conta ADD CONSTRAINT fk_parcelas_loja FOREIGN KEY (loja_id) references loja(id); 
ALTER TABLE parcelas_conta ADD CONSTRAINT fk_parcelas_conta_loja FOREIGN KEY (conta_bancaria_loja) references conta_bancaria(id); 
ALTER TABLE parcelas_conta ADD CONSTRAINT fk_parcelas_conta FOREIGN KEY (conta_origem) references conta(id); 
ALTER TABLE parcelas_conta ADD CONSTRAINT fk_parcelas_fornecedor FOREIGN KEY (fornecedor_id) references fornecedor(id); 
ALTER TABLE parcelas_conta ADD CONSTRAINT fk_parcelas_conta_contA_fornecedor FOREIGN KEY (conta_bancaria_fornecedor) references conta_bancaria(id); 
ALTER TABLE pessoa ADD CONSTRAINT fk_pessoa_1 FOREIGN KEY (cidade_id) references cidade(id); 
ALTER TABLE pessoa ADD CONSTRAINT fk_pessoa_colaborador FOREIGN KEY (colaborador_id) references colaborador(id); 
ALTER TABLE pessoa ADD CONSTRAINT fk_pessoa_tipo FOREIGN KEY (tipo_pessoa) references grupo(id); 
ALTER TABLE pessoa_grupo ADD CONSTRAINT fk_pessoa_grupo_2 FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE pessoa_grupo ADD CONSTRAINT fk_pessoa_grupo_1 FOREIGN KEY (grupo_id) references grupo(id); 
ALTER TABLE Preco ADD CONSTRAINT fk_Preco_tabela_preco FOREIGN KEY (id_tabela) references tabela_preco(id); 
ALTER TABLE Preco ADD CONSTRAINT fk_Preco_produto FOREIGN KEY (id_produto) references produto(id); 
ALTER TABLE prod_estoque ADD CONSTRAINT fk_prod_estoque_deposito FOREIGN KEY (id_deposito) references deposito(id); 
ALTER TABLE prod_estoque ADD CONSTRAINT fk_prod_estoque_marca FOREIGN KEY (produto_marca) references marca(id); 
ALTER TABLE prod_estoque ADD CONSTRAINT fk_prod_estoque_fornecedor FOREIGN KEY (produto_fornecedor) references fornecedor(id); 
ALTER TABLE prod_estoque ADD CONSTRAINT fk_prod_estoque_categoria FOREIGN KEY (produto_categoria) references categoria_produto(id); 
ALTER TABLE prod_estoque ADD CONSTRAINT fk_prod_estoque_produto FOREIGN KEY (id_produto) references produto(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_categoria FOREIGN KEY (categoria_produto_id) references categoria_produto(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_unidade FOREIGN KEY (unidade_id) references unidade(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_tipo_cadastro FOREIGN KEY (tipo_cadastro) references tipo_cadastro_prod(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_situacao FOREIGN KEY (situacao_prod) references situacao_prod(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_marca FOREIGN KEY (marca) references marca(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_fornecedor FOREIGN KEY (fornecedor_id) references fornecedor(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_mestreVariavel FOREIGN KEY (mestre_variavel) references mestre_variavel(id); 
ALTER TABLE prospeccao ADD CONSTRAINT fk_prospeccao_2 FOREIGN KEY (vendedor_id) references pessoa(id); 
ALTER TABLE prospeccao ADD CONSTRAINT fk_prospeccao_1 FOREIGN KEY (cliente_id) references pessoa(id); 
ALTER TABLE subparcelas_parcela ADD CONSTRAINT fk_subparcelas_parcela_conta FOREIGN KEY (conta_origem) references conta(id); 
ALTER TABLE subparcelas_parcela ADD CONSTRAINT fk_subparcelas_parcela_loja FOREIGN KEY (loja_id) references loja(id); 
ALTER TABLE subparcelas_parcela ADD CONSTRAINT fk_subparcelas_parcela_fornecedor FOREIGN KEY (fornecedor_id) references fornecedor(id); 
ALTER TABLE subparcelas_parcela ADD CONSTRAINT fk_subparcelas_parcela_conta_loja FOREIGN KEY (conta_bancaria_loja) references conta_bancaria(id); 
ALTER TABLE subparcelas_parcela ADD CONSTRAINT fk_subparcelas_parcela_mestre FOREIGN KEY (id_parcela_mestre) references parcelas_conta(id); 
ALTER TABLE subparcelas_parcela ADD CONSTRAINT fk_subparcelas_parcela_conta_fornecedor FOREIGN KEY (conta_bancaria_fornecedor) references conta_bancaria(id); 
ALTER TABLE transferencia_colaborador ADD CONSTRAINT fk_transferencia_colaborador FOREIGN KEY (colaborador_id) references colaborador(id); 
ALTER TABLE transferencia_colaborador ADD CONSTRAINT fk_transferencia_colaborador_motivo FOREIGN KEY (motivo_transferencia) references motivo_transferencia_colaborador(id); 
ALTER TABLE transferencia_colaborador ADD CONSTRAINT fk_transferencia_colaborador_loja_origem FOREIGN KEY (loja_origem) references loja(id); 
ALTER TABLE transferencia_colaborador ADD CONSTRAINT fk_transferencia_colaborador_loja_destino FOREIGN KEY (loja_destino) references loja(id); 
ALTER TABLE transferencia_etq ADD CONSTRAINT fk_transferencia_etq_env FOREIGN KEY (deposito_env) references deposito(id); 
ALTER TABLE transferencia_etq ADD CONSTRAINT fk_transferencia_etq_prod FOREIGN KEY (estoque_id) references prod_estoque(id); 
ALTER TABLE transferencia_etq ADD CONSTRAINT fk_transferencia_etq_produto FOREIGN KEY (id_produto) references produto(id); 
ALTER TABLE vale_transporte ADD CONSTRAINT fk_vale_transporte_colaborador FOREIGN KEY (colaborador_id) references colaborador(id); 
ALTER TABLE vale_transporte ADD CONSTRAINT fk_vale_transporte_mes FOREIGN KEY (dias_uteis) references mes(id); 
ALTER TABLE venda_item_alt ADD CONSTRAINT fk_venda_item_1 FOREIGN KEY (venda_id) references venda_alt(id); 
ALTER TABLE venda_item_alt ADD CONSTRAINT fk_venda_item_2 FOREIGN KEY (produto_id) references produto(id); 

  
