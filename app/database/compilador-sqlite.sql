PRAGMA foreign_keys=OFF; 

CREATE TABLE curvaABC( 
      id  INTEGER    NOT NULL  , 
      nome_produto varchar  (100)   NOT NULL  , 
      sku varchar  (30)   NOT NULL  , 
      produto_id int   NOT NULL  , 
      deposito_id int   , 
      curva varchar  (1)   NOT NULL  , 
      dtAtualizacao date   , 
      porcentagem double   , 
      valor double   , 
      posicao int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produtoDiario( 
      id  INTEGER    NOT NULL  , 
      nome_produto varchar  (100)   NOT NULL  , 
      produto_id int   NOT NULL  , 
      semanal int   , 
      valor_semanal double   , 
      quinzenal int   , 
      valor_quinzenal double   , 
      mensal int   , 
      valor_mensal double   , 
      dtAtualizacao date   , 
      loja_id int   NOT NULL  , 
      estoque_id int   , 
      deposito_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produtoMensal( 
      id  INTEGER    NOT NULL  , 
      nome_produto varchar  (100)   NOT NULL  , 
      quantidade int   NOT NULL  , 
      valor double   NOT NULL  , 
      mes int  (12)   NOT NULL  , 
      ano int   NOT NULL  , 
      dtAtualizacao date   , 
      loja_id int   NOT NULL  , 
      produto_id int   NOT NULL  , 
      estoque_id int   , 
      deposito_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
 
  
