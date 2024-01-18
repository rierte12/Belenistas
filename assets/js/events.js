console.log("Events");
let createEvent = document.getElementById('new-event');
let form;
createEvent.addEventListener('click', function () {
    form = new FormBuilder();
    form.setParams('/eventos/new', 'POST');
    form.addInputs([
        {
            label: 'Nombre',
            type: 'text',
            name: 'name',
            id: 'nombre',
            placeholder: 'Nombre del evento',
            required: true
        },
        {
            label: 'Descripción',
            type: 'text',
            name: 'description',
            id: 'descripcion',
            placeholder: 'Descripción del evento',
            required: true
        },
        {
            label: 'Lugar',
            type: 'text',
            name: 'location',
            id: 'lugar',
            placeholder: 'Lugar del evento',
            required: true
        },
        {
            label: 'Inicio',
            type: 'datetime-local',
            name: 'start',
            id: 'end',
            placeholder: '',
            required: true
        },
        {
            label: 'Final',
            type: 'datetime-local',
            name: 'end',
            id: 'end',
            placeholder: '',
            required: true
        }
    ]);
    form.addDetails('Nuevo evento', 'Rellena los campos para crear un nuevo evento');
    let container = form.createForm();
    document.body.appendChild(container);
});