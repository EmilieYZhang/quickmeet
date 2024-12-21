# quickmeet - team scriptSquad
### COMP307 F2024 Project - SOCS Competition

URL: https://www.cs.mcgill.ca/~ezhang19/quickmeet/backend/Landing.php
GitHub repo: https://github.com/EmilieYZhang/quickmeet

## Project Overview
This booking website simplifies the process of scheduling meetings and appointments. Key features include:
- Users can create recurring or one-time booking pages and share links for others to reserve time slots.
- Non-registered users can reserve time slots without needing an account, while registered users can create and manage bookings.
- Meeting organizers can specify if slots accept single or multiple participants.
- Booking confirmations are sent via email using PHPMailer.
- Users can send alternative time requests if the listed availabilities don’t suit them.
- We have implemented all required features outlined in the project description, including use cases like recurring office hours, one-time events, and alternative time suggestions.

**Note**: This project was developed and tested primarily in **Google Chrome**.

Team Contributions
| **Name**             | **Contributions**                                                                                                                                                                                                                                                                                                                                               |
|----------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Emilie Yahui Zhang** | - Developed **Database SQL scripts** and **QuickMeet API backend** in collaboration with teammates to provide necessary backend functionality <br/>- Created **backend routing** for 3 types of unique URL generation: Booking, Edit-Booking, and Registration <br/>- Implemented **Request Availability** feature <br/>- For **public-facing booking URLs**: extended calendar to 4 weeks, added timeslot status coloring (Red = Full, Blue = Available, Grey = Past) <br/>- Added **Cancel Reservation** feature <br/>- For **private-facing Edit-Booking URLs**: generated list of current timeslots with an option to delete timeslots <br/>- Handled **edge case alerts** for improper user inputs in bookings and registration interactions |
| **Serhii Artemenko**  | - Implemented the **"Reserve a Time Slot"** feature, displaying available time slots in a calendar for a specific booking URL (Emilie extended it to display previous/current/next/next-next weeks with color-coding based on number of registrants) <br/>- Added the **"Add a Time Slot"** feature for booking owners <br/>- Created the **"Edit Booking"** functionality to update booking attributes <br/>- Developed the **"Edit Notes"** feature on the reservation URL, allowing users to add or edit notes for their reservations <br/>- Created **landing, login, and registration HTML pages** and corresponding **CSS** files (styling/responsiveness) |
| **Japmann Sarinn**    | - Focused on **backend development** and **API integration** <br/>- Implemented **Gmail SMTP API** with PHPMailer to automate email notifications (confirmations for bookings, edits, timeslots, and reservations) <br/>- Authored **email_sender.php** to handle email operations efficiently <br/>- Managed **POST and GET request** functionality to ensure seamless communication between front end and database <br/>- Contributed to **debugging and resolving** backend-related issues, implementing essential functional infrastructure |
| **Hudanur Kacmaz**    | - Implemented **ticket system** to keep track of logged-in users <br/>- Verified and handled errors for **registration and login**, added **logout** feature <br/>- Created **dashboard** front end using Serhii’s stylesheets <br/>- Built the **FAQ** page <br/>- Prepared **header.php** for all dynamic pages to validate user tickets                                                                                     |


#### Tech stack
**Backend:** PHP, MySQL (via XAMPP)

**Frontend:** HTML, CSS, JavaScript

**Email Service:** PHPMailer

## Project Directory
Public Pages
- Landing
- Register
- Login
- FAQ
- Search Booking
  
Private Pages
- Dashboard: Create, view, and manage bookings.
- Bookings Management: Edit time slots, view active and past bookings.

## Meeting minimal requirements for SOCS project: 
Checklist of all the minimal requirements listed by Professor Vybihal

### Public facing web pages: 
- [x] Landing page
- [x] Login and register page with a ticket system for security 
- [x] Book a meeting using URL page 

### Private facing web pages: 
- [x] Create booking page and create the invite URL.
- [x] Send a request for a meeting with someone outside their availability page.

### For all users: 
- [x] Central location page to see all active appointments and history of appointments. 
- [x] Additional optional features: Availability Request feature - The creator of the regular booking may not be available for a particular date and would need a way to indicate that or remove those dates from the regular booking schedule. 

## Additional/Extra unique features for Quickmeet: 
### Email API: 
**Booking confirmation:** A fully-fledged API backend setup with comprehensive documentation 
![image](https://github.com/user-attachments/assets/c3553e9b-e547-4a12-a430-d9327f7f978d)


**Reservation confirmation** script to run to create all necessary sql database tables
![image](https://github.com/user-attachments/assets/1fa1c167-ce4b-4ffa-a5b3-a0eb2619b760)


### Backend Dev Features: 
**./apiendpoints.php/:** A fully-fledged API backend setup with comprehensive documentation 

**createtbl.sql:** script to run to create all necessary sql database tables

**loaddata.sql:** script to run to load dummy entries into all database tables

**droptbl.sql:** script to run to delete database tables to reset
