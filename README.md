# Overview:

O objetivo deste teste é demonstrar um pouco da capacidade com o Framework Symfony e com testes.

Como é apenas um teste, não reflete a arquitetura que eu normalmente empregaria em um ambiente de verdade.

Normalmente costumo separar backend de frontend em projetos separados. A arquitetura que mais tenho utilizado é Symfony como RestAPI e frontend eu defino com a equipe de frontend, gosto de Angular ou React.

### Componentes utilizados para o teste:

*   **php 7**
*   **Symfony 3.4.1** por ser a LTS utilizando symfony-flex
*   **ramsey/uuid-doctrine** - Gerador de UUID para doctrine
*   **sensiolabs/security-checker** - Para checagem de pacotes
*   **symfony/asset** - Para manipulação de assets
*   **symfony/form** - Para formulários* (apenas pra demonstrar o uso, geralmente não gosto de usar)
*   **symfony/maker** - Gerador de códigos
*   **symfony/twig-bundle** - Estrutura de templates
*   **symfony/webpack-encore-pack** - Estrutura para uso de webpack
*   **symfony/orm-pack** - Estrutura para uso de Mapeamento Objeto Relacional
*   **symfony/orm-pack** - Estrutura para uso de Mapeamento Objeto Relacional
*   [DEV] **doctrine/doctrine-fixtures-bundle** - Para geração de fixtures de teste

### Instruções para rodar o projeto:

##### Rodar o Composer
>`composer install`
> - [x] Caso não tenha o composer instalado instale [aqui](https://getcomposer.org/download/).

##### Testes
>   Após instalação do composer rode os testes: 
>`php bin/phpunit`

##### Arquivo dotenv
>   Crie o arquivo .env na raiz com dados para banco de dados.
> Um arquivo `.env.dist` está disponível na raiz do projeto para auxiliar.

##### Database
>   Execute o comando para criar a database: 
>`php bin/console doctrine:database:create`
> - [x] Verifique se o seu usurio de banco possui permissões de criação de database, caso não possuo, pule esta etapa e crie o banco com um usuário que possua permissão.

>   Execute o comando para rodar as migrações: 
>`php bin/console doctrine:migrations:migrate`

##### Frontend yarn
>   Execute o comando para instalar as dependências do frontend: 
>`yarn install`
> - [x] Caso não tenha o yarn instalado instale [aqui](https://yarnpkg.com/en/docs/install).

>   Execute o comando para compilar as dependências do frontend: 
>`yarn run encore dev`

##### Servidor Built-in
>   Execute o servidor: 
>`php bin/console server:run`
>Acesse o link exibido no comando.


# Instruções do teste:
[PDF com as instruções](https://github.com/edermariano/codengage-test/blob/master/public/pdf/teste.pdf)


