-- Criar Tabela de Usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criar Tabela de Produtos
CREATE TABLE IF NOT EXISTS produtos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    categoria VARCHAR(50),
    estoque INT DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir Produtos Iniciais
INSERT INTO produtos (nome, descricao, preco, imagem, categoria, estoque) VALUES 
('Relógio Quartzo Luxo', 'Design elegante e sofisticado.', 160.00, 'assets/img/Relogio_Quartzo_luxo.JPG', 'Acessórios', 50),
('Camisa Manga Comprida Estilo Icon', 'Conforto e caimento premium.', 159.90, 'assets/img/Camisa_Manga_Comprida_Estilo_Icon.JPG', 'Roupas', 100),
('Camiseta Masculina Versátil Luxo', 'Ideal para o dia a dia.', 120.00, 'assets/img/Camiseta_Masculina_Versatil_luxo.JPG', 'Roupas', 150);