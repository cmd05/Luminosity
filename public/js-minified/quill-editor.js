import {
	newTokenData,
	URL,
	isJson,
	syncFileReader
} from './script.js';

// Always create h2
let Header = Quill.import('formats/header');
Header.create = function (value) {
	return document.createElement('h2');
};

/**
 * Remove Code highlighting after removing codeblocks
*/
let _get = function get(object, property, receiver) {
	if (object === null) object = Function.prototype;

	const desc = Object.getOwnPropertyDescriptor(object, property);
	if (desc === undefined) {
		const parent = Object.getPrototypeOf(object);
		return parent === null ? undefined : get(parent, property, receiver);
	} else if ("value" in desc) {
		return desc.value;
	} else {
		const getter = desc.get;
		return getter === undefined ? undefined : getter.call(receiver);
	}
};

const CodeBlock = Quill.import('formats/code-block');
class NewCodeBlock extends CodeBlock {
	replaceWith(block) {
		this.domNode.textContent = this.domNode.textContent;
		this.attach();
		let newItem = _get(CodeBlock.prototype.__proto__ || Object.getPrototypeOf(CodeBlock.prototype), 'replaceWith', this).call(this, block);
		newItem.domNode.textContent = newItem.domNode.textContent;
		this.scroll.update('silent');
	}
}

NewCodeBlock.className = 'ql-syntax';
Quill.register(NewCodeBlock, true);

// Character Counter
Quill.register('modules/counter', function (quill, options) {
	const container = document.querySelector(options.container);
	quill.on('text-change', function () {
		const chars = document.querySelector('.ql-editor').innerText.replace(/\n|\r/g, "");
		container.innerText = options.unit === "word" ? 
							  chars.split(/\s+/).length + ' words' :
							  chars.length + ' characters';
	});
});


// Icons
const icons = Quill.import('ui/icons');
const icon_undo = `<svg viewbox="0 0 18 18"><polygon class="ql-fill ql-stroke" points="6 10 4 12 2 10 6 10"></polygon><path class="ql-stroke" d="M8.09,13.91A4.6,4.6,0,0,0,9,14,5,5,0,1,0,4,9"></path></svg>`;
const icon_redo = `<svg viewbox="0 0 18 18"><polygon class="ql-fill ql-stroke" points="12 10 14 12 16 10 12 10"></polygon><path class="ql-stroke" d="M9.91,13.91A4.6,4.6,0,0,1,9,14a5,5,0,1,1,5-5"></path></svg>`;
icons["undo"] = icon_undo;
icons["redo"] = icon_redo;
icons["collapse-items"] = `<i class="fas fa-angle-down"></i>`;
icons['code-block'] = '<svg viewbox="0 -2 15 18">\n' + '\t<polyline class="ql-even ql-stroke" points="2.48 2.48 1 3.96 2.48 5.45"/>\n' + '\t<polyline class="ql-even ql-stroke" points="8.41 2.48 9.9 3.96 8.41 5.45"/>\n' + '\t<line class="ql-stroke" x1="6.19" y1="1" x2="4.71" y2="6.93"/>\n' + '\t<polyline class="ql-stroke" points="12.84 3 14 3 14 13 2 13 2 8.43"/>\n' + '</svg>';

// Toolbar and formats
const toolbarItems = [
	["collapse-items"],
	['undo', 'redo'],
	[{header: [2, false]}],
	["bold", "italic", "underline", "strike"], //collapse items for small screens
	[{'script': 'sub'}, {'script': 'super'}], // superscript/subscript
	["blockquote", "code", "code-block"],
	[{'list': 'ordered'}, {'list': 'bullet'}],
	["image", "video", "link"],
	['clean'] // remove formatting button
];

const formats = [
	'header', 'bold', 'italic', 'underline', 'strike', 'script',
	'blockquote', 'code', 'code-block', 'list', 'image', 'video', 'link'
];

const quill = new Quill("#editor", {
	theme: "snow",
	formats: formats,
	modules: {
		syntax: true,
		'auto-links': true,
		toolbar: {
			container: toolbarItems
		},
		counter: {
			container: '#counter',
			unit: 'character'
		}
	}
});

// store index on change
let quillIndex = 0;
quill.on('editor-change', function () {
	quillIndex = quill.getSelection() ? quill.getSelection().index : 0;
});

async function fetchUrl(src) {
	const formData = newTokenData();
	formData.append("src", src);
	// console.log('wait start');
	
	// console.log('promise api');
	const response = await fetch(`${URL}/ajax/write/upload-image`, {
		method: "POST",
		body: formData
	});
	const json = await response.text();
	return json;
}

let delay = ms => new Promise(res => setTimeout(res, ms));

// On content paste with images
document.querySelector('.ql-editor').addEventListener('paste', e => {

	const clipboardData = e.clipboardData || window.clipboardData;
	let tmp = document.createElement('div');
	tmp.innerHTML = clipboardData.getData('text/html');
	
	let toast = document.getElementById('img-toast');
	toast.querySelector('.toast-body').innerHTML = 'Uploading Images <i class="fas fa-circle-notch fa-spin"></i>';
	let bsAlert = new bootstrap.Toast(toast, {
		delay: 4000
	});
	const uploadCount = tmp.querySelectorAll("img").length;
	if(uploadCount > 0)	bsAlert.show();
	
	const main = async () => {
		const validateUrl = document.querySelector("[name='img_valid_url']").value;
		await delay(1000); // wait for paste to finish
		bsAlert.hide();
        

		document.querySelectorAll('.ql-editor img').forEach(img => {
            if((img.src).indexOf(validateUrl) !== 0) img.classList.add("loading-img");
        })

        const els = document.querySelectorAll('.ql-editor img');

        async function loop() {
            for(let x = 0; x < els.length; x++) {
                let img = els[x]
                let src = img.src;
                img.classList.add("loading-img");
                    
                if(src.indexOf(validateUrl) !== 0) {
                    await delay(1000)
                    const upload = async () => {
                        const json = await fetchUrl(src);
                        if (isJson(json)) {
                            let obj = JSON.parse(json);
                            if (obj.status === 200) {
                                img.src = obj.url;
                            } else {
                                img.src = `${URL}/assets/img-not-found.png`;;
                            }
                        } else {
                            img.src = `${URL}/assets/img-not-found.png`;;
                        }
                        return 1;
                    }
                    upload().then(a=> {
                        img.classList.remove("loading-img");
                    });
                } else {
                    img.classList.remove("loading-img");
                }
            }
	    }
        loop();
    }
	main();
});

document.querySelector(".ql-undo").addEventListener("click", function () {
	quill.history.undo();
});
document.querySelector(".ql-redo").addEventListener("click", function () {
	quill.history.redo();
});

// Collapse on medium devices
let collapsed = true;
const collapser = document.querySelector(".ql-collapse-items");
collapser.addEventListener("click", function () {
	const toolbar = document.querySelector(".ql-toolbar");
	const items = toolbar.querySelectorAll(".ql-formats");
	const array = [items[4], items[5], items[6], items[7]];

	if (collapsed) {
		//open menu
		collapsed = false;

		array.forEach(el => el.style.display = "inline-block")
		items.forEach(el => el.style.height = "34px")
		collapser.innerHTML = "<i class='fa fa-angle-up collapser-icon'></i>";
	} else {
		//close menu
		collapsed = true;

		array.forEach(el => el.style.display = "none")
		items.forEach(el => el.style.height = "32px")
		collapser.innerHTML = `<i class='fa fa-angle-down collapser-icon'></i>`;
	}
});

// All icons on large screen
window.onresize = function (event) {
	const toolbar = document.querySelector(".ql-toolbar");
	const items = toolbar.querySelectorAll(".ql-formats");
	const array = [items[4], items[5], items[6], items[7]];

	if (window.screen.width > 1100) array.forEach(el => el.style.display = "inline-block")
};

quill.getModule("toolbar").addHandler("video", videoHandler);

function videoHandler() {
	let range = (quill.getSelection())['index'];
	let url = prompt("Enter Video URL: ")??" ";
	url = getVideoUrl(url);
	if (url != null) {
		quill.insertEmbed(range, 'video', url);
	}
}

function getVideoUrl(url) {
	let match = url.match(/^(?:(https?):\/\/)?(?:(?:www|m)\.)?youtube\.com\/watch.*v=([a-zA-Z0-9_-]+)/) ||
		url.match(/^(?:(https?):\/\/)?(?:(?:www|m)\.)?youtu\.be\/([a-zA-Z0-9_-]+)/) ||
		url.match(/^.*(youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#\&\?]*).*/) || '';

	let src = match[2] ?? '';

	if (match && src.length === 11) {
		return 'https://www.youtube.com/embed/' + match[2] + '?showinfo=0';
	} else if (match = url.match(/^(?:(https?):\/\/)?(?:www\.)?vimeo\.com\/(\d+)/)) {
		return 'https://player.vimeo.com/video/' + match[2];
	}

	return null;
}

// Tagline input
const taglineInput = document.querySelector("#tagline");

taglineInput.addEventListener("input", resizeTagline);
document.addEventListener("DOMContentLoaded", resizeTagline)

function resizeTagline() {
	taglineInput.style.height = "1px";
	taglineInput.style.height = (taglineInput.scrollHeight) + "px";
}