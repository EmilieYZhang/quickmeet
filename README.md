# quickmeet - team scriptSquad
### COMP307 F2024 Project - SOCS Competition

URL: 

## Project Overview
This booking website simplifies the process of scheduling meetings and appointments. Key features include:
- Users can create recurring or one-time booking pages and share links for others to reserve time slots.
- Non-registered users can reserve time slots without needing an account, while registered users can create and manage bookings.
- Meeting organizers can specify if slots accept single or multiple participants.
- Booking confirmations are sent via email using PHPMailer.
- Users can send alternative time requests if the listed availabilities don’t suit them.
- We have implemented all required features outlined in the project description, including use cases like recurring office hours, one-time events, and alternative time suggestions.

Team Contributions
Emilie Yahui Zhang:	
Serhii Artemenko:	
Japmann Sarinn:	
Hudanur Kacmaz:

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
