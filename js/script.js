
const $checkbox = document.querySelector('.show_completed');

if ($checkbox) {
  $checkbox.addEventListener('change', (event) => {
    const is_checked = event.target.checked ? 1 : 0;  

    const searchParams = new URLSearchParams(window.location.search);
    searchParams.set('show_completed', is_checked);

    window.location = '/index.php?' + searchParams.toString();
  });
}

const $taskCheckboxes = document.querySelector('.tasks');

if($taskCheckboxes) {
  $taskCheckboxes.addEventListener('change', (event) => {
    const checkbox = event.target;
    console.log(checkbox)
    if(checkbox.matches('.checkbox__input')) {
      const is_checked = checkbox.checked ? 1 : 0;  
      const task_id = checkbox.id;

      const searchParams = new URLSearchParams(window.location.search);
      searchParams.set('task_id', task_id);
      searchParams.set('check', is_checked);

      window.location = '/index.php?' + searchParams.toString();;
    }
  });
}

flatpickr('#date', {
  enableTime: false,
  dateFormat: "Y-m-d",
  locale: "ru"
});


