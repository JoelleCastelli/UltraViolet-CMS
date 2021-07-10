console.log("utilisation de articles.js dans bodyscript");

const stateRadios = document.querySelectorAll("input[name='state']");
const publicationDate = document.querySelector("#publicationDate");

stateRadios.forEach((state) => {
  state.addEventListener("click", (e) => {
    console.log("event d'un radio");
    if (e.target.checked && e.target.value === "scheduled") {
      publicationDate.disabled = true;
    } else if (e.target.checked && e.target.value !== "scheduled") {
      publicationDate.removeAttribute("disabled");
    }
  });
});
