(function ($, Drupal) {
  Drupal.behaviors.ProposalForm = {
    attach: function (context, settings) {
      const createCustommer = context.querySelectorAll('.create-custommer');

      if (createCustommer) {
        createCustommer.forEach(linkcustommer => {
          linkcustommer.addEventListener('click', function(event) {
            event.preventDefault();
            const modalPro = context.querySelector('#modal-pro');
            if(modalPro){
              modalPro.classList.remove('hide');
            }else{
              showModal();
            }
          });
        });
      }

      function showModal() {
        let modal = document.createElement("div");
        modal.id = "modal-pro";
        modal.className = "modal";
        modal.innerHTML = `
          <div class="modal-content">
            <a href="javascript:void(0);"><span class="close-modal">&times;</span></a>
            <div class="content-inputs">
              <input type="text" name="modal_name" value="" placeholder="Nombres">
              <input type="text" name="modal_lastname" value="" placeholder="Apellidos">
              <input type="text" name="modal_document" value="" placeholder="Documento">
              <select name="modal_document_type">
                <option value="dni">DNI</option>
              </select>
              <input type="date" name="moda_date" >
              <a href="#" class="button send_modal">Crear cliente</a>
            </div>
          </div>
        `;

        const form = context.querySelector('.node-form');
        if (form) {
          form.appendChild(modal);
          let closeModal = modal.querySelector('.close-modal');
          closeModal.addEventListener('click', function(event) {
            console.log("Cerrando modal");
            modal.classList.add('hide');
          });
        }
      }
    },
  };
})(jQuery, Drupal);
