# Sistema Administrativo - O Recomeço

## Visão Geral

O sistema administrativo permite gerenciar completamente o jogo "O Recomeço", incluindo:

- **Gerenciamento de Eventos**: Criar, editar, visualizar e excluir eventos
- **Configuração de Partidas**: Selecionar personagens e eventos para cada partida
- **Visualização de Estatísticas**: Acompanhar dados do sistema

## Estrutura de Arquivos

```
Adm/
├── index.php                 # Home do admin (dashboard principal)
├── gerenciarEventos.php      # Lista e gerencia eventos
├── cadastrarEvento.php       # Formulário para criar eventos
├── editarEvento.php          # Formulário para editar eventos
├── excluirEvento.php         # Confirmação de exclusão
├── configurarPartida.php    # Seleção de personagens e eventos
├── salvarConfiguracao.php    # Salva configuração da partida
└── visualizarPartida.php    # Visualiza e inicia partida configurada
```

## Funcionalidades

### 1. Dashboard Principal (`index.php`)
- **Estatísticas do sistema**: Total de eventos, eventos positivos/negativos
- **Navegação rápida**: Acesso a todas as funcionalidades
- **Design responsivo**: Interface moderna e intuitiva

### 2. Gerenciamento de Eventos (`gerenciarEventos.php`)
- **Listagem completa**: Todos os eventos com filtros
- **Busca avançada**: Por nome, dificuldade e tipo
- **Ações rápidas**: Editar e excluir eventos
- **Paginação**: Suporte para grandes quantidades de eventos

### 3. Cadastro de Eventos (`cadastrarEvento.php`)
- **Formulário completo**: Todos os campos necessários
- **Validação**: Campos obrigatórios e tipos de dados
- **Preview em tempo real**: Visualização do evento enquanto digita
- **Feedback visual**: Mensagens de sucesso/erro

### 4. Configuração de Partidas (`configurarPartida.php`)
- **Seleção de personagens**: Entre 2 e 4 personagens
- **Seleção de eventos**: Por tipo e dificuldade
- **Validação**: Garante configuração válida
- **Interface intuitiva**: Cards clicáveis com feedback visual

### 5. Visualização de Partidas (`visualizarPartida.php`)
- **Resumo da configuração**: Personagens e eventos selecionados
- **Estatísticas**: Contadores e informações da partida
- **Ações**: Iniciar partida ou reconfigurar
- **Integração**: Link direto para o tabuleiro

## Integração com o Sistema Existente

### Banco de Dados
- Utiliza a mesma conexão (`../banco/conexao.php`)
- Trabalha com as tabelas existentes (`tbevento`, `tbEventoPersonagem`)
- Mantém compatibilidade com o sistema atual

### Sessões
- Usa sessões PHP para armazenar configurações temporárias
- Dados persistem entre páginas do admin
- Limpeza automática ao reconfigurar

### Navegação
- Integração com o sistema de login existente
- Botões de voltar para facilitar navegação
- Links para o tabuleiro do jogo

## Características Técnicas

### Design
- **Bootstrap 5**: Framework CSS moderno
- **Gradientes**: Visual atrativo e profissional
- **Responsivo**: Funciona em desktop e mobile
- **Animações**: Transições suaves e feedback visual

### Segurança
- **Validação de dados**: Sanitização de inputs
- **Prepared statements**: Proteção contra SQL injection
- **Verificação de sessão**: Controle de acesso
- **Confirmações**: Para ações destrutivas

### Usabilidade
- **Interface intuitiva**: Fácil de usar
- **Feedback visual**: Confirmações e mensagens
- **Navegação clara**: Botões e links bem posicionados
- **Validação em tempo real**: Prevenção de erros

## Como Usar

### 1. Acessar o Admin
```
http://localhost/O_Recomeco/admin.php
```
ou
```
http://localhost/O_Recomeco/Adm/index.php
```

### 2. Gerenciar Eventos
1. Clique em "Gerenciar Eventos"
2. Use os filtros para encontrar eventos específicos
3. Clique em "Editar" ou "Excluir" para modificar
4. Use "Adicionar Novo Evento" para criar eventos

### 3. Configurar Partida
1. Clique em "Configurar Partida"
2. Selecione entre 2 e 4 personagens
3. Escolha os tipos de eventos desejados
4. Clique em "Salvar Configuração"

### 4. Iniciar Partida
1. Clique em "Iniciar Partida"
2. Revise a configuração
3. Clique em "Iniciar Partida" para começar o jogo

## Requisitos

- **PHP 7.4+**: Para funcionalidades modernas
- **MySQL/MariaDB**: Banco de dados
- **Bootstrap 5**: Framework CSS
- **Sessões PHP**: Para armazenar configurações

## Manutenção

### Backup
- Faça backup regular do banco de dados
- Mantenha cópias dos arquivos de configuração

### Atualizações
- Teste em ambiente de desenvolvimento primeiro
- Mantenha compatibilidade com o sistema existente
- Documente mudanças importantes

### Monitoramento
- Verifique logs de erro do PHP
- Monitore performance do banco de dados
- Acompanhe uso das funcionalidades

## Suporte

Para dúvidas ou problemas:
1. Verifique os logs de erro
2. Confirme a configuração do banco de dados
3. Teste em ambiente limpo
4. Consulte a documentação do sistema principal
