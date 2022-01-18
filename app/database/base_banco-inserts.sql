INSERT INTO banco (id,cod,nome) VALUES (1,001,'Banco do Brasil'); 

INSERT INTO cargo (id,cargo,salario,descricao,escala) VALUES (1,'vendedor',null,'vendedor de loja',1); 

INSERT INTO cargo (id,cargo,salario,descricao,escala) VALUES (2,'Administrativo',null,'Auxiliar administrativo',1); 

INSERT INTO cargo (id,cargo,salario,descricao,escala) VALUES (3,'Apoio',null,'',1); 

INSERT INTO cidade (id,nome,estado_id) VALUES (1,'CABO FRIO',1); 

INSERT INTO conf_etiqu_produto (id,leftMargin,topMargin,labelWidth,labelHeight,spaceBetween,rowsPerPage,colsPerPage,fontSize,barcodeHeight,imageMargin,barcodeMethod,nome) VALUES (1,15,0,65,20,7,1,3,15,9,0,'EAN13','Configuração padrão'); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (1,'DEPOSITO MISS AMORA',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (2,'DEPOSITO CD',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (3,'DEPOSITO CF AV',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (4,'DEPOSITO SGO PTG SHOPP',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (5,'DEPOSITO SPA I',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (6,'DEPOSITO RIOS I',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (7,'DEPOSITO SPA II',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (9,'DEPOSITO CF SHOPP',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (10,'DEPOSITO CF LSA',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (11,'DEPOSITO SGO SHOPP BR',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (12,'DEPOSITO AMA AVI',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (13,'DEPOSITO AMA AVII',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (15,'DEPOSITO SGO CAL',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (17,'DEPOSITO AMA OPM',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (18,'DEPOSITO CF CAL',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (19,'DEPOSITO NI PEDREIRA SHOPP',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (20,'DEPOSITO MDR SHOPP',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (21,'DEPOSITO LOJA MATRIZ',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (22,'DEPOSITO CAXIAS SHOPP',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (23,'DEPOSITO E-COMMERCE',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (24,'DEPOSITO NI TOP SHOPP',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (25,'DEPOSITO RIOS II',null,null); 

INSERT INTO deposito (id,nome_deposito,loja,prod_estoque) VALUES (26,'DEPOSITO ALFENAS',null,null); 

INSERT INTO escala (id,descricao,carga_horaria_diaria) VALUES (1,'Escala padrão','8'); 

INSERT INTO estado (id,nome,uf) VALUES (1,'RIO DE JANEIRO','RJ'); 

INSERT INTO estado_venda (id,nome) VALUES (1,'Nova'); 

INSERT INTO estado_venda (id,nome) VALUES (2,'Finalizada'); 

INSERT INTO estado_venda (id,nome) VALUES (3,'Cancelada'); 

INSERT INTO grupo (id,nome) VALUES (2,'Clientes'); 

INSERT INTO grupo (id,nome) VALUES (4,'Funcionários'); 

INSERT INTO grupo_lojas (id,nome) VALUES (1,'GRUPO DE LOJAS A'); 

INSERT INTO grupo_lojas (id,nome) VALUES (2,'GRUPO DE LOJAS B'); 

INSERT INTO marca (id,marca,fornecedor_id) VALUES (1,'MARCA EXEMPLO',1); 

INSERT INTO mestre_variavel (id,tipo) VALUES (1,'mestre'); 

INSERT INTO mestre_variavel (id,tipo) VALUES (2,'variavel'); 

INSERT INTO natureza (id,nome) VALUES (1,'Vendas'); 

INSERT INTO natureza (id,nome) VALUES (2,'Serviços'); 

INSERT INTO natureza (id,nome) VALUES (3,'Locações'); 

INSERT INTO pessoa_grupo (id,grupo_id,pessoa_id) VALUES (1,2,1); 

INSERT INTO pessoa_grupo (id,grupo_id,pessoa_id) VALUES (2,5,2); 

INSERT INTO situacao_prod (id,situacao_prod) VALUES (1,'ATIVO'); 

INSERT INTO situacao_prod (id,situacao_prod) VALUES (2,'INATIVO'); 

INSERT INTO tabela_preco (id,id_preco,nome_tabela_preco) VALUES (1,null,'TABELA PADRAO'); 

INSERT INTO tipo_cadastro_prod (id,descricao) VALUES (1,'NOVO'); 

INSERT INTO tipo_cadastro_prod (id,descricao) VALUES (2,'DETALHADO'); 

INSERT INTO tipo_cadastro_prod (id,descricao) VALUES (3,'ANTIGO'); 

INSERT INTO tipo_conta (id,nome) VALUES (1,'Receber'); 

INSERT INTO tipo_conta (id,nome) VALUES (2,'Pagar'); 

INSERT INTO tipo_conta_bancaria (id,conta_bancaria) VALUES (1,'Loja'); 

INSERT INTO tipo_conta_bancaria (id,conta_bancaria) VALUES (2,'Fornecedor'); 

INSERT INTO tipo_conta_bancaria (id,conta_bancaria) VALUES (3,'Colaborador'); 

INSERT INTO tipo_conta_bancaria (id,conta_bancaria) VALUES (4,'Cliente'); 

INSERT INTO tipo_conta_bancaria (id,conta_bancaria) VALUES (5,'Parceiro'); 

INSERT INTO tipo_documento (id,tipo) VALUES (1,'CPF'); 

INSERT INTO tipo_documento (id,tipo) VALUES (2,'CNH'); 

INSERT INTO tipo_documento (id,tipo) VALUES (3,'CTPS'); 

INSERT INTO tipo_documento (id,tipo) VALUES (4,'Certidão casamento'); 

INSERT INTO tipo_documento (id,tipo) VALUES (5,'Título eleitor'); 

INSERT INTO unidade (id,nome) VALUES (1,'UN'); 
