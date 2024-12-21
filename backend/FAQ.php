<?php
include '../backend/header.php'; 
?>

//@author: Hudanur Kacmaz

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>

    <link href="../FrontEndCode/RegisterCssPho.css" rel="stylesheet">
    <link href="../FrontEndCode/RegisterCssLap.css" rel="stylesheet">

    <style>
        .faq-container {
            margin: 40px auto;
            padding: 20px;
            background-color: #15182b;
            border-radius: 8px;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .faq-item {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-question {
            font-size: 18px;
	    font-family: "Segoe UI";
            color: #ffffff;
            cursor: pointer;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-question:hover {
            color: #0088DC;
        }

        .faq-answer {
            margin-top: 10px;
            font-size: 16px;
            line-height: 1.6;
            color: #ffffff;
            display: none;
            padding-left: 20px;
        }

        .faq-answer.show {
            display: block;
        }

        .faq-arrow {
            transition: transform 0.3s ease;
        }

        .faq-arrow.rotate {
            transform: rotate(90deg);
        }

        /* Title Styling */
        .faq-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
	    font-family: "Segoe UI";
            color: #ffffff;
            margin-bottom: 30px;
	    margin-top: 30px;
        }

    </style>
</head>

<body style="background-color: #0C3D65;">

    <!-- Main FAQ Content -->
    <div class="fromDivImgDiv">
        <div class="divForForm">
            <h1 class="faq-title">Frequently Asked Questions</h1>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        What is QuickMeet?
                        <span class="faq-arrow">▶</span>
                    </div>
                    <div class="faq-answer">Quickmeet is a platform that allows students and staff of the McGill University to book appointments quickly and easily by selecting a date and time.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Do I need a McGill email address to create an account on QuickMeet?
                        <span class="faq-arrow">▶</span>
                    </div>
                    <div class="faq-answer">Yes. Our system allows you to register only with a valid McGill email address to make sure that you are a McGill staff or user.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Can I schedule meetings with multiple participants?
                        <span class="faq-arrow">▶</span>
                    </div>
                    <div class="faq-answer">Yes, QuickMeet allows you to schedule meetings with multiple participants. When creating a meeting, simply add multiple email addresses to invite attendees.</div>
                </div>

		<div class="faq-item">
                    <div class="faq-question">
                        How can I create a booking page with my availabilities?
                        <span class="faq-arrow">▶</span>
                    </div>
                    <div class="faq-answer">To create a booking page, go to your dashboard and click the "Create Booking" button. Add a title, description, and date for your booking. It will then be displayed on your dashboard. Click on the booking you created to add time slots and share the link with others.</div>
                </div>

		<div class="faq-item">
                    <div class="faq-question">
                        How will I know the time of my reservation if I am not registered?
                        <span class="faq-arrow">▶</span>
                    </div>
                    <div class="faq-answer">When you book a time slot using the shared URL, you will receive an email confirmation containing all the details of your meeting.</div>
                </div>


		<div class="faq-item">
                    <div class="faq-question">
                        What should I do if I am not available during the listed availabilities in the URL provided to me?
                        <span class="faq-arrow">▶</span>
                    </div>
                    <div class="faq-answer">You can send a request to the meeting holder with your possible alternate times. The meeting holder will be notified of your request and can adjust the schedule accordingly.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        Is QuickMeet available on mobile devices?
                        <span class="faq-arrow">▶</span>
                    </div>
                    <div class="faq-answer">Yes! QuickMeet is fully responsive and can be used on mobile devices. You can access the website from any smartphone or tablet to manage your meetings on the go.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Who can I contact for support?
                        <span class="faq-arrow">▶</span>
                    </div>
                    <div class="faq-answer">You can reach out to our support team by sending an email to quickmeet.mcgill@gmail.com</div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Navbar and FAQ Functionality --!>
    <script>
        function dropDownMenu() {
            const linksForPhone = document.querySelector(".LinksForPhone");
            linksForPhone.classList.toggle('show');
        }

        document.querySelectorAll('.faq-question').forEach((question) => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const arrow = question.querySelector('.faq-arrow');

                answer.classList.toggle('show');

                arrow.classList.toggle('rotate');
            });
        });
    </script>

</body>
</html>
