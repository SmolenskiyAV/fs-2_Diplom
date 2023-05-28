
const headers = Array.from(document.querySelectorAll('.conf-step__header'));

function findNodeByInnerHTML(nodelist, innerHTML){ // функция поиска элемента по innerHTML
  for(let i = 0; i < nodelist.length; i++){
      if(nodelist[i].innerHTML === innerHTML)
          return nodelist[i]
  }
}

function planeFormation (seats, hall_plane) {  // функция формирования массива "Планировка зала"
  for (let j = 0; j < seats.length; j++) {  

    let row, seat, type = 0;

    row = seats[j].dataset.row;
    seat = seats[j].dataset.seat;
    type = seats[j].dataset.type;

    hall_plane[j] = [row, seat, type];
  }

  if (document.querySelector('input[name="hall_plane"]')) {
    document.querySelector('input[name="hall_plane"]').value = JSON.stringify(hall_plane); // заполнение скрытого поля формы "Планировка зала"
  }
  
}

const HallsControl = document.getElementById('Halls_Control');  //секция "Управление залами"
const HallsControlBtnsTrash = HallsControl.querySelectorAll('.conf-step__button-trash');
const addHallBtn = findNodeByInnerHTML(HallsControl.querySelectorAll('.conf-step__button-accent'), 'Создать зал');
const InputFields = document.querySelectorAll('.conf-step__input'); //коллеция input-полей 

const HallConfig = document.getElementById('Hall_Config');      //секция "Конфигурация залов"
const cancelBtnPlane = findNodeByInnerHTML(HallConfig.querySelectorAll('.conf-step__button-regular'), 'Отмена');
const hallPlane = HallConfig.querySelector('.conf-step__hall')  // блок "План мест зала"
const seats = hallPlane.querySelectorAll('.conf-step__chair'); //коллеция мест 

const CostConfig = document.getElementById('Cost_Config');      //секция "Конфигурация цен"
const cancelBtnCost = findNodeByInnerHTML(CostConfig.querySelectorAll('.conf-step__button-regular'), 'Отмена');

const popupCreateHall = document.getElementById('Halls_Create'); //popup "Создать зал"
const InputCreateHall = popupCreateHall.querySelector('.conf-step__inputв'); //input-поле в popup "Создать зал"
const popupDeleteHall = document.getElementById('Halls_Delete'); //popup "Удалить зал"

const SeanceConfig = document.getElementById('Seance_Config');  //секция "Сетка сеансов"
const SessionsPlaneBtnsTrash = SeanceConfig.querySelectorAll('.conf-step__button-trash');
const addFilmBtn = findNodeByInnerHTML(SeanceConfig.querySelectorAll('.conf-step__button-accent'), 'Добавить фильм');
const addHallSessionsPlanBtn = findNodeByInnerHTML(SeanceConfig.querySelectorAll('.conf-step__button-accent'), 'Добавить суточный план');
const cancelBtnFilm = findNodeByInnerHTML(SeanceConfig.querySelectorAll('.conf-step__button-regular'), 'Отмена');

const popupFilmAdd = document.getElementById('Films_Add'); //popup "Добавить фильм"
const popupDeleteFilm = document.getElementById('Films_Delete'); //popup "Удалить фильм"
const FilmsTitleElementTrash = SeanceConfig.querySelectorAll('.conf-step__movie-title');
const FilmsDurationElementTrash = SeanceConfig.querySelectorAll('.conf-step__movie-duration');
const popupHallSessionsPlan = document.getElementById('HallSessionsPlan_Add'); //popup "Добавить суточный план"
const popupDeleteSessionsPlane = document.getElementById('SessionsPlane_Delete'); //popup "Удалить суточный план"

const addFilmSessionBtns = SeanceConfig.querySelectorAll('h3');
const popupFilmSessionAdd = document.getElementById('FilmSession_Add'); //popup "Добавить сеанс фильма в суточный план"


const SaleStatus = document.getElementById('Sale_Status');      //секция "Открыть продажи"

let hall_name = '';
let film_name = '';
let hall_plane = [];
let hall_plane_default = [];
let usual_cost_default = 0;
let vip_cost_default = 0;
let planedHallName = '';
let planedHallDate = '';
let full_plane_name = '';


headers.forEach(header => header.addEventListener('click', () => {
  header.classList.toggle('conf-step__header_closed');
  header.classList.toggle('conf-step__header_opened');
}));

addHallBtn.addEventListener('click', () => { // клик "создать зал"
  popupCreateHall.classList.add('active');
});

popupCreateHall.addEventListener('click', (event) => { // клик-события внутри popup "Создать зал"
  const { target } = event;
  
  if ((target.alt === 'Закрыть') || (target.innerHTML === "Отменить")) {
    event.preventDefault();
    InputCreateHall.value = '';
    popupCreateHall.classList.remove('active');
  }
});

for (let d = 0; d < HallsControlBtnsTrash.length; d++) {
  HallsControlBtnsTrash[d].addEventListener('click', (event) =>{ // клик "удалить зал"
    const { target } = event;

    hall_name = target.parentElement.textContent;
    popupDeleteHall.querySelector('span').textContent = hall_name;
    popupDeleteHall.querySelector('input[name="hall_name"]').value = hall_name; // заполнение скрытого поля формы "Удалить зал"
    
    popupDeleteHall.classList.add('active');
    hall_name = '';
  });
}

popupDeleteHall.addEventListener('click', (event) => { // клик-события внутри popup "Удалить зал"
  const { target } = event;
  
  if ((target.alt === 'Закрыть') || (target.innerHTML === "Отменить")) {
    event.preventDefault();
    popupDeleteHall.classList.remove('active');
    
    hall_name = '';
    popupDeleteHall.querySelector('input[name="hall_name"]').value = '';  // очистка скрытого поля формы "Удалить зал"
  }
  
});

for (let i = 0; i < InputFields.length; i++) {    // Разрешаем ввод только цифр в полях input секций "Конфигурация залов" и "Конфигурация цен"
  InputFields[i].addEventListener('keydown', (event) =>{ 
    // Разрешаем: backspace, delete, tab и escape
	if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
		// Разрешаем: Ctrl+A
		(event.keyCode == 65 && event.ctrlKey === true) ||
		// Разрешаем: home, end, влево, вправо
		(event.keyCode >= 35 && event.keyCode <= 39)) {
		
		return;
	} else {
		// Запрещаем все, кроме цифр на основной клавиатуре, а так же Num-клавиатуре
		if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
			event.preventDefault();
		}
	}
  });
}

document.addEventListener('DOMContentLoaded', () => { // после того, как страница загружена
  
  planeFormation (seats, hall_plane);  // заполнить "Планировку зала" по умолчанию 

  for (let j = 0; j < seats.length; j++) {  // запомнить первоначальные значения планировки зала

    let row, seat, type = 0;

    row = seats[j].dataset.row;
    seat = seats[j].dataset.seat;
    type = seats[j].dataset.type;

    hall_plane_default[j] = [row, seat, type];
  }

  usual_cost_default = document.querySelector('input[name="hall_usual_cost"]').value; // запомнить первоначальное значение цены обычного места
  vip_cost_default = document.querySelector('input[name="hall_vip_cost"]').value;     // запомнить первоначальное значение цены vip-места

});

for (let s = 0; s < seats.length; s++) {
  seats[s].addEventListener('click', (event) =>{ // циклический клик "поменять тип места"
    const { target } = event;

    if (target.classList.contains("conf-step__chair_disabled")) {
      target.classList.replace('conf-step__chair_disabled', 'conf-step__chair_standart');
      target.dataset.type=1;  // тип места = "обычное"
      planeFormation (seats, hall_plane);
      return
    }

    if (target.classList.contains("conf-step__chair_standart")) {
      target.classList.replace('conf-step__chair_standart', 'conf-step__chair_vip');
      target.dataset.type=2;  // тип места = "vip"
      planeFormation (seats, hall_plane);
      return
    }

    if (target.classList.contains("conf-step__chair_vip")) {
      target.classList.replace('conf-step__chair_vip', 'conf-step__chair_disabled');
      target.dataset.type=0;  // тип места = "нет кресла"
      planeFormation (seats, hall_plane);
      return
    }
    
  });
}

if (document.querySelector('input[name="hall_plane"]')) {
  cancelBtnPlane.addEventListener('click', (event) => { // клик "ОТМЕНА" в секции "КОНФИГУРАЦИЯ ЗАЛОВ"
    event.preventDefault();
    document.querySelector('input[name="hall_plane"]').value = JSON.stringify(hall_plane_default); // заполнение первоначальным значением скрытого поля формы "Планировка зала"
    for (let s = 0; s < seats.length; s++) {
   
      seats[s].dataset.type = hall_plane_default[s][2];
    
      seats[s].className = 'conf-step__chair';
    
      if (seats[s].dataset.type == 1) {
        seats[s].classList.add('conf-step__chair_standart');      
      } 
      if (seats[s].dataset.type == 2) {
        seats[s].classList.add('conf-step__chair_vip');     
      } 
      if (seats[s].dataset.type == 0) {
        seats[s].classList.add('conf-step__chair_disabled');      
      }        
    }
  });

  cancelBtnCost.addEventListener('click', (event) => { // клик "ОТМЕНА" в секции "КОНФИГУРАЦИЯ ЦЕН"
    event.preventDefault();

    document.querySelector('input[name="hall_usual_cost"]').value = usual_cost_default; // заполнение первоначальным значением поля формы "Цена обычного места"
    document.querySelector('input[name="hall_vip_cost"]').value = vip_cost_default;     // заполнение первоначальным значением поля формы "Цена vip-места"

  });
};

addFilmBtn.addEventListener('click', () => { // клик "Добавить фильм"
  popupFilmAdd.classList.add('active');
});

popupFilmAdd.addEventListener('click', (event) => { // клик-события внутри popup "Добавить фильм"
  const { target } = event;
  
  if ((target.alt === 'Закрыть') || (target.innerHTML === "Отменить")) {
    event.preventDefault();
    document.querySelector('input[name="film_name"]').value = "";
    document.querySelector('input[name="film_duration"]').value = '';
    document.querySelector('input[name="poster"]').value = '';
    popupFilmAdd.classList.remove('active');
  }
});

for (let t = 0; t < FilmsTitleElementTrash.length; t++) {
  FilmsTitleElementTrash[t].addEventListener('click', (event) =>{ // клик по титлу "удалить фильм"
    const { target } = event;

    film_name = target.textContent;
    popupDeleteFilm.querySelector('span').textContent = film_name;
    popupDeleteFilm.querySelector('input[name="film_name"]').value = film_name; // заполнение скрытого поля формы "Удалить фильм"
    
    popupDeleteFilm.classList.add('active');
    film_name = '';
  });
}

for (let d = 0; d < FilmsDurationElementTrash.length; d++) {
  FilmsDurationElementTrash[d].addEventListener('click', (event) =>{ // клик по элементу "продолжительность фильма" "удалить фильм"
    const { target } = event;

    film_name = target.dataset.name;
    popupDeleteFilm.querySelector('span').textContent = film_name;
    popupDeleteFilm.querySelector('input[name="film_name"]').value = film_name; // заполнение скрытого поля формы "Удалить фильм"
    
    popupDeleteFilm.classList.add('active');
    film_name = '';
  });
}

popupDeleteFilm.addEventListener('click', (event) => { // клик-события внутри popup "Удалить фильм"
  const { target } = event;
  
  if ((target.alt === 'Закрыть') || (target.innerHTML === "Отменить")) {
    event.preventDefault();
    popupDeleteFilm.classList.remove('active');
    
    film_name = '';
    popupDeleteFilm.querySelector('input[name="film_name"]').value = '';  // очистка скрытого поля формы "Удалить фильм"
  }
  
});

addHallSessionsPlanBtn.addEventListener('click', () => { // клик "Добавить суточный план"
  popupHallSessionsPlan.classList.add('active');
  popupHallSessionsPlan.querySelector('input[name="sessions_date"]').value = '';  // очистка поля формы "Дата плана в сетке сеансов"
});

popupHallSessionsPlan.addEventListener('click', (event) => { // клик-события внутри popup "Добавить суточный план"
  const { target } = event;
  
  if ((target.alt === 'Закрыть') || (target.innerHTML === "Отменить")) {
    event.preventDefault();

    popupHallSessionsPlan.querySelector('input[name="sessions_date"]').value = '';  // очистка поля формы "Дата плана в сетке сеансов"

    popupHallSessionsPlan.classList.remove('active');
  }
});

for (let d = 0; d < SessionsPlaneBtnsTrash.length; d++) {
  SessionsPlaneBtnsTrash[d].addEventListener('click', (event) =>{ // клик "удалить суточный план"
    const { target } = event;

    planedHallName = target.dataset.planedhallname;
    planedHallDate = target.dataset.planedhalldate;
    full_plane_name = target.dataset.fullplanedname;
    popupDeleteSessionsPlane.querySelector('span[name="planedHallName"]').textContent = planedHallName;
    popupDeleteSessionsPlane.querySelector('span[name="planedHallDate"]').textContent = planedHallDate;
    popupDeleteSessionsPlane.querySelector('input[name="fullPlanedName"]').value = full_plane_name; // заполнение скрытого поля формы "Удалить суточный план"
    popupDeleteSessionsPlane.querySelector('input[name="hallPlanedName"]').value = planedHallName;
    popupDeleteSessionsPlane.querySelector('input[name="hallPlanedDate"]').value = planedHallDate;

    popupDeleteSessionsPlane.classList.add('active');
    
    planedHallName = '';
    planedHallDate = '';
    full_plane_name = '';
  });
}

popupDeleteSessionsPlane.addEventListener('click', (event) => { // клик-события внутри popup "Удалить суточный план"
  const { target } = event;
  
  if ((target.alt === 'Закрыть') || (target.innerHTML === "Отменить")) {
    event.preventDefault();
    popupDeleteSessionsPlane.classList.remove('active');
    
    planedHallName = '';
    planedHallDate = '';
    full_plane_name = '';
    popupDeleteSessionsPlane.querySelector('input[name="fullPlanedName"]').value = '';  // очистка скрытого поля формы "Удалить суточный план"
  }
  
});

for (let a = 0; a < addFilmSessionBtns.length; a++) {
  addFilmSessionBtns[a].addEventListener('click', (event) => { // клик "Добавить сеанс в суточный план"
    const { target } = event
    event.preventDefault();

    planedHallName = target.textContent;
    planedHallDate = target.nextSibling.textContent;

    popupFilmSessionAdd.querySelector('span[name="hall_name"]').textContent = planedHallName;
    popupFilmSessionAdd.querySelector('span[name="film_date"]').textContent = planedHallDate;
    popupFilmSessionAdd.querySelector('input[name="hall_planed_name"]').value = planedHallName;
    popupFilmSessionAdd.querySelector('input[name="session_date"]').value = planedHallDate;

    popupFilmSessionAdd.classList.add('active');
    planedHallName ='';
    planedHallDate = '';
  })
}

popupFilmSessionAdd.addEventListener('click', (event) => { // клик-события внутри popup "Добавить сеанс в суточный план"
  const { target } = event;
  
  if ((target.alt === 'Закрыть') || (target.innerHTML === "Отменить")) {
    event.preventDefault();

    popupFilmSessionAdd.classList.remove('active');

    popupFilmSessionAdd.querySelector('span[name="hall_name"]').value = '';
    popupFilmSessionAdd.querySelector('span[name="film_date"]').value = '';
    popupFilmSessionAdd.querySelector('input[name="hall_planed_name"]').value = '';
    popupFilmSessionAdd.querySelector('input[name="session_date"]').value = '';
    popupFilmSessionAdd.querySelector('input[name="session_time"]').value = '';
    popupFilmSessionAdd.querySelector('span[name="popupWarning1"]').style.display = 'none';
  }

  if (target.innerHTML === "Добавить сеанс") {
    event.preventDefault();
  
    if ((popupFilmSessionAdd.querySelector('input[name="session_time"]').value === '') || 
        (popupFilmSessionAdd.querySelector('input[name="session_time"]').value === NULL) ||
        (popupFilmSessionAdd.querySelector('input[name="session_time"]').value === undefined)) {
      
          popupFilmSessionAdd.querySelector('span[name="popupWarning1"]').style.display = 'block';
      return;
    }
  }
});