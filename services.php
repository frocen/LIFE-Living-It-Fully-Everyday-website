<?php require_once('includes/authorise.php'); ?>
<?php $services = getServices(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('includes/head.php'); ?>
</head>

<body>
<?php require_once('includes/navbar.php'); ?>
      <!-- two column layout -->
<!-- first container -->
  <section class="container">
    <img src="assets/images/services.jpg" alt="first background" class="layoutImage">
    <article class="rightText">
      <h1>Try it</h1>
      <h2>Feel free to try any services</h2>
    </article>
  </section>

<!-- Four image links -->
<div class="containerFourImage">
    <!-- create my own designed icon depends on database service -->
    <?php foreach($services as $service) { ?>
        <div class="imgbox">
            <!-- habits page is special -->
            <?php if ($service['service_id'] == 4) { ?>
            <a href="habits.php">
            <?php } else { ?>
            <a href="service.php?id=<?php echo $service['service_id']; ?>">
            <?php } ?>
                <img src="<?php echo $service['image_path']; ?>"/>
                <div class="mask">
                    <summary><?php echo $service['name']; ?></summary>
                </div>
            </a>
        </div>
    <?php } ?>
</div>

  <!-- Footer -->
  <footer>
      <?php require_once('includes/footer.php'); ?>
  </footer>

</body>
</html>