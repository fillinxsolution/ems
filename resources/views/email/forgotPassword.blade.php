<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EmailTemplate</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; padding: 0; max-width: 500px; margin: auto">
<header style="display: flex">
{{--    <img--}}
{{--        src="https://i.ibb.co/wgJnWZN/mail-head.webps"--}}
{{--        alt=""--}}
{{--        style="width: 100%; max-width: 500px; margin: auto"--}}
{{--    />--}}
</header>
<main>
    <div>
        <div>
            <p>Hello</p>
            <p>
                Click Below Link to Reset Your password
            </p>

            <div style="display: flex; justify-content: center">
                <a href="{{$emailbody}}"
                   style="
                background-color: #359200;
                border-radius: 8px;
                color: white;
                margin: auto;
                border: none;
                padding: 10px 24px;
              "
                >
                  Reset Your Password
                </a>
            </div>

        </div>
    </div>
</main>
<!-- footer -->
<footer>
    <div style="text-align: center">
        <h3>Fillinx Solution</h3>
    </div>
</footer>
</body>
</html>
