<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Picter</title>
  <link rel="stylesheet" href="main.css" />
  <script src="script.js"></script>
</head>

<body>
  <form method="post" action="">
    <div>Изменение контрастности изображения</div>
    <div>
      Контрастность рисунка:
      <input type="range" min="-100" max="100" id="contrast" name="contrastName" value="<?php if (isset($_POST['contrastName'])) {
                                                                                          echo $_POST["contrastName"];
                                                                                        } else {
                                                                                          echo 0;
                                                                                        } ?>" onchange="document.getElementById('contrast_output').value = this.value;" />
    </div>
    <div>
      Положение рисунка:
      <output class="form-rating__output" id="contrast_output"><?php if (isset($_POST['contrastName'])) {
                                                                  echo $_POST["contrastName"];
                                                                } else {
                                                                  echo 0;
                                                                } ?>
      </output>
      <input type="submit" class="button_submit" value="СТАРТ СТАРТ" />
    </div>
  </form>
  <!-- <img src="Saransk2018.jpg"> -->
  <img src="picter.php?param=<?php echo $_POST['contrastName']; ?>" id="pic" width="700" height="480">
</body>

</html>