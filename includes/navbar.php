<nav>
    <ul>
        <li>
            <a href="index.php">
                <img src="assets/images/logo.png" alt="LIFE logo">
            </a>
        </li>
        <?php if(isUserLoggedIn()) { ?>
        <li class="dropdown">
            <a href="services.php" class="dropbtn">Services </a>
        </li>
        <?php } ?>
        <li><a href="contact.php" class="dropbtn">contact</a></li>
        <!-- if user logged in print name -->
        <?php if(isUserLoggedIn()) { ?>
            <span class="dropbtn" id="welcome">
                Welcome, <?= getLoggedInUser()['first_name']; ?>
            </span>
            <a href="logout.php" class="dropbtn">Logout</a>
        <?php } else { ?>
            <li id="loginButton"><a href="login.php" class="dropbtn">Login</a></li>
            <li><a href="regi.php" class="dropbtn">register</a></li>
        <?php } ?>
    </ul>
</nav>