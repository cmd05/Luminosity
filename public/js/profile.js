import { URL, loginMdl, newTokenData,  isJson, LiveLoader, ht } from "./script.js";

const userId = document.querySelector("[name='user_uniq_id']").value;

const bodyLoader = new LiveLoader();
bodyLoader.addIdSelector("[name='last_article_id']");
bodyLoader.addBtn("#load-more-articles");
bodyLoader.addParams({
    "profile_uniq": userId
});
bodyLoader.addEndPoint("ajax/profile/load-profile-articles");

document.addEventListener("click", (e) => {
    const target = e.target;
    if(target.classList.contains("toggle-bookmark")) {
        const articleId = target.getAttribute("data-article-id");
        const form = newTokenData({"article_id": articleId})
        fetch(`${URL}/ajax/article/toggle-bookmark`, {method: "POST", body: form})
        .then(r => r.text())
        .then(res => {
            if(isJson(res)) {
                target.querySelector("i").classList.toggle("far");
                target.querySelector("i").classList.toggle("fas");
            } else {
                loginMdl();
            }
        })
    }
})

if(document.querySelector("#follow-btn")) {
	document.querySelector("#follow-btn").addEventListener("click", function() {
		const form = newTokenData({"profile_uniq": userId})
		fetch(`${URL}/ajax/profile/toggle-follow`, {method: "POST", body: form})
		.then(r => r.text())
		.then(res => {
			if(isJson(res)) {
				let obj = JSON.parse(res);
				if(obj.status === 200) {
					this.classList.toggle("active");
					if(this.innerHTML.trim() == "Follow") {
						this.innerHTML = "Following";
					} else {
						this.innerHTML = "Follow";
					}
				}
			} else {
				loginMdl();
			}
		})
	})
}

bodyLoader.addListener((response) => {
    document.querySelector(".articles-container").innerHTML += response.article_renders;
});