async function likeArticle(event) {
  try {
    const id = window.location.pathname.split("/")[2];
    const url = window.location.origin + `/like/${id}`;
    const response = await fetch(url, {
      method: "POST",
      credentials: "include",
    });
    if (!response.ok) {
      throw new Error(`${response.status} ${response.statusText}`);
    }
    event.target.classList.add("liked");
    event.target.disabled = true;
    event.target.innerText = "Liked";
  } catch (e) {
    //console.log(e);
    event.target.disabled = true;
  }
}

async function dislikeArticle(event) {
  try {
    const id = window.location.pathname.split("/")[2];
    const url = window.location.origin + `/dislike/${id}`;
    const response = await fetch(url, {
      method: "POST",
      credentials: "include",
    });
    if (!response.ok) {
      throw new Error(`${response.status} ${response.statusText}`);
    }
    event.target.classList.add("disliked");
    event.target.disabled = true;
    event.target.innerText = "Disliked";
  } catch (e) {
    //console.log(e);
    event.target.disabled = true;
  }
}

window.addEventListener("load", () => {
  document.getElementById("back").addEventListener("click", (event) => {
    window.location.href = "../articles";
  });
  document.getElementById("edit-article").addEventListener("click", (event) => {
    const id = window.location.pathname.split("/")[2];
    window.location.href = `../article-edit/${id}`;
  });

  document.getElementById("like").addEventListener("click", likeArticle);
  document.getElementById("dislike").addEventListener("click", dislikeArticle);
});
