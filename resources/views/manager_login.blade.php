<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Login</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            width: 320px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .login-box input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }

        .login-box input:focus {
            border-color: #2a5298;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background: #2a5298;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
            transition: 0.3s;
        }

        .login-box button:hover {
            background: #1e3c72;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="login-box">
    <h2>Manager Login</h2>

    <form method="POST" action="{{ route('manager.login.submit') }}">
        @csrf

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif
    </form>
</div>

</body>
</html>