-- Script para criar tabela de configurações de partidas
-- Execute este script no seu banco de dados MySQL/MariaDB

CREATE TABLE IF NOT EXISTS tbConfiguracaoPartida (
    idConfiguracao INT AUTO_INCREMENT PRIMARY KEY,
    nomeConfiguracao VARCHAR(100) NOT NULL,
    personagens JSON NOT NULL,
    eventosPersonagem JSON NOT NULL,
    temasSelecionados JSON NOT NULL,
    eventosSelecionados JSON NOT NULL,
    eventosCasas JSON NOT NULL,
    dataCriacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dataModificacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
);

-- Adicionar coluna temaAula na tabela tbevento se não existir
ALTER TABLE tbevento ADD COLUMN IF NOT EXISTS temaAula VARCHAR(100) DEFAULT NULL AFTER descricaoEvento;

-- Remover coluna dificuldadeEvento se existir (opcional)
-- ALTER TABLE tbevento DROP COLUMN IF EXISTS dificuldadeEvento;

-- Atualizar eventos existentes com temas padrão (opcional)
UPDATE tbevento SET temaAula = 'SUS' WHERE nomeEvento LIKE '%SUS%' OR descricaoEvento LIKE '%SUS%';
UPDATE tbevento SET temaAula = 'DESIGUALDADE SOCIAL' WHERE nomeEvento LIKE '%desigualdade%' OR descricaoEvento LIKE '%desigualdade%';
UPDATE tbevento SET temaAula = 'EMPREGO E TRABALHO' WHERE nomeEvento LIKE '%emprego%' OR descricaoEvento LIKE '%trabalho%';
UPDATE tbevento SET temaAula = 'EDUCAÇÃO' WHERE nomeEvento LIKE '%educação%' OR descricaoEvento LIKE '%escola%';
UPDATE tbevento SET temaAula = 'MORADIA' WHERE nomeEvento LIKE '%moradia%' OR descricaoEvento LIKE '%casa%';
UPDATE tbevento SET temaAula = 'TRANSPORTE PÚBLICO' WHERE nomeEvento LIKE '%transporte%' OR descricaoEvento LIKE '%ônibus%';

-- Definir tema padrão para eventos sem tema
UPDATE tbevento SET temaAula = 'GERAL' WHERE temaAula IS NULL OR temaAula = '';

