<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body class="login">

    <div class="container">

        <div class="left-side">

            <h1>LAMPUNG TRIP</h1>

            <h3>
                Discover the beauty of
                Lampung with ease.
            </h3>

            <p>
                Explore amazing destinations,
                find travel information,
                and join exciting open trips
                for unforgettable experiences.
            </p>

        </div>

        <div class="login-box">

            <?php if (isset($_SESSION['error'])): ?>

                <p class="error">
                    <?= $_SESSION['error']; ?>
                </p>

                <?php unset($_SESSION['error']); ?>

            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>auth/proseslogin">

                <label>Email</label>

                <input type="email" name="email" placeholder="Enter your email" required>

                <label>Password</label>

                <input type="password" name="password" placeholder="Enter your password" required>

                <a href="#" class="forgot-password">
                    Forgot Password?
                </a>

                <button type="submit">
                    SIGN IN
                </button>

                <div class="divider">
                    or
                </div>

                <p class="google">
                    Sign in with Google
                </p>

                <p class="SignUp">

                    Are you new?

                    <a href="<?= BASE_URL ?>auth/register">
                        Create an Account
                    </a>

                </p>

            </form>

        </div>

    </div>

    <script src="<?= BASE_URL ?>assets/js/auth.js"></script>

</body>

</html>