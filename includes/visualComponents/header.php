<header>
    <?php
        echo "<a href='mainPage'>Main Page</a>";
        if (isset($_SESSION['uid'])) {
            echo "<a display='none' href='myAccount'><img class='headImg' src='/includes/assets/profile.png'></a>";
        } else {
            echo "<a href='login'>Login</a>";
            
        }
    ?>
</header>