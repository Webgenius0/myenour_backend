<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cover bg-center" style="background-image: url('/path-to-your-background-image.jpg');">

    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-gray-800 bg-opacity-60 p-8 rounded-xl shadow-lg w-96">
            <h2 class="text-white text-2xl text-center font-bold mb-6">Login</h2>

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="text-white">Username</label>
                    <div class="relative">
                        <input type="text" name="username" class="w-full p-3 rounded-lg bg-gray-700 text-white outline-none focus:ring-2 focus:ring-blue-500" placeholder="Username">
                        <span class="absolute right-3 top-4 text-gray-400">
                            🔍
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-white">Password</label>
                    <div class="relative">
                        <input type="password" name="password" class="w-full p-3 rounded-lg bg-gray-700 text-white outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password">
                        <span class="absolute right-3 top-4 text-gray-400">
                            🔒
                        </span>
                    </div>
                </div>

                <div class="flex justify-between items-center text-white mb-4">
                    <div>
                        <input type="checkbox" id="remember" class="mr-2">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="text-blue-400">Forgot password?</a>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition">Login</button>
            </form>

            <p class="text-center text-white mt-4">Don't have an account? <a href="#" class="text-blue-400">Register</a></p>
        </div>
    </div>

</body>
</html>
