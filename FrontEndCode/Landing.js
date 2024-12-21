/*@author (Serhii Artemenko)*/
//Just displays a calendar with the current day highlighted.
const calendarEl = document.querySelector('.calendar');

function renderCalendar() {
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth(); // 0-based
    const today = now.getDate();
    const daysInMonth = new Date(year, month + 1, 0).getDate();


    for (let day = 1; day <= daysInMonth; day++) {
 

        const newDay = document.createElement('div');
        newDay.classList.add('date'); // Add styling class
        newDay.textContent = day; // Set the day number

        if (day === today) {
            newDay.classList.add('current-day'); 
        }

        calendarEl.appendChild(newDay); 
    }


   
}

renderCalendar();

