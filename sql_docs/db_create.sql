CREATE DATABASE music_store;

use music_store;

CREATE TABLE users (
    userID INT(9) AUTO_INCREMENT,
    firstName VARCHAR(30) NOT NULL,
    lastName VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phoneNumber VARCHAR(50),
    userPassword VARCHAR(60) NOT NULL,
    isAdmin BOOLEAN NOT NULL,
    PRIMARY KEY (userID)
);

CREATE TABLE items (
    itemID INT(9) AUTO_INCREMENT,
    category VARCHAR(30) NOT NULL,
    brand VARCHAR(30) NOT NULL,
    yearMade YEAR(4) NOT NULL,
    features VARCHAR(150) NOT NULL,
    pricePerDay INT(6) NOT NULL,
    overduePrice INT(6) NOT NULL,
    available BOOLEAN NOT NULL,
    userID INT(9),
    PRIMARY KEY (itemID),
    FOREIGN KEY (userID) REFERENCES users (userID)
);
/*
CREATE TABLE itemTransaction (
    transactionID INT(9) AUTO_INCREMENT,
    userID INT(9) NOT NULL,
    itemID INT(9) NOT NULL,
    transactionStart DATE NOT NULL,
    transactionFinish DATE NOT NULL,
    wasOverdue BOOLEAN NOT NULL
    PRIMARY KEY (transactionID),
    FOREIGN KEY (userID) REFERENCES users (userID),
    FOREIGN KEY (itemID) REFERENCES items (itemID)
);
*/
