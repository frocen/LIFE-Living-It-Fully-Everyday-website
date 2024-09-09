<?php require_once('includes/functions.php'); ?>
<?php
$errors = [];
//register button clicked
if (isset($_POST['register'])) {
    $errors = registerUser($_POST);

    if (count($errors) === 0)
        //validation all good, go services page
        redirect('services.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('includes/head.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            const age = $("#Age");
            const ageChange = function () {
                $("#age-display").text("Age: " + age.val());
            }
            ageChange();
            age.mousemove(ageChange).change(ageChange);
        });
    </script>
</head>

<body>
<?php require_once('includes/navbar.php'); ?>

<!-- two column layout -->
<!-- first container -->
<section class="container">
    <img src="assets/images/background.jpg" alt="first background" class="layoutImage">
    <section class="registration">

        <h1 class="centerElement">registration</h1>
        <form method="post">
            <section class="registration-container">
                <section class="registration-block">
                    <input type="text" id="firstName" name="firstName"
                           placeholder="First name" <?php displayValue($_POST, 'firstName'); ?> />
                    <?php displayError($errors, 'firstname'); ?>
                    <input type="text" id="lastName" name="lastName"
                           placeholder="Last Name" <?php displayValue($_POST, 'lastName'); ?> />
                    <?php displayError($errors, 'lastName'); ?>
                    <label for="Age" id="ageLabel">
                        <span id="age-display"></span>
                    </label>
                    <input type="range" id="Age" name="Age" min="1" max="120"
                           value="16"<?php displayValue($_POST, 'Age'); ?>
                        <?php if (!isset($_POST['Age'])) echo 'value="1"'; ?> />
                    <?php displayError($errors, 'Age'); ?>
                </section>
                <section class="registration-block">
                    <input type="text" id="emailAddress" name="emailAddress" placeholder="Email Address"
                           onCopy="return false" onCut="return false"<?php displayValue($_POST, 'emailAddress'); ?> />
                    <?php displayError($errors, 'emailAddress'); ?>
                    <input type="text" id="confirmEmail" name="confirmEmail" placeholder="Confirm Email"
                           onpaste="return false;">
                    <?php displayError($errors, 'confirmEmail'); ?>
                    <input type="text" id="phoneNumber" name="phoneNumber"
                           placeholder="+61 4xx xxx xxx" <?php displayValue($_POST, 'phoneNumber'); ?> />
                    <?php displayError($errors, 'phoneNumber'); ?>
                </section>
            </section>

            <section class="radioContainer">
                <section class="radio-block">
                    <h3>Student status:</h3>
                    <input type="radio" name="studentStatus" id="studentStatusYes"
                           value="true" <?php displayChecked($_POST, 'studentStatus', 'true'); ?> />
                    <label for="studentStatusYes">yes</label>
                    <input type="radio" name="studentStatus" id="studentStatusNo"
                           value="false"<?php displayChecked($_POST, 'studentStatus', 'false'); ?> />
                    <label for="studentStatusNo">No</label>
                    <?php displayError($errors, 'studentStatus'); ?>
                </section>

                <section class="radio-block">
                    <h3>Employment status:</h3>
                    <input type="radio" name="employmentStatus" id="employmentStatusYes"
                           value="true" <?php displayChecked($_POST, 'employmentStatus', 'true'); ?> />
                    <label for="employmentStatusYes">yes</label>
                    <input type="radio" name="employmentStatus" id="employmentStatusNo"
                           value="false" <?php displayChecked($_POST, 'employmentStatus', 'false'); ?> />
                    <label for="employmentStatusNo">No</label>
                    <?php displayError($errors, 'employmentStatus'); ?>
                </section>
            </section>
            <section class="registration-container">
                <section class="registration-block">
                    <input type="password" id="password" name="password" placeholder="Password"
                           onCopy="return false" onCut="return false"/>
                    <?php displayError($errors, 'password'); ?>
                </section>
                <section class="registration-block">
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password"
                           onpaste="return false;"/>
                    <?php displayError($errors, 'confirmPassword'); ?>
                </section>
            </section>
            <button type="submit" class="centerElement" name="register" value="register">REGISTER</button>
        </form>
        <h2 id="fee" class="centerElement"></h2>
    </section>
</section>
<!-- Footer -->
<footer>
    <?php require_once('includes/footer.php'); ?>
</footer>

</body>

</html>