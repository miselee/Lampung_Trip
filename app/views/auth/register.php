<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

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

                <?php if (isset($_SESSION['success'])): ?>

                <p class="success">
                        <?= $_SESSION['success']; ?>
                </p>

                    <?php unset($_SESSION['success']); ?>

                <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>auth/prosesregister">

                <label>Nama</label>

                <input type="text" name="nama" placeholder="Enter your name" required>

                <label>Email</label>

                <input type="email" name="email" placeholder="Enter your email" required>

                <label>Password</label>

                <input type="password" id="password" name="password" placeholder="Create password" required>

                <label>Confirm Password</label>

                <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirm password"
                    required>

                <button type="submit">
                    SIGN UP
                </button>

                <div class="divider">
                    or
                </div>

                <p class="SignUp">

                    Already have an account?

                    <a href="<?= BASE_URL ?>auth/login">
                        Login
                    </a>

                </p>

            </form>

        </div>

    </div>

    <script src="<?= BASE_URL ?>assets/js/global.js"></script>

</body>

</html>