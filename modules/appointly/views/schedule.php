<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="content">
    <h4>Schedule for <?php echo $username; ?></h4>
    <div id="calendar-container">
        <!-- Your calendar HTML and JavaScript here -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarContainer = document.getElementById('calendar-container');
        // Reuse the calendar JavaScript code provided earlier
        // Ensure it fits within the Perfex CRM UI

        // Example:
        const timeslots = [
            '02:40', '02:50', '03:10', '03:20', '03:40', '03:50', '04:00', '04:20'
        ];

        const selectedDateElem = document.createElement('p');
        const timeslotList = document.createElement('div');
        timeslotList.id = 'timeslot-list';

        function renderCalendar() {
            // Your calendar rendering logic here
            // ...
        }

        function loadTimeSlots(date) {
            timeslotList.innerHTML = '';
            timeslots.forEach(slot => {
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

        renderCalendar();
        calendarContainer.appendChild(selectedDateElem);
        calendarContainer.appendChild(timeslotList);
    });
</script>
