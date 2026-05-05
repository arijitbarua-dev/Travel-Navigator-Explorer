<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">

<div class="bg-white p-8 rounded-xl shadow w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">Sign Up</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <input type="text" name="name" placeholder="Name"
            class="w-full mb-3 p-2 border rounded" required>

        <input type="email" name="email" placeholder="Email"
            class="w-full mb-3 p-2 border rounded" required>
            

        <input type="password" name="password" placeholder="Password"
            class="w-full mb-4 p-2 border rounded" required>

        <button class="w-full bg-green-600 text-white py-2 rounded">
            Register
        </button>
    </form>

    <p class="mt-4 text-center">
        Already have an account?
        <a href="{{ route('login') }}" class="text-blue-600">Login</a>
    </p>
</div>

</body>
</html>