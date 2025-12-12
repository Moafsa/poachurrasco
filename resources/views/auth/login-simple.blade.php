<!DOCTYPE html>
<html>
<head>
    <title>Login - Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .container { max-width: 400px; margin: 0 auto; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background: #dc2626; color: white; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login Test Page</h1>
        <p>If you can see this, the view rendering works!</p>
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="{{ route('home') }}">Back to home</a></p>
    </div>
</body>
</html>



















