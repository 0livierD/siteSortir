window.onload = () => {

    const handleFilterChange = () => {
        const FORM_FILTER = document.querySelector('#filtreForm');

        document.querySelectorAll("#filtreForm input, #filtreForm select").forEach(input => {


            input.addEventListener("change", () => {
                //console.log("change"+input);
                const FORM = new FormData(FORM_FILTER);
                // fabrication de la queryString
                const PARAMS = new URLSearchParams()


                FORM.forEach((value, key) => {
                    PARAMS.append(key, value)
                })

                // on récupère l'url active
                const Url = new URL(window.location.href)
                //on lance la requete ajax
                fetch(Url.pathname + "?" + PARAMS.toString() + "&ajax=1", {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                }).then(response => response.json()
                ).then(data => {
                    const CONTENT = document.querySelector("#listSortie")
                    CONTENT.innerHTML = data.content;
                    //mise à jour de l'url
                    history.pushState({}, null, Url.pathname + "?" + PARAMS.toString())
                    handleDesinscription();
                    handleInscription();
                }).catch(e => alert(e))
            })
        })
    }



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
                        const Url = new URL(window.location.href)
                        fetch(Url.pathname + "?&ajax=2", {
                            headers: {
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        }).then(response => response.json()
                        ).then(data => {
                            const CONTENT = document.querySelector("#listSortie")
                            CONTENT.innerHTML = data.content;
                            handleDesinscription();
                            handleInscription();
                            const notification = document.querySelector('#notificationSuccessDesistement')
                            notification.classList.add('show')
                            setTimeout(()=>{
                                notification.classList.remove('show')
                            },2000)
                        }).catch(e => alert(e))
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
                            //throw new Error('Erreur lors de la requête coucou');
                            const Url = new URL(window.location.href)
                            fetch(Url.pathname + "?&ajax=2", {
                                headers: {
                                    "X-Requested-With": "XMLHttpRequest"
                                }
                            }).then(response => response.json()
                            ).then(data => {
                                const CONTENT = document.querySelector("#listSortie")
                                CONTENT.innerHTML = data.content;
                                const notification = document.querySelector('#notification')
                                notification.classList.add('show')
                                setTimeout(()=>{
                                    notification.classList.remove('show')
                                },2000)
                                handleDesinscription();
                                handleInscription();
                            }).catch(e => alert(e))
                        }

                        return response.json();
                    })
                    .then(function () {
                        const Url = new URL(window.location.href)
                        fetch(Url.pathname + "?&ajax=2", {
                            headers: {
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        }).then(response => response.json()
                        ).then(data => {
                            const CONTENT = document.querySelector("#listSortie")
                            CONTENT.innerHTML = data.content;
                            handleInscription();
                            handleDesinscription();
                            const notification = document.querySelector('#notificationSuccessInscription')
                            notification.classList.add('show')
                            setTimeout(()=>{
                                notification.classList.remove('show')
                            },2000)
                            console.log('coucou')
                        }).catch(e => alert(e))
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
    handleFilterChange()

}