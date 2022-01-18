CREATE TABLE advertencia( 
      id  SERIAL    NOT NULL  , 
      motivo varchar  (200)   NOT NULL  , 
      colaborador_id integer   NOT NULL  , 
      dt_advertencia date   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE aso( 
      id  SERIAL    NOT NULL  , 
      colaborador_id integer   NOT NULL  , 
      dt_realizado date   , 
      tipo_aso varchar  (30)   NOT NULL  , 
      status varchar  (30)   NOT NULL    DEFAULT 'a realizar', 
      vencimento date   , 
      link_scan_aso varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE atestado( 
      id  SERIAL    NOT NULL  , 
      colaborador_id integer   NOT NULL  , 
      dt_atestado date   NOT NULL  , 
      dt_retorno date   , 
      dias integer   NOT NULL  , 
      motivo varchar  (150)   NOT NULL  , 
      link_scan_atestado varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE banco( 
      id  SERIAL    NOT NULL  , 
      cod integer   NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cargo( 
      id  SERIAL    NOT NULL  , 
      cargo varchar  (50)   NOT NULL  , 
      salario integer   , 
      descricao varchar  (100)   , 
      escala integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria_produto( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
      id_externo integer   , 
      ncm_padrao integer   NOT NULL  , 
      iconeCategoria varchar  (400)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Cest( 
      id  SERIAL    NOT NULL  , 
      n_cest varchar  (20)   NOT NULL  , 
      descricao varchar  (1000)   NOT NULL  , 
      id_woo_cst integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ChatAPi( 
      id  SERIAL    NOT NULL  , 
      bot_nome varchar  (30)   , 
      bot_token varchar  (50)   NOT NULL  , 
      chat_id varchar  (50)   NOT NULL  , 
      loja integer   , 
      grupo_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
      estado_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE colaborador( 
      id  SERIAL    NOT NULL  , 
      pessoa_id integer   NOT NULL  , 
      rg varchar  (30)   , 
      ctps varchar  (40)   , 
      cnh varchar  (30)   , 
      dt_registro date   NOT NULL  , 
      dt_desligamento date   , 
      status_colaborador varchar  (20)   NOT NULL    DEFAULT 'ativo', 
      dt_nascimento date   NOT NULL  , 
      contrato1 date   , 
      contrato2 date   , 
      salario_familia boolean   NOT NULL    DEFAULT false, 
      salario_familia_qtd integer     DEFAULT 0, 
      bonificacao boolean     DEFAULT true, 
      status_ferias varchar  (30)   NOT NULL    DEFAULT 'inapto', 
      loja_registro integer   NOT NULL  , 
      loja_atual integer   NOT NULL  , 
      cargo integer   NOT NULL  , 
      salario integer   NOT NULL  , 
      carga_horaria integer   NOT NULL  , 
      escala integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE compra_funcionario( 
      id  SERIAL    NOT NULL  , 
      colaborador_id integer   NOT NULL  , 
      venda_id integer   NOT NULL  , 
      dt_venda date   NOT NULL  , 
      valor_venda float   NOT NULL  , 
      loja_venda integer   NOT NULL  , 
      quitado boolean   NOT NULL    DEFAULT false, 
 PRIMARY KEY (id)) ; 

CREATE TABLE conf_etiqu_produto( 
      id  SERIAL    NOT NULL  , 
      leftMargin integer   NOT NULL  , 
      topMargin integer   NOT NULL  , 
      labelWidth integer   NOT NULL  , 
      labelHeight integer   NOT NULL  , 
      spaceBetween integer   NOT NULL  , 
      rowsPerPage integer   NOT NULL  , 
      colsPerPage integer   NOT NULL  , 
      fontSize integer   NOT NULL  , 
      barcodeHeight integer   NOT NULL  , 
      imageMargin integer   NOT NULL  , 
      barcodeMethod varchar  (20)   NOT NULL  , 
      nome varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conta( 
      id  SERIAL    NOT NULL  , 
      tipo_conta_id integer   NOT NULL  , 
      loja integer   NOT NULL  , 
      natureza_id integer   NOT NULL  , 
      fornecedor integer   , 
      forma_pagamento varchar  (50)   , 
      dt_emissao date   NOT NULL  , 
      dt_vencimento date   NOT NULL  , 
      valor float   NOT NULL  , 
      desconto float   , 
      juros float   , 
      multa float   , 
      obs varchar  (200)   , 
      quitada char  (1)   NOT NULL    DEFAULT 'f', 
      parcelas integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conta_bancaria( 
      id  SERIAL    NOT NULL  , 
      loja integer   , 
      fornecedor integer   , 
      colaborador integer   , 
      cliente integer   , 
      parceiro integer   , 
      id_referencia_tipo integer   NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
      agencia integer   , 
      numero_conta integer   , 
      banco integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contato( 
      id  SERIAL    NOT NULL  , 
      pessoa_id integer   , 
      email varchar  (30)   , 
      nome varchar  (30)   , 
      telefone varchar  (20)   , 
      obs varchar  (50)   , 
      fornecedor_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE deposito( 
      id  SERIAL    NOT NULL  , 
      nome_deposito varchar  (50)   NOT NULL  , 
      loja integer   , 
      prod_estoque integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documento( 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (50)   NOT NULL  , 
      dt_registro date   NOT NULL  , 
      colaborador_id integer   NOT NULL  , 
      tipo_documento integer   NOT NULL    DEFAULT 1, 
      link_scan_documento varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE escala( 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (50)   , 
      carga_horaria_diaria time   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (30)   , 
      uf varchar  (2)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_negociacao( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (30)   NOT NULL  , 
      cor varchar  (50)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_orcamento( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_venda( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ferias( 
      id  SERIAL    NOT NULL  , 
      dt_inicio date   , 
      dt_fim date   , 
      periodo integer   NOT NULL    DEFAULT 30, 
      status_ferias varchar  (30)   NOT NULL    DEFAULT 'inapto', 
      colaborador_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fornecedor( 
      id  SERIAL    NOT NULL  , 
      razao_social varchar  (100)   NOT NULL  , 
      nome_fantasia varchar  (50)   , 
      cnpj varchar  (20)   NOT NULL  , 
      observacao varchar  (200)   , 
      fone varchar  (20)   , 
      email varchar  (30)   , 
      cidade integer   NOT NULL  , 
      rua varchar  (100)   , 
      numero varchar  (30)   , 
      bairro varchar  (100)   , 
      complemento varchar  (100)   , 
      dt_ativacao date   NOT NULL  , 
      inscr_estadual varchar  (30)   , 
      possui_ie integer   NOT NULL  , 
      icms varchar  (20)   NOT NULL  , 
      inscr_municipal varchar  (30)   , 
      regime_tributario varchar  (100)   NOT NULL  , 
      contato integer   , 
      marca integer   , 
      vazio1 varchar  (200)   , 
      vazio2 varchar  (200)   , 
      vazio3 varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo_lojas( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_negociacao( 
      id  SERIAL    NOT NULL  , 
      negociacao_id integer   NOT NULL  , 
      tipo_contato_id integer   NOT NULL  , 
      dt_contato timestamp   NOT NULL  , 
      descricao varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Import( 
      id  SERIAL    NOT NULL  , 
      link_sheet varchar  (200)   , 
      nome_tabela varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE loja( 
      id  SERIAL    NOT NULL  , 
      razao_social varchar  (50)   NOT NULL  , 
      abreviacao varchar  (5)   , 
      nome_fantasia varchar  (100)   , 
      cnpj varchar  (20)   NOT NULL  , 
      grupo integer   NOT NULL  , 
      observacao varchar  (200)   , 
      fone varchar  (30)   , 
      email varchar  (100)   , 
      cidade integer   NOT NULL  , 
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
      deposito integer   , 
      tipo_emissao integer   , 
      lat float   , 
      lon float   , 
      unidade integer   , 
      idEmpresa varchar  (50)   , 
      csc_producao varchar  (50)   , 
      id_csc_producao varchar  (50)   , 
      serie_nf_producao integer   , 
      seq_nf_producao integer   , 
      csc_homologacao varchar  (50)   , 
      id_csc_homologacao varchar  (50)   , 
      serie_nf_homologacao integer   , 
      seq_nf_homologacao integer   , 
      senha_certificado varchar  (50)   , 
      funcionario integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE marca( 
      id  SERIAL    NOT NULL  , 
      marca varchar  (100)   NOT NULL  , 
      fornecedor_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mes( 
      id  SERIAL    NOT NULL  , 
      loja integer   , 
      descricao varchar  (100)   , 
      mes integer   NOT NULL  , 
      ano integer   NOT NULL  , 
      qtd_dias_uteis integer   NOT NULL  , 
      cidade integer   , 
      valor_passagem float   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mestre_variavel( 
      id  SERIAL    NOT NULL  , 
      tipo varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE motivo_transferencia_colaborador( 
      id  SERIAL    NOT NULL  , 
      motivo varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE natureza( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (100)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Ncm( 
      id  SERIAL    NOT NULL  , 
      n_ncm varchar  (50)   , 
      cest integer   , 
      id_woo_ncm integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao( 
      id  SERIAL    NOT NULL  , 
      cliente_id integer   NOT NULL  , 
      vendedor_id integer   NOT NULL  , 
      origem_negociacao_id integer   NOT NULL  , 
      tipo_negociacao_id integer   NOT NULL  , 
      estado_negociacao_id integer   NOT NULL  , 
      system_unit_id integer   NOT NULL  , 
      dt_inicio_negociacao timestamp   NOT NULL  , 
      dt_fim_negociacao timestamp   , 
      descricao varchar  (200)   NOT NULL  , 
      obs varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao_produto( 
      id  SERIAL    NOT NULL  , 
      produto_id integer   NOT NULL  , 
      negociacao_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nfce_request_alt( 
      id  SERIAL    NOT NULL  , 
      ambienteEmissao varchar  (30)   , 
      informacoesAdicionais varchar  (200)   , 
      presencaConsumidor varchar  (20)   , 
      numVenda varchar  (30)   NOT NULL  , 
      status varchar  (20)   , 
      n_nfce integer   , 
      link_cupom varchar  (1000)   , 
      id_loja integer   , 
      retorno_nfce integer   , 
      venda_id integer   , 
      dt_nfce date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento( 
      id  SERIAL    NOT NULL  , 
      cliente_id integer   NOT NULL  , 
      vendedor_id integer   NOT NULL  , 
      estado_orcamento_id integer   NOT NULL  , 
      system_unit_id integer   NOT NULL  , 
      dt_orcamento timestamp   NOT NULL  , 
      obs varchar  (200)   , 
      frete float   , 
      valor_total float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_item( 
      id  SERIAL    NOT NULL  , 
      orcamento_id integer   NOT NULL  , 
      produto_id integer   NOT NULL  , 
      quantidade float   NOT NULL  , 
      valor float   NOT NULL  , 
      desconto float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE origem_negociacao( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (50)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE parcelas_conta( 
      id  SERIAL    NOT NULL  , 
      conta_origem integer   NOT NULL  , 
      loja_id integer   NOT NULL  , 
      fornecedor_id integer   , 
      valor float   NOT NULL  , 
      forma_pagamento varchar  (100)   NOT NULL  , 
      conta_bancaria_loja integer   , 
      conta_bancaria_fornecedor integer   , 
      vencimento date   NOT NULL  , 
      quitada char  (1)   NOT NULL    DEFAULT 'f', 
      id_parcela_mestre integer   , 
      tipo_parcela varchar  (30)   NOT NULL    DEFAULT 'parcela', 
      link_comprovante varchar  (200)   , 
      obs varchar  (200)   , 
      vazio1 varchar  (50)   , 
      vazio2 varchar  (50)   , 
      vazio3 varchar  (50)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa( 
      id  SERIAL    NOT NULL  , 
      system_user_id integer   , 
      tipo_pessoa integer   NOT NULL    DEFAULT 1, 
      nome varchar  (50)   NOT NULL  , 
      documento varchar  (100)   , 
      obs varchar  (200)   , 
      fone varchar  (20)   , 
      email varchar  (50)   , 
      cidade_id integer   NOT NULL  , 
      endereco varchar  (400)     DEFAULT 'null', 
      estado_id integer   , 
      cep varchar  (20)   , 
      dt_ativacao date   NOT NULL  , 
      dt_desativacao date   , 
      id_cliente_pdv2 integer   , 
      id_cliente_pdv1 integer   , 
      colaborador_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_grupo( 
      id  SERIAL    NOT NULL  , 
      grupo_id integer   NOT NULL  , 
      pessoa_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Preco( 
      id  SERIAL    NOT NULL  , 
      preco_venda float   NOT NULL  , 
      preco_custo float   NOT NULL  , 
      id_produto integer   NOT NULL  , 
      id_tabela integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE prod_estoque( 
      id  SERIAL    NOT NULL  , 
      quantidade integer   NOT NULL  , 
      qtd_min integer   , 
      qtd_max integer   , 
      id_deposito integer   NOT NULL  , 
      id_produto integer   NOT NULL  , 
      produto_marca integer   , 
      produto_referencia varchar  (30)   , 
      produto_sku varchar  (100)   , 
      produto_nome varchar  (200)   , 
      produto_nome_variacao varchar  (50)   , 
      produto_fornecedor integer   , 
      produto_categoria integer   , 
      produto_cod_barras varchar  (13)   , 
      curva varchar  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produto( 
      id  SERIAL    NOT NULL  , 
      unidade_id integer   , 
      categoria_produto_id integer   NOT NULL  , 
      fornecedor_id integer   , 
      descricao varchar  (60)   , 
      desc_variacao varchar  (50)   , 
      dt_cadastro date   NOT NULL  , 
      SKU varchar  (20)   NOT NULL  , 
      cod_barras varchar  (20)   , 
      obs varchar  (20)   , 
      id_externo integer   , 
      estoque integer   , 
      tabela_preco integer   , 
      preco integer   , 
      qtd_max integer   , 
      marca integer   , 
      situacao_prod integer   , 
      referencia varchar  (30)   , 
      tipo_cadastro integer   , 
      deposito integer   , 
      id_familia integer   , 
      mestre_variavel integer   NOT NULL  , 
      qtd_min integer   , 
      valor_custo float   , 
      valor_venda float   , 
      sit_tribut varchar  (20)   , 
      qtd integer   , 
      ncm varchar  (20)   , 
      cest varchar  (30)   , 
      id_externo_promocao integer   , 
      origem varchar  (100)   , 
      link_site varchar  (100)   , 
      status varchar  (50)   NOT NULL    DEFAULT 'Correto', 
      vazio1 varchar  (200)   , 
      vazio2 varchar  (200)   , 
      vazio3 varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE prospeccao( 
      id  SERIAL    NOT NULL  , 
      vendedor_id integer   NOT NULL  , 
      cliente_id integer   NOT NULL  , 
      system_unit_id integer   NOT NULL  , 
      horario_inicial timestamp   NOT NULL  , 
      horario_final timestamp   NOT NULL  , 
      titulo varchar  (30)   , 
      cor varchar  (30)   , 
      observacao varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE salario( 
      id  SERIAL    NOT NULL  , 
      valor float   NOT NULL  , 
      decimo_terceiro float   , 
      bonificacao_valor float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE situacao_prod( 
      id  SERIAL    NOT NULL  , 
      situacao_prod varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE subparcelas_parcela( 
      id  SERIAL    NOT NULL  , 
      conta_origem integer   NOT NULL  , 
      loja_id integer   NOT NULL  , 
      fornecedor_id integer   , 
      valor float   NOT NULL  , 
      forma_pagamento varchar  (100)   NOT NULL  , 
      conta_bancaria_loja integer   , 
      conta_bancaria_fornecedor integer   , 
      vencimento date   NOT NULL  , 
      quitada char  (1)   NOT NULL  , 
      id_parcela_mestre integer   , 
      tipo_parcela varchar  (30)   NOT NULL  , 
      link_comprovante varchar  (200)   , 
      obs varchar  (200)   , 
      vazio1 varchar  (50)   , 
      vazio2 varchar  (50)   , 
      vazio3 varchar  (50)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tabela_preco( 
      id  SERIAL    NOT NULL  , 
      id_preco integer   , 
      nome_tabela_preco varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_cadastro_prod( 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (200)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (200)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta_bancaria( 
      id  SERIAL    NOT NULL  , 
      conta_bancaria varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_contato( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_documento( 
      id  SERIAL    NOT NULL  , 
      tipo varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_negociacao( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (30)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE transferencia_colaborador( 
      id  SERIAL    NOT NULL  , 
      colaborador_id integer   NOT NULL  , 
      loja_origem integer   NOT NULL  , 
      loja_destino integer   NOT NULL  , 
      dt_transferencia date   NOT NULL  , 
      motivo_transferencia integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE transferencia_etq( 
      id  SERIAL    NOT NULL  , 
      deposito_rec integer   NOT NULL  , 
      deposito_env integer   NOT NULL  , 
      estoque_id integer   NOT NULL  , 
      quantidade integer   NOT NULL  , 
      dt_registro date   NOT NULL  , 
      usuario integer   , 
      id_transferencia integer   , 
      id_produto integer   , 
      tipo_transferencia varchar  (20)   NOT NULL    DEFAULT 'transferencia', 
      saldo_posterior integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE unidade( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (4)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vale_transporte( 
      id  SERIAL    NOT NULL  , 
      valor float   , 
      quantidade integer   , 
      colaborador_id integer   NOT NULL  , 
      dias_uteis integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_alt( 
      id  SERIAL    NOT NULL  , 
      n_venda varchar  (30)   NOT NULL  , 
      id_interno varchar  (20)   NOT NULL  , 
      cliente_id integer   , 
      status varchar  (20)   , 
      vendedor_id integer   , 
      estado_venda_id integer   , 
      system_unit_id integer   , 
      dt_venda timestamp   NOT NULL  , 
      obs varchar  (400)   , 
      valor_total float   , 
      total_desconto float   , 
      loja integer   NOT NULL  , 
      id_venda integer   , 
      variavel_duplicidade varchar  (600)   , 
      forma_pagamento varchar  (30)   , 
      caixa varchar  (30)   , 
      func_caixa varchar  (50)   , 
      fiscal varchar  (1)   , 
      total_produtos float   , 
      total_pagamentos float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_item_alt( 
      id  SERIAL    NOT NULL  , 
      produto_id integer   NOT NULL  , 
      deposito integer   , 
      name varchar  (200)   , 
      venda_id integer   NOT NULL  , 
      quantidade integer   NOT NULL  , 
      valor_unitario float   NOT NULL  , 
      valor_desconto float   , 
      valor_total float   , 
      SKU varchar  (50)   , 
      loja_id integer   , 
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

  
