window.onload = () => {
  const productionCard = document.querySelector("#production-card");
  const detailsModel = document.querySelector("#details-modal");
  const bgReset = document.querySelector(".clickable-bg");

  productionCard.addEventListener("click", (e) => {
    detailsModel.classList.toggle("visible");
  });

  bgReset.addEventListener("click", (e) => {
    detailsModel.classList.toggle("visible");
  });
};
