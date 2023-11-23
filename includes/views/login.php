<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="includes/css/style.css">
    <title>LOGIN</title>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <form class="login" action="login" method="POST">
                <div class="messages">
                    <?php if(isset($messages)) {
                        foreach ($messages as $message) {
                            echo $message;
                        }
                    }
                    ?>
                </div>
                <input name="email" type="text" placeholder="email@email.com">
                <input name="password" type="password" placeholder="password">
                <button  type="submit">LOGIN</button>
            </form>

        </div>
    </div>
</body>
