$('#add-children').click(function () {
    // Je récupère le numéro des futurs champs que je vais créer
    const index = +$('#widgets-counter').val();
    // Je récupère le prototype des entrées
    const tmpl = $('#booking_childrens').data('prototype').replace(/__name__/g, index);
    // J'injecte ce code au sein de la div
    $('#booking_childrens').append(tmpl);

    $('#widgets-counter').val(index + 1);
    // Je gère le bouton suppression
    handleDeleteButtons();

})

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();
    })
}

function updateCounter() {
    const count = +$('#booking_childrens div.form-group').length;
    $('#widgets-counter').val(count);
}
updateCounter();

handleDeleteButtons();