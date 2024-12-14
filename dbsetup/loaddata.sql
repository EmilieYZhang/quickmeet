-- TODO: Hudanur to edit below, Insert dummy data into Users table
INSERT INTO Users (username, userpassword, firstname, lastname, mcgillemail)
VALUES
    ('john_doe', 'password123', 'John', 'Doe', 'john.doe@mcgill.ca'),
    ('jane_smith', 'password456', 'Jane', 'Smith', 'jane.smith@mcgill.ca'),
    ('mark_jones', 'password789', 'Mark', 'Jones', 'mark.jones@mcgill.ca');

-- Insert dummy data into Booking table
INSERT INTO Booking (bookingurl, uid, startdatetime, enddatetime, bookingtitle, bookingdescription)
VALUES
    ('booking1-url', 1, '2024-12-10 09:00:00', '2024-12-10 17:00:00', 'Meeting with Team', 'Discuss project updates'),
    ('booking2-url', 2, '2024-12-11 10:00:00', '2024-12-11 12:00:00', 'Doctor Appointment', 'Regular check-up'),
    ('booking3-url', 3, '2024-12-12 14:00:00', '2024-12-12 16:00:00', 'Workshop on SQL', 'Learn advanced SQL techniques');

-- Insert dummy data into Timeslot table
INSERT INTO Timeslot (bookingurl, slottitle, hostname, location, startdatetime, enddatetime, numopenslots, maxslots)
VALUES
    ('booking1-url', 'Slot 1 - Morning', 'Host 1', 'Room A', '2024-12-10 09:00:00', '2024-12-10 12:00:00', 10, 20),
    ('booking1-url', 'Slot 2 - Morning', 'Host 2', 'Room A', '2024-12-11 09:00:00', '2024-12-10 12:00:00', 10, 20),
    ('booking1-url', 'Slot 3 - Afternoon', 'Host 2', 'Room D', '2024-12-13 09:00:00', '2024-12-10 12:00:00', 10, 20),
    ('booking1-url', 'Slot 4 - Night', 'Host 14', 'Room B', '2024-12-14 09:00:00', '2024-12-10 12:00:00', 10, 20),
    ('booking2-url', 'Slot 2 - Afternoon', 'Host 2', 'Room B', '2024-12-11 10:00:00', '2024-12-11 12:00:00', 5, 10),
    ('booking3-url', 'Slot 3 - Evening', 'Host 3', 'Room C', '2024-12-12 14:00:00', '2024-12-12 16:00:00', 3, 5);

-- Insert dummy data into Reservation table
INSERT INTO Reservation (reservationurl, sid, notes)
VALUES
    ('reservation1-url', 1, 'Registered for the meeting'),
    ('reservation2-url', 2, 'Attending for check-up'),
    ('reservation3-url', 3, 'Signed up for the SQL workshop');

-- Insert dummy data into AvailabilityRequests table
INSERT INTO AvailabilityRequests (bookingurl, startdatetime, enddatetime)
VALUES
    ('booking1-url', '2024-12-10 08:00:00', '2024-12-10 09:00:00'),
    ('booking2-url', '2024-12-11 08:00:00', '2024-12-11 10:00:00'),
    ('booking3-url', '2024-12-12 13:00:00', '2024-12-12 14:00:00');
