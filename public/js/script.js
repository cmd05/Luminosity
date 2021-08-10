function newTokenData(addData = {}) {
    const TOKEN = document.querySelector("#ajax_csrf").value;
    const data = new FormData;
    data.append("csrf_token", TOKEN);
    for(let [key, value] of Object.entries(addData)) {
        data.append(key, value);
    }
    return data;
}

const URL = document.querySelector("[name='app_url']").value;

function isJson(str) {
    var parsedStr = str;
    try {
        parsedStr = JSON.parse(str);
    } catch (e) {
        return false;
    }
    return typeof parsedStr === 'object'
}

function addFormErrors(obj) {
    for (let key in obj) {
        const value = obj[key];
        const errorBox = document.querySelector(`[name='${key}']`);

        if (errorBox) {
            errorBox.innerText = value;
        }

        const input = document.querySelector(`[data-error='${key}']`);

        if (input) {
            // if not empty error
            if (value != '') {
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        }
    }
}

function removeFormErrors(obj) {
    for (let key in obj) {
        const value = obj[key];
        const errorBox = document.querySelector(`[name='${key}']`);
        if (errorBox) {
            errorBox.innerText = '';
        }

        const input = document.querySelector(`[data-error='${key}']`);

        if (input) {
            input.classList.remove('is-invalid');
        }
    }
}

function dataURLtoFile(dataurl, filename) {
    var arr = dataurl.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);

    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }

    return new File([u8arr], filename, { type: mime });
}

function syncFileReader(file) {
    let self = this;
    let ready = false;
    let result = '';

    const sleep = function (ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    self.readAsDataURL = async function() {
        while (ready === false) {
            await sleep(100);
        }
        return result;
    }    

    const reader = new FileReader();
    reader.onloadend = function(evt) {
        result = evt.target.result;
        ready = true;
    };
    reader.readAsDataURL(file);
}

function constructFormNameObj(arr) {
    let obj = {};
    arr.forEach(x => obj[x] = document.querySelector(`[name='${x}']`).value);
    return obj;
}

function clipboardCopy(str) {
    var tempInput = document.createElement("input");
    tempInput.style = "position: absolute; left: -1000px; top: -1000px";
    tempInput.value = str;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
}

function ht(html, len = null){
    if(len != null) {
        const tmp = html.length;
        html = html.substring(0, len);
        if(tmp.len > len) html += '...';
    }
    
    var text = document.createTextNode(html);
    var p = document.createElement('p');
    p.appendChild(text);
    return p.innerHTML;
}

// function showLoginModal
  
class LiveLoader {
    constructor() {
        this.params = {};
    }

    addIdSelector = (idSelector) => {
        this.idSelector = document.querySelector(`${idSelector}`);
        if(!document.querySelector(idSelector)) 
            console.warn(`Element ${idSelector} not found for IDSelector @LiveLoader`);

    }

    addBtn = (btnSelector, loadingText = null) => {
        this.loadingText = loadingText == null 
            ? 'Loading <i class="ms-1 fas fa-circle-notch fa-spin"></i>' : loadingText;
        this.moreBtn = document.querySelector(`${btnSelector}`);

        if(!document.querySelector(btnSelector)) 
            console.warn(`Button ${btnSelector} not found for BtnSelector @LiveLoader`);
    }

    addEndPoint = (url) => {
        this.endpoint = url;
    }

    addParams = (obj) => {
        this.params = obj;
    }

    addListener = (fn) => {
        this.callbackFn = fn;

        const defaultText = this.moreBtn.innerHTML;
        
        const fetchUrl = async () => {
            const form = newTokenData({'last_id': this.idSelector, ...this.params});
            const response = await fetch(`${URL}/${this.endpoint}/${this.idSelector.value}`, {
                method: "POST",
                body: form
            })

            const json = await response.text();
	        return json;
        }

        const updateContents = (data) => {
            setTimeout(() => {
                if(isJson(data)) {
                    let obj = JSON.parse(data);
                    if(obj.status === 200) {
                        this.idSelector.value = obj.last_id;
                        this.callbackFn(obj);  
                        this.moreBtn.innerHTML = defaultText;
                        this.moreBtn.disabled = false;
                    } else {
                        this.moreBtn.remove();
                    }
                } else {
                    this.moreBtn.remove();
                }
            }, 500);
        }

        this.moreBtn.addEventListener("click", () => {
            // Add loading animation
            this.moreBtn.innerHTML = this.loadingText;
            this.moreBtn.disabled = true;

            const fetchAndCall = async () => {
                const data = await fetchUrl();
                updateContents(data);
            }
            
            fetchAndCall();
        })
    }
}

function loginMdl() {
    document.querySelector("#bs-login-mdl-btn").click();
}


export { newTokenData, URL, isJson, addFormErrors, removeFormErrors, 
         dataURLtoFile, syncFileReader, constructFormNameObj, clipboardCopy,
         LiveLoader, ht, loginMdl
       };