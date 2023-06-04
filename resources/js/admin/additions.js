
export function findNodeByInnerHTML(nodelist, innerHTML){ // функция поиска элемента по innerHTML
    for(let i = 0; i < nodelist.length; i++){
        if(nodelist[i].innerHTML === innerHTML)
            return nodelist[i]
    }
  }
  
  export function planeFormation (seats, hall_plane) {  // функция формирования массива "Планировка зала"
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

  export function addNewSessionFilm(HallSessionsPlan, filmname, time, startpixel, stoppixel, posterpath, planename) { // ВСТАВКА НОВОГО ЭЛЕМЕНТА (сеанс фильма) внутрь суточного плана
    
    const width = stoppixel - startpixel;

    HallSessionsPlan.insertAdjacentHTML('afterbegin',
        `<div class="conf-step__seances-movie" name="filmSession" data-planename="${planename}" data-tickets="" data-startpixel="${startpixel}" data-stoppixel="${stoppixel}" data-mutator="add" style="width: ${width}px; background-color: rgb(133, 255, 137); left: ${startpixel}px; cursor: pointer;">
            <fieldset title="${filmname}">
                <img class="conf-step__movie-poster" style="width: 100%; height: 100%; top: 0; left: 0; position: absolute; border: 2px dashed red;" alt="poster" src=${posterpath}>
            </fieldset>      
            <p class="conf-step__seances-movie-start">${time}</p>
        </div>`);
    
  }