
const seatsCollection = document.querySelector('.buying-scheme__wrapper').querySelectorAll('.buying-scheme__chair'); // коллекция всех мест в плане зала
const vip_cost = Number(document.querySelector('.buying-scheme__legend').firstElementChild.lastElementChild.lastElementChild.textContent);  // цена vip-билета
const usual_cost = Number(document.querySelector('.buying-scheme__legend').firstElementChild.firstElementChild.lastElementChild.textContent); // цена обычного билета
const WaitBgrnd = document.getElementById('Waiting_Background');      // заглушка "Фон ожидания"
const BookBtn = document.querySelector('.acceptin-button');  // кнопка "Забронировать"

document.querySelector('input[name="arr"]').value ='';
BookBtn.setAttribute('style', 'display: none');

const usual_price = Number(usual_cost.textContent);
const vip_price = Number(vip_cost.textContent);
let total_cost = 0; // стоимость всех выбранных билетов
let arr = [];   // массив объектов всех выбранных билетов

for (let s = 0; s < seatsCollection.length; s++) {
    seatsCollection[s].addEventListener('click', (event) =>{ // циклический клик "выбрать/отменить выбор места"
      const { target } = event;
  
      if (target.classList.contains("buying-scheme__chair_standart")) {
        target.classList.replace('buying-scheme__chair_standart', 'buying-scheme__chair_selected');
        target.dataset.selected="buying-scheme__chair_standart";  // выбор места = "обычное" 
        
        let addedNewSeat ={
          row: target.dataset.row,
          seat:target.dataset.seat,
          seatnumber: target.dataset.seatnumber 
        }

        arr.push(addedNewSeat);
        total_cost = total_cost + usual_cost;
        document.querySelector('input[name="arr"]').value = JSON.stringify(arr);
        document.querySelector('input[name="total_cost"]').value = total_cost;
        BookBtn.setAttribute('style', 'cursor: pointer');

        return
      }
  
      if ((target.classList.contains("buying-scheme__chair_selected")) && (target.dataset.selected === 'buying-scheme__chair_standart' )) {
        target.classList.replace('buying-scheme__chair_selected', 'buying-scheme__chair_standart');
        target.dataset.selected="";  // отмена выбора места = "обычное" 
        
        const del_id = target.dataset.seatnumber;
        arr.splice(arr.findIndex(n => n.seatnumber === del_id), 1); 
        
        total_cost = total_cost - usual_cost;
        document.querySelector('input[name="arr"]').value = JSON.stringify(arr);
        document.querySelector('input[name="total_cost"]').value = total_cost;

        if (arr.length === 0) {
            BookBtn.setAttribute('style', 'display: none');
        } else {
            BookBtn.setAttribute('style', 'cursor: pointer');
        }
        
        return
      }
  
      if (target.classList.contains("buying-scheme__chair_vip")) {
        target.classList.replace('buying-scheme__chair_vip', 'buying-scheme__chair_selected');
        target.dataset.selected="buying-scheme__chair_vip";  // выбор места = "vip"
        
        let addedNewSeat ={
          row: target.dataset.row,
          seat:target.dataset.seat,
          seatnumber: target.dataset.seatnumber 
        }

        arr.push(addedNewSeat);
        total_cost = total_cost + vip_cost;
        document.querySelector('input[name="arr"]').value = JSON.stringify(arr);
        document.querySelector('input[name="total_cost"]').value = total_cost;
        BookBtn.setAttribute('style', 'cursor: pointer');

        return
      }
  
      if ((target.classList.contains("buying-scheme__chair_selected")) && (target.dataset.selected === 'buying-scheme__chair_vip' )) {
        target.classList.replace('buying-scheme__chair_selected', 'buying-scheme__chair_vip');
        target.dataset.selected="";  // отмена выбора места = "vip"

        const del_id = target.dataset.seatnumber;        
        arr.splice(arr.findIndex(n => n.seatnumber === del_id), 1);
        
        total_cost = total_cost - vip_cost;
        document.querySelector('input[name="arr"]').value = JSON.stringify(arr);
        document.querySelector('input[name="total_cost"]').value = total_cost;

        if (arr.length === 0) {
            BookBtn.setAttribute('style', 'display: none');
        } else {
            BookBtn.setAttribute('style', 'cursor: pointer');
        };
        
        return
      }      
    });
};

document.querySelector('button').addEventListener('click', () => { // клик "Забронировать"
    
    WaitBgrnd.classList.add('active');  // ЗАГЛУШКА "Жди..."  
});

