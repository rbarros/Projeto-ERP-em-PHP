SELECT setval('nfce_id_seq', coalesce(max(id),0) + 1, false) FROM nfce;
SELECT setval('nfce_item_id_seq', coalesce(max(id),0) + 1, false) FROM nfce_item;
SELECT setval('nfce_pagamento_id_seq', coalesce(max(id),0) + 1, false) FROM nfce_pagamento;
SELECT setval('venda_id_seq', coalesce(max(id),0) + 1, false) FROM venda;
SELECT setval('venda_item_id_seq', coalesce(max(id),0) + 1, false) FROM venda_item;
SELECT setval('venda_pagamento_id_seq', coalesce(max(id),0) + 1, false) FROM venda_pagamento;