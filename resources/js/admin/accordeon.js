import {
  findNodeByInnerHTML, planeFormation, addNewSessionFilm,
} from './additions';

const headers = Array.from(document.querySelectorAll('.conf-step__header'));

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
let SessionsPlaneBtnsTrash = SeanceConfig.querySelectorAll('.conf-step__button-trash');
let SessionsPlaneBtnsTrashLength = SessionsPlaneBtnsTrash.length;
const addFilmBtn = findNodeByInnerHTML(SeanceConfig.querySelectorAll('.conf-step__button-accent'), 'Добавить фильм');
const addHallSessionsPlanBtn = findNodeByInnerHTML(SeanceConfig.querySelectorAll('.conf-step__button-accent'), 'Добавить суточный план');
const cancelBtnFilm = findNodeByInnerHTML(SeanceConfig.querySelectorAll('.conf-step__button-regular'), 'Отмена');

const popupFilmAdd = document.getElementById('Films_Add'); //popup "Добавить фильм"
const popupDeleteFilm = document.getElementById('Films_Delete'); //popup "Удалить фильм"
const FilmsTitleElementTrash = SeanceConfig.querySelectorAll('.conf-step__movie-title');
const FilmsDurationElementTrash = SeanceConfig.querySelectorAll('.conf-step__movie-duration');
const popupHallSessionsPlan = document.getElementById('HallSessionsPlan_Add'); //popup "Добавить суточный план"
const popupDeleteSessionsPlane = document.getElementById('SessionsPlane_Delete'); //popup "Удалить суточный план"

let addFilmSessionBtns = SeanceConfig.querySelectorAll('h3[name="addfilmsessionbtn"]');
let addFilmSessionBtnsLength = addFilmSessionBtns.length;
const popupFilmSessionAdd = document.getElementById('FilmSession_Add'); //popup "Добавить сеанс фильма в суточный план"
const popupFilmSessionDel = document.getElementById('FilmSession_Del'); //popup "Удалить сеанс фильма из суточного плана"

const defaultSeancesPlans = document.getElementById('Seances_Plans');
let defaultSeancesPlansContent = defaultSeancesPlans.innerHTML; // дефолтная карта всех суточных планов
let filmSessions = document.getElementById('Seances_Plans').querySelectorAll('div[name="filmSession"]'); // коллекция всех сеансов в Сетке Сеансов
let filmSessionsLength = filmSessions.length;

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
let newSessionsArray = [];       // массив, для временного хранения добавленных/удалённых элементов Сетки Сеансов
let newSessionsArrayLength = 0;
let editingSessionsPlane = null; // переменная, для временного хранения элемента Суточного Плана, в который будет вставлен новый сеанс
document.querySelector('input[name="sessionsarray"]').value = [];

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

if (addHallSessionsPlanBtn){
  addHallSessionsPlanBtn.addEventListener('click', () => { // клик "Добавить суточный план"
    popupHallSessionsPlan.classList.add('active');
    popupHallSessionsPlan.querySelector('input[name="sessions_date"]').value = '';  // очистка поля формы "Дата плана в сетке сеансов"
  });
};

popupHallSessionsPlan.addEventListener('click', (event) => { // клик-события внутри popup "Добавить суточный план"
  const { target } = event;
  
  if ((target.alt === 'Закрыть') || (target.innerHTML === "Отменить")) {
    event.preventDefault();

    popupHallSessionsPlan.querySelector('input[name="sessions_date"]').value = '';  // очистка поля формы "Дата плана в сетке сеансов"

    popupHallSessionsPlan.classList.remove('active');
  }
});

defaultSeancesPlans.addEventListener('click', (event) => {  // клик-события внутри секции "СЕТКА СЕАНСОВ"
  const { target } = event
  event.preventDefault();
  if (filmSessions) { 
    for (let a = 0; a < filmSessionsLength; a++) {
      if (target === filmSessions[a].firstElementChild.firstElementChild){  // клик "Удалить сеанс из суточного плана"

        const currentFullName = target.parentElement.parentElement.dataset.planename;
        const currentFilmDate = currentFullName.split('*')[1];                              // получаем дату из названия суточного плана
        const currentHallName = currentFullName.substring(0, currentFullName.indexOf("*")); // получаем имя зала из названия суточного плана 
        const currentFilmName = target.parentElement.getAttribute('title');
        const currentSessionTime = target.parentElement.nextElementSibling.textContent;
        
        if(target.parentElement.parentElement.dataset.mutator === 'add'){
          target.parentElement.parentElement.remove();

          for (let p = 0; p < newSessionsArrayLength; p++) {
            if(newSessionsArray.find(item => item.hall_name === currentHallName && 
                item.session_date === currentFilmDate &&
                item.film_name === currentFilmName &&
                item.session_time === currentSessionTime)) {
              newSessionsArray.splice(p, 1);
              newSessionsArrayLength = newSessionsArray.length; 
              document.querySelector('input[name="sessionsarray"]').value = JSON.stringify(newSessionsArray);
            }
            console.log('fantom deleted. arr is: ',newSessionsArray);
            return;
          }
        }
        /*
        popupFilmSessionDel.querySelector('span[name="planedFilmName"]').textContent = currentFilmName;
        popupFilmSessionDel.querySelector('span[name="planedHallName"]').textContent = currentHallName;
        popupFilmSessionDel.querySelector('span[name="planedHallDate"]').textContent = currentFilmDate;
        popupFilmSessionDel.querySelector('input[name="fullSessionName"]').value = currentFullName;
                
        popupFilmSessionDel.classList.add('active');

        let formData = new FormData(popupFilmSessionDel);
        let xhr = new XMLHttpRequest();

        xhr.open("POST", "/infoFilmSession");
        xhr.send(formData);

        xhr.onload = () => alert(xhr.response);
        */
      }
    }       
  } 
  
  if (SessionsPlaneBtnsTrash) {
    for (let d = 0; d < SessionsPlaneBtnsTrashLength; d++) {
      if (target === SessionsPlaneBtnsTrash[d]){          // клик "удалить суточный план"

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
        
      }
    }
  }

  if (addFilmSessionBtns) {
    for (let c = 0; c < addFilmSessionBtnsLength; c++) {      
      if (target === addFilmSessionBtns[c]){          // клик "Добавить сеанс в суточный план"

        editingSessionsPlane = target.parentElement.parentElement.nextElementSibling;
    
        planedHallName = target.textContent;
        planedHallDate = target.nextSibling.textContent;
    
        popupFilmSessionAdd.querySelector('span[name="hall_name"]').textContent = planedHallName;
        popupFilmSessionAdd.querySelector('span[name="film_date"]').textContent = planedHallDate;
    
        popupFilmSessionAdd.classList.add('active');

        planedHallName ='';
        planedHallDate = '';
      }      
    }
  }

});

popupFilmSessionDel.addEventListener('click', (event) => { // клик-события внутри popup "Удалить сеанс из суточного плана"
  const { target } = event;
  
  if ((target.alt === 'Закрыть') || (target.innerHTML === "Отменить")) {
    event.preventDefault();

    popupFilmSessionAdd.classList.remove('active');

    popupFilmSessionDel.querySelector('span[name="planedFilmName"]').textContent = "";
    popupFilmSessionDel.querySelector('span[name="planedHallName"]').textContent = "";
    popupFilmSessionDel.querySelector('span[name="planedHallDate"]').textContent = "";
    popupFilmSessionDel.querySelector('input[name="fullSessionName"]').value = "";   
  }

  if (target.innerHTML === "Удалить сеанс") {
    event.preventDefault();
  
    const currenFullSessionName = popupFilmSessionDel.querySelector('input[name="fullSessionName"]').value;              

    
            
    
  }
});

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

popupFilmSessionAdd.addEventListener('click', (event) => { // клик-события внутри popup "Добавить сеанс в суточный план"
  const { target } = event;
  
  if ((target.alt === 'Закрыть') || (target.innerHTML === "Отменить")) {
    event.preventDefault();

    popupFilmSessionAdd.classList.remove('active');

    popupFilmSessionAdd.querySelector('span[name="hall_name"]').value = '';
    popupFilmSessionAdd.querySelector('span[name="film_date"]').value = '';
    popupFilmSessionAdd.querySelector('input[name="session_time"]').value = '';
    popupFilmSessionAdd.querySelector('span[name="popupWarning1"]').style.display = 'none';
    popupFilmSessionAdd.querySelector('span[name="popupWarning2"]').style.display = 'none';
  }

  if (target.innerHTML === "Добавить сеанс") {
    event.preventDefault();
  
    if ((popupFilmSessionAdd.querySelector('input[name="session_time"]').value === '') || 
        (popupFilmSessionAdd.querySelector('input[name="session_time"]').value === null) ||
        (popupFilmSessionAdd.querySelector('input[name="session_time"]').value === undefined)) {
      
          popupFilmSessionAdd.querySelector('span[name="popupWarning1"]').style.display = 'block';
      return;
    }
    /*
    let formData = new FormData(popupFilmSessionAdd.querySelector('form'));
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "/addFilmSessions");
    xhr.send(formData);

    xhr.onload = () => alert(xhr.response);*/
            
    const currentHallName = popupFilmSessionAdd.querySelector('span[name="hall_name"]').textContent;
    const currentFilmDate = popupFilmSessionAdd.querySelector('span[name="film_date"]').textContent;
    const planename = `${currentHallName}*${currentFilmDate}`;  //  имя суточного плана, в который вставляется новый сеанс
    const currentFilmName = popupFilmSessionAdd.querySelector('select[name="film_name"]').value;
    const currentSessionTime = popupFilmSessionAdd.querySelector('input[name="session_time"]').value;
    
    const currentHours = currentSessionTime[0] + currentSessionTime[1];
    const currentMinutes = currentSessionTime[3] + currentSessionTime[4];
           
    const FilmParameters = JSON.parse(popupFilmSessionAdd.querySelector('input[name="json_parameters"]').value)
    let currentFilmDuration = "";
    let currentPosterPath = "";
    
    for (let d = 0; d < FilmParameters.length; d++) {  
      if (FilmParameters[d].film_name === currentFilmName) {
        currentFilmDuration = FilmParameters[d].film_duration; // определяем продолжительность выбранного в форме фильма
        currentPosterPath = FilmParameters[d]. poster_path;    // определяем постер выбранного в форме фильма
      }
    }
    
    const startPixel = Math.floor(((Number(currentHours) * 60) + Number(currentMinutes)) * 0.5);  // левая граница значка сеанса в px (с округлением в мЕньшую сторону)
    const stopPixel = Math.ceil(startPixel + (currentFilmDuration * 0.5));                        // правая граница значка сеанса в px (с округлением в бОльшую сторону)
    filmSessions = document.getElementById('Seances_Plans').querySelectorAll('div[name="filmSession"]'); // переопределение коллекции всех сеансов в Сетке Сеансов
        
    if (filmSessions) {   // проверка: пересекается по времени создаваемый сеанс с уже существующими или не пересекается
      filmSessionsLength = filmSessions.length;
      
      for (let f = 0; f < filmSessionsLength; f++) {  
        
        if (filmSessions[f].dataset.planename === planename){
          if (((startPixel >= Number(filmSessions[f].dataset.startpixel)) && (startPixel <= Number(filmSessions[f].dataset.stoppixel))) || 
              ((stopPixel >= Number(filmSessions[f].dataset.startpixel)) && (stopPixel <= Number(filmSessions[f].dataset.stoppixel)))) {
                
              popupFilmSessionAdd.querySelector('span[name="popupWarning2"]').style.display = 'block';
            return;
          }
        } 
      }
    }

    addNewSessionFilm(editingSessionsPlane, currentFilmName, currentSessionTime, startPixel, stopPixel, currentPosterPath, planename); // отрисовка элемента нового сеанса в суточном плане
    
    let addedNewSession ={
      hall_name: currentHallName,
      session_date: currentFilmDate,
      film_name: currentFilmName,
      session_time: currentSessionTime,
      action: 'add' 
    }
    newSessionsArray.push(addedNewSession);
    newSessionsArrayLength = newSessionsArray.length; 
    document.querySelector('input[name="sessionsarray"]').value = JSON.stringify(newSessionsArray);  

    editingSessionsPlane = null;
    popupFilmSessionAdd.classList.remove('active');
        
    filmSessions = document.getElementById('Seances_Plans').querySelectorAll('div[name="filmSession"]'); // переопределение коллекции всех сеансов в Сетке Сеансов
    filmSessionsLength = filmSessions.length;    
  }

  popupFilmSessionAdd.querySelector('span[name="hall_name"]').value = '';
  popupFilmSessionAdd.querySelector('span[name="film_date"]').value = '';
  popupFilmSessionAdd.querySelector('input[name="session_time"]').value = '';
  popupFilmSessionAdd.querySelector('span[name="popupWarning1"]').style.display = 'none';
  popupFilmSessionAdd.querySelector('span[name="popupWarning2"]').style.display = 'none';
});


SeanceConfig.querySelector('form[name="operate_all_plans"]').addEventListener('click', (event) => { // клик-события на кнопки обработки всей карты суточных планов (отправить на сервер/отменить изменения)
  const { target } = event;

  if (target.innerHTML === "Отмена") {
    event.preventDefault();
    defaultSeancesPlans.innerHTML = "";
    newSessionsArray = [];  
    defaultSeancesPlans.insertAdjacentHTML('afterbegin', defaultSeancesPlansContent); // восстановления дефолтной карты суточных планов

    SessionsPlaneBtnsTrash = SeanceConfig.querySelectorAll('.conf-step__button-trash'); // переопределение коллекции кнопок "удалить суточный план"
    SessionsPlaneBtnsTrashLength = SessionsPlaneBtnsTrash.length; 
    addFilmSessionBtns = SeanceConfig.querySelectorAll('h3[name="addfilmsessionbtn"]'); // переопределение коллекции кнопок "добавить сеанс"
    addFilmSessionBtnsLength = addFilmSessionBtns.length;
  }

  if (target.value === "Сохранить") {
    console.log('***Final Array***')
    console.log('newSessionsArray is: ', newSessionsArray);
    console.log('*** end ***');
    
    /*
    let formData = new FormData(popupFilmSessionDel);
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "/changeFilmSession");
    xhr.send(formData);

    //xhr.onload = () => alert(xhr.response);*/
  }

}); 