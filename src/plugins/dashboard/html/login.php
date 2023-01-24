<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="plugins/dashboard/html/assets/fonts/Gilroy/gilroy.css">
    <style>
        /*toast*/
        #snackbar {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-image: linear-gradient(to right,#EABFFF,#98F9FF);
            color: #000000;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            top: 30px;
            font-size: 17px;
        }
        #snackbar.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }
        @-webkit-keyframes fadein {
            from {top: 0; opacity: 0;}
            to {top: 30px; opacity: 1;}
        }
        @keyframes fadein {
            from {top: 0; opacity: 0;}
            to {top: 30px; opacity: 1;}
        }
        @-webkit-keyframes fadeout {
            from {top: 30px; opacity: 1;}
            to {top: 0; opacity: 0;}
        }
        @keyframes fadeout {
            from {top: 30px; opacity: 1;}
            to {top: 0; opacity: 0;}
        }

        /*main*/
        body {
            background-color: #310F52;
            height: 94vh;
            display: flex;
            justify-content: center;
            text-align: center;
            align-items: center;
            font-family: 'Gilroy', sans-serif;
        }
        .figure {
            z-index: -1;
            position: absolute;
        }
        .conus {
            left: 1%;
            top: 5%;
        }
        .cube {
            left: 50%;
            top: 37%;
        }
        .circle {
            right: -10%;
            top: -30%;
        }
        h4 {
            font-size: 34px;
            color: white;
            font-weight: lighter;
        }
        input {
            background-color: unset;
            color: #98F9FF;
            font-size: 16px;
            border: unset;
            width: 220px;
            height: 30px;
            padding-left: 8px;
            font-weight: 100;
        }
        input:focus-visible {
            outline: unset;
            font-weight: 100;
        }
        input::placeholder {
            color: #98F9FF;
            font-weight: 100;
        }
        .form {
            width: 350px;
            height: 400px;
        }
        .text {
            font-weight: lighter;
            color: #EABFFF;
            position: relative;
            left: 70px;
            top: 10px;
            cursor: pointer;
        }
        .text:hover {
            color: #98F9FF;
        }
        .icon {
            position: relative;
            top: 4px;
        }
        .button {
            position: relative;
            left: 44px;
            top: 40px;
            cursor: pointer;
            background-image: url("plugins/dashboard/html/img/button.svg");
            width: 166px;
            height: 38px;
        }
        .button:hover {
            filter: hue-rotate(90deg);
        }
    </style>
    <title>Document</title>
</head>
<body>
    <img class="figure conus" src="plugins/dashboard/html/img/conus.svg" alt="conus">
    <img class="figure cube" src="plugins/dashboard/html/img/square.svg" alt="sqaure">
    <img class="figure circle" src="plugins/dashboard/html/img/circle.svg" alt="circle">
    <img class="figure card" src="plugins/dashboard/html/img/card.png" alt="card">

    <div class="form">
        <form action="/auth/login" method="post" id="authForm">
            <h4>Вход</h4>
            <?php \Neo\Core\App\Modules\Protector\Protector::csrf(); ?>

            <!-- INPUT USERNAME -->
            <img class="icon" src="plugins/dashboard/html/img/user.svg" alt="user">
            <input type="text" name="login" placeholder="Логин"><br>
            <img class="line" src="plugins/dashboard/html/img/line.svg" alt="line"><br><br>

            <!-- INPUT PASSWORD -->
            <img class="icon" src="plugins/dashboard/html/img/lock.svg" alt="user">
            <input type="text" name="password" placeholder="Пароль"><br>
            <img class="line" src="plugins/dashboard/html/img/line.svg" alt="line"><br>


            <span class="text">Забыл пароль</span>
            <br>

            <input class="button" type="submit" value="">
        </form>
    </div>

    <div id="snackbar"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function showToast(text) {
            var x = document.getElementById("snackbar");
            x.className = "show";
            x.textContent = text;
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }

        $("#authForm").submit(function(e) {
            e.preventDefault();

            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: "POST",
                url: actionUrl,
                data: form.serialize(),
                success: function(data)
                {
                    location.reload()
                },
                error: function(error) {
                    const errorText = JSON.parse(error.responseText).message

                    if (errorText === 'bad password') {
                        showToast('Неверный пароль!')
                    }
                    else if (errorText === 'too many attempts') {
                        showToast('Слишком много попыток! Попробуйте через час.')
                    }
                    else if (errorText === 'user not found') {
                        showToast('Неверный логин!')
                    }
                    else {
                        showToast(errorText)
                    }
                }
            });

        });
    </script>
</body>
</html>