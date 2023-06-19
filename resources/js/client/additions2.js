
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

  export function addSessionFilmInfo(filmSessionInfo, arrayObj, rows, seats_per_row) { // ВСТАВКА ЭЛЕМЕНТА "Билеты на сеанс"
    
    const length = arrayObj[0].length;
    let htm_element = '';
    let count = 0;
    let vip_sold = 0;       // продано vip-билетов
    let vip_all =0;
    let stndrd_sold = 0;    // продано обычных билетов
    let stndrd_all = 0;

    for (let s = 0; s < seats_per_row; s++) {
        htm_element = htm_element + '<div class="conf-step__hall-wrapper" style="margin-left: 4px; margin-right: 4px">\n';

        for (let r = 0; r < rows; r++) {
            
            let type = arrayObj[count].type;
            let sold = arrayObj[count].sold;
            let qr = arrayObj[count]["qr-code"];

            htm_element = htm_element + '<div style="margin-top: 8px; margin-bottom: 8px">\n';

            if (type === 1 && sold == 0){
                htm_element = htm_element + `<span class="conf-step__chair conf-step__chair_standart" data-row="${r}" data-seat="${s}" data-type=1></span>\n`;
                stndrd_all++;
            } 
            if (type === 2 && sold == 0){
                htm_element = htm_element + `<span class="conf-step__chair conf-step__chair_vip" data-row="${r}" data-seat="${s}" data-type=1></span>\n`;
                vip_all++;
            }

            if (type === 1 && sold == 1){
                htm_element = htm_element + `<span class="conf-step__chair conf-step__chair_standart" data-row="${r}" data-seat="${s}" data-type=2 style="border: 3px solid red"></span>\n`;
                stndrd_all++;
                stndrd_sold++;
            } 
            if (type === 2 && sold == 1){
                htm_element = htm_element + `<span class="conf-step__chair conf-step__chair_vip" data-row="${r}" data-seat="${s}" data-type=2 style="border: 3px solid red"></span>\n`;
                vip_sold++;
                vip_all++;
            }

            if (type === 0){
                htm_element = htm_element + `<span class="conf-step__chair conf-step__chair_disabled" data-row="${r}" data-seat="${s}" data-type=0></span>\n`;
            }

            count++;
            htm_element = htm_element + '</div>\n';
        }
        htm_element = htm_element + '</div>';
    }
    
    filmSessionInfo.insertAdjacentHTML('afterbegin', htm_element);

    return [vip_sold, stndrd_sold, vip_all, stndrd_all];
    
  }