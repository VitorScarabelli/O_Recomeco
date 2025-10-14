-- Script para adicionar a coluna temaAula na tabela tbevento
-- Execute este script no seu banco de dados MySQL/MariaDB

ALTER TABLE tbevento ADD COLUMN temaAula VARCHAR(50) DEFAULT NULL AFTER dificuldadeEvento;

-- Atualizar eventos existentes com temas padrão (opcional)
UPDATE tbevento SET temaAula = 'sus' WHERE nomeEvento LIKE '%SUS%' OR descricaoEvento LIKE '%SUS%';
UPDATE tbevento SET temaAula = 'desigualdade' WHERE nomeEvento LIKE '%desigualdade%' OR descricaoEvento LIKE '%desigualdade%';
UPDATE tbevento SET temaAula = 'emprego' WHERE nomeEvento LIKE '%emprego%' OR descricaoEvento LIKE '%trabalho%';
UPDATE tbevento SET temaAula = 'educacao' WHERE nomeEvento LIKE '%educação%' OR descricaoEvento LIKE '%escola%';
UPDATE tbevento SET temaAula = 'moradia' WHERE nomeEvento LIKE '%moradia%' OR descricaoEvento LIKE '%casa%';
UPDATE tbevento SET temaAula = 'transporte' WHERE nomeEvento LIKE '%transporte%' OR descricaoEvento LIKE '%ônibus%';

-- Definir tema padrão para eventos sem tema
UPDATE tbevento SET temaAula = 'sus' WHERE temaAula IS NULL;

-- Adicionar coluna para nomes de jogadores na configuração de partidas (JSON)
-- Estrutura: [{"idPersonagem": 1, "nomeUsuario": "Alice"}, ...]
ALTER TABLE tbConfiguracaoPartida
    ADD COLUMN nomesJogadores LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL
    CHECK (JSON_VALID(`nomesJogadores`));

