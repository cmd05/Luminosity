import { LiveLoader } from "../script.js";

const bodyLoader = new LiveLoader();
bodyLoader.addIdSelector("[name='last_article_id']");
bodyLoader.addBtn("#load-more-articles");
bodyLoader.addParams({
	"query": document.querySelector("[name='query']").value
});
bodyLoader.addEndPoint("ajax/explore/load-tagged-articles");


function isBlank(str) {
	return (!str || /^\s*$/.test(str));
}

bodyLoader.addListener((response) => {
	document.querySelector(".articles-container").innerHTML += response.article_renders;
});