-- @author: Emilie Zhang
-- to be used after droptbl.sql and createtbl.sql

INSERT INTO users (fname, lname, username, email, password)
VALUES
    ('Alice', 'Smith', 'alice123', 'alice.smith@mail.mcgill.ca', 'password123'),
    ('Bob', 'Johnson', 'bobbyJ', 'bob.johnson@mail.mcgill.ca', 'securePass!'),
    ('Charlie', 'Brown', 'charlie_b', 'charlie.brown@mail.mcgill.ca', 'pass@word1'),
    ('Diana', 'White', 'dianaW', 'diana.white@mail.mcgill.ca', 'Diana12345'),
    ('Ethan', 'Taylor', 'ethan.t', 'ethan.taylor@mail.mcgill.ca', 'Taylor$789'),
    ('Fiona', 'Moore', 'fiona_m', 'fiona.moore@mail.mcgill.ca', 'Moore@2023'),
    ('George', 'Hill', 'georgeH', 'george.hill@mail.mcgill.ca', 'Hill!345'),
    ('Hannah', 'Clark', 'hannah.c', 'hannah.clark@mail.mcgill.ca', 'Clark#001'),
    ('Ian', 'Adams', 'ian.adams', 'ian.adams@mail.mcgill.ca', 'Adams@MCG1'),
    ('Julia', 'Davis', 'julia.d', 'julia.davis@mail.mcgill.ca', 'Secure*Pass');

INSERT INTO Booking (bookingurl, editbookingurl, uid, startdatetime, enddatetime, bookingtitle, bookingdescription)
VALUES
    ('booking1-url', 'editbooking1-url', 1, '2024-12-19 09:00:00', '2024-12-19 17:00:00', 'Meeting with Team', 'Discuss project updates'),
    ('booking2-url', 'editbooking2-url', 2, '2024-12-18 10:00:00', '2024-12-27 12:00:00', 'Doctor Appointment', 'Regular check-up'),
    ('booking3-url', 'editbooking3-url', 3, '2024-12-18 14:00:00', '2024-12-28 16:00:00', 'Workshop on SQL', 'Learn advanced SQL techniques'),
    ('booking4-url', 'editbooking4-url', 2, '2024-12-19 09:30:00', '2024-12-28 11:30:00', 'Client Call', 'Discuss project requirements'),
    ('booking5-url', 'editbooking5-url', 2, '2024-12-20 13:00:00', '2024-12-20 15:00:00', 'Team Workshop', 'Brainstorming session for marketing strategies');

INSERT INTO Timeslot (bookingurl, slottitle, hostname, location, startdatetime, enddatetime, numopenslots, maxslots)
VALUES
    ('booking1-url', 'Slot 1 - Morning', 'Host 1', 'Room A', '2024-12-19 09:00:00', '2024-12-10 10:00:00', 10, 20),
    ('booking1-url', 'Slot 2 - Morning', 'Host 2', 'Room A', '2024-12-19 11:00:00', '2024-12-10 12:00:00', 10, 20),
    ('booking1-url', 'Slot 3 - Afternoon', 'Host 2', 'Room D', '2024-12-19 14:00:00', '2024-12-10 15:00:00', 10, 20),
    ('booking1-url', 'Slot 4 - Night', 'Host 14', 'Room B', '2024-12-19 16:00:00', '2024-12-10 17:00:00', 10, 20),
    ('booking2-url', 'Slot 2 - Afternoon', 'Host 2', 'Room B', '2024-12-17 10:00:00', '2024-12-11 12:00:00', 5, 10),
    ('booking3-url', 'Slot 3 - Evening', 'Host 3', 'Room C', '2024-12-18 14:00:00', '2024-12-12 16:00:00', 3, 5);

INSERT INTO Reservation (reservationurl, sid, notes)
VALUES
    ('reservation1-url', 1, 'Registered for the meeting'),
    ('reservation2-url', 2, 'Attending for check-up'),
    ('reservation3-url', 3, 'Signed up for the SQL workshop');

INSERT INTO AvailabilityRequests (bookingurl, startdatetime, enddatetime)
VALUES
    ('booking1-url', '2024-12-10 08:00:00', '2024-12-10 09:00:00'),
    ('booking2-url', '2024-12-11 08:00:00', '2024-12-11 10:00:00'),
    ('booking3-url', '2024-12-12 13:00:00', '2024-12-12 14:00:00');
