use music_store;

INSERT INTO users (firstName, lastName, email, phoneNumber, userPassword, isAdmin) 
VALUES ("Wendy", "Malone", "wm123@random-email.com", "397-057-909", SHA1("wm123456"), FALSE),  
("Melvin", "Walton", "mw123@random-email.com", "851-502-152", SHA1("mw123456"), FALSE),
("Diane", "Wallace", "dw123@random-email.com", "730-692-181", SHA1("dw123456"), FALSE),
("Opal", "Brewer", "ob123@random-email.com", "922-575-736", SHA1("op123456"), FALSE),
("Tomas", "Guerrero", "tg123@random-email.com", "871-461-302", SHA1("tg123456"), FALSE),
("Charles", "Day", "cd123@random-email.com", "982-968-379", SHA1("cd123456"), FALSE),
("Joanne", "Mullins", "jm123@random-email.com", "628-912-704", SHA1("jm123456"), FALSE),
("Conrad", "Carlson", "cc123@random-email.com", "226-978-594", SHA1("cc123456"), FALSE),
("Leigh", "Perry", "lp123@random-email.com", "690-382-966", SHA1("lp123456"), FALSE),
("Isabel", "Griffin", "ig123@random-email.com", "306-158-417", SHA1("ig123456"), FALSE),
("Curtis", "Lucas", "cl123@random-email.com", "855-763-812", SHA1("cl123456"), TRUE),
("Adrian", "Phelps", "ap123@random-email.com", "675-361-976", SHA1("ap123456"), TRUE);

INSERT INTO items (category, brand, yearMade, features, pricePerDay, overduePrice, available, userID) 
VALUES ("Drum", "Yamaha", "2020", "Red, Good condition.", 10, 20, FALSE, 1), 
("Drum", "Sonor", "2018", "Blue, Okay condition.", 8, 15, TRUE, NULL), 
("Drum", "Tama", "2010", "Red, Bad condition.", 5, 13, TRUE, NULL), 
("Drum", "Ludwig", "2021", "Black, Great condition!", 15, 30, TRUE, NULL), 
("Guitar", "Martin", "2015", "Red, Good condition.", 11, 22, FALSE, 2), 
("Guitar", "Yamaha", "2020", "Yellow, Good condition.", 8, 20, TRUE, NULL), 
("Guitar", "Taylor", "2015", "Red, Bad condition.", 5, 10, FALSE, 3), 
("Guitar", "Yamaha", "2010", "Red, Okay condition.", 10, 20, TRUE, NULL), 
("Guitar", "Martin", "2016", "Blue, Bad condition.", 5, 10, TRUE, NULL), 
("Guitar", "Taylor", "2020", "Red, Good condition.", 11, 22, TRUE, NULL), 
("Guitar", "Yamaha", "2021", "Purple, Great condition.", 15, 30, TRUE, NULL), 
("Keyboard", "Yamaha", "2020", "Good condition.", 10, 20, FALSE, 4), 
("Keyboard", "Roland", "2010", "Okay condition.", 10, 20, TRUE, NULL), 
("Keyboard", "Kawai", "2017", "Bad condition.", 10, 20, TRUE, NULL), 
("Keyboard", "Yamaha", "2005", "Good condition.", 10, 20, TRUE, NULL),
("Keyboard", "Casio", "2021", "Great condition.", 10, 20, TRUE, NULL), 
("Amplifier", "Marshall", "2020", "Good condition.", 15, 20, FALSE, 5), 
("Amplifier", "Peavey", "2021", "Great condition.", 10, 20, TRUE, NULL), 
("Amplifier", "Fender", "2019", "Good condition.", 10, 20, TRUE, NULL), 
("Amplifier", "Fender", "2018", "Oaky condition.", 10, 20, TRUE, NULL);


INSERT INTO itemTransactions (userID, itemID, startDate, endDate, active, daysOverdue, totalCost)
VALUES (1, 1, STR_TO_DATE("01-01-2021", "%d-%m-%Y"), STR_TO_DATE("01-07-2021", "%d-%m-%Y"), FALSE, 0, 60), 
(2, 3, STR_TO_DATE("20-01-2021", "%d-%m-%Y"), STR_TO_DATE("24-01-2021", "%d-%m-%Y"), FALSE, 0, 20), 
(5, 5, STR_TO_DATE("20-02-2021", "%d-%m-%Y"), STR_TO_DATE("24-02-2021", "%d-%m-%Y"), FALSE, 1, 66),
(1, 1, STR_TO_DATE("10-06-2021", "%d-%m-%Y"), STR_TO_DATE("15-06-2021", "%d-%m-%Y"), TRUE, 0, 50), 
(2, 5, STR_TO_DATE("1-06-2021", "%d-%m-%Y"), STR_TO_DATE("10-06-2021", "%d-%m-%Y"), TRUE, 0, 110),
(3, 7, STR_TO_DATE("1-06-2021", "%d-%m-%Y"), STR_TO_DATE("10-06-2021", "%d-%m-%Y"), TRUE, 1, 60),
(4, 12, STR_TO_DATE("1-06-2021", "%d-%m-%Y"), STR_TO_DATE("10-06-2021", "%d-%m-%Y"), TRUE, 1, 120),
(5, 17, STR_TO_DATE("1-06-2021", "%d-%m-%Y"), STR_TO_DATE("10-06-2021", "%d-%m-%Y"), TRUE, 1, 170);
