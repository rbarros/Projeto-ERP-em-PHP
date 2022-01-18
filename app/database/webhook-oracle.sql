CREATE TABLE nfce_retorno( 
      id number(10)    NOT NULL , 
      id_externo varchar  (20)    NOT NULL , 
      tipo varchar  (30)   , 
      status varchar  (50)   , 
      motivoStatus varchar  (200)   , 
      ambienteEmissao varchar  (20)   , 
      enviadaPorEmail varchar  (30)   , 
      dataCriacao date   , 
      dataUltimaAlteracao date   , 
      forcarEmissaoContingencia char(1)   , 
      numero number(10)   , 
      serie number(10)   , 
      dataEmissao date   , 
      chaveAcesso varchar  (200)   , 
      dataAutorizacao date   , 
      linkDanfe varchar  (200)   , 
      linkDownloadXml varchar  (200)   , 
      lnkConsultaPorChaveAcesso varchar  (200)   , 
      emitidaEmContingencia char(1)   , 
      empresaId varchar  (50)   , 
      numeroProtocolo varchar  (100)   , 
      digestValue varchar  (30)   , 
      valorTotal binary_double   , 
      informacoesAdicionais varchar  (400)   , 
      qrCode varchar  (400)   , 
 PRIMARY KEY (id)) ; 

 
 ALTER TABLE nfce_retorno ADD UNIQUE (id_externo);
  CREATE SEQUENCE nfce_retorno_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER nfce_retorno_id_seq_tr 

BEFORE INSERT ON nfce_retorno FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT nfce_retorno_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
  
