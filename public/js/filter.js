window.onload = () => {
    const FORM_FILTER = document.querySelector('#filtreForm');

    console.log(FORM_FILTER)

    document.querySelectorAll("#filtreForm input").forEach(input => {

        input.addEventListener("change",() => {
            console.log("clic");
            const  FORM = new FormData(FORM_FILTER);
            // fabrication de l'url
            const PARAMS = new for

            FORM.forEach((value,key)=>[
                console.log(key,value)
            ])
        })
    })

}