<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <style type="text/css">
        body {
            background: rgb(240 240 240);
        }

        .Content {
            background: rgb(252 252 252);
            border-radius: 10px/9px;
            padding: 15px 15px;
            width: 50%;
            height: 70%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0px 4px 10px -9px rgb(64 64 64 / 70%);
        }

        .Title {
            font-size: 30px;
            padding: 10px;
            margin: 10px;
        }

        .Description {
            font-size: 20px;
            padding: 10px;
            margin: 10px;
        }

        .Code {
            background: rgb(255 255 255);
            border-radius: 10px/9px;
            font-size: 25px;
            padding: 10px;
            margin: 10px;
            box-shadow: 0px 3px 10px -2px rgb(64 64 64 / 27%);
        }
    </style>
</head>

<body>
    <div class="Content">
        <div class="Title">Ваш код для создания аккаунта в Element</div>
        <div class="Description">Мы используем почту исключительно для подтверждения аккаунта. Если вы потеряете к ней доступ, нечего беспокоиться.</div>
        <div class="Code">{Code}</div>
    </div>
</body>

</html>