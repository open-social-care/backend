# Social Care App

O Social Care é um projeto pensando em melhorar o dia a dia de trabalho de assistente social, o sistema auxilia o cadastro de sujeitos e atendimentos realizados.

Projeto desenvolvido para atividades da UTFPR - Guarapuava, Curso de Sistemas para internet.

## Ambiente de desenvolvimento e ferramentas

Esse repositório se trata da parte back end do projeto, desenvolvido com as ferramentas:

- [Framework Laravel (versão 10)](https://laravel.com/).
- [Laravel Sail (versão 10)](https://laravel.com/docs/10.x/installation#laravel-and-docker)
- Testado em Pop OS 22.04 LTS

## Executando o projeto em ambiente de desenvolvimento
1. Clone o projeto 

    `$ git clone https://github.com/projeto-conselho-da-comunidade/social-care-app-back.git`



2. No terminal, inicie o sail

        `./vendor/bin/sail up`

### Observações:
- Utilize o camando a seguir para parar o ambiente docker sail: `./vendor/bin/sail stop`.


- Recomendado a utilização de um alias no shell para facilitar o comando `./vendor/bin/sail`, para isso [configure conforme documentação](https://laravel.com/docs/10.x/sail#configuring-a-shell-alias). 

## License

[MIT license](https://opensource.org/licenses/MIT).
