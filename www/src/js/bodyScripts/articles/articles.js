const stateRadios = document.querySelectorAll("input[name='state']");
const publicationDate = document.querySelector("#publicationDate");

stateRadios.forEach((state) => {
  state.addEventListener("click", (e) => {
    if (e.target.checked && e.target.value === "scheduled") {
      publicationDate.removeAttribute("readOnly");
    } else if (e.target.checked && e.target.value !== "scheduled") {
      publicationDate.readOnly = true;
      publicationDate.value = "";
    }
  });
});

const mediaCta = document.querySelector("#media-cta");
const modalMedia = document.querySelector(".background-modal");
const removeBG = document.querySelector(".clickable-bg");

mediaCta.addEventListener("click", (e) => {
  modalMedia.classList.toggle("visible");
});

removeBG.addEventListener("click", (e) => {
  modalMedia.classList.toggle("visible");
});
