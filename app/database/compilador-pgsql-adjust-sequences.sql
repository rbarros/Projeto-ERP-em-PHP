SELECT setval('curvaABC_id_seq', coalesce(max(id),0) + 1, false) FROM curvaABC;
SELECT setval('produtoDiario_id_seq', coalesce(max(id),0) + 1, false) FROM produtoDiario;
SELECT setval('produtoMensal_id_seq', coalesce(max(id),0) + 1, false) FROM produtoMensal;