<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<style>

    .container {
        display: flex;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        width: inherit;
    }
    .left, .right {
        padding: 20px;
    }
    .left {
        width: 40%;
        border-right: 1px solid #e0e0e0;
    }
    .right {
        width: 60%;
        display:flex;
        justify-content: space-around;
    }
    .right .calendar-container{
        width:280px;
    }
    h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }
    p {
        margin: 10px 0;
    }
    .calendar {
        display: flex;
        flex-wrap: wrap;
        margin-top: 10px;
        width: 25vw;
    }
    .calendar div {
        width: 40px;
        height: 40px;
        margin: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        background-color: #e0e0e0;
        cursor: pointer;
        font-size:10px;
        
    }
    .calendar div.selected {
        background-color: #007bff;
        color: #fff;
    }
    .calendar div.disabled {
        background-color: #f0f0f0;
        cursor: not-allowed;
    }
    .calendar div.disabled:empty {
        background: transparent;
    }
    .timezone {
        margin-top: 20px;
    }
    .month-switch {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 22vw;
    }
    .month-switch button {
        background-color: transparent;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }
    .timeslots {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
        margin-left: 30px
    }
    .timeslots button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 20px;
        cursor: pointer;
        margin-bottom: 10px;
    }
    .timeslot {
        min-width: 135px;
        border: 1px solid #007bff;
        border-radius: 5px;
        color: #007bff;
        margin: 5px 0;
        padding: 10px;
        text-align: center;
        cursor: pointer;
    }
    .timeslot.selected {
        background-color: #007bff;
        color: white;
    }
</style>

<body>

<div class="container">
    <div class="left">
        <img src="logo.png" alt="Pôle Démarches" style="width:100%;">
        <h1>Validation of Appointment by Video</h1>
        <p><strong>Duration:</strong> 10 min</p>
        <p>Online Conference Confirmation</p>
        <p><strong>Cost:</strong> 89 EUR</p>
        <p>2:20 - 3:20, Monday 1 July, 2024</p>
        <p>Pacafic Time - USA and Canada</p>
        <br>
        <p><strong>Documents to Provide:</strong></p>
        <ul>
        <ul>
            <li>Identity Documents</li>
            <li>Proof of Residence</li>
            <li>Any document related to your situation</li>
        </ul>
    </div>
    <div class="right">
        <div>
            <h2>Select Date and Time</h2>
            <div class="calendar-container">
                <div class="month-switch">
                    <button type="button" id="prev-month">&lt;</button>
                    <span id="current-month-year"></span>
                    <button type="button" id="next-month">&gt;</button>
                </div>
                <div class="calendar" id="calendar">
                    <!-- Calendar days will be generated here -->
                </div>
                <div class="timezone">
                    <p><strong>Time Zone:</strong></p>
                    <p>USA and Canada (10:53)</p>
                </div>
            </div>
        </div>
        <div class="timeslots" id="timeslots">
            <p id="selected-date"></p>
            <div id="timeslot-list"></div>
        </div>
    </div>
</div>

<script>
   
        const calendar = document.getElementById('calendar');
        const currentMonthYear = document.getElementById('current-month-year');
        const prevMonthButton = document.getElementById('prev-month');
        const nextMonthButton = document.getElementById('next-month');
        const timeslots = document.getElementById('timeslots');
        const timeslotList = document.getElementById('timeslot-list');
        const selectedDateElem = document.getElementById('selected-date');

        let date = new Date();
        
        const daysOfWeek = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        function renderCalendar() {
            calendar.innerHTML = '';
            const year = date.getFullYear();
            const month = date.getMonth();
            const firstDayOfMonth = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            currentMonthYear.textContent = `${monthNames[month]} ${year}`;

            // Add days of week headers
            daysOfWeek.forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.className = 'disabled';
                dayElement.textContent = day;
                calendar.appendChild(dayElement);
            });

            // Add empty elements for days before the first day of the month
            const startDay = (firstDayOfMonth + 6) % 7; // Adjust for Sunday start
            for (let i = 0; i < startDay; i++) {
                const emptyElement = document.createElement('div');
                emptyElement.className = 'disabled';
                calendar.appendChild(emptyElement);
            }

            // Add days of the month
            for (let i = 1; i <= daysInMonth; i++) {
                const dayElement = document.createElement('div');
                dayElement.textContent = i;
                dayElement.setAttribute('data-day', i);
                calendar.appendChild(dayElement);

                dayElement.addEventListener('click', function () {
                    document.querySelectorAll('.calendar div[data-day]').forEach(d => d.classList.remove('selected'));
                    this.classList.add('selected');
   
                    // Display timeslots and update the selected date
                    const selectedDate = `${daysOfWeek[(startDay + i - 1) % 7]}, ${i} ${monthNames[month]}`;
                    selectedDateElem.textContent = selectedDate;
                    timeslots.style.display = 'flex';
                    // Load time slots dynamically
                    loadTimeSlots(selectedDate);
                });
            }
        }
        function loadTimeSlots(date) {
            // This is a placeholder for dynamic slot loading logic.
            // You can replace it with an actual API call to fetch available time slots.
            const availableTimeSlots = ['02:40', '02:50', '03:10', '03:20', '03:40', '03:50', '04:00', '04:20'];

            timeslotList.innerHTML = '';
            availableTimeSlots.forEach(slot => {
                const slotElement = document.createElement('div');
                slotElement.className = 'timeslot';
                slotElement.textContent = slot;
                timeslotList.appendChild(slotElement);

                slotElement.addEventListener('click', function () {
                    document.querySelectorAll('.timeslot').forEach(t => t.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });
        }

        prevMonthButton.addEventListener('click', function () {
            date.setMonth(date.getMonth() - 1);
            renderCalendar();
        });

        nextMonthButton.addEventListener('click', function () {
            date.setMonth(date.getMonth() + 1);
            renderCalendar();
        });

        renderCalendar();

</script>








<script>

        const calendarDays = document.querySelectorAll('.calendar div[data-day]');
        
        calendarDays.forEach(day => {
            day.addEventListener('click', function () {
                // Remove 'selected' class from all days
                calendarDays.forEach(d => d.classList.remove('selected'));
                // Add 'selected' class to clicked day
                this.classList.add('selected');
            });
        });

</script>







    </div>
                        </div>
    
</body>
</html>
