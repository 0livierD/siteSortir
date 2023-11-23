window.onload = () => {

    const handleDesinscription = () => {

        let buttonsDesistement = document.querySelectorAll('.desistement');
        buttonsDesistement.forEach(function (button) {
            button.addEventListener('click', function () {
                let sortieId = button.getAttribute("sortieId")

                fetch('/se-desister/' + sortieId)
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la requête');
                        }
                        return response.json();
                    })
                    .then(function () {
                        window.location.href = window.location.href
                    })
                    .catch(function (error) {
                        console.error('Erreur lors de l\'inscription:', error);
                        // Gérer les erreurs ici
                    });
            })
        })
    }


    const handleInscription = () => {


        let buttonsInscription = document.querySelectorAll('.inscription');
        buttonsInscription.forEach(function (button) {
            button.addEventListener('click', function () {
                let sortieId = button.getAttribute("sortieId")

                fetch('/inscription/' + sortieId)
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la requête coucou');
                            /*const Url = new URL(window.location.href)
                            fetch(Url.pathname + "?&ajax=2", {
                                headers: {
                                    "X-Requested-With": "XMLHttpRequest"
                                }
                            }).then(response => response.json()
                            ).then(data => {
                                const CONTENT = document.querySelector("#sortieShow")
                                CONTENT.innerHTML = data.content;
                                console.log('content'.CONTENT)
                                handleDesinscription();
                                handleInscription();
                                const notification = document.querySelector('#notification')
                                notification.classList.add('show')
                                setTimeout(()=>{
                                    notification.classList.remove('show')
                                },2000)
                            }).catch(e => alert(e))*/
                        }

                        return response.json();
                    })
                    .then(function () {
                        window.location.href = window.location.href
                    })
                    .catch(function (error) {
                        console.error('Erreur lors de l\'inscription:', error);
                        // Gérer les erreurs ici
                    });
            })
        })
    }


    handleDesinscription()
    handleInscription()

}