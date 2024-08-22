CREATE DATABASE IF NOT EXISTS Book;

CREATE TABLE ThongTinSach (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    publisher VARCHAR(255),
    publish_year INT,
    quantity INT,
    FOREIGN KEY (author_id) REFERENCES TacGia(id),
    FOREIGN KEY (category_id) REFERENCES TheLoaiSach(id)
);
CREATE TABLE TacGia (
    id INT PRIMARY KEY AUTO_INCREMENT,
    author_name VARCHAR(255) NOT NULL,
    book_numbers INT
);
CREATE TABLE TheLoaiSach (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(255) NOT NULL
);


INSERT INTO TacGia (author_name) VALUES ('J.K. Rowling');
INSERT INTO TacGia (author_name) VALUES ('George R.R. Martin');
INSERT INTO TacGia (author_name) VALUES ('J.R.R. Tolkien');
INSERT INTO TacGia (author_name) VALUES ('Agatha Christie');
INSERT INTO TacGia (author_name) VALUES ('Stephen King');
INSERT INTO TacGia (author_name) VALUES ('Dan Brown');
INSERT INTO TacGia (author_name) VALUES ('Margaret Atwood');
INSERT INTO TacGia (author_name) VALUES ('Haruki Murakami');
INSERT INTO TacGia (author_name) VALUES ('John Green');
INSERT INTO TacGia (author_name) VALUES ('Orwell George');

INSERT INTO TheLoaiSach (category_name) VALUES ('Tiểu thuyết');
INSERT INTO TheLoaiSach (category_name) VALUES ('Kinh dị');
INSERT INTO TheLoaiSach (category_name) VALUES ('Khoa học viễn tưởng');
INSERT INTO TheLoaiSach (category_name) VALUES ('Huyền bí');
INSERT INTO TheLoaiSach (category_name) VALUES ('Lịch sử');
INSERT INTO TheLoaiSach (category_name) VALUES ('Triết học');
INSERT INTO TheLoaiSach (category_name) VALUES ('Tự truyện');
INSERT INTO TheLoaiSach (category_name) VALUES ('Lãng mạn');
INSERT INTO TheLoaiSach (category_name) VALUES ('Kinh tế');
INSERT INTO TheLoaiSach (category_name) VALUES ('Chính trị');

INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES ('Harry Potter and the Sorcerer\'s Stone', 1, 1, 'Bloomsbury', 1997, 50);
INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES ('A Game of Thrones', 2, 1, 'Bantam Books', 1996, 40);
INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES ('The Hobbit', 3, 1, 'HarperCollins', 1937, 30);
INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES ('Murder on the Orient Express', 4, 1, 'Collins Crime Club', 1934, 20);
INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES ('The Shining', 5, 2, 'Doubleday', 1977, 25);
INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES ('Angels & Demons', 6, 1, 'Doubleday', 2000, 35);
INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES ('The Handmaid\'s Tale', 7, 1, 'McClelland and Stewart', 1985, 15);
INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES ('1Q84', 8, 1, 'Shinchosha', 2009, 10);
INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES ('The Fault in Our Stars', 9, 1, 'Dutton Books', 2012, 45);
INSERT INTO ThongTinSach (title, author_id, category_id, publisher, publish_year, quantity) VALUES ('1984', 10, 1, 'Secker & Warburg', 1949, 60);
