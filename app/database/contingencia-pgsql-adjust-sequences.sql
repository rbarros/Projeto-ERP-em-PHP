SELECT setval('motivo_contingencia_id_seq', coalesce(max(id),0) + 1, false) FROM motivo_contingencia;
SELECT setval('retorno_error_id_seq', coalesce(max(id),0) + 1, false) FROM retorno_error;
SELECT setval('vendaError_id_seq', coalesce(max(id),0) + 1, false) FROM vendaError;