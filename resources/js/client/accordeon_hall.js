
const seatsCollection = document.querySelector('.buying-scheme__wrapper').querySelectorAll('.buying-scheme__chair'); // коллекция всех мест в плане зала
const WaitBgrnd = document.getElementById('Waiting_Background');      // заглушка "Фон ожидания"

for (let s = 0; s < seatsCollection.length; s++) {
    seatsCollection[s].addEventListener('click', (event) =>{ // циклический клик "выбрать/отменить выбор места"
      const { target } = event;
  
      if (target.classList.contains("buying-scheme__chair_standart")) {
        target.classList.replace('buying-scheme__chair_standart', 'buying-scheme__chair_selected');
        target.dataset.selected="buying-scheme__chair_standart";  // выбор места = "обычное"        
        return
      }
  
      if ((target.classList.contains("buying-scheme__chair_selected")) && (target.dataset.selected === 'buying-scheme__chair_standart' )) {
        target.classList.replace('buying-scheme__chair_selected', 'buying-scheme__chair_standart');
        target.dataset.selected="";  // отмена выбора места = "обычное"        
        return
      }
  
      if (target.classList.contains("buying-scheme__chair_vip")) {
        target.classList.replace('buying-scheme__chair_vip', 'buying-scheme__chair_selected');
        target.dataset.selected="buying-scheme__chair_vip";  // выбор места = "vip"        
        return
      }
  
      if ((target.classList.contains("buying-scheme__chair_selected")) && (target.dataset.selected === 'buying-scheme__chair_vip' )) {
        target.classList.replace('buying-scheme__chair_selected', 'buying-scheme__chair_vip');
        target.dataset.selected="";  // отмена выбора места = "vip"        
        return
      }
      
    });
  }

  document.querySelector('button').addEventListener('click', () => { // клик "Забронировать"
    
    WaitBgrnd.classList.add('active');  // ЗАГЛУШКА "Жди..."  
});