CREATE TABLE advertencia( 
      id number(10)    NOT NULL , 
      motivo varchar  (200)    NOT NULL , 
      colaborador_id number(10)    NOT NULL , 
      dt_advertencia date    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE aso( 
      id number(10)    NOT NULL , 
      colaborador_id number(10)    NOT NULL , 
      dt_realizado date   , 
      tipo_aso varchar  (30)    NOT NULL , 
      status varchar  (30)    DEFAULT 'a realizar'  NOT NULL , 
      vencimento date   , 
      link_scan_aso varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE atestado( 
      id number(10)    NOT NULL , 
      colaborador_id number(10)    NOT NULL , 
      dt_atestado date    NOT NULL , 
      dt_retorno date   , 
      dias number(10)    NOT NULL , 
      motivo varchar  (150)    NOT NULL , 
      link_scan_atestado varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE banco( 
      id number(10)    NOT NULL , 
      cod number(10)    NOT NULL , 
      nome varchar  (100)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cargo( 
      id number(10)    NOT NULL , 
      cargo varchar  (50)    NOT NULL , 
      salario number(10)   , 
      descricao varchar  (100)   , 
      escala number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria_produto( 
      id number(10)    NOT NULL , 
      nome varchar  (50)    NOT NULL , 
      id_externo number(10)   , 
      ncm_padrao number(10)    NOT NULL , 
      iconeCategoria varchar  (400)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Cest( 
      id number(10)    NOT NULL , 
      n_cest varchar  (20)    NOT NULL , 
      descricao varchar  (1000)    NOT NULL , 
      id_woo_cst number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ChatAPi( 
      id number(10)    NOT NULL , 
      bot_nome varchar  (30)   , 
      bot_token varchar  (50)    NOT NULL , 
      chat_id varchar  (50)    NOT NULL , 
      loja number(10)   , 
      grupo_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id number(10)    NOT NULL , 
      nome varchar  (50)    NOT NULL , 
      estado_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE colaborador( 
      id number(10)    NOT NULL , 
      pessoa_id number(10)    NOT NULL , 
      rg varchar  (30)   , 
      ctps varchar  (40)   , 
      cnh varchar  (30)   , 
      dt_registro date    NOT NULL , 
      dt_desligamento date   , 
      status_colaborador varchar  (20)    DEFAULT 'ativo'  NOT NULL , 
      dt_nascimento date    NOT NULL , 
      contrato1 date   , 
      contrato2 date   , 
      salario_familia char(1)    DEFAULT false  NOT NULL , 
      salario_familia_qtd number(10)    DEFAULT 0 , 
      bonificacao char(1)    DEFAULT true , 
      status_ferias varchar  (30)    DEFAULT 'inapto'  NOT NULL , 
      loja_registro number(10)    NOT NULL , 
      loja_atual number(10)    NOT NULL , 
      cargo number(10)    NOT NULL , 
      salario number(10)    NOT NULL , 
      carga_horaria number(10)    NOT NULL , 
      escala number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE compra_funcionario( 
      id number(10)    NOT NULL , 
      colaborador_id number(10)    NOT NULL , 
      venda_id number(10)    NOT NULL , 
      dt_venda date    NOT NULL , 
      valor_venda binary_double    NOT NULL , 
      loja_venda number(10)    NOT NULL , 
      quitado char(1)    DEFAULT false  NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conf_etiqu_produto( 
      id number(10)    NOT NULL , 
      leftMargin number(10)    NOT NULL , 
      topMargin number(10)    NOT NULL , 
      labelWidth number(10)    NOT NULL , 
      labelHeight number(10)    NOT NULL , 
      spaceBetween number(10)    NOT NULL , 
      rowsPerPage number(10)    NOT NULL , 
      colsPerPage number(10)    NOT NULL , 
      fontSize number(10)    NOT NULL , 
      barcodeHeight number(10)    NOT NULL , 
      imageMargin number(10)    NOT NULL , 
      barcodeMethod varchar  (20)    NOT NULL , 
      nome varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conta( 
      id number(10)    NOT NULL , 
      tipo_conta_id number(10)    NOT NULL , 
      loja number(10)    NOT NULL , 
      natureza_id number(10)    NOT NULL , 
      fornecedor number(10)   , 
      forma_pagamento varchar  (50)   , 
      dt_emissao date    NOT NULL , 
      dt_vencimento date    NOT NULL , 
      valor binary_double    NOT NULL , 
      desconto binary_double   , 
      juros binary_double   , 
      multa binary_double   , 
      obs varchar  (200)   , 
      quitada char  (1)    DEFAULT 'f'  NOT NULL , 
      parcelas number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conta_bancaria( 
      id number(10)    NOT NULL , 
      loja number(10)   , 
      fornecedor number(10)   , 
      colaborador number(10)   , 
      cliente number(10)   , 
      parceiro number(10)   , 
      id_referencia_tipo number(10)    NOT NULL , 
      nome varchar  (50)    NOT NULL , 
      agencia number(10)   , 
      numero_conta number(10)   , 
      banco number(10)  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE contato( 
      id number(10)    NOT NULL , 
      pessoa_id number(10)   , 
      email varchar  (30)   , 
      nome varchar  (30)   , 
      telefone varchar  (20)   , 
      obs varchar  (50)   , 
      fornecedor_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE deposito( 
      id number(10)    NOT NULL , 
      nome_deposito varchar  (50)    NOT NULL , 
      loja number(10)   , 
      prod_estoque number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE documento( 
      id number(10)    NOT NULL , 
      descricao varchar  (50)    NOT NULL , 
      dt_registro date    NOT NULL , 
      colaborador_id number(10)    NOT NULL , 
      tipo_documento number(10)    DEFAULT 1  NOT NULL , 
      link_scan_documento varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE escala( 
      id number(10)    NOT NULL , 
      descricao varchar  (50)   , 
      carga_horaria_diaria time    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id number(10)    NOT NULL , 
      nome varchar  (30)   , 
      uf varchar  (2)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_negociacao( 
      id number(10)    NOT NULL , 
      nome varchar  (30)    NOT NULL , 
      cor varchar  (50)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_orcamento( 
      id number(10)    NOT NULL , 
      nome varchar  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado_venda( 
      id number(10)    NOT NULL , 
      nome varchar  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ferias( 
      id number(10)    NOT NULL , 
      dt_inicio date   , 
      dt_fim date   , 
      periodo number(10)    DEFAULT 30  NOT NULL , 
      status_ferias varchar  (30)    DEFAULT 'inapto'  NOT NULL , 
      colaborador_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fornecedor( 
      id number(10)    NOT NULL , 
      razao_social varchar  (100)    NOT NULL , 
      nome_fantasia varchar  (50)   , 
      cnpj varchar  (20)    NOT NULL , 
      observacao varchar  (200)   , 
      fone varchar  (20)   , 
      email varchar  (30)   , 
      cidade number(10)    NOT NULL , 
      rua varchar  (100)   , 
      numero varchar  (30)   , 
      bairro varchar  (100)   , 
      complemento varchar  (100)   , 
      dt_ativacao date    NOT NULL , 
      inscr_estadual varchar  (30)   , 
      possui_ie number(10)    NOT NULL , 
      icms varchar  (20)    NOT NULL , 
      inscr_municipal varchar  (30)   , 
      regime_tributario varchar  (100)    NOT NULL , 
      contato number(10)   , 
      marca number(10)   , 
      vazio1 varchar  (200)   , 
      vazio2 varchar  (200)   , 
      vazio3 varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo( 
      id number(10)    NOT NULL , 
      nome varchar  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo_lojas( 
      id number(10)    NOT NULL , 
      nome varchar  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_negociacao( 
      id number(10)    NOT NULL , 
      negociacao_id number(10)    NOT NULL , 
      tipo_contato_id number(10)    NOT NULL , 
      dt_contato timestamp(0)    NOT NULL , 
      descricao varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Import( 
      id number(10)    NOT NULL , 
      link_sheet varchar  (200)   , 
      nome_tabela varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE loja( 
      id number(10)    NOT NULL , 
      razao_social varchar  (50)    NOT NULL , 
      abreviacao varchar  (5)   , 
      nome_fantasia varchar  (100)   , 
      cnpj varchar  (20)    NOT NULL , 
      grupo number(10)    NOT NULL , 
      observacao varchar  (200)   , 
      fone varchar  (30)   , 
      email varchar  (100)   , 
      cidade number(10)    NOT NULL , 
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
      deposito number(10)   , 
      tipo_emissao number(10)   , 
      lat binary_double   , 
      lon binary_double   , 
      unidade number(10)   , 
      idEmpresa varchar  (50)   , 
      csc_producao varchar  (50)   , 
      id_csc_producao varchar  (50)   , 
      serie_nf_producao number(10)   , 
      seq_nf_producao number(10)   , 
      csc_homologacao varchar  (50)   , 
      id_csc_homologacao varchar  (50)   , 
      serie_nf_homologacao number(10)   , 
      seq_nf_homologacao number(10)   , 
      senha_certificado varchar  (50)   , 
      funcionario number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE marca( 
      id number(10)    NOT NULL , 
      marca varchar  (100)    NOT NULL , 
      fornecedor_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mes( 
      id number(10)    NOT NULL , 
      loja number(10)   , 
      descricao varchar  (100)   , 
      mes number(10)    NOT NULL , 
      ano number(10)    NOT NULL , 
      qtd_dias_uteis number(10)    NOT NULL , 
      cidade number(10)   , 
      valor_passagem binary_double    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mestre_variavel( 
      id number(10)    NOT NULL , 
      tipo varchar  (30)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE motivo_transferencia_colaborador( 
      id number(10)    NOT NULL , 
      motivo varchar  (100)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE natureza( 
      id number(10)    NOT NULL , 
      nome varchar  (100)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Ncm( 
      id number(10)    NOT NULL , 
      n_ncm varchar  (50)   , 
      cest number(10)   , 
      id_woo_ncm number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao( 
      id number(10)    NOT NULL , 
      cliente_id number(10)    NOT NULL , 
      vendedor_id number(10)    NOT NULL , 
      origem_negociacao_id number(10)    NOT NULL , 
      tipo_negociacao_id number(10)    NOT NULL , 
      estado_negociacao_id number(10)    NOT NULL , 
      system_unit_id number(10)    NOT NULL , 
      dt_inicio_negociacao timestamp(0)    NOT NULL , 
      dt_fim_negociacao timestamp(0)   , 
      descricao varchar  (200)    NOT NULL , 
      obs varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao_produto( 
      id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      negociacao_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nfce_request_alt( 
      id number(10)    NOT NULL , 
      ambienteEmissao varchar  (30)   , 
      informacoesAdicionais varchar  (200)   , 
      presencaConsumidor varchar  (20)   , 
      numVenda varchar  (30)    NOT NULL , 
      status varchar  (20)   , 
      n_nfce number(10)   , 
      link_cupom varchar  (1000)   , 
      id_loja number(10)   , 
      retorno_nfce number(10)   , 
      venda_id number(10)   , 
      dt_nfce date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento( 
      id number(10)    NOT NULL , 
      cliente_id number(10)    NOT NULL , 
      vendedor_id number(10)    NOT NULL , 
      estado_orcamento_id number(10)    NOT NULL , 
      system_unit_id number(10)    NOT NULL , 
      dt_orcamento timestamp(0)    NOT NULL , 
      obs varchar  (200)   , 
      frete binary_double   , 
      valor_total binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_item( 
      id number(10)    NOT NULL , 
      orcamento_id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      quantidade binary_double    NOT NULL , 
      valor binary_double    NOT NULL , 
      desconto binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE origem_negociacao( 
      id number(10)    NOT NULL , 
      nome varchar  (50)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE parcelas_conta( 
      id number(10)    NOT NULL , 
      conta_origem number(10)    NOT NULL , 
      loja_id number(10)    NOT NULL , 
      fornecedor_id number(10)   , 
      valor binary_double    NOT NULL , 
      forma_pagamento varchar  (100)    NOT NULL , 
      conta_bancaria_loja number(10)   , 
      conta_bancaria_fornecedor number(10)   , 
      vencimento date    NOT NULL , 
      quitada char  (1)    DEFAULT 'f'  NOT NULL , 
      id_parcela_mestre number(10)   , 
      tipo_parcela varchar  (30)    DEFAULT 'parcela'  NOT NULL , 
      link_comprovante varchar  (200)   , 
      obs varchar  (200)   , 
      vazio1 varchar  (50)   , 
      vazio2 varchar  (50)   , 
      vazio3 varchar  (50)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa( 
      id number(10)    NOT NULL , 
      system_user_id number(10)   , 
      tipo_pessoa number(10)    DEFAULT 1  NOT NULL , 
      nome varchar  (50)    NOT NULL , 
      documento varchar  (100)   , 
      obs varchar  (200)   , 
      fone varchar  (20)   , 
      email varchar  (50)   , 
      cidade_id number(10)    NOT NULL , 
      endereco varchar  (400)    DEFAULT 'null' , 
      estado_id number(10)   , 
      cep varchar  (20)   , 
      dt_ativacao date    NOT NULL , 
      dt_desativacao date   , 
      id_cliente_pdv2 number(10)   , 
      id_cliente_pdv1 number(10)   , 
      colaborador_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_grupo( 
      id number(10)    NOT NULL , 
      grupo_id number(10)    NOT NULL , 
      pessoa_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE Preco( 
      id number(10)    NOT NULL , 
      preco_venda binary_double    NOT NULL , 
      preco_custo binary_double    NOT NULL , 
      id_produto number(10)    NOT NULL , 
      id_tabela number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE prod_estoque( 
      id number(10)    NOT NULL , 
      quantidade number(10)    NOT NULL , 
      qtd_min number(10)   , 
      qtd_max number(10)   , 
      id_deposito number(10)    NOT NULL , 
      id_produto number(10)    NOT NULL , 
      produto_marca number(10)   , 
      produto_referencia varchar  (30)   , 
      produto_sku varchar  (100)   , 
      produto_nome varchar  (200)   , 
      produto_nome_variacao varchar  (50)   , 
      produto_fornecedor number(10)   , 
      produto_categoria number(10)   , 
      produto_cod_barras varchar  (13)   , 
      curva varchar  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produto( 
      id number(10)    NOT NULL , 
      unidade_id number(10)   , 
      categoria_produto_id number(10)    NOT NULL , 
      fornecedor_id number(10)   , 
      descricao varchar  (60)   , 
      desc_variacao varchar  (50)   , 
      dt_cadastro date    NOT NULL , 
      SKU varchar  (20)    NOT NULL , 
      cod_barras varchar  (20)   , 
      obs varchar  (20)   , 
      id_externo number(10)   , 
      estoque number(10)   , 
      tabela_preco number(10)   , 
      preco number(10)   , 
      qtd_max number(10)   , 
      marca number(10)   , 
      situacao_prod number(10)   , 
      referencia varchar  (30)   , 
      tipo_cadastro number(10)   , 
      deposito number(10)   , 
      id_familia number(10)   , 
      mestre_variavel number(10)    NOT NULL , 
      qtd_min number(10)   , 
      valor_custo binary_double   , 
      valor_venda binary_double   , 
      sit_tribut varchar  (20)   , 
      qtd number(10)   , 
      ncm varchar  (20)   , 
      cest varchar  (30)   , 
      id_externo_promocao number(10)   , 
      origem varchar  (100)   , 
      link_site varchar  (100)   , 
      status varchar  (50)    DEFAULT 'Correto'  NOT NULL , 
      vazio1 varchar  (200)   , 
      vazio2 varchar  (200)   , 
      vazio3 varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE prospeccao( 
      id number(10)    NOT NULL , 
      vendedor_id number(10)    NOT NULL , 
      cliente_id number(10)    NOT NULL , 
      system_unit_id number(10)    NOT NULL , 
      horario_inicial timestamp(0)    NOT NULL , 
      horario_final timestamp(0)    NOT NULL , 
      titulo varchar  (30)   , 
      cor varchar  (30)   , 
      observacao varchar  (200)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE salario( 
      id number(10)    NOT NULL , 
      valor binary_double    NOT NULL , 
      decimo_terceiro binary_double   , 
      bonificacao_valor binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE situacao_prod( 
      id number(10)    NOT NULL , 
      situacao_prod varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE subparcelas_parcela( 
      id number(10)    NOT NULL , 
      conta_origem number(10)    NOT NULL , 
      loja_id number(10)    NOT NULL , 
      fornecedor_id number(10)   , 
      valor binary_double    NOT NULL , 
      forma_pagamento varchar  (100)    NOT NULL , 
      conta_bancaria_loja number(10)   , 
      conta_bancaria_fornecedor number(10)   , 
      vencimento date    NOT NULL , 
      quitada char  (1)    NOT NULL , 
      id_parcela_mestre number(10)   , 
      tipo_parcela varchar  (30)    NOT NULL , 
      link_comprovante varchar  (200)   , 
      obs varchar  (200)   , 
      vazio1 varchar  (50)   , 
      vazio2 varchar  (50)   , 
      vazio3 varchar  (50)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tabela_preco( 
      id number(10)    NOT NULL , 
      id_preco number(10)   , 
      nome_tabela_preco varchar  (30)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_cadastro_prod( 
      id number(10)    NOT NULL , 
      descricao varchar  (200)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta( 
      id number(10)    NOT NULL , 
      nome varchar  (200)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta_bancaria( 
      id number(10)    NOT NULL , 
      conta_bancaria varchar  (30)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_contato( 
      id number(10)    NOT NULL , 
      nome varchar  (30)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_documento( 
      id number(10)    NOT NULL , 
      tipo varchar  (30)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_negociacao( 
      id number(10)    NOT NULL , 
      nome varchar  (30)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE transferencia_colaborador( 
      id number(10)    NOT NULL , 
      colaborador_id number(10)    NOT NULL , 
      loja_origem number(10)    NOT NULL , 
      loja_destino number(10)    NOT NULL , 
      dt_transferencia date    NOT NULL , 
      motivo_transferencia number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE transferencia_etq( 
      id number(10)    NOT NULL , 
      deposito_rec number(10)    NOT NULL , 
      deposito_env number(10)    NOT NULL , 
      estoque_id number(10)    NOT NULL , 
      quantidade number(10)    NOT NULL , 
      dt_registro date    NOT NULL , 
      usuario number(10)   , 
      id_transferencia number(10)   , 
      id_produto number(10)   , 
      tipo_transferencia varchar  (20)    DEFAULT 'transferencia'  NOT NULL , 
      saldo_posterior number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE unidade( 
      id number(10)    NOT NULL , 
      nome varchar  (4)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vale_transporte( 
      id number(10)    NOT NULL , 
      valor binary_double   , 
      quantidade number(10)   , 
      colaborador_id number(10)    NOT NULL , 
      dias_uteis number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_alt( 
      id number(10)    NOT NULL , 
      n_venda varchar  (30)    NOT NULL , 
      id_interno varchar  (20)    NOT NULL , 
      cliente_id number(10)   , 
      status varchar  (20)   , 
      vendedor_id number(10)   , 
      estado_venda_id number(10)   , 
      system_unit_id number(10)   , 
      dt_venda timestamp(0)    NOT NULL , 
      obs varchar  (400)   , 
      valor_total binary_double   , 
      total_desconto binary_double   , 
      loja number(10)    NOT NULL , 
      id_venda number(10)   , 
      variavel_duplicidade varchar  (600)   , 
      forma_pagamento varchar  (30)   , 
      caixa varchar  (30)   , 
      func_caixa varchar  (50)   , 
      fiscal varchar  (1)   , 
      total_produtos binary_double   , 
      total_pagamentos binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_item_alt( 
      id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      deposito number(10)   , 
      name varchar  (200)   , 
      venda_id number(10)    NOT NULL , 
      quantidade number(10)    NOT NULL , 
      valor_unitario binary_double    NOT NULL , 
      valor_desconto binary_double   , 
      valor_total binary_double   , 
      SKU varchar  (50)   , 
      loja_id number(10)   , 
      dt_venda date    NOT NULL , 
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
 CREATE SEQUENCE advertencia_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER advertencia_id_seq_tr 

BEFORE INSERT ON advertencia FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT advertencia_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE aso_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER aso_id_seq_tr 

BEFORE INSERT ON aso FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT aso_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE atestado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER atestado_id_seq_tr 

BEFORE INSERT ON atestado FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT atestado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE banco_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER banco_id_seq_tr 

BEFORE INSERT ON banco FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT banco_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cargo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cargo_id_seq_tr 

BEFORE INSERT ON cargo FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT cargo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE categoria_produto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER categoria_produto_id_seq_tr 

BEFORE INSERT ON categoria_produto FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT categoria_produto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE Cest_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER Cest_id_seq_tr 

BEFORE INSERT ON Cest FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT Cest_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ChatAPi_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ChatAPi_id_seq_tr 

BEFORE INSERT ON ChatAPi FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT ChatAPi_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cidade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cidade_id_seq_tr 

BEFORE INSERT ON cidade FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT cidade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE colaborador_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER colaborador_id_seq_tr 

BEFORE INSERT ON colaborador FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT colaborador_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE compra_funcionario_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER compra_funcionario_id_seq_tr 

BEFORE INSERT ON compra_funcionario FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT compra_funcionario_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE conf_etiqu_produto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER conf_etiqu_produto_id_seq_tr 

BEFORE INSERT ON conf_etiqu_produto FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT conf_etiqu_produto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE conta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER conta_id_seq_tr 

BEFORE INSERT ON conta FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT conta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE conta_bancaria_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER conta_bancaria_id_seq_tr 

BEFORE INSERT ON conta_bancaria FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT conta_bancaria_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE contato_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER contato_id_seq_tr 

BEFORE INSERT ON contato FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT contato_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE deposito_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER deposito_id_seq_tr 

BEFORE INSERT ON deposito FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT deposito_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE documento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER documento_id_seq_tr 

BEFORE INSERT ON documento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT documento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE escala_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER escala_id_seq_tr 

BEFORE INSERT ON escala FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT escala_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE estado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER estado_id_seq_tr 

BEFORE INSERT ON estado FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT estado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE estado_negociacao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER estado_negociacao_id_seq_tr 

BEFORE INSERT ON estado_negociacao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT estado_negociacao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE estado_orcamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER estado_orcamento_id_seq_tr 

BEFORE INSERT ON estado_orcamento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT estado_orcamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE estado_venda_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER estado_venda_id_seq_tr 

BEFORE INSERT ON estado_venda FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT estado_venda_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ferias_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ferias_id_seq_tr 

BEFORE INSERT ON ferias FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT ferias_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE fornecedor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER fornecedor_id_seq_tr 

BEFORE INSERT ON fornecedor FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT fornecedor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE grupo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER grupo_id_seq_tr 

BEFORE INSERT ON grupo FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT grupo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE grupo_lojas_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER grupo_lojas_id_seq_tr 

BEFORE INSERT ON grupo_lojas FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT grupo_lojas_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE historico_negociacao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER historico_negociacao_id_seq_tr 

BEFORE INSERT ON historico_negociacao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT historico_negociacao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE Import_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER Import_id_seq_tr 

BEFORE INSERT ON Import FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT Import_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE loja_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER loja_id_seq_tr 

BEFORE INSERT ON loja FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT loja_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE marca_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER marca_id_seq_tr 

BEFORE INSERT ON marca FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT marca_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE mes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER mes_id_seq_tr 

BEFORE INSERT ON mes FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT mes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE mestre_variavel_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER mestre_variavel_id_seq_tr 

BEFORE INSERT ON mestre_variavel FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT mestre_variavel_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE motivo_transferencia_colaborador_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER motivo_transferencia_colaborador_id_seq_tr 

BEFORE INSERT ON motivo_transferencia_colaborador FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT motivo_transferencia_colaborador_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE natureza_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER natureza_id_seq_tr 

BEFORE INSERT ON natureza FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT natureza_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE Ncm_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER Ncm_id_seq_tr 

BEFORE INSERT ON Ncm FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT Ncm_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE negociacao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER negociacao_id_seq_tr 

BEFORE INSERT ON negociacao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT negociacao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE negociacao_produto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER negociacao_produto_id_seq_tr 

BEFORE INSERT ON negociacao_produto FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT negociacao_produto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE nfce_request_alt_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER nfce_request_alt_id_seq_tr 

BEFORE INSERT ON nfce_request_alt FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT nfce_request_alt_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE orcamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER orcamento_id_seq_tr 

BEFORE INSERT ON orcamento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT orcamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE orcamento_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER orcamento_item_id_seq_tr 

BEFORE INSERT ON orcamento_item FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT orcamento_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE origem_negociacao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER origem_negociacao_id_seq_tr 

BEFORE INSERT ON origem_negociacao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT origem_negociacao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE parcelas_conta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER parcelas_conta_id_seq_tr 

BEFORE INSERT ON parcelas_conta FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT parcelas_conta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pessoa_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pessoa_id_seq_tr 

BEFORE INSERT ON pessoa FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT pessoa_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pessoa_grupo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pessoa_grupo_id_seq_tr 

BEFORE INSERT ON pessoa_grupo FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT pessoa_grupo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE Preco_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER Preco_id_seq_tr 

BEFORE INSERT ON Preco FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT Preco_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE prod_estoque_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER prod_estoque_id_seq_tr 

BEFORE INSERT ON prod_estoque FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT prod_estoque_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE produto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER produto_id_seq_tr 

BEFORE INSERT ON produto FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT produto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE prospeccao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER prospeccao_id_seq_tr 

BEFORE INSERT ON prospeccao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT prospeccao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE salario_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER salario_id_seq_tr 

BEFORE INSERT ON salario FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT salario_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE situacao_prod_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER situacao_prod_id_seq_tr 

BEFORE INSERT ON situacao_prod FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT situacao_prod_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE subparcelas_parcela_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER subparcelas_parcela_id_seq_tr 

BEFORE INSERT ON subparcelas_parcela FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT subparcelas_parcela_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tabela_preco_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tabela_preco_id_seq_tr 

BEFORE INSERT ON tabela_preco FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT tabela_preco_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_cadastro_prod_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_cadastro_prod_id_seq_tr 

BEFORE INSERT ON tipo_cadastro_prod FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT tipo_cadastro_prod_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_conta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_conta_id_seq_tr 

BEFORE INSERT ON tipo_conta FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT tipo_conta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_conta_bancaria_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_conta_bancaria_id_seq_tr 

BEFORE INSERT ON tipo_conta_bancaria FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT tipo_conta_bancaria_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_contato_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_contato_id_seq_tr 

BEFORE INSERT ON tipo_contato FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT tipo_contato_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_documento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_documento_id_seq_tr 

BEFORE INSERT ON tipo_documento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT tipo_documento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_negociacao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_negociacao_id_seq_tr 

BEFORE INSERT ON tipo_negociacao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT tipo_negociacao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE transferencia_colaborador_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER transferencia_colaborador_id_seq_tr 

BEFORE INSERT ON transferencia_colaborador FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT transferencia_colaborador_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE transferencia_etq_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER transferencia_etq_id_seq_tr 

BEFORE INSERT ON transferencia_etq FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT transferencia_etq_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE unidade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER unidade_id_seq_tr 

BEFORE INSERT ON unidade FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT unidade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE vale_transporte_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER vale_transporte_id_seq_tr 

BEFORE INSERT ON vale_transporte FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT vale_transporte_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE venda_alt_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER venda_alt_id_seq_tr 

BEFORE INSERT ON venda_alt FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT venda_alt_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE venda_item_alt_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER venda_item_alt_id_seq_tr 

BEFORE INSERT ON venda_item_alt FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT venda_item_alt_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
  
