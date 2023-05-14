
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

  document.querySelector('input[name="hall_plane"]').value = JSON.stringify(hall_plane); // заполнение скрытого поля формы "Планировка зала"
  
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
const SeanceConfig = document.getElementById('Seance_Config');  //секция "Сетка сеансов"
const SaleStatus = document.getElementById('Sale_Status');      //секция "Открыть продажи"

const popupCreateHall = document.getElementById('Halls_Create'); //popup "Создать зал"
const popupDeleteHall = document.getElementById('Halls_Delete'); //popup "Удалить зал"

let hall_name = '';
let hall_plane = [];
let hall_plane_default = [];
let usual_cost_default = 0;
let vip_cost_default = 0;

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

for (let i = 0; i < InputFields.length; i++) {    // Разрешаем ввод только цифр в полях input
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

