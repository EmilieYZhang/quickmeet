-- Create Members table
CREATE TABLE Members (
    userid INT PRIMARY KEY,              -- Primary Key
    username VARCHAR(255) UNIQUE,        -- Unique constraint
    userpassword VARCHAR(255) NOT NULL,  -- Not nullable
    firstname VARCHAR(255) NOT NULL,     -- Not nullable
    lastname VARCHAR(255) NOT NULL,      -- Not nullable
    mcgillemail VARCHAR(255) NOT NULL    -- Not nullable
);

-- Create Bookings table
CREATE TABLE Bookings (
    bookingid INT PRIMARY KEY,           -- Primary Key
    bookingurl VARCHAR(255) UNIQUE,      -- Unique constraint
    userid INT,                          -- Foreign Key reference to Members table
    startdatetime DATETIME NOT NULL,     -- Not nullable
    enddatetime DATETIME NOT NULL,       -- Not nullable
    bookingtitle VARCHAR(255) NOT NULL,  -- Not nullable
    bookingdescription TEXT              -- Nullable field
    FOREIGN KEY (userid) REFERENCES Members(userid)
);

-- Create Timeslot table
CREATE TABLE Timeslot (
    slotid INT PRIMARY KEY,              -- Primary Key
    bookingurl VARCHAR(255),             -- Foreign Key reference to Bookings table
    slottitle VARCHAR(255) NOT NULL,      -- Not nullable
    hostname VARCHAR(255) NOT NULL,       -- Not nullable
    location VARCHAR(255) NOT NULL,       -- Not nullable
    startdatetime DATETIME NOT NULL,     -- Not nullable
    enddatetime DATETIME NOT NULL,       -- Not nullable
    numopenslots INT NOT NULL,            -- Not nullable
    maxslots INT NOT NULL,                -- Not nullable
    FOREIGN KEY (bookingurl) REFERENCES Bookings(bookingurl)
);

-- Create Registrations table
CREATE TABLE Registrations (
    registrationurl VARCHAR(255) PRIMARY KEY, -- Primary Key
    slotid INT,                               -- Foreign Key reference to Timeslot table
    notes TEXT,                               -- Nullable field
    FOREIGN KEY (slotid) REFERENCES Timeslot(slotid)
);

-- Create AvailabilityRequests table
CREATE TABLE AvailabilityRequests (
    requestid INT PRIMARY KEY,             -- Primary Key
    bookingurl VARCHAR(255),               -- Foreign Key reference to Bookings table
    startdatetime DATETIME NOT NULL,       -- Not nullable
    enddatetime DATETIME NOT NULL,         -- Not nullable
    FOREIGN KEY (bookingurl) REFERENCES Bookings(bookingurl)
);
