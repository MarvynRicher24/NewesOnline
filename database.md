MYSQL :

CREATE DATABASE IF NOT EXISTS dwwm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dwwm;

CREATE TABLE IF NOT EXISTS admin_users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS subscriber (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50) NOT NULL UNIQUE,
    email       VARCHAR(255) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    avatar      VARCHAR(255),
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS category (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS announcements (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(255) NOT NULL,
    subtitle     VARCHAR(255),
    content      TEXT NOT NULL,
    image        VARCHAR(255),
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    category_id  INT NULL,
    CONSTRAINT fk_ann_category
        FOREIGN KEY (category_id) REFERENCES category(id)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subscriber_id INT NOT NULL,
    announcement_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_sub_ann (subscriber_id, announcement_id),
    CONSTRAINT fk_comment_sub
        FOREIGN KEY (subscriber_id) REFERENCES subscriber(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_comment_ann
        FOREIGN KEY (announcement_id) REFERENCES announcements(id)
        ON DELETE CASCADE
);

INSERT INTO category (name) VALUES
    ('Vehicle'),
    ('Video game'),
    ('History'),
    ('Science'),
    ('Finance');

INSERT INTO admin_users (username, password)
VALUES ('marvyn', 'marvyn');

---------------------------------------------------------------------------------------------------------------------------------

SQL :

CREATE SCHEMA dwwm;
SET search_path TO dwwm;

CREATE TABLE admin_users (
    id          INTEGER        IDENTITY PRIMARY KEY,
    username    VARCHAR(50)    NOT NULL UNIQUE,
    password    VARCHAR(255)   NOT NULL,
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subscriber (
    id          INTEGER        IDENTITY PRIMARY KEY,
    username    VARCHAR(50)    NOT NULL UNIQUE,
    email       VARCHAR(255)   NOT NULL UNIQUE,
    password    VARCHAR(255)   NOT NULL,
    avatar      VARCHAR(255),
    description TEXT,
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE category (
    id          INTEGER        IDENTITY PRIMARY KEY,
    name        VARCHAR(255)   NOT NULL,
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE announcements (
    id           INTEGER        IDENTITY PRIMARY KEY,
    title        VARCHAR(255)   NOT NULL,
    subtitle     VARCHAR(255),
    content      TEXT           NOT NULL,
    image        VARCHAR(255),
    created_at   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    category_id  INTEGER,
    CONSTRAINT fk_ann_category
        FOREIGN KEY (category_id)
        REFERENCES category(id)
        ON DELETE SET NULL
);

CREATE TABLE comments (
    id               INTEGER        IDENTITY PRIMARY KEY,
    subscriber_id    INTEGER        NOT NULL,
    announcement_id  INTEGER        NOT NULL,
    rating           SMALLINT       NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment          TEXT           NOT NULL,
    created_at       TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
                       ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_sub_ann UNIQUE(subscriber_id, announcement_id),
    CONSTRAINT fk_comment_sub
        FOREIGN KEY (subscriber_id)
        REFERENCES subscriber(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_comment_ann
        FOREIGN KEY (announcement_id)
        REFERENCES announcements(id)
        ON DELETE CASCADE
);

INSERT INTO category (name) VALUES
    ('Vehicle'),
    ('Video game'),
    ('History'),
    ('Science'),
    ('Finance');

INSERT INTO admin_users (username, password) VALUES
    ('marvyn', 'marvyn');