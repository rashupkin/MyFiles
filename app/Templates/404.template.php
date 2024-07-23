<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <h1>Status code: <?php echo $statusCode ?></h1>
  <p>Message: <?php echo $statusMessage ?? "Not Found" ?></p>
</body>

</html>