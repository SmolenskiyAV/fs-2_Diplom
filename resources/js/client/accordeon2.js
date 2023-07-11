
const Nav = document.querySelector('nav'); //лента навигации по дням сеансов

let now = new Date().toLocaleString("ru-RU", {
  timeZone: "Europe/Moscow",
  dateStyle: "full"
});

const arr = now.split(/\s|,/);  

Nav.querySelector('.page-nav__day_today').querySelectorAll('span')[0].textContent = arr[0]; // текущий день недели
Nav.querySelector('.page-nav__day_today').querySelectorAll('span')[1].textContent = arr[2]; // текущий день месяца
Nav.querySelector('.page-nav__day_today').querySelectorAll('span')[3].textContent = arr[3]; // текущий месяц