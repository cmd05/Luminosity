import {URL, isJson, newTokenData, loginMdl, ht} from './script.js'

const commentInput = document.querySelector("#comment-area");
const commentInfo = document.querySelector("#comment-info");
const commentBtn = document.querySelector("#comment-btn");
const replyBar = document.querySelector("#reply-bar");
const editBar = document.querySelector("#edit-bar");
const attrBar = document.querySelector(".comment-attr");
const commentBox = document.querySelector(".comments-box");
const articleId = document.querySelector("[name='article_id']").value;
const toast = document.getElementById('message-toast');
const toastBody = toast.querySelector('.toast-body');

commentInput.addEventListener("input", () => {
    if(commentInput.value.trim() === "") {
        commentBtn.style.opacity = 0.5;
        commentBtn.disabled = true;
    } else {
        commentBtn.style.opacity = 1;
        commentBtn.disabled = false;
    }

    commentInput.style.height = "1px";
	commentInput.style.height = (commentInput.scrollHeight) + "px";
})


function replyClick() {
    const parentId = this.getAttribute("data-parent-id");
    commentInfo.setAttribute("data-parent-id", parentId);
    commentInfo.setAttribute("data-edit-id", "0");
    commentInfo.setAttribute("data-method", "add");
    const mention = this.getAttribute("data-mention");
    
    attrBar.style.display = 'block';
    replyBar.style.display = 'inline-flex';
    editBar.style.display = 'none';

    replyBar.querySelector('.reply-to').textContent = "Replying to "+mention;
    commentBtn.textContent = "Add Reply";
    commentInput.value = "@"+mention+" ";
    commentInput.focus();
}

function editClick() {
    const selector = this.querySelector("a");
    const id = selector.getAttribute("data-comment-id");
    const commentType = selector.getAttribute("data-type");
    const upper = commentType[0].toUpperCase() + commentType.substring(1);
    commentInfo.setAttribute("data-method", "edit");
    commentInfo.setAttribute("data-edit-id", id);
    commentInfo.setAttribute("data-parent-id", "0");
    
    attrBar.style.display = 'block';
    editBar.style.display = 'inline-flex';
    replyBar.style.display = 'none';
    commentBtn.textContent = "Edit " + upper;

    commentInput.value = selector.getAttribute("data-content");
    commentInput.focus();

    editBar.querySelector(".edit-info").textContent = "Editing " + upper;
}

function likeComment() {
    const id = this.getAttribute("data-comment-id");
    const form = newTokenData();
    
    fetch(`${URL}/ajax/article/toggle-comment-like/${id}/${articleId}`, 
    {method: "POST", body: form})
    .then(r => r.text())
    .then(res => {
        if(isJson(res)) {
            const span = this.querySelector(".count-heart-"+id);
            const count = parseInt(span.innerHTML);
            const num = this.classList.contains("reacted") ? count - 1 : count + 1;

            span.innerHTML = num;
            this.classList.toggle("reacted");
        } else {
            loginMdl();
        }
    })
}

function deleteComment() {
    const id = this.querySelector("a").getAttribute("data-comment-id");
    const form = newTokenData();
    
    fetch(`${URL}/ajax/article/delete-comment/${id}`, 
    {method: "POST", body: form})
    .then(r => r.text())
    .then(res => {
        if(isJson(res)) {
            this.closest(".row").remove();
            document.querySelector("#reply-"+id).remove();
            toastBody.innerHTML = `Deleted Comment`;
            let bsAlert = new bootstrap.Toast(toast, { delay: 2000 });
            bsAlert.show();
            // bsAlert.hide();
        }
    })
}

function addListeners() {
    commentInfo.setAttribute("data-parent-id", "0");
    commentInfo.setAttribute("data-edit-id", "0");
    commentInfo.setAttribute("data-method", "add");
    
    editBar.style.display = 'none';
    replyBar.style.display = 'none';
    commentInput.value = '';
    attrBar.style.display = 'none';
    commentBtn.textContent = "Add Comment";
    commentInput.blur();

    document.querySelectorAll(".reply-btn").forEach(btn => {
        if(btn.getAttribute("data-set")) 
            btn.removeEventListener("click", replyClick);
        else
            btn.setAttribute("data-set", true);

        btn.addEventListener("click", replyClick)

    })

    document.querySelectorAll(".edit-comment").forEach(btn => {
        if(btn.getAttribute("data-set")) 
            btn.removeEventListener("click", editClick);
        else
            btn.setAttribute("data-set", true);
        
        btn.addEventListener("click", editClick)
    })

    document.querySelectorAll(".react-heart").forEach(btn => {
        if(btn.getAttribute("data-set")) 
            btn.removeEventListener("click", likeComment);
        else
            btn.setAttribute("data-set", true);
        
        btn.addEventListener("click", likeComment)
    })

    document.querySelectorAll(".delete-comment").forEach(btn => {
        if(btn.getAttribute("data-set")) 
            btn.removeEventListener("click", deleteComment);
        else
            btn.setAttribute("data-set", true);
        
        btn.addEventListener("click", deleteComment)
    })
}


document.querySelectorAll(".expand-replies").forEach(btn => {
    btn.addEventListener("click", function() {
        const id = this.getAttribute("data-comment-id");
        document.querySelector(`#reply-${id}`).classList.toggle("none");
        this.querySelector("i").classList.toggle("fa-angle-down");
        this.querySelector("i").classList.toggle("fa-angle-up");
    })
})

addListeners();

document.querySelector(".cancel-reply").addEventListener("click", function() {
    commentInfo.setAttribute("data-parent-id", "0");
    commentInfo.setAttribute("data-method", "add");
    replyBar.style.display = 'none';
    commentInput.value = '';
    attrBar.style.display = 'none';
    commentBtn.textContent = "Add Comment";
    commentInput.blur();
})

document.querySelector(".cancel-edit").addEventListener("click", function() {
    commentInfo.setAttribute("data-parent-id", "0");
    commentInfo.setAttribute("data-edit-id", "0");
    commentInfo.setAttribute("data-method", "add");
    
    editBar.style.display = 'none';
    commentInput.value = '';
    attrBar.style.display = 'none';
    commentBtn.textContent = "Add Comment";
    commentInput.blur();
})

document.querySelector("#comment-btn").addEventListener("click", function() {
    const method = commentInfo.getAttribute("data-method");

    if(method === "add") {
        const parentId = commentInfo.getAttribute("data-parent-id");
        const form = newTokenData({
            "article_id": articleId,
            "content": commentInput.value,
        });

        fetch(`${URL}/ajax/article/add-comment/${parentId}`, {method: "POST", body: form})
        .then(r => r.text())
        .then(res => {
            if(isJson(res)) {
                const obj = JSON.parse(res);

                if(obj.status === 200) {
                    const comment = obj.comment;
                    if(parentId === "0") {
                        document.querySelector(`#comments-box`).innerHTML += comment;
                        toastBody.innerHTML = `Added Comment`;
                    } else {
                        document.querySelector(`#reply-${parentId}`).innerHTML += comment;
                        toastBody.innerHTML = `Added Reply`;
                    }
                    
                    addListeners();
                } else {
                    delete obj.status;
                    toastBody.innerHTML = `${obj[Object.keys(obj)[0]]}`;
                }

                let bsAlert = new bootstrap.Toast(toast, { delay: 2000 });
                bsAlert.show();
                // bsAlert.hide();
            } else {
                loginMdl();
            }
        })
    } else {
        // edit comment
        const id = commentInfo.getAttribute("data-edit-id");
        const form = newTokenData({
            "content": commentInput.value,
        });

        fetch(`${URL}/ajax/article/edit-comment/${id}`, {method: "POST", body: form})
        .then(r => r.text())
        .then(res => {
            if(isJson(res)) {
                const obj = JSON.parse(res);

                if(obj.status === 200) {
                    toastBody.innerHTML = `Edited Comment`;
                    document.querySelector("#username-"+id).innerHTML = obj.new_time + "&nbsp; (edited)";
                    document.querySelector("#content-"+id).textContent = commentInput.value;
                    document.querySelector(`.edit-comment a[data-comment-id='${id}']`).setAttribute("data-content", ht(commentInput.value))
                    addListeners();
                } else {
                    delete obj.status;
                    toastBody.innerHTML = `${obj[Object.keys(obj)[0]]}`;
                }

                let bsAlert = new bootstrap.Toast(toast, { delay: 2000 });
                bsAlert.show();
                // bsAlert.hide();
            }
        })
    }
})
