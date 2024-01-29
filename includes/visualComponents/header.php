<header>
    <a href="login">Login</a>
    <a href="mainPage">Main Page</a>
    
    <?php
    if (isset($_SESSION['uid'])) {
        echo "<a href='myAccount'>My account</a>";
        echo "<a href='logout'>Logout</a>";
    }
    ?>
    
</header>