const commentBtn = document.querySelector("#add-btn");
const commentInput = document.querySelector("#test-comment");
const textareaComment = document.querySelector(".textarea-comment");

commentBtn.addEventListener("click", (e) => {
  commentInput.classList.toggle("toggled");
  textareaComment.classList.toggle("toggled");
});

const btns = document.querySelectorAll(".btn");
for (const btn of btns) {
  btn.classList.toggle("sized-btn");
}
