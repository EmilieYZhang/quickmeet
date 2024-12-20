-- @author: Emilie Zhang
-- to create sql tables

CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,            -- Primary Key
    fname VARCHAR(255) NOT NULL,     -- Not nullable
    lname VARCHAR(255) NOT NULL,      -- Not nullable
    username VARCHAR(255) DEFAULT NULL,        -- Unique constraint
    email VARCHAR(255) NOT NULL,    -- Not nullable
    password VARCHAR(255) NOT NULL,  -- Not nullable
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Booking (
    bid INT AUTO_INCREMENT PRIMARY KEY,           -- Primary Key
    bookingurl VARCHAR(255) UNIQUE,      -- Unique constraint
    editbookingurl VARCHAR(255) UNIQUE,      -- Unique constraint
    uid INT,                          -- Foreign Key reference to users table
    startdatetime DATETIME NOT NULL,     -- Not nullable
    enddatetime DATETIME NOT NULL,       -- Not nullable
    bookingtitle VARCHAR(255) NOT NULL,  -- Not nullable
    bookingdescription TEXT,              -- Nullable field
    FOREIGN KEY (uid) REFERENCES users(id)
);

CREATE TABLE Timeslot (
    sid INT AUTO_INCREMENT PRIMARY KEY,             -- Primary Key
    bookingurl VARCHAR(255),             -- Foreign Key reference to Booking table
    slottitle VARCHAR(255) NOT NULL,      -- Not nullable
    hostname VARCHAR(255) NOT NULL,       -- Not nullable
    location VARCHAR(255) NOT NULL,       -- Not nullable
    startdatetime DATETIME NOT NULL,     -- Not nullable
    enddatetime DATETIME NOT NULL,       -- Not nullable
    numopenslots INT NOT NULL,            -- Not nullable
    maxslots INT NOT NULL,                -- Not nullable
    FOREIGN KEY (bookingurl) REFERENCES Booking(bookingurl)
);

CREATE TABLE Reservation (
    reservationurl VARCHAR(255) PRIMARY KEY, -- Primary Key
    sid INT,                               -- Foreign Key reference to Timeslot table
    notes TEXT,                               -- Nullable field
    FOREIGN KEY (sid) REFERENCES Timeslot(sid)
);

CREATE TABLE AvailabilityRequests (
    rid INT AUTO_INCREMENT PRIMARY KEY,             -- Primary Key
    bookingurl VARCHAR(255),               -- Foreign Key reference to Booking table
    startdatetime DATETIME NOT NULL,       -- Not nullable
    enddatetime DATETIME NOT NULL,         -- Not nullable
    FOREIGN KEY (bookingurl) REFERENCES Booking(bookingurl)
);

CREATE TABLE user_tickets (
    id INT(11) AUTO_INCREMENT PRIMARY KEY, -- Primary Key
    user_id INT(11) NOT NULL,  -- Not nullable
    ticket VARCHAR(64) NOT NULL,  -- Not nullable
    expiry INT(11) NOT NULL, -- Not nullable
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
