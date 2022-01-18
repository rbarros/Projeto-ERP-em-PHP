CREATE TABLE nfce_retorno( 
      id  INT IDENTITY    NOT NULL  , 
      id_externo varchar  (20)   NOT NULL  , 
      tipo varchar  (30)   , 
      status varchar  (50)   , 
      motivoStatus varchar  (200)   , 
      ambienteEmissao varchar  (20)   , 
      enviadaPorEmail varchar  (30)   , 
      dataCriacao date   , 
      dataUltimaAlteracao date   , 
      forcarEmissaoContingencia bit   , 
      numero int   , 
      serie int   , 
      dataEmissao date   , 
      chaveAcesso varchar  (200)   , 
      dataAutorizacao date   , 
      linkDanfe varchar  (200)   , 
      linkDownloadXml varchar  (200)   , 
      lnkConsultaPorChaveAcesso varchar  (200)   , 
      emitidaEmContingencia bit   , 
      empresaId varchar  (50)   , 
      numeroProtocolo varchar  (100)   , 
      digestValue varchar  (30)   , 
      valorTotal float   , 
      informacoesAdicionais varchar  (400)   , 
      qrCode varchar  (400)   , 
 PRIMARY KEY (id)) ; 

 
 ALTER TABLE nfce_retorno ADD UNIQUE (id_externo);
 
  
