# Bank

Simples projeto em Laravel 8, feito para simular um Caixa Eletrônico.

## Requisitos

- PHP 8
- Docker e Docker Compose

## Como utilizar

Instalação e execução do projeto localmente:

```bash
cp .env.example .env
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
```

O seeder vai configurar um usuário com email `example@example.com` e senha `password`.

### Documentação de Rotas

Para acessar a documentação sobre as rotas é necessário utilizar a ferramenta do **Insomnia** e importar o arquivo *InsomniaDocs.json* dentro dele, onde vai disponibilizar todas as rotas possíveis e payloads de exemplo.

### Executando os testes

Para executar os testes realizar a execução do comando abaixo:

```bash
./vendor/bin/sail test
```
