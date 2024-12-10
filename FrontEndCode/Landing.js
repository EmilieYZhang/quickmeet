// function getDateForCalendar(){
//     const curDateObj = new Date();
//     const curYear = curDateObj.getFullYear();
//     const curMonth = curDateObj.getMonth();
//     const curDay = curDateObj.getDate();
// }




const calendarEl = document.querySelector('.calendar');

function renderCalendar() {
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth(); // 0-based
    const today = now.getDate();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

           //the month
        //    const monthNames = [
        //     "January", "February", "March", "April", "May", "June",
        //     "July", "August", "September", "October", "November", "December"
        //     ];
        
        //     const currentMonth = monthNames[now.getMonth()]; 
        
            
        //     calendarEl.innerHTML = `<div class="month-name">${currentMonth}</div>` + calendarEl.innerHTML;
        //

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

// Render the calendar when the page loads
renderCalendar();

