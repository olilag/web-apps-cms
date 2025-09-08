window.addEventListener("load", () => {
  document.getElementById("back").addEventListener("click", (event) => {
    window.location.href = "../articles";
  });
  const saveButton = document.getElementById("save");
  document.getElementById("name").addEventListener("input", (event) => {
    if (event.target.value.length > 0) {
      saveButton.disabled = false;
      saveButton.classList.remove("dialog-disabled");
      saveButton.classList.add("green");
    } else {
      saveButton.disabled = true;
      saveButton.classList.add("dialog-disabled");
      saveButton.classList.remove("green");
    }
  });
});
