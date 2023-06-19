import {
  findNodeByInnerHTML, planeFormation, addNewSessionFilm, addSessionFilmInfo,
} from './additions2';

const Nav = document.querySelector('nav'); //лента навигации по дням сеансов

const days_week = [
  'Воскресенье',
  'Понедельник',
  'Вторник',
  'Среда',
  'Четверг',
  'Пятница',
  'Суббота'
];

const months = [
  'Янв',
  'Фев',
  'Мар',
  'Апр',
  'Май',
  'Июн',
  'Июл',
  'Авг',
  'Сент',
  'Окт',
  'Нояб',
  'Дек'
];

let now = new Date();
let day_week = days_week[now.getDay()]; // текущий день недели
let day = now.getDate();           // текущий день месяца
let month = now.getMonth();           // текущий месяц

Nav.querySelector('.page-nav__day_today').querySelectorAll('span')[0].textContent = day_week;
Nav.querySelector('.page-nav__day_today').querySelectorAll('span')[1].textContent = day;
Nav.querySelector('.page-nav__day_today').querySelectorAll('span')[3].textContent = months[month];