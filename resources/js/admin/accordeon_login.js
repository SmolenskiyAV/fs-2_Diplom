

const WaitBgrnd = document.getElementById('Waiting_Background');      // заглушка "Фон ожидания"
const btnRedirect = document.querySelector('button[name="redirect"]');
const btnSendMe = document.querySelector('button[name="sendMe"]');



document.querySelector('button').addEventListener('click', (event) => {
  const { target } = event;

	if ((target === btnSendMe) && 
		(document.querySelector('form').querySelector('input[name="email"]').checkValidity()) &&
		(document.querySelector('form').querySelector('input[name="password"]').checkValidity())) {

			WaitBgrnd.classList.add('active');  // ЗАГЛУШКА "Жди..."  
	}	   
});



