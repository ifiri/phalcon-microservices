HTMLFormElement.prototype.changeNotice = function(type, content) {
    const noticeSection = this.querySelector('[data-notice]');

    noticeSection.dataset.notice = type;
    noticeSection.textContent = content;
};

const handlers = {
    signinSubmit: function(event) {
        const loginField = this.elements.login;
        const passwordField = this.elements.password;

        const authData = {
            'login': loginField.value,
            'password': passwordField.value,
        };

        if(!authData.login || !authData.password) {
            throw new Error('You should fill all fields of the form');
        }

        const result = fetch('/api/signin', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'omit',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(authData)
        }).then(response => {
            return response.json().then(json => {
                return {
                    'json': json, 
                    'status': response.status,
                };
            }).catch(error => {
                throw new Error('Unexpected error');
            });
        }).then(response => {
            const json = response.json;

            if(!json.result) {
                throw new Error('Unexpected error');
            }

            switch(response.status) {
                case 200:
                    return json;

                default:
                    throw new Error(json.result);
            }
        }).then(json => {
            this.changeNotice('success', json.result);
        }).catch(error => {
            console.log(error);
            this.changeNotice('error', error.message);

            throw error;
        });
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const signinForm = document.getElementById('signin-form');

    if(signinForm) {
        signinForm.handleSubmit = handlers.signinSubmit;
        signinForm.addEventListener('submit', function(event) {
            try {
                signinForm.handleSubmit.call(signinForm, event);
            } catch(Exception) {
                signinForm.changeNotice('error', Exception.message);
            }

            event.preventDefault();
        });
    }
});