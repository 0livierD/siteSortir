window.onload = () => {
    const FORM_FILTER = document.querySelector('#filtreForm');

    console.log(FORM_FILTER)

    document.querySelectorAll("#filtreForm input").forEach(input => {

        input.addEventListener("change",() => {
            console.log("clic");
        })
    })

}