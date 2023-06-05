CREATE TABLE films (
    id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title varchar(128) NOT NULL DEFAULT '',
    director varchar(128) NOT NULL DEFAULT '',
    year varchar(4) NOT NULL DEFAULT ''
);

CREATE TABLE librarians (
    id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(128) NOT NULL DEFAULT '',
    phone varchar(11) NOT NULL DEFAULT ''
);

CREATE TABLE customers (
    id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(128) NOT NULL DEFAULT '',
    phone varchar(11) NOT NULL DEFAULT '',
    email varchar(128) NOT NULL DEFAULT ''
);

CREATE TABLE orders (
    id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    film_id int(10) unsigned NOT NULL,
    librarian_id int(10) unsigned NOT NULL,
    customer_id int(10) unsigned NOT NULL,
    date varchar(10) NOT NULL DEFAULT '',
    FOREIGN KEY (film_id) REFERENCES films(id),
    FOREIGN KEY (librarian_id) REFERENCES librarians(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);


