-- Insert dummy data into Members table
INSERT INTO Members (userid, username, userpassword, firstname, lastname, mcgillemail)
VALUES
    (1, 'john_doe', 'password123', 'John', 'Doe', 'john.doe@mcgill.ca'),
    (2, 'jane_smith', 'password456', 'Jane', 'Smith', 'jane.smith@mcgill.ca'),
    (3, 'mark_jones', 'password789', 'Mark', 'Jones', 'mark.jones@mcgill.ca');

-- Insert dummy data into Bookings table
INSERT INTO Bookings (bookingid, bookingurl, userid, startdatetime, enddatetime, bookingtitle, bookingdescription)
VALUES
    (1, 'booking1-url', 1, '2024-12-10 09:00:00', '2024-12-10 17:00:00', 'Meeting with Team', 'Discuss project updates'),
    (2, 'booking2-url', 2, '2024-12-11 10:00:00', '2024-12-11 12:00:00', 'Doctor Appointment', 'Regular check-up'),
    (3, 'booking3-url', 3, '2024-12-12 14:00:00', '2024-12-12 16:00:00', 'Workshop on SQL', 'Learn advanced SQL techniques');

-- Insert dummy data into Timeslot table
INSERT INTO Timeslot (slotid, bookingurl, slottitle, hostname, location, startdatetime, enddatetime, numopenslots, maxslots)
VALUES
    (1, 'booking1-url', 'Slot 1 - Morning', 'Host 1', 'Room A', '2024-12-10 09:00:00', '2024-12-10 12:00:00', 10, 20),
    (2, 'booking2-url', 'Slot 2 - Afternoon', 'Host 2', 'Room B', '2024-12-11 10:00:00', '2024-12-11 12:00:00', 5, 10),
    (3, 'booking3-url', 'Slot 3 - Evening', 'Host 3', 'Room C', '2024-12-12 14:00:00', '2024-12-12 16:00:00', 3, 5);

-- Insert dummy data into Registrations table
INSERT INTO Registrations (registrationurl, slotid, notes)
VALUES
    ('registration1-url', 1, 'Registered for the meeting'),
    ('registration2-url', 2, 'Attending for check-up'),
    ('registration3-url', 3, 'Signed up for the SQL workshop');

-- Insert dummy data into AvailabilityRequests table
INSERT INTO AvailabilityRequests (requestid, bookingurl, startdatetime, enddatetime)
VALUES
    (1, 'booking1-url', '2024-12-10 08:00:00', '2024-12-10 09:00:00'),
    (2, 'booking2-url', '2024-12-11 08:00:00', '2024-12-11 10:00:00'),
    (3, 'booking3-url', '2024-12-12 13:00:00', '2024-12-12 14:00:00');
