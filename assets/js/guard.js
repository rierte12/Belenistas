let url = window.location.href;
let id = url.substring(url.lastIndexOf('/') + 1);
let calendarEl = document.getElementById('calendar');
fetch('/eventos/' + id + '/json').then(response => response.json()).then(data => {
    calendarEl.innerHTML = '';
    let calendar = new FullCalendar.Calendar(calendarEl, {
        validRange: {
            start: data.start,
            end: data.end
        },
        initialView: 'timeGridWeek',
        events: data.guards,
        allDaySlot: false,
        minTime: "08:00:00",
        maxTime: "23:00:00",
        height: 'auto',
        locale: 'es',
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana'
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,dayGridMonth'
        },
        selectable: true,
        select: createEvent,
        //eventContent: render,
        eventClick: eventClick,
    });
    calendar.render();
});

function createEvent(info) {
    let formBuilder = new FormBuilder();
    formBuilder.setParams('/guardias/new', 'POST');
    formBuilder.addDetails('Crear guardia', 'Rellena los campos para crear una guardia');
    formBuilder.addInputs([
        {
            label: 'Nº de voluntarios',
            type: 'number',
            name: 'volunteers',
            id: 'volunteers',
            placeholder: 'Ej: 2',
            value: 5,
            required: true
        },
        {
            label: 'Hora inicio',
            type: 'datetime-local',
            name: 'start',
            id: 'start',
            placeholder: 'Hora inicio',
            value: info.startStr.substring(0, 16),
            required: true
        },
        {
            label: 'Hora fin',
            type: 'datetime-local',
            name: 'end',
            id: 'end',
            placeholder: 'Hora fin',
            value: info.endStr.substring(0, 16),
            required: true
        },
        {
            type: 'hidden',
            name: 'eventId',
            id: 'eventId',
            value: id
        }
    ]);

    let container = formBuilder.createForm();
    document.body.appendChild(container);
}

function eventClick(info) {
    console.log(info.event.extendedProps);
    console.log(info.event.id);
    let formBuilder = new FormBuilder();
    formBuilder.setParams('/guardias/join/' + info.event.id, 'POST');
    formBuilder.addDetails('Apuntarse a guardia', `La guardia empieza a las ${info.event.startStr.substring(11, 16)} y termina a las ${info.event.endStr.substring(11, 16)}. Una vez apuntado, recibirás un correo con la información de la guardia.`);
    let container = formBuilder.createForm("Apuntarse");
    document.body.appendChild(container);
}

function render(element) {

    let tileEvent = document.createElement('h5');
    console.log(element);
    //check if view is in week or month
    if (element.view.type === 'timeGridWeek') {
        console.log("Por semana");
    } else if (element.view.type === 'dayGridMonth') {
        //Vista por mes
        tileEvent.innerHTML = `${element.timeText} - ${element.event.extendedProps.volunteers.size} voluntarios`;
    }

    return {domNodes: [tileEvent]}
}