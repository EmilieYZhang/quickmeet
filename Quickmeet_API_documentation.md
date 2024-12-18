# README for the Quickmeet API

*This API is solely for the purpose of using the Quickmeet application developed by scriptSquad.\
Will accept json by default.*

## Relevant files
***apitest.html*** for an example of how to use the api in frontend\
***apiendpoints.php*** api code

## Documentation
There exists the following API Endpoints, organized by the db tables names\
Format: (tablename) Fields: Field_1, Field_2, ..., Field_n\

### _Fields syntax_
**-fieldname-** indicates unique primary key\
**\~fieldname\~** indicates reference to another table\
**/fieldname/** indicates unique\
**|fieldname|** indicates nullable\

### _API Endpoints_
### (Booking) Fields: -bid-, /bookingurl/, \~uid\~, startdatetime, enddatetime, bookingtitle, |bookingdescription|
**GET /quickmeetapi/booking/{bid}:** fetch a booking by id, return a specific instance of booking\
**GET /quickmeetapi/booking/{uid}/userid:** fetch all bookings by creator id, return a specific instance of booking\
**GET /quickmeetapi/booking/{bookingurl}/bookingurl:** fetch a booking by url, return a specific instance of booking\
**POST /quickmeetapi/booking:** create a new instance of booking url with a body containing the fields, return bid\
**POST /quickmeetapi/booking/edit:** edit the details of the booking with body containing all fields\
**DELETE /quickmeetapi/booking/{bid}:** delete a specific instance of booking

### (Timeslot) Fields: -sid-, \~bookingurl\~, slottitle, hostname, location, startdatetime, enddatetime, numopenslots, maxslots
**GET /quickmeetapi/timeslot/{sid}:** fetch a timeslot by id\
**GET /quickmeetapi/timeslot/{bookingurl}/bookingurl:** fetch all timeslots by bookingurl\
**POST /quickmeetapi/timeslot:** create a specific instance of timeslot with body containing all fields, return sid\
**POST /quickmeetapi/timeslot/edit:** edit the details of the timeslot with body containing all fields\
**DELETE /quickmeetapi/timeslot/{sid}:** delete a specific instance of timeslot


### (Reservation) Fields: -reservationurl-, \~sid\~, |notes|
**GET /quickmeetapi/reservation/{reservationurl}:** fetch a reservation fields/details through url\
**POST /quickmeetapi/reservation:** create a new reservation instance with body containing sid\
**POST /quickmeetapi/reservation/edit:** edit the notes of a reservation\
**DELETE /quickmeetapi/reservation/{reservationurl}:** delete a specific instance of reservation

### (AvailabilityRequests) Fields: -rid-, \~bookingurl\~, startdatetime, enddatetime
**GET /quickmeetapi/availability/{bookingurl}:** return all pending availability requests for a editbookingurl\
**POST /quickmeetapi/availability:** post availability request with a body containing all fields, return rid\
**DELETE /quickmeetapi/availability/{rid}:** delete a specific instance of availability request id

*TODO Hudanur update the following fields for the Users table*
### (Users) Fields: -uid-, /email/, password, firstname, lastname
NO API provided for this, only accessible through login.php and register.php
