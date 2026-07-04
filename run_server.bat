@echo off
setlocal
:: Forzar a PHP a usar nuestro php.ini local para evitar conflictos con instalaciones globales
set "PHPRC=%~dp0..\php"
..\php\php.exe launcher.php
