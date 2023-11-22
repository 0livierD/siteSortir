window.onload = () => {
    const FORM_FILTER = document.querySelector('#filtreForm');

    document.querySelectorAll("#filtreForm input, #filtreForm select").forEach(input => {


        input.addEventListener("change",() => {
            //console.log("change"+input);
            const  FORM = new FormData(FORM_FILTER);
            // fabrication de la queryString
            const PARAMS = new URLSearchParams()


            FORM.forEach((value,key)=>{
                PARAMS.append(key,value)
            })

            // on récupère l'url active
            const Url = new URL(window.location.href)

            //on lance la requete ajax
            fetch(Url.pathname +"?"+PARAMS.toString()+"&ajax=1", {headers : {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response=> response.json()
            ).then(data => {
                    const CONTENT = document.querySelector("#listSortie")
                    CONTENT.innerHTML = data.content;
                    //mise à jour de l'url
                    history.pushState({},null,Url.pathname +"?"+PARAMS.toString())
            }).catch(e => alert(e))
        })
    })

}