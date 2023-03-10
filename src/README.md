<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

<p align="center">
<img src="https://i.ibb.co/xjKVdhD/image.png" alt="Logo Neo">
<br>
<span style="font-size: 24px; color: #8951f7; font-family: 'Lobster', sans-serif"><i> Keep IT Simple.</i></span>
</p>

## Основное
- **Модуль** - это компонент для плагинов и тем не имеющий html и sql папок
- **Плагин** - компонент, ускоряющий разработку тем и зависимый от модулей
- **Тема** - компонент, в котором ведется вся разработка приложения, зависит от модулей и плагинов

## Архитектура компонента
- **app** - тут хранится вся логика компонента
- **html** - тут html, css, js, картинки и прочее
- **sql** - файлы sql на создание таблиц в БД

## Архитектура движка
- **core** - ядро движка, где находится загрузчик, контейнер и модули
- **plugins** - плагины для ускорения разработки 
- **themes** - темы, вот тут и ведется вся разработка
- **vendor** - пакеты для composer

## Ядро
Ядро движка это точно такой же компонент, как и любой другой.

## Neo CMD
Для вызова командного процессора Neo нужно написать ***php neo help***

## История создания
Добро пожаловать в Neo - небольшую оболочку для языка PHP. Это - не фреймворк, как может показаться на первый взгляд.
Neo - вещь куда более простая, и таковой стремиться быть всегда. Идея этого движка у автора зародилась в момент,
когда ему потребовалось создать простенький интернет-магазин по продаже гаджетов, и ему не понравилось сложность
современных фреймворков. Варианты CMS в духе WordPress / OpenCart / Magento даже не рассматривались, поскольку
WP не адаптирован под интернет-магазины, OpenCart крайне устаревший код, а Magento избыточно громоздкая. В общем
в голову не пришло ничего иного, кроме как написание собственного велосипеда в погоне за простотой, чем автор и занялся.
На выходе получился замечательный компонентный движок, части которого можно использовать даже за пределами Neo! 