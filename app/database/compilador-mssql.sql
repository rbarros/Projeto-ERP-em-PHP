CREATE TABLE curvaABC( 
      id  INT IDENTITY    NOT NULL  , 
      nome_produto varchar  (100)   NOT NULL  , 
      sku varchar  (30)   NOT NULL  , 
      produto_id int   NOT NULL  , 
      deposito_id int   , 
      curva varchar  (1)   NOT NULL  , 
      dtAtualizacao date   , 
      porcentagem float   , 
      valor float   , 
      posicao int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produtoDiario( 
      id  INT IDENTITY    NOT NULL  , 
      nome_produto varchar  (100)   NOT NULL  , 
      produto_id int   NOT NULL  , 
      semanal int   , 
      valor_semanal float   , 
      quinzenal int   , 
      valor_quinzenal float   , 
      mensal int   , 
      valor_mensal float   , 
      dtAtualizacao date   , 
      loja_id int   NOT NULL  , 
      estoque_id int   , 
      deposito_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produtoMensal( 
      id  INT IDENTITY    NOT NULL  , 
      nome_produto varchar  (100)   NOT NULL  , 
      quantidade int   NOT NULL  , 
      valor float   NOT NULL  , 
      mes int  (12)   NOT NULL  , 
      ano int   NOT NULL  , 
      dtAtualizacao date   , 
      loja_id int   NOT NULL  , 
      produto_id int   NOT NULL  , 
      estoque_id int   , 
      deposito_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
 
  
