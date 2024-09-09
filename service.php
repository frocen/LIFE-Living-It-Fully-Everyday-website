<?php require_once('includes/authorise.php'); ?>
<?php
$id = (int)$_GET['id'];
$service = getService($id);

$errors = [];
if (isset($_POST['activity'])) {
    $email = getLoggedInUser()['email'];
    //write information inn database
    $errors = recordActivity($email, $id, $_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('includes/head.php'); ?>
</head>
<body>
<?php require_once('includes/navbar.php'); ?>
<div class="container">
    <img src="<?php echo $service['image_path']; ?>" class="layoutImage">
        <?php // The form below is displayed if type has not been submitted. ?>
        <?php if (!isset($_POST['type'])) { ?>
            <?php $serviceInstructions = getServiceInstructions($id); ?>
            <?php if ($id === 1) { ?>
            <div class="serviceLeftText">
                <h1><?php echo $service['name']; ?></h1>
                <h2>
        <pre>
1. Improves strength, balance and flexibility
2. Helps with back pain relief
3. Ease arthritis symptoms
4. Benefits heart health
5. Relaxes you, to help you sleep better
6. Mean more energy and brighter moods
7. Manage stress
8. Connects you with a supportive
community
9. Promotes better self-care</pre>
                </h2>
            </div>
            <div class="rightText">
                <form method="post">
                    <br>
                    <?php foreach ($serviceInstructions as $serviceInstruction) { ?>
                        <?php $t = $serviceInstruction['service_type']; ?>
                        <input type="radio"
                               id="<?php echo $t; ?>" name="type" value="<?php echo $t; ?>"/>
                        <label for="<?php echo $t; ?>"><?php echo $t; ?></label>
                        <br>
                    <?php } ?>

                    <!-- nothing select will give a error message -->
                    <?php if (isset($_POST['service'])) { ?>
                        <div class='error'>You must select a type.</div>
                    <?php } ?>

                    <br>
                    <button type="submit" name="service">Go</button>
                    <br>
                    <a href="services.php">Back to myServices</a>
                </form>
            </div>
                <?php } elseif($id === 2) { ?>
                <div class="serviceLeftText">
                    <h1><?php echo $service['name']; ?></h1>
                    <h2>
        <pre>
1. Reduces stress
2. Controls anxiety
3. Promotes emotional health
4. Enhances self-awareness
5. Lengthens attention span
6. May reduce age-related memory loss
7. Can generate kindness
8. May help fight addictions
9. Improves sleep
10. Helps control pain</pre>
                    </h2>
                </div>
                <div class="rightText">
                    <form method="post">
                        <br>
                        <?php foreach ($serviceInstructions as $serviceInstruction) { ?>
                            <?php $t = $serviceInstruction['service_type']; ?>
                            <input type="radio"
                                   id="<?php echo $t; ?>" name="type" value="<?php echo $t; ?>"/>
                            <label for="<?php echo $t; ?>"><?php echo $t; ?></label>
                            <br>
                        <?php } ?>
                        <!-- nothing select will give a error message -->
                        <?php if (isset($_POST['service'])) { ?>
                            <div class='error'>You must select a type.</div>
                        <?php } ?>

                        <br>
                        <button type="submit" name="service">Go</button>
                        <br>
                        <a href="services.php">Back to myServices</a>
                    </form>
                </div>
            <?php } elseif($id === 3) { ?>
                <div class="serviceLeftText">
                    <h1><?php echo $service['name']; ?></h1>
                    <h2>
        <pre>
1. Increases your flexibility
2. Increases your range of motion
3. Improves your performance in physical
activities
4. Increases blood flow to your muscles
5. Improves your posture
6. Helps to heal and prevent back pain
7. Is great for stress relief
8. Can calm your mind
9. Helps decrease tension headaches</pre>
                    </h2>
                </div>
                <div class="rightText">
                    <form method="post">
                        <br>
                        <?php foreach ($serviceInstructions as $serviceInstruction) { ?>
                            <?php $t = $serviceInstruction['service_type']; ?>
                            <input type="radio"
                                   id="<?php echo $t; ?>" name="type" value="<?php echo $t; ?>"/>
                            <label for="<?php echo $t; ?>"><?php echo $t; ?></label>
                            <br>
                        <?php } ?>

                        <!-- nothing select will give a error message -->
                        <?php if (isset($_POST['service'])) { ?>
                            <div class='error'>You must select a type.</div>
                        <?php } ?>

                        <br>
                        <button type="submit" name="service">Go</button>
                        <br>
                        <a href="services.php">Back to myServices</a>
                    </form>
                </div>
            <?php } elseif($id === 4) {
                redirect('habits.php');
            } else { ?>
                <div class="rightText">
                    <h1>Under construction!</h1>
                </div>
            <?php } ?>
        <?php } else { ?>
            <?php $serviceInstruction = getServiceInstruction($id, $_POST['type']); ?>
            <div class="serviceLeftText">
                <h2><?php echo $serviceInstruction['service_type']; ?></h2>
                <?php if ($serviceInstruction['service_type']=="Audio") { ?>
                    <audio controls>
                        <source src="<?php echo $serviceInstruction['path']; ?>" type="audio/mpeg">
                    </audio>
                <?php } else { ?>
                    <video width="650" controls>
                        <source src="<?php echo $serviceInstruction['path']; ?>" type="video/mp4">
                    </video>
                <?php } ?>
            </div>
            <?php if (!isset($_POST['activity']) || count($errors) > 0) { ?>
                <div class="rightText">

                    <form method="post">
                        <br>
                        <input type="hidden" name="type" value="<?php echo $_POST['type']; ?>"/>

                        <label for="duration">Duration (minutes)</label>
                        <input type="text" id="duration" name="duration"
                            <?php displayValue($_POST, 'duration'); ?> />
                        <?php displayError($errors, 'duration'); ?><br>

                        <button type="submit" name="activity">Record Activity</button>
                        <br>
                        <a href="">Cancel</a>
                    </form>
                </div>
            <?php } else { ?>
                <!-- submit successful -->
                <h2 class="rightText">
                    You have successfully recorded <?php echo $_POST['duration']; ?>minutes of
                    <?php echo $_POST['type']; ?> <?php echo $service['name'];?>.
                    <br><br>
                    <a href="">More <?php echo $service['name']; ?></a>
                    <a href="services.php">Back to my services</a>
                </h2>
            <?php } ?>
        <?php } ?>

</div>
<footer>
    <?php require_once('includes/footer.php'); ?>
</footer>
</body>
</html>