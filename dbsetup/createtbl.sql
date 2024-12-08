-- Create Users table
-- TODO: Hudanur to edit below
CREATE TABLE Users (
    uid INT AUTO_INCREMENT PRIMARY KEY,            -- Primary Key
    username VARCHAR(255) UNIQUE,        -- Unique constraint
    userpassword VARCHAR(255) NOT NULL,  -- Not nullable
    firstname VARCHAR(255) NOT NULL,     -- Not nullable
    lastname VARCHAR(255) NOT NULL,      -- Not nullable
    mcgillemail VARCHAR(255) NOT NULL    -- Not nullable
);

-- Create Booking table
CREATE TABLE Booking (
    bid INT AUTO_INCREMENT PRIMARY KEY,           -- Primary Key
    bookingurl VARCHAR(255) UNIQUE,      -- Unique constraint
    uid INT,                          -- Foreign Key reference to Users table
    startdatetime DATETIME NOT NULL,     -- Not nullable
    enddatetime DATETIME NOT NULL,       -- Not nullable
    bookingtitle VARCHAR(255) NOT NULL,  -- Not nullable
    bookingdescription TEXT,              -- Nullable field
    FOREIGN KEY (uid) REFERENCES Users(uid)
);

-- Create Timeslot table
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

-- Create Reservation table
CREATE TABLE Reservation (
    reservationurl VARCHAR(255) PRIMARY KEY, -- Primary Key
    sid INT,                               -- Foreign Key reference to Timeslot table
    notes TEXT,                               -- Nullable field
    FOREIGN KEY (sid) REFERENCES Timeslot(sid)
);

-- Create AvailabilityRequests table
CREATE TABLE AvailabilityRequests (
    rid INT AUTO_INCREMENT PRIMARY KEY,             -- Primary Key
    bookingurl VARCHAR(255),               -- Foreign Key reference to Booking table
    startdatetime DATETIME NOT NULL,       -- Not nullable
    enddatetime DATETIME NOT NULL,         -- Not nullable
    FOREIGN KEY (bookingurl) REFERENCES Booking(bookingurl)
);
