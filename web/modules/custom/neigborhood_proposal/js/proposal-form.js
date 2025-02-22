(function ($, Drupal) {
  Drupal.behaviors.ProposalForm = {
    attach: function (context, settings) {
      const title = context.querySelector('.form-item--title-0-value');
      const prize = context.querySelector('#edit-field-prize-0-value');
      const totalPrize = context.querySelector('#edit-field-total-prize-0-value');

      if(title)
        title.classList.add('hide');
      if(prize)
        prize.setAttribute("readonly", true);
      if(totalPrize)
        totalPrize.setAttribute("readonly", true);

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

      async function getDocumentType() {
        const response = await fetch('/get/document-type');
        const data = await response.json().then(data => { return data});
        return data;
      }
    

      async function showModal() {
        const documentType = await getDocumentType();
        let options = "";
        Object.entries(documentType).forEach(([key, val]) => {
          options = `<option value="${key}">${val}</option>` + options;   
        });
        console.log(options);
        let modal = document.createElement("div");
        modal.id = "modal-pro";
        modal.className = "modal";
        modal.innerHTML = `
          <div class="modal-content">
            <form method="POST" id="form-modal-client">
            <a href="javascript:void(0);"><span class="close-modal">&times;</span></a>
            <div class="content-inputs">
              <input type="text" name="modal_name" value="" placeholder="Nombres" required="true">
              <input type="text" name="modal_lastname" value="" placeholder="Apellidos" required="true">
              <select name="modal_document_type" required="true">
              ${options}
              </select>
              <input type="number" name="modal_document" value="" placeholder="Documento" required="true">
              <input type="date" name="modal_date" >
              <input type="submit" class="button send_modal" value="Crear cliente">
            </div>
            </form>
          </div>
        `;
        const form = context.querySelector('.node-form');
        if (form) {
          form.appendChild(modal);
          let closeModal = modal.querySelector('.close-modal');
          closeModal.addEventListener('click', function(event) {
            modal.classList.add('hide');
          });
        }
        sendModal = context.querySelector('#form-modal-client');
        if(sendModal){
          sendModal.addEventListener('submit', function(event) {
            event.preventDefault();
            const obj = {
              names : context.querySelector('input[name="modal_name"]').value,
              lastnames : context.querySelector('input[name="modal_lastname"]').value,
              document_type : context.querySelector('select[name="modal_document_type"]').value,
              document : context.querySelector('input[name="modal_document"]').value,
              borth_date : context.querySelector('input[name="modal_date"]').value,
            };
            setClient(obj); 
          })
        }

      }
      let sendModal = context.querySelector('#form-modal-client');

      async function setClient(obj) {
        const data = {
          names: obj.names,
          lastnames: obj.lastnames,
          document_type: obj.document_type,
          document: obj.document,
          borth_date: obj.borth_date,
        };
        try {
          const response = await fetch("/create/client", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
          });
          const result = await response.json();
          console.log(result);
          if (result) {
            return result[0];
          } else {
            return false;
          }
        } catch (error) {
          console.error("Error en la petición:", error);
        }
      }

    },
  };
})(jQuery, Drupal);
