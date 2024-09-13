<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## photo-gallery
criando_uma_galeria_de_fotos_utilizando_Laravel_com_opções_de_upload_e_delete_de_arquivos_com_integração_com_serviço_de_nuvem

## criando o projeto Laravel
para criar é necessario ter o php e o 'composer' instalado e utilizar o seguinte comando:
`composer create-project laravel/laravel nome-do-projeto`

## quando clonar o repositório do github
copiar o arquivo `.env.example` e renomear para `.env` criar um banco de dados e enseri-lo em 'DB_DATABASE' do arquivo

## alterar configuarações do laravel
o laravel por padrão deixa uns arquivos que não são necessarios ser mechidos escondidos, mas para publica-los utilize o seguinte comando:
`php artisan config:publish`

após esse comando ele mostrará qual arquivo deseja publicar para alterar

## criar ou alterar as tabelas no banco de dados
`php artisan migrate`

## executar o projeto criado
`php artisan serve`

## criar components
componente são partes reutilizáveis de código que encapsulam determinada lógica ou apresentação de interface do usuário
`php artisan make:component Image`