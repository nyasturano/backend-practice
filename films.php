<?php

    header('Content-Type: text/html; charset=UTF-8');

    include('utils.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // add film
        if (isset($_POST['add'])) {
            $stmt = $db->prepare("INSERT INTO films (title, director, year) 
                VALUES (:title, :director, :year)");

            $stmt->bindParam(':title', $_POST['title']);
            $stmt->bindParam(':director', $_POST['director']);
            $stmt->bindParam(':year', $_POST['year']);
            $stmt->execute();

        } else if (isset($_POST['update'])) {
            // update film
            $stmt = $db->prepare("UPDATE films SET title = ?, director = ?, year = ? WHERE id = ?");
            $stmt->execute([$_POST['title'], $_POST['director'], $_POST['year'], $_POST['id']]);
        } else if (isset($_POST['delete'])) {
            // delete film

            $stmt = $db->prepare("SELECT * FROM orders WHERE film_id=?");
            $stmt->execute([$_POST['id']]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                $stmt = $db->prepare("DELETE FROM films WHERE id=?");
                $stmt->execute([$_POST['id']]);
            }
        }

        header('Location: films.php');  
    }


    $stmt = $db->prepare("SELECT * FROM films");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.png">

    <title>Фильмотека</title>
</head>
<body>

    <!-- navbar -->
    <nav>
        <a class="active" href="films.php"><b>ФИЛЬМЫ</b></a>
        <a href="customers.php"><b>КЛИЕНТЫ</b></a>
        <a href="librarians.php"><b>СОТРУДНИКИ</b></a>
        <a href="orders.php"><b>ЖУРНАЛ</b></a>
    </nav>

    <!-- add film -->

    <div class="editor">
        <form method="POST" action="">
            <div class="form-group">
                <label for="film-title"><b>Название</b></label>
                <input type="text" class="form-control" name="title" id="film-title">
            </div>
    
            <div class="form-group">
                <label for="film-director"><b>Режиссер</b></label>
                <input type="text" class="form-control" name="director" id="film-director">
            </div>
    
            <div class="form-group">
                <label for="film-year"><b>Год выпуска</b></label>
                <input type="text" class="form-control" name="year" id="film-year">
            </div>
            <button name="add" value="add" type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>

    <!-- all films -->

    <div class="table-list">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Режиссер</th>
                    <th scope="col">Год выпуска</th>
                    <th scope="col">Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($result as $res):?>
                <tr>
                    <th scope="row"><div class="cell"><?= $res["id"]?></div></th>
                    <form method="POST" action="">
                        <input value="<?= $res["id"]?>" type="hidden" name="id">
                        <td><div class="cell"><input type="text" class="form-control" name="title" value="<?= $res["title"]?>"></div></td>
                        <td><div class="cell"><input type="text" class="form-control" name="director" value="<?= $res["director"]?>"></div></td>
                        <td><div class="cell"><input type="text" class="form-control" name="year" value="<?= $res["year"]?>"></div></td>
                        <td>
                            <div class="cell actions">
                                <button type="submit" class="btn btn-warning" name="update" value="update">Изменить</button>
                                <button type="submit" class="btn btn-danger" name="delete" value="delete">Удалить</button>
                            </div>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($result)) print('<div style="margin: 10px;">Данные не найдены</div>') ?>
    </div>
    

    
</body>
</html>