class FormBuilder {
    constructor() {
        this.fullContainer = document.createElement('div');
        this.fullContainer.setAttribute('id', 'full-container');
        this.fullContainerInner = document.createElement('div');
        this.fullContainerInner.classList.add('full-container-inner');
        this.fullContainer.appendChild(this.fullContainerInner);
        document.addEventListener('keydown', this.#handleKey.bind(this));
    }

    setParams(url, method) {
        this.form = document.createElement('form');
        this.form.setAttribute('method', method);
        this.form.setAttribute('action', url);
    }
    addDetails(title, description) {
        let titleElement = document.createElement('h2');
        titleElement.innerHTML = title;
        let descriptionElement = document.createElement('p');
        descriptionElement.innerHTML = description;
        this.fullContainerInner.appendChild(titleElement);
        this.fullContainerInner.appendChild(descriptionElement);
    }

    addInputs(json) {
        json.forEach(element => {
            if(element.label !== undefined) {
                let label = document.createElement('label');
                label.setAttribute('for', element.name);
                label.innerHTML = element.label;
                this.form.appendChild(label);
            }

            let input = document.createElement('input');
            input.setAttribute('type', element.type);
            input.setAttribute('name', element.name);
            input.setAttribute('id', element.id);
            input.setAttribute('placeholder', element.placeholder);
            input.setAttribute('required', element.required);

            if(element.value !== undefined) {
                input.setAttribute('value', element.value);
            }
            this.form.appendChild(input);
        });
    }

    createForm(sendText) {
        let sendButton = document.createElement('input');
        sendButton.setAttribute('type', 'submit');
        if (sendText === undefined) {
            sendText = 'Enviar';
        }
        sendButton.setAttribute('value', sendText);
        this.form.appendChild(sendButton);
        this.fullContainerInner.appendChild(this.form);

        let closeButton = document.createElement('div');
        closeButton.setAttribute('id', 'close-full-container');
        closeButton.addEventListener('click', function () {
            document.getElementById('full-container').remove();
            document.removeEventListener('keydown', this);
        });
        this.fullContainerInner.appendChild(closeButton);

        return this.fullContainer;
    }

    #handleKey(event) {
        if (event.key === 'Escape' || event.keyCode === 27) {
            this.fullContainer.remove();
            document.removeEventListener('keydown', this.#handleKey);
        }

    };
}