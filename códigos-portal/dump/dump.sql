CREATE DATABASE login;

USE login;

CREATE TABLE users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_visit TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE pacientes (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED,
    idade INT(3) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    data_nascimento DATE NOT NULL,
    sexo ENUM('feminino', 'masculino', 'outro') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE consultas (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED,
    medico VARCHAR(255) NOT NULL,
    periodo ENUM('manha', 'tarde') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);



-- ALTER TABLE users
-- ADD COLUMN first_visit INT(1) DEFAULT 1 NOT NULL;
