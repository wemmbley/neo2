<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Montserrat:wght@100;200;300;400&display=swap" rel="stylesheet">
    <style>
        /*****************************************
         * MAIN
         *****************************************/
        * {
            font-family: 'Montserrat', sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        ul, li {
            text-decoration: none;
        }
        body {
            background-color: #F3F3F3;
        }

        /*****************************************
         * TOOLTIP
         *****************************************/
        .tooltip {
            position: relative;
            display: inline-block;
        }
        .tooltip .tooltip-text {
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            width: 100%;
            height: 66px;
            background-color: #9F9EE0;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            bottom: 0;
            left: 60px;
            margin-left: -60px;
        }
        .tooltip:hover .tooltip-text {
            visibility: visible;
        }

        /*****************************************
         * HEADER
         *****************************************/
        header {
            width: 100%;
            height: 148px;
            background-color: #5452B0;
            color: white;
            display: flex;
            font-size: 36px;
            align-items: center;
            padding-left: 100px;
            font-weight: 400;
        }
        header p {
            margin-left: 20px;
        }
        .errorText {
            font-size: 36px;
            color: #5452B0;
            width: 900px;
            margin: 80px auto;
            text-align: center;
            hyphens: auto;
        }

        /*****************************************
         * ERROR FILE
         *****************************************/
        h2 {
            font-size: 36px;
            font-weight: 200;
            margin-left: 40px;
            margin-bottom: 20px;
        }
        ul > li {
            display: flex;
        }
        .file > ul > li > .number {
            width: 54px;
            height: 48px;
            background-color: #9F9EE0;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .file > ul > li:last-child > .number,
        .file > ul > li:first-child > .number {
            height: 16px;
        }
        .file > ul {
            width: 1142px;
            margin: auto;
            background-color: white;
            margin-bottom: 80px;
        }
        .file > ul > li > .line {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-left: 40px;
        }
        .file > ul > li:nth-child(4) {
            background-color: #E8E8FF;
            width: 1142px;
            justify-content: start !important;
        }

        /*****************************************
         * STACKTRACE
         *****************************************/
        .stacktrace > ul {
            width: 1142px;
            margin: auto;
        }
        .stacktrace > ul > li > .number {
            width: 66px;
            height: 66px;
            background-color: #9F9EE0;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stacktrace > ul > li > .file {
            display: flex;
            align-items: center;
            background-color: white;
            padding-left: 40px;
            width: 100%;
        }
        .stacktrace > ul > li:first-child .file {
            background-color: #E8E8FF;
        }
        .stacktrace > ul > li:not(:first-child) {
            width: 1112px;
            margin-left: 30px;
        }

        /*****************************************
         * FOOTER
         *****************************************/
        footer {
            width: 100%;
            height: 148px;
            background-color: #9F9EE0;
            margin-top: 80px;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        footer > .telegram {
            display: flex;
            font-size: 36px;
            font-weight: 200;
        }
        footer > .telegram > a {
            text-decoration: none;
            color: white;
        }
        footer > .telegram > a:hover {
            text-decoration: underline;
        }
        footer > .telegram > svg {
            margin-right: 20px;
        }
    </style>
    <title>NEO Debug</title>
    <link rel="shortcut icon" type="image/png" href="/core/html/error.png">
</head>
<body>

<header>
    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M41.184 3H18.816L3 18.816V41.184L18.816 57H41.184L57 41.184V18.816L41.184 3ZM33 45H27V39H33V45ZM33 33H27V15H33V33Z" fill="#F8F8F8"/>
    </svg>
    <p>Bro, we have some troubles...</p>
</header>

<p class="errorText"><?= $params['message'] ?></p>

<section class="file">
    <h2><?= $params['file'] ?></h2>
    <ul>
        <li>
            <div class="number"></div>
        </li>
        <?php foreach ($params['fileLines'] as $line => $text): ?>
            <li>
                <div class="number"><?= $line ?></div>
                <div class="line"><?= $text ?></div>
            </li>
        <?php endforeach; ?>
        <li>
            <div class="number"></div>
        </li>
    </ul>
</section>

<section class="stacktrace">
    <h2>Stacktrace</h2>
    <ul>
        <?php foreach($params['trace'] as $line => $trace): ?>
            <?php if(isset($trace['class'])): ?>
                <?php $tooltip = $trace['class'] . $trace['type'] . $trace['function'] . '()' ?>
            <?php else: ?>
                <?php $tooltip = $trace['function'] . '()' ?>
            <?php endif; ?>

            <li>
                <div class="number">#<?= $line ?></div>
                <div class="file selected tooltip">
                    <span class="tooltip-text"><?= $tooltip ?></span>
                    <?= $trace['file'] ?> (<?= $trace['line'] ?>)
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<footer>
    <div class="telegram">
        <svg width="35" height="40" viewBox="0 0 35 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M34.8984 7.70317L29.6172 32.6094C29.2188 34.3672 28.1797 34.8047 26.7031 33.9766L18.6563 28.0469L14.7734 31.7813C14.3438 32.211 13.9844 32.5704 13.1563 32.5704L13.7344 24.375L28.6484 10.8985C29.2969 10.3204 28.5078 10 27.6406 10.5782L9.20313 22.1875L1.26563 19.7032C-0.460932 19.1641 -0.492182 17.9766 1.62501 17.1485L32.6719 5.18755C34.1094 4.64848 35.3672 5.50786 34.8984 7.70317Z" fill="white"/>
        </svg>
        <a href="telegram:neo_chat">Telegram chat for help</a>
    </div>
</footer>
</body>
</html>