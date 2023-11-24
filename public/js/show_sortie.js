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
                        window.location.href
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
                            throw new Error('Erreur lors de la requête');
                        }

                        return response.json();
                    })
                    .then(function () {
                        window.location.href
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