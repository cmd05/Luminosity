var _extends = Object.assign || function(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var DEFAULT_OPTIONS = {
    paste: true,
    type: true
};

var REGEXP = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;

function registerTypeListener(quill) {
    quill.keyboard.addBinding({
        collapsed: true,
        key: ' ',
        prefix: REGEXP,
        handler: function() {
            var prevOffset = 0;
            return function(range) {
                var url = void 0;
                var text = quill.getText(prevOffset, range.index);
                var match = text.match(REGEXP);
                if (match === null) {
                    prevOffset = range.index;
                    return true;
                }
                if (match.length > 1) {
                    url = match[match.length - 1];
                } else {
                    url = match[0];
                }
                var ops = [];
                ops.push({ retain: range.index - url.length });
                ops.push({ 'delete': url.length });
                ops.push({ insert: url, attributes: { link: url } });
                quill.updateContents({ ops: ops });
                prevOffset = range.index;
                return true;
            };
        }()
    });
}

function registerPasteListener(quill) {
    quill.clipboard.addMatcher(Node.TEXT_NODE, function(node, delta) {
        if (typeof node.data !== 'string') {
            return;
        }
        var matches = node.data.match(REGEXP);
        if (matches && matches.length > 0) {
            var ops = [];
            var str = node.data;
            matches.forEach(function(match) {
                var split = str.split(match);
                var beforeLink = split.shift();
                ops.push({ insert: beforeLink });
                ops.push({ insert: match, attributes: { link: match } });
                str = split.join(match);
            });
            ops.push({ insert: str });
            delta.ops = ops;
        }

        return delta;
    });
}

var AutoLinks = function AutoLinks(quill) {
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

    _classCallCheck(this, AutoLinks);

    var opts = _extends({}, DEFAULT_OPTIONS, options);

    if (opts.type) {
        registerTypeListener(quill);
    }
    if (opts.paste) {
        registerPasteListener(quill);
    }
};


Quill.register('modules/auto-links', AutoLinks)