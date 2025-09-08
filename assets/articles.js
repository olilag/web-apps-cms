
const PAGE_SIZE = 10;
let current_page = 1;

// dat load do vlastnej funckie - napr9klad main, ako hlavny vstupny bod programu
window.addEventListener('load', () => {
    assignDelete();
    document.getElementById('previous').addEventListener('click', (event) => {
        current_page--;
        redrawCurrentPage();
    });
    document.getElementById('next').addEventListener('click', (event) => {
        current_page++;
        redrawCurrentPage();
    });
    const dialog = document.getElementById('create-dialog');
    document.getElementById('create-article').addEventListener('click', (event) => {
        dialog.showModal();
    });
    document.getElementById('dialog-cancel').addEventListener('click', (event) => {
        event.preventDefault();
        dialog.close();
    });
    document.getElementById('name').addEventListener('input', (event) => {
        const saveButton = document.getElementById('dialog-submit');
        if (event.target.value.length > 0)
        {
            saveButton.disabled = false;
            saveButton.classList.remove('dialog-disabled');
            saveButton.classList.add('green');
        }
        else
        {
            saveButton.disabled = true;
            saveButton.classList.add('dialog-disabled');
            saveButton.classList.remove('green');
        }
    });

    document.getElementById('sort-by-likes').addEventListener('click', (event) => {
        const articles = Array.from(document.querySelectorAll("#articles ul li"));
        orderArticlesByLikes(articles);
        event.target.innerText = '↓';
        document.getElementById('sort-by-dislikes').innerText = '↕';
    });

    document.getElementById('sort-by-dislikes').addEventListener('click', (event) => {
        const articles = Array.from(document.querySelectorAll("#articles ul li"));
        orderArticlesByDislikes(articles);
        event.target.innerText = '↓';
        document.getElementById('sort-by-likes').innerText = '↕';
    });
});

function redrawCurrentPage()
{
    let articles = document.querySelectorAll("#articles ul li");
    document.getElementById('previous').disabled = false;
    document.getElementById('next').disabled = false;
    if (current_page < 1)
    {
        current_page = 1;
    }
    if (current_page === 1)
    {
        document.getElementById('previous').disabled = true;
        if (articles.length <= PAGE_SIZE)
        {
            document.getElementById('next').disabled = true;
        }
    }
    const maxPage = Math.ceil(document.querySelectorAll("#articles ul li").length / PAGE_SIZE);
    if (current_page >= maxPage)
    {
        current_page = maxPage;
        document.getElementById('next').disabled = true;
    }
    for (let index = 0; index < articles.length; index++)
    {
        if (PAGE_SIZE * (current_page - 1) <= index && index < PAGE_SIZE * current_page)
        {
            articles[index].style.display = 'block';
        }
        else
        {
            articles[index].style.display = 'none';
        }
    }
    document.getElementById('page-count').textContent = 'Page count: ' + maxPage;
}

async function deleteArticle(event)
{
    try
    {
        const articleId = event.target.parentElement.id;
        const deleteUrl = window.location.origin + '/~38613013/cms/article/' + articleId;
        const response = await fetch(deleteUrl, { method: 'DELETE' });
        if (!response.ok)
        {
            throw new Error(`${response.status} ${response.statusText}`);
        }
        event.target.parentElement.parentElement.remove();
        redrawCurrentPage();
    }
    catch (e)
    {
        //console.log(e);
    }
}

function assignDelete()
{
    let buttons = document.querySelectorAll("#articles ul li button.delete");
    for (const button of buttons)
    {
        button.addEventListener('click', deleteArticle);
    }
}

function reOrder(articles)
{
    const parent = articles[0].parentNode;
    for (const article of articles) {
        parent.appendChild(article);
    }
    current_page = 1;
    redrawCurrentPage();
}

function orderArticlesByLikes(articles)
{
    orderArticles(articles, true);
}

function orderArticlesByDislikes(articles)
{
    orderArticles(articles, false);
}

function orderArticles(articles, likes)
{
    data_index = likes ? 0 : 1;
    articles.sort((a, b) => {    
        value_a = Number(a.children[0].children[data_index].innerText);
        value_b = Number(b.children[0].children[data_index].innerText)
        return value_b - value_a;
    });
    reOrder(articles);
}
