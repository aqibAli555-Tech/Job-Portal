<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        @media only screen and (max-width: 1000px) {
            .main {
                width: 85% !important;
            }
        }
    </style>
</head>
<div class="container">

    <h1>
        Subject:
        Company X has shortlisted you for an interview!

        Dear Person X,

        Company X has shortlisted you for a potential interview for the position of X that you have applied for.

        If Company X chooses you between other shortlisted applicants and is interested, they will contact you by the phone number and/or email you put on your CV.
        They may also contact you by Direct Message through your HungryForJobs dashboard.

        Good Luck!

        Stay Hungry, Stay Munching,
        Hungry For Jobs Team
        <h1>
</div>


@includeFirst([config('larapen.core.customizedViewPath') . 'emails.inc.footer', 'emails.inc.footer'])