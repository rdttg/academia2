CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
);

ALTER TABLE usuarios 
ADD email VARCHAR(50) NOT NULL



