<?php

    header('Content-Type: text/html; charset=UTF-8');

    include('utils.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // add customer
        if (isset($_POST['add'])) {
            $stmt = $db->prepare("INSERT INTO customers (name, phone, email) 
                VALUES (:name, :phone, :email)");

            $stmt->bindParam(':name', $_POST['name']);
            $stmt->bindParam(':phone', $_POST['phone']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->execute();

        } else if (isset($_POST['update'])) {
            // update customer
            $stmt = $db->prepare("UPDATE customers SET name = ?, phone = ?, email = ? WHERE id = ?");
            $stmt->execute([$_POST['name'], $_POST['phone'], $_POST['email'], $_POST['id']]);
        } else if (isset($_POST['delete'])) {
            // delete customer
            $stmt = $db->prepare("SELECT * FROM orders WHERE customer_id=?");
            $stmt->execute([$_POST['id']]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                $stmt = $db->prepare("DELETE FROM customers WHERE id=?");
                $stmt->execute([$_POST['id']]);
            }
        }

        header('Location: customers.php');  
    }


    $stmt = $db->prepare("SELECT * FROM customers");
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
        <a href="films.php"><b>ФИЛЬМЫ</b></a>
        <a class="active" href="customers.php"><b>КЛИЕНТЫ</b></a>
        <a href="librarians.php"><b>СОТРУДНИКИ</b></a>
        <a href="orders.php"><b>ЖУРНАЛ</b></a>
    </nav>

    <!-- add customer -->

    <div class="editor">
        <form method="POST" action="">
            <div class="form-group">
                <label for="name"><b>Имя</b></label>
                <input type="text" class="form-control" name="name" id="name">
            </div>
    
            <div class="form-group">
                <label for="phone"><b>Телефонный номер</b></label>
                <input type="tel" class="form-control" name="phone" id="phone">
            </div>
    
            <div class="form-group">
                <label for="email"><b>E-mail</b></label>
                <input type="email" class="form-control" name="email" id="email">
            </div>
            <button name="add" value="add" type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>

    <!-- all customers -->

    <div class="table-list">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Телефонный номер</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($result as $res):?>
                    <tr>
                        <th scope="row"><div class="cell"><?= $res["id"]?></div></th>
                        <form method="POST" action="">
                            <input value="<?= $res["id"]?>" type="hidden" name="id">
                            <td><div class="cell"><input type="text" class="form-control" name="name" value="<?= $res["name"]?>"></div></td>
                            <td><div class="cell"><input type="text" class="form-control" name="phone" value="<?= $res["phone"]?>"></div></td>
                            <td><div class="cell"><input type="text" class="form-control" name="email" value="<?= $res["email"]?>"></div></td>
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