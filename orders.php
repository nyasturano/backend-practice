<?php

    header('Content-Type: text/html; charset=UTF-8');

    include('utils.php');


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (!isset($_POST['find'])) {
            // add order
            if (isset($_POST['add'])) {
        
                $stmt = $db->prepare("INSERT INTO orders (film_id, customer_id, librarian_id, date) 
                    VALUES (:film, :customer, :librarian, :date)");
    
                $stmt->bindParam(':film', $_POST['film']);
                $stmt->bindParam(':customer', $_POST['customer']);
                $stmt->bindParam(':librarian', $_POST['librarian']);
                $stmt->bindParam(':date', $_POST['date']);
                $stmt->execute();
    
            } else if (isset($_POST['update'])) {
                // update order
                $stmt = $db->prepare("UPDATE orders SET film_id = ?, customer_id = ?, librarian_id = ?, date = ? WHERE id = ?");
                $stmt->execute([$_POST['film'], $_POST['customer'], $_POST['librarian'], $_POST['date'], $_POST['id']]);
            } else if (isset($_POST['delete'])) {
                // delete order
                $stmt = $db->prepare("DELETE FROM orders WHERE id=?");
                $stmt->execute([$_POST['id']]);
            }
    
            header('Location: orders.php');  
        } else {
            // filter orders
            if ($_POST['customer'] != -1 && $_POST['librarian'] != -1) {
                $stmt = $db->prepare("SELECT * FROM orders WHERE customer_id = ? and librarian_id = ?");
                $stmt->execute([$_POST['customer'], $_POST['librarian']]);
            } else if ($_POST['librarian'] != -1) {
                $stmt = $db->prepare("SELECT * FROM orders WHERE librarian_id = ?");
                $stmt->execute([$_POST['librarian']]);
            } else if ($_POST['customer'] != -1) {
                $stmt = $db->prepare("SELECT * FROM orders WHERE customer_id = ?");
                $stmt->execute([$_POST['customer']]);
            }
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    } else {
        $stmt = $db->prepare("SELECT * FROM orders");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // load all films, librarians and customers

    $stmt = $db->prepare("SELECT * FROM films");
    $stmt->execute();
    $films_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT * FROM customers");
    $stmt->execute();
    $customers_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT * FROM librarians");
    $stmt->execute();
    $librarians_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <a href="customers.php"><b>КЛИЕНТЫ</b></a>
        <a href="librarians.php"><b>СОТРУДНИКИ</b></a>
        <a class="active" href="orders.php"><b>ЖУРНАЛ</b></a>
    </nav>



    <!-- add order -->

    <div class="editor">
        <form method="POST" action="">
            <div class="form-group">
                <label for="film"><b>Фильм</b></label>
                <select name="film" id="film" class="form-select">
                    <?php
                        foreach($films_list as $film) {
                            print("<option value=\"{$film['id']}\">");
                            print("{$film['title']} [{$film['year']}]");
                            print('</option>');
                        }
                    ?>
                </select>
            </div>
    
            <div class="form-group">
                <label for="customer"><b>Клиент</b></label>
                <select name="customer" id="customer" class="form-select">
                    <?php
                        foreach($customers_list as $customer) {
                            print("<option value=\"{$customer['id']}\">");
                            print("{$customer['name']} [{$customer['email']}]");
                            print('</option>');
                        }
                    ?>
                </select>
            </div>
    
            <div class="form-group">
                <label for="librarian"><b>Библиотекарь</b></label>
                <select name="librarian" id="librarian" class="form-select">
                    <?php
                        foreach($librarians_list as $librarian) {
                            print("<option value=\"{$librarian['id']}\">");
                            print("{$librarian['name']}");
                            print('</option>');
                        }
                    ?>
                </select>
                
            </div>

            <div class="form-group">
                <label for="date"><b>Дата выдачи</b></label>
                <input type="date" class="form-control" name="date" value="2023-06-06" id="date">
            </div>

            <button name="add" value="add" type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>


    <!-- filter -->

    <form class="filter" method="POST">
        <div>
            <div>Фильтр по клиенту</div>
            <select name="customer" class="form-select">
                <option value="-1" selected>—</option>
                <?php
                    foreach($customers_list as $customer) {
                        if ($customer['id'] == $_POST['customer']) {
                            print("<option selected value=\"{$customer['id']}\">");
                        } else {
                            print("<option value=\"{$customer['id']}\">");
                        }
                        print("{$customer['name']} [{$customer['email']}]");
                        print('</option>');
                    }
                ?>
            </select>
        </div>

        <div>
            <div>Фильтр по сотруднику</div>
            <select name="librarian" class="form-select">
            <option value="-1" selected>—</option>
                <?php
                    foreach($librarians_list as $librarian) {
                        if ($librarian['id'] == $_POST['librarian']) {
                            print("<option selected value=\"{$librarian['id']}\">");
                        } else {
                            print("<option value=\"{$librarian['id']}\">");
                        }
                       
                        print("{$librarian['name']}");
                        print('</option>');
                    }
                ?>
            </select>
        </div>
        <div>
            <button name="find" value="find" type="submit" class="btn btn-primary">Показать</button>
            <a href="orders.php">
                <button class="btn btn-dark">Сбросить</button>
            </a>
        </div>
    </form>

    <!-- all orders -->

    <div class="table-list">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Фильм</th>
                    <th scope="col">Клиент</th>
                    <th scope="col">Библиотекарь</th>
                    <th scope="col">Дата выдачи</th>
                    <th scope="col">Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($orders as $order):

                        $stmt = $db->prepare("SELECT * FROM films WHERE id=?");
                        $stmt->execute([$order['film_id']]);
                        $film = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $stmt = $db->prepare("SELECT * FROM customers WHERE id=?");
                        $stmt->execute([$order['customer_id']]);
                        $customer = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $stmt = $db->prepare("SELECT * FROM librarians WHERE id=?");
                        $stmt->execute([$order['librarian_id']]);
                        $librarian = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <tr>
                    <th scope="row"><div class="cell"><?= $order["id"]?></div></th>
                    <form method="POST" action="">

                        <input value="<?= $order["id"]?>" type="hidden" name="id">

                        <td>
                            <select name="film" class="form-select">
                                <?php
                                    foreach($films_list as $f) {
                                        if ($f['id'] == $film[0]['id']) {
                                            print("<option selected value=\"{$f['id']}\">");
                                        } else {
                                            print("<option value=\"{$f['id']}\">");
                                        }
                                        print("{$f['title']} [{$f['year']}]");
                                        print('</option>');
                                    }
                                ?>
                            </select>
                        </td>

                        <td>
                            <select name="customer" class="form-select">
                                <?php
                                    foreach($customers_list as $c) {
                                        if ($c['id'] == $customer[0]['id']) {
                                            print("<option selected value=\"{$c['id']}\">");
                                        } else {
                                            print("<option value=\"{$c['id']}\">");
                                        }
                                        print("{$c['name']} [{$c['email']}]");
                                        print('</option>');
                                    }
                                ?>
                            </select>
                        </td>

                        <td>
                            <select name="librarian" class="form-select">
                                <?php
                                    foreach($librarians_list as $l) {
                                        if ($l['id'] == $librarian[0]['id']) {
                                            print("<option selected value=\"{$l['id']}\">");
                                        } else {
                                            print("<option value=\"{$l['id']}\">");
                                        }
                                        print("{$l['name']}");
                                        print('</option>');
                                    }
                                ?>
                            </select>
                        </td>

                        <td><input type="date" name="date" class="form-control" value="<?= $order['date'] ?>"></td>

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
        <?php if (empty($orders)) print('<div style="margin: 10px;">Данные не найдены</div>') ?>
    </div>
    

    
</body>
</html>