<?php require_once('includes/authorise.php'); ?>
<?php
$mealList = array();
$email = getLoggedInUser()['email'];
if (!isset($_SESSION['savedMeal'])) {
    $_SESSION['savedMeal'][0] = "";
}
if (isset($_POST['generate'])) {
    $mealList = generate($_POST);
    $_SESSION['savedMeal'] = $mealList;
}

if (isset($_POST['savePlan'])) {
    $j = 1;
    while ($j < count($_SESSION['savedMeal'])) {
        $k = $j + 1;
        recordMeal($email, $_SESSION['savedMeal'][$j], $_SESSION['savedMeal'][$k]);
        $j = $j + 2;
    }
}
if (isset($_POST['delete'])) {
    $_SESSION['savedMeal'][0] = "";
    deleteMealPlan($email);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('includes/head.php'); ?>
</head>

<body>
<?php require_once('includes/navbar.php'); ?>
<!-- Calories calculator -->
<section class="container">
    <img src="assets/images/foods.jpg" alt="first background" class="layoutImage">
    <?php if (!haveUserMeal($email)) { ?>
        <section class="caloriesCalculator">
            <h1 class="centerElement">Meal Planner</h1>
            <form method="post">
                <label for="Calories" id="caloriesLabel">I want to eat 700 Calories</label>
                <input type="range" id="Calories" name="Calories" placeholder="Calories" min="600" max="3000"
                       value="700"
                       onchange="document.getElementById('caloriesLabel').innerHTML ='I want to eat '+this.value +' Calories'">
                <section class="mealPlanContainer">
                    <section class="mealPlan-block">
                        <h3>In meals:</h3>
                        <input type="radio" name="mealNumber" id="3meal" value="3" checked>
                        <label for="3meal">3</label>
                        <input type="radio" name="mealNumber" id="4meal" value="4">
                        <label for="4meal">4</label>
                    </section>
                    <section class="mealPlan-block">
                        <h3>Meal type:</h3>
                        <input type="radio" name="mealType" id="any" value="Any" checked>
                        <label for="any">any</label>
                        <input type="radio" name="mealType" id="Vegetarian" value="Vegetarian">
                        <label for="vegetarian">vegetarian</label>
                        <input type="radio" name="mealType" id="meat" value="Meat">
                        <label for="meat">meat</label>
                    </section>
                </section>
                <div class="twoButton">
                    <button type="submit" class="buttonSG" name="generate">generate</button>
                    <?php if ($_SESSION['savedMeal'][0] != "") { ?>
                        <button type="submit" class="buttonSG" name="savePlan">save</button>
                    <?php } ?>
                </div>
            </form>
        </section>

        <section class="meal" id="showMeals">
            <section class="mealContainer" id="mealList">
                <?php echo $_SESSION['savedMeal'][0]; ?>
            </section>
        </section>
    <?php } else { ?>
        <section class="meal" id="showMeals">
            <section class="mealContainer" id="mealList">
                <section class="meal-block">
                    <?php echo showMealTable($email) ?>
                </section>
            </section>
            <form method="post">
                <button type="submit" class="centerElement" name="delete">delete</button>
            </form>
        </section>
    <?php } ?>

</section>
<!-- second container -->
<section class="container2">
    <img src="assets/images/habitBackground.jpg" alt="long paragraph" class="layoutImage">
    <!-- mid text -->
    <article class="centerText">
        <h1>How healthy habits helps?</h1>
        <h2>when you develop dozens of these habits, you can make a huge difference in your healthy life</h2>
    </article>
    <!-- Yoga -->
    <article class="leftText">
        <h1>Physical Activity</h1>
        <h2>
        <pre>
1. Take 30-minute early morning walks.
2. Take the stairs instead of the elevator.
3. Do housework.
4. Use a treadmill desk.
5. Use a height-adjustable desk.
6. Aim for 10,000 steps a day. Wear a step
-tracking device.
7. Go geocaching. 
8. Go hiking more often.
9. Take a dance break.</pre>
        </h2>
    </article>
    <img src="assets/images/activity.jpg" alt="activity" class="yogaImage">
    <!-- Meditation -->
    <article class="rightText2">
        <h1>Forgiveness</h1>
        <h2>
        <pre>
10. Don't go to sleep angry.
11 Focus on understanding yourself 
instead of blaming others.
12. Live in the present instead of 
being stuck in the past.
13. Do it for yourself and your own 
peace of mind.
14. Remember the times when you were 
forgiven. 
15. Observe, don't judge.</pre>
        </h2>
    </article>
    <img src="assets/images/forgiveness.jpg" alt="Meditation image" class="meditationImage">
    <!-- Stretching -->
    <article class="leftText2">
        <h1>Healthy Eating</h1>
        <h2>
        <pre>
16. Avoid eating when feeling stressed.
17. Use portion-control containers to 
store your meals.
18. Use portion-control plates when eating 
at home.
19. Keep a food diary or journal.
20. Make and drink healthy smoothies.
21. Plan your meals every week.
22. Stick to your grocery list.
23. Limit distractions during meal times.</pre>
        </h2>
    </article>
    <img src="assets/images/healthyEat.jpg" alt="Stretching image" class="stretchingImage">
    <!-- Healthy habits -->
    <article class="rightText3">
        <h1>Adequate Sleep</h1>
        <h2>
        <pre>
24. Avoid caffeine in the afternoon.
25. Avoid heavy meals close to bedtime.
26. Keep your pets out of the bed.
27. Quit smoking.
28. Wear socks.
29. Get spiritual.
30. Keep a sleep log.
31. Learn how to get back to sleep.
</pre>
        </h2>
    </article>
    <img src="assets/images/sleep.jpg" alt="Habits image" class="habitsImage">
</section>
<!-- Footer -->
<footer>
    <?php require_once('includes/footer.php'); ?>
</footer>

</body>

</html>