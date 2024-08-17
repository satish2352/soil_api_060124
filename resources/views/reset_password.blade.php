<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <form method="POST" action="{{ url('resetPassword') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        
        <label for="password">New Password:</label>
        <input type="password" name="password" required>
        
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" required>
        
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
