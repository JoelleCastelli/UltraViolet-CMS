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
