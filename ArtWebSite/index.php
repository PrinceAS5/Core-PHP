<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artworks</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body,
        html {
            height: 100%;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
    </style>
</head>

<body>

    <div class="container">
        <?php
        if (isset($_SESSION['message'])) :
            echo "<h4>" . $_SESSION['message'];
            unset($_SESSION['message']);
        endif;
        ?>
        <form action="code.php" method="POST" enctype="multipart/form-data" class="row">

            <div class="form-group col-md">
                <input type="file" name="importFile" class="form-control" required>
            </div>

            <label for="artist">Artist</label>
            <div class="form-group col-md">
                <select class="form-control" name="artists">
                    <option value="" selected></option>
                    <option value="Xi_Liu">Xi Liu</option>
                    <option value="Yuan_Li">Yuan Li</option>
                    <option value="Zhong_Huan">Zhong Huan</option>
                </select>
                <?php if (isset($_SESSION['validate'])) : ?>
                    <span style="color: red;">
                        <h5><?php echo $_SESSION['validate']; ?></h5>
                    </span>
                    <?php unset($_SESSION['validate']); ?>
                <?php endif; ?>
            </div>

            <div class="form-group col-md">
                <button type="submit" name="saveExcelData" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>