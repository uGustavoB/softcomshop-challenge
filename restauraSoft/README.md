
# 🍽️ RestauraSoft

## 📖 Descrição
O **RestauraSoft** é o projeto final da formação, desenvolvido com o objetivo de consolidar e demonstrar, na prática, os conhecimentos adquiridos ao longo do curso. O sistema simula a gestão de um restaurante, abordando desde o controle de mesas, categorias, pedidos e pratos até autenticação de usuários e organização de uma API REST robusta.

Este projeto foi pensado para se aproximar de um cenário real de mercado, envolvendo decisões técnicas, resolução de erros, configuração de ambiente, uso de containers e aplicação de boas práticas de desenvolvimento.

## 🎯 Objetivo do projeto

-   Consolidar os conhecimentos adquiridos durante a formação
-   Desenvolver uma aplicação completa com back-end estruturado
-   Aplicar conceitos de arquitetura, organização de código e boas práticas
-   Trabalhar com Docker, Nginx e ambiente Linux (WSL)
-   Implementar autenticação JWT e proteção de rotas
-   Resolver problemas reais de configuração, dependências e ambiente

## 🛠️ Tecnologias utilizadas

-   PHP 7.3
-   Laravel
-   MySQL
-   JWT (jwt-auth)
-   Docker & Docker Compose
-   Nginx
-   WSL (Windows Subsystem for Linux)
-   Git & GitHub

## 💻 Como rodar o projeto:

 1. Clonar repositório
```bash
git clone https://github.com/uGustavoB/softcomshop-challenge.git
cd softcomshop-challenge/restauraSoft
```

2. Instalar dependências do Laravel
```bash
cd back-end
docker73 composer install
```

3. Configurar ambiente do Laravel
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
docker73 php artisan migrate
```

4. Acessar a API:
```bash
http://localhost:73/api
```

