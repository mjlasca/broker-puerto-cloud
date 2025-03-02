(function ($, Drupal) {
  Drupal.behaviors.ProposalForm = {
    attach: function (context, settings) {
      const title = context.querySelector(".form-item--title-0-value");
      const prize = context.querySelector("#edit-field-prize-0-value");
      const totalPrize = context.querySelector(
        "#edit-field-total-prize-0-value"
      );
      const date0Validate = context.querySelector('#edit-field-valid-since-0-value-date');
      const time0Validate = context.querySelector('#edit-field-valid-since-0-value-time');
      const date1Validate = context.querySelector('#edit-field-validity-until-0-value-date');
      const timeValidate = context.querySelector('#edit-field-validity-until-0-value-time');
      const groupPolizas = context.querySelector("#edit-group-polizas table");
      const months = context.querySelector('#edit-field-months');

      if(date1Validate){
        date1Validate.setAttribute('readonly',true);
        timeValidate.setAttribute('readonly',true);
      }

      if(date0Validate){
        const startDate0 = new Date();
        const formattedDate0 = dateFns.format(startDate0, "yyyy-MM-dd");
        date0Validate.value = formattedDate0;
        time0Validate.value = "00:00:01";
        time0Validate.setAttribute('readonly',true);
        date0Validate.addEventListener("change", function(event) {
          calculateEndDate(date0Validate,date1Validate,months);
        });
      }
      if(months){
        months.addEventListener("change", function(event) {
          calculateEndDate(date0Validate,date1Validate,months);
        });
      }

      if (groupPolizas) {
        const allCustommerLines = groupPolizas.querySelectorAll(
          "input[name*='[subform][field_custommer]']"
        );
        const lines = groupPolizas.querySelectorAll(
          ".paragraph-type--proposals-lines"
        );
        lines.forEach((line) => {
          const selectActivity = line.querySelector(
            "select[name*='[subform][field_activity]']"
          );
          const selectClassification = line.querySelector(
            "select[name*='[subform][field_classification]']"
          );
          const custommerLine = line.querySelector(
            "input[name*='[subform][field_custommer]']"
          );
          custommerLine.addEventListener('change', function(event) {
            const exists = Array.from(allCustommerLines).some(
              (select) => select !== this && select.value === this.value
            );
            if(exists){
              alert('El cliente ya ha sido agregado');
              custommerLine.value = "";
            }
          });
          selectActivity.addEventListener("change", async function (event) {
            if (this.value != "") {
              const clasifications = await getClasificationsOptions(this.value);
              selectClassification.innerHTML = "";
              let options = "";
              Object.entries(clasifications).forEach(([key, val]) => {
                options = `<option value="${key}">${val}</option>` + options;
              });
              selectClassification.innerHTML = options;
            }
          });
        });
      }

      if (title) title.classList.add("hide");
      if (prize) prize.setAttribute("readonly", true);
      if (totalPrize) totalPrize.setAttribute("readonly", true);

      const createCustommer = context.querySelectorAll(".create-custommer");
      if (createCustommer) {
        createCustommer.forEach((linkcustommer) => {
          linkcustommer.addEventListener("click", function (event) {
            event.preventDefault();
            const modalPro = context.querySelector("#modal-pro");
            if (modalPro) {
              modalPro.classList.remove("hide");
            } else {
              showModal();
            }
          });
        });
      }

      async function getDocumentType() {
        const response = await fetch("/get/document-type");
        const data = await response.json().then((data) => {
          return data;
        });
        return data;
      }

      async function getClasificationsOptions(idActivity) {
        const response = await fetch(
          "/get/activity-clasification/" + idActivity
        );
        const data = await response.json().then((data) => {
          return data;
        });
        return data;
      }

      async function showModal() {
        const documentType = await getDocumentType();
        let options = "";
        Object.entries(documentType).forEach(([key, val]) => {
          options = `<option value="${key}">${val}</option>` + options;
        });
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
        const form = context.querySelector(".node-form");
        if (form) {
          form.appendChild(modal);
          let closeModal = modal.querySelector(".close-modal");
          closeModal.addEventListener("click", function (event) {
            modal.classList.add("hide");
          });
        }
        sendModal = context.querySelector("#form-modal-client");
        if (sendModal) {
          sendModal.addEventListener("submit", function (event) {
            event.preventDefault();
            const obj = {
              names: context.querySelector('input[name="modal_name"]').value,
              lastnames: context.querySelector('input[name="modal_lastname"]')
                .value,
              document_type: context.querySelector(
                'select[name="modal_document_type"]'
              ).value,
              document: context.querySelector('input[name="modal_document"]')
                .value,
              birth_date: context.querySelector('input[name="modal_date"]')
                .value,
            };
            setClient(obj);
          });
        }
      }
      let sendModal = context.querySelector("#form-modal-client");

      async function setClient(obj) {
        const data = {
          names: obj.names,
          lastnames: obj.lastnames,
          document_type: obj.document_type,
          document: obj.document,
          birth_date: obj.birth_date,
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
          if (result && result.success == true) {
            sendModal.reset();
            context.querySelector(".close-modal").click();
            return result;
          } else {
            return false;
          }
        } catch (error) {
          console.error("Error en la petición:", error);
        }
      }

      function calculateEndDate(startDateInput,endDateInput,monthsSelect) {
        const startDateValue = startDateInput.value;
        const monthsToAdd = parseInt(monthsSelect.value, 10);
        if (!startDateValue || isNaN(monthsToAdd) || monthsToAdd === 0) {
            endDateInput.value = "";
            timeValidate.value = "";
            return;
        }
        let [year, month, day] = startDateValue.split("-").map(Number);
        const startDate = new Date(year, month - 1 , day);
        const newDate = dateFns.addMonths(startDate, monthsToAdd);
        const formattedDate = dateFns.format(newDate, "yyyy-MM-dd");
        endDateInput.value = formattedDate;
        timeValidate.value = "23:59:59";
      }

    },
  };
})(jQuery, Drupal);
