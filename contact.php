<?php require_once('includes/functions.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('includes/head.php'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./plugin/jquery.validate.js"></script>
    <script src="./js/form-validation.js"></script>
</head>

<body>
<?php require_once('includes/navbar.php'); ?>
  <!-- two column layout -->
  <!-- first container -->
  <section class="container">
    <img src="assets/images/background.jpg" alt="first background" class="layoutImage">
    <section class="contactText">
      <h1 class="centerElement">Contact</h1>
      <form action="mailto:LIFE@localcouncil.com" name="registration">
        <label for="address">Home address</label>
        <input type="text" name="address" id="address" placeholder="Enter your address">
        <label for="email">Email</label>
        <input type="email"name="email" id="email" placeholder="xxx@xxx.xxx">
        <label for="Phone">Phone number</label>
        <input type="tel" name="Phone" id="Phone" placeholder="04xxxxxxxx">
        <label for="Enquiry">Enquiry</label>
        <textarea type="text" rows="8"wrap="hard" name="Enquiry" id="Enquiry" placeholder="Enter your enquiry"></textarea>
        <input type="submit">
      </form>
    </section>
  </section>
  <!-- Footer -->
  <footer>
      <?php require_once('includes/footer.php'); ?>
  </footer>

</body>

</html>