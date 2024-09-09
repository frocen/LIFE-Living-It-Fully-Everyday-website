<?php require_once('includes/functions.php'); ?>
<?php
$errors = [];
//if click login button
if (isset($_POST['login'])) {
    //check password
    $errors = loginUser($_POST);

    //login password is correct
    if (count($errors) === 0)
        //go to services page
        redirect('services.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('includes/head.php'); ?>
</head>
<body>
<?php require_once('includes/navbar.php'); ?>
<section class="container">
    <img src="assets/images/background.jpg" alt="first background" class="layoutImage">
    <section class="contactText">
        <h1 class="centerElement">Login</h1>
        <form method="post">
            <label for="emailAddress">Email</label>
            <input type="text" id="emailAddress" name="emailAddress"
                <?php displayValue($_POST, 'emailAddress'); ?> />
            <?php displayError($errors, 'emailAddress'); ?>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" />
            <?php displayError($errors, 'password'); ?>
            <button type="submit" class="centerElement" name="login" value="login">Login</button>
        </form>
    </section>
</section>
<footer>
    <?php require_once('includes/footer.php'); ?>
</footer>
</body>
</html>
