import {isJson, ht, URL, newTokenData} from './script.js'

const navToggler = document.querySelector("#nav-toggle-mobile");
const navbar = document.querySelector('.navbar');
const nav2Toggler = document.querySelector("#toggle-nav-2");

navToggler.addEventListener("click", function() {
    nav2Toggler.click();
});

const searchCombo = document.querySelector('.search-combo');
const searchInput = document.querySelector("#nav-search-input");
const mobileInput = document.querySelector(".mobile-search-input");
const results_1 = document.querySelector(".search-results-1");
const results_2 = document.querySelector(".search-results-2");

document.addEventListener("DOMContentLoaded", function() {
    searchInput.value = '';
    mobileInput.value = '';
})

document.addEventListener('keydown', function(event) {
    if (event.ctrlKey && event.key === '/') {
        searchInput.focus();
    } else if (event.key === 'Escape') {
        searchInput.blur();
        searchInput.value = searchInput.value;
    }
});

document.querySelectorAll('.user-dropdown-menu').forEach(menu => {
    menu.addEventListener('click', function(e) {
        e.stopPropagation();
    })
})

searchCombo.addEventListener("click", function() {
    searchInput.focus();
});

searchInput.addEventListener("focus", function() {
    searchCombo.style.display = 'none';
    searchInput.placeholder = '';
})

searchInput.addEventListener("blur", function() {
    setTimeout(() => {
        if (!searchInput.value) {
            searchInput.placeholder = '           Search';
            searchCombo.style.display = 'inline';
        }
        results_1.style.display = "none";    
    }, 200);
})

mobileInput.addEventListener("focus", function() {
    searchInput.placeholder = '';
})

mobileInput.addEventListener("blur", function() {
    setTimeout(() => {
        if (!mobileInput.value) {
            mobileInput.placeholder = 'Search';
        }
        results_2.style.display = 'none';    
    }, 200);
})

const arr = [mobileInput, searchInput];
arr.forEach(input => {
    input.addEventListener("input", liveResults);
})

function liveResults() {
    if(this.value.trim() !== "") {
        results_1.style.display = "block";
        results_2.style.display = "block";
        
        fetch(`${URL}/ajax/explore/live-search/${this.value}`, {method: "POST", body: newTokenData()})
        .then(r => r.text())
        .then(res => {
            const baseURL = `${URL}/explore/search?q=${this.value}`;
            document.querySelectorAll(".results-users-link").forEach(a => a.setAttribute("href", `${baseURL}&type=users`));
            document.querySelectorAll(".results-articles-link").forEach(a => a.setAttribute("href", `${baseURL}&type=articles`));

            res = JSON.parse(res);

            document.querySelectorAll(".user-results-container").forEach(box => box.innerHTML = '');

            res.users.forEach(user => {
                document.querySelectorAll(".user-results-container").forEach(box => {
                    box.innerHTML += `
                    <a href="${URL}/profile?u=${user.username}" class='text-decoration-none text-dark row m-0 mb-2 user-result-box'>
                        <div class="col-3 text-center">
                            <div class="user-result" style="background-image: url(${URL}/uploads/${user.profile_img});">
                            </div>
                        </div>
                        <div class="col-9">
                            <div class='mb-0 pb-0'>${ht(user.display_name, 20)}</div>
                            <small class="text-muted p-0 m-0">@${ht(user.username)}</small>
                        </div>
                    </a>`;            
                })
            })

            document.querySelectorAll(".articles-results-container").forEach(box => box.innerHTML = '');
            res.articles.forEach(article => {
                document.querySelectorAll(".articles-results-container").forEach(box => {
                    box.innerHTML += `
                    <a href="${URL}/article?a=${article.article_id}" class='text-decoration-none text-dark row m-0 mb-2 user-result-box'>
                        <b>${ht(article.title, 20)}</b>
                        <small class="text-muted">${ht(article.tagline, 30)}</small>
                    </a>
                    `;
                });
            })
        })
    } else {
        results_1.style.display = "none";
        results_2.style.display = "none";
    }
}